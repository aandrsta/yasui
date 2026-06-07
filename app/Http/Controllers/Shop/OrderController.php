<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $midtransService;

    /**
     * Inject MidtransService into controller.
     *
     * @param  \App\Services\MidtransService  $midtransService
     */
    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Display the specified order detail page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Cancel expired orders in real-time on load to keep data synced
        \App\Console\Commands\CancelExpiredOrders::cancelExpired();

        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('items.product')
            ->firstOrFail();

        // Passive background status check from Midtrans API to keep local DB synced on page load
        if ($order->payment_status === Order::PAYMENT_UNPAID && $order->status !== Order::STATUS_CANCELLED) {
            try {
                $status = $this->midtransService->getTransactionStatus($order->order_number);
                
                $transactionStatus = isset($status->transaction_status) ? $status->transaction_status : null;
                $paymentType = isset($status->payment_type) ? $status->payment_type : null;
                $transactionId = isset($status->transaction_id) ? $status->transaction_id : null;
                $fraudStatus = isset($status->fraud_status) ? $status->fraud_status : null;

                if ($transactionStatus) {
                    $paymentStatus = 'pending';

                    \Illuminate\Support\Facades\DB::transaction(function () use ($transactionStatus, $paymentType, $transactionId, $fraudStatus, $order, $status, &$paymentStatus) {
                        if ($transactionStatus == 'capture') {
                            if ($paymentType == 'credit_card') {
                                if ($fraudStatus == 'accept') {
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

                        \App\Models\Payment::updateOrCreate(
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

                    // Refresh model so view displays updated database state
                    $order->refresh();
                }
            } catch (\Exception $e) {
                // Silently ignore so page load doesn't crash when transaction isn't created yet or API is down
            }
        }

        $snapToken = null;

        // Generate or retrieve Snap Token only if the order is unpaid and not cancelled
        if ($order->payment_status === Order::PAYMENT_UNPAID && $order->status !== Order::STATUS_CANCELLED) {
            if ($order->snap_token) {
                $snapToken = $order->snap_token;
            } else {
                try {
                    $snapToken = $this->midtransService->getSnapToken($order);
                    
                    // Cache the snap token in database
                    $order->update([
                        'snap_token' => $snapToken
                    ]);
                } catch (\Exception $e) {
                    Log::error('Midtrans Token Generation Failed for Order ' . $order->order_number . '. Error: ' . $e->getMessage());
                }
            }
        }

        return view('orders.show', compact('order', 'snapToken'));
    }

    /**
     * Display a listing of the user's orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Cancel expired orders in real-time on load to keep data synced
        \App\Console\Commands\CancelExpiredOrders::cancelExpired();

        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Cancel the specified order (user self-cancel).
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Order $order)
    {
        // Ensure user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Can only cancel if order is pending and unpaid
        if ($order->status !== Order::STATUS_PENDING || $order->payment_status !== Order::PAYMENT_UNPAID) {
            return redirect()->back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
            $order->load('items.product');
            // Revert stock
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update([
                'status' => Order::STATUS_CANCELLED,
                'payment_status' => Order::PAYMENT_FAILED
            ]);
        });

        return redirect()->back()->with('success', 'Pesanan Anda berhasil dibatalkan. Stok barang telah dikembalikan.');
    }

    /**
     * Mark the specified order as completed (user confirms receipt).
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Order $order)
    {
        // Ensure user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Can only complete if order is shipped
        if ($order->status !== Order::STATUS_SHIPPED) {
            return redirect()->back()->with('error', 'Status pesanan tidak valid untuk diselesaikan.');
        }

        $order->update([
            'status' => Order::STATUS_COMPLETED
        ]);

        return redirect()->back()->with('success', 'Pesanan telah selesai! Terima kasih telah berbelanja di YASSUI.');
    }
}
