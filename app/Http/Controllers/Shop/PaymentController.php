<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
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

    /**
     * Local Simulator: Force trigger simulated successful payment webhook.
     * Only works in local environment.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function simulateSuccess(Order $order)
    {
        // Security check: only allow in local development
        if (config('app.env') !== 'local') {
            abort(403, 'Aksi simulator ini dilarang di lingkungan produksi.');
        }

        try {
            DB::transaction(function () use ($order) {
                $order->update([
                    'payment_status' => Order::PAYMENT_PAID,
                    'status' => Order::STATUS_PROCESSING
                ]);

                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'transaction_id' => 'SIM-SUCCESS-' . strtoupper(Str::random(8)),
                        'payment_type' => 'local_simulation',
                        'amount' => $order->total_price,
                        'status' => 'paid',
                        'raw_response' => ['simulation' => 'success'],
                    ]
                );
            });

            return redirect()->route('orders.show', $order->id)->with('success', 'Simulasi Pembayaran Berhasil! Status pesanan kini LUNAS dan SEDANG DIPROSES.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mensimulasikan pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Local Simulator: Force trigger simulated failed/cancelled payment webhook.
     * Only works in local environment.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function simulateFailure(Order $order)
    {
        // Security check: only allow in local development
        if (config('app.env') !== 'local') {
            abort(403, 'Aksi simulator ini dilarang di lingkungan produksi.');
        }

        try {
            DB::transaction(function () use ($order) {
                // Revert stock if not already cancelled
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

                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'transaction_id' => 'SIM-FAILED-' . strtoupper(Str::random(8)),
                        'payment_type' => 'local_simulation',
                        'amount' => $order->total_price,
                        'status' => 'failed',
                        'raw_response' => ['simulation' => 'failure'],
                    ]
                );
            });

            return redirect()->route('orders.show', $order->id)->with('error', 'Simulasi Pembayaran Gagal! Status pesanan kini BATAL dan stok barang dikembalikan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mensimulasikan kegagalan: ' . $e->getMessage());
        }
    }
}
