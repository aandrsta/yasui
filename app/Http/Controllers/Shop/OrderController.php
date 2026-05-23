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
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('items.product')
            ->firstOrFail();

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
                } catch (Exception $e) {
                    Log::error('Midtrans Token Generation Failed for Order ' . $order->order_number . '. Error: ' . $e->getMessage());
                }
            }
        }

        return view('orders.show', compact('order', 'snapToken'));
    }
}
