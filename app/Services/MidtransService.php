<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    /**
     * Initialize Midtrans Configuration settings.
     */
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Request a Snap Token from Midtrans Sandbox for a specific order.
     *
     * @param  \App\Models\Order  $order
     * @return string
     * @throws \Exception
     */
    public function getSnapToken(Order $order)
    {
        // Load items relation if not loaded
        if (!$order->relationLoaded('items')) {
            $order->load('items');
        }

        // Build item details for Midtrans checkout screen
        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => (string) $item->product_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => substr($item->product_name, 0, 50), // Midtrans limit: 50 characters max
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total_price,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->shipping_name,
                'phone' => $order->shipping_phone,
                'email' => $order->user->email,
            ],
            // Optional: Expiry time (e.g. 24 hours)
            'expiry' => [
                'start_time' => date("Y-m-d H:i:s O"),
                'unit' => 'hours',
                'duration' => 24
            ]
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Retrieve the transaction status from Midtrans API.
     *
     * @param  string  $orderNumber
     * @return object
     * @throws \Exception
     */
    public function getTransactionStatus($orderNumber)
    {
        return \Midtrans\Transaction::status($orderNumber);
    }
}
