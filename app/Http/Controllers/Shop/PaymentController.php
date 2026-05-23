<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Check the real-time transaction status of an order directly from Midtrans API.
     * Updates the database order state based on the fetched status.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkStatus(Order $order, \App\Services\MidtransService $midtransService)
    {
        // Security check: only allow order owner
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Aksi dilarang.');
        }

        try {
            // Fetch transaction status from Midtrans API using the service wrapper
            $status = $midtransService->getTransactionStatus($order->order_number);
            
            // Convert status object to array or get properties directly
            $transactionStatus = isset($status->transaction_status) ? $status->transaction_status : null;
            $paymentType = isset($status->payment_type) ? $status->payment_type : null;
            $transactionId = isset($status->transaction_id) ? $status->transaction_id : null;
            $fraudStatus = isset($status->fraud_status) ? $status->fraud_status : null;

            if (!$transactionStatus) {
                return redirect()->route('orders.show', $order->id)
                    ->with('warning', 'Transaksi belum dibuat di Midtrans.');
            }

            // Map and update status
            $paymentStatus = 'pending';
            $flashType = 'success';
            $flashMessage = 'Status pembayaran Anda sedang diverifikasi.';

            DB::transaction(function () use ($transactionStatus, $paymentType, $transactionId, $fraudStatus, $order, $status, &$paymentStatus, &$flashType, &$flashMessage) {
                if ($transactionStatus == 'capture') {
                    if ($paymentType == 'credit_card') {
                        if ($fraudStatus == 'accept') {
                            $order->update([
                                'payment_status' => Order::PAYMENT_PAID,
                                'status' => Order::STATUS_PROCESSING
                            ]);
                            $paymentStatus = 'paid';
                            $flashMessage = 'Pembayaran berhasil dikonfirmasi! Pesanan Anda sedang diproses.';
                        }
                    }
                } elseif ($transactionStatus == 'settlement') {
                    $order->update([
                        'payment_status' => Order::PAYMENT_PAID,
                        'status' => Order::STATUS_PROCESSING
                    ]);
                    $paymentStatus = 'paid';
                    $flashMessage = 'Pembayaran berhasil dikonfirmasi! Pesanan Anda sedang diproses.';
                } elseif ($transactionStatus == 'pending') {
                    $order->update([
                        'payment_status' => Order::PAYMENT_UNPAID,
                        'status' => Order::STATUS_PENDING
                    ]);
                    $paymentStatus = 'pending';
                    $flashType = 'warning';
                    $flashMessage = 'Pembayaran masih tertunda. Segera selesaikan pembayaran Anda.';
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    // Only revert stock if the order wasn't already cancelled
                    if ($order->status !== Order::STATUS_CANCELLED) {
                        $order->load('items.product');
                        foreach ($order->items as $item) {
                            if ($item->product) {
                                $item->product->increment('stock', $item->quantity);
                            }
                        }
                    }

                    $order->update([
                        'payment_status' => Order::PAYMENT_FAILED,
                        'status' => Order::STATUS_CANCELLED
                    ]);
                    $paymentStatus = 'failed';
                    $flashType = 'error';
                    $flashMessage = 'Pembayaran gagal, dibatalkan, atau kadaluwarsa.';
                }

                // Save or update payment details
                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'transaction_id' => $transactionId,
                        'payment_type' => $paymentType,
                        'amount' => $order->total_price,
                        'status' => $paymentStatus,
                        'raw_response' => (array) $status,
                    ]
                );
            });

            $alertKey = $flashType === 'error' ? 'error' : ($flashType === 'warning' ? 'warning' : 'success');
            return redirect()->route('orders.show', $order->id)->with($alertKey, $flashMessage);

        } catch (\Exception $e) {
            // Check if it is a 404 from Midtrans (Transaction not found)
            if (str_contains($e->getMessage(), '404') || str_contains($e->getMessage(), 'does not exist')) {
                return redirect()->route('orders.show', $order->id)
                    ->with('warning', 'Silakan klik "Bayar Sekarang" terlebih dahulu untuk memulai pembayaran.');
            }
            
            Log::error('Midtrans Check Status Error: ' . $e->getMessage());
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Gagal memeriksa status pembayaran ke Midtrans.');
        }
    }

    /**
     * Handle automated payment notifications (Webhook) from Midtrans.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleNotification(Request $request)
    {
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');
        $transactionStatus = $request->input('transaction_status');
        $paymentType = $request->input('payment_type');
        $transactionId = $request->input('transaction_id');

        // 1. Verify Midtrans SHA512 Signature Key (Security check)
        $serverKey = config('midtrans.server_key');
        $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $calculatedSignature) {
            Log::warning('Midtrans Webhook: Invalid signature detected for Order ID ' . $orderId);
            return response()->json([
                'status' => 'error',
                'message' => 'Tanda tangan transaksi tidak valid.'
            ], 403);
        }

        // 2. Fetch the Order by order number
        $order = Order::where('order_number', $orderId)->with('items.product')->first();
        if (!$order) {
            Log::warning('Midtrans Webhook: Order ' . $orderId . ' not found.');
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.'
            ], 404);
        }

        // 3. Process Status Mapping inside DB Transaction
        try {
            DB::transaction(function () use ($transactionStatus, $paymentType, $transactionId, $order, $request) {
                $paymentStatus = 'pending';

                if ($transactionStatus == 'capture') {
                    if ($paymentType == 'credit_card') {
                        if ($request->input('fraud_status') == 'accept') {
                            $order->update([
                                'payment_status' => Order::PAYMENT_PAID,
                                'status' => Order::STATUS_PROCESSING
                            ]);
                            $paymentStatus = 'paid';
                        }
                    }
                } elseif ($transactionStatus == 'settlement') {
                    $order->update([
                        'payment_status' => Order::PAYMENT_PAID,
                        'status' => Order::STATUS_PROCESSING
                    ]);
                    $paymentStatus = 'paid';
                } elseif ($transactionStatus == 'pending') {
                    $order->update([
                        'payment_status' => Order::PAYMENT_UNPAID,
                        'status' => Order::STATUS_PENDING
                    ]);
                    $paymentStatus = 'pending';
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    // Only revert stock if the order wasn't already cancelled
                    if ($order->status !== Order::STATUS_CANCELLED) {
                        foreach ($order->items as $item) {
                            if ($item->product) {
                                $item->product->increment('stock', $item->quantity);
                            }
                        }
                    }

                    $order->update([
                        'payment_status' => Order::PAYMENT_FAILED,
                        'status' => Order::STATUS_CANCELLED
                    ]);
                    $paymentStatus = 'failed';
                }

                // 4. Save Payment record in database
                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'transaction_id' => $transactionId,
                        'payment_type' => $paymentType,
                        'amount' => $order->total_price,
                        'status' => $paymentStatus,
                        'raw_response' => $request->all(),
                    ]
                );
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Status pembayaran berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Webhook: Error updating order ' . $orderId . '. Message: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Kesalahan internal server.'
            ], 500);
        }
    }
}
