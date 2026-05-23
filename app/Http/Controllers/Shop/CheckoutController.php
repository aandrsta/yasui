<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page with shipping form and order summary.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        // Redirect back if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong, tidak dapat melanjutkan ke checkout.');
        }

        // Calculate total
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        $formattedTotalPrice = 'Rp ' . number_format($totalPrice, 0, ',', '.');

        return view('shop.checkout', compact('cartItems', 'totalPrice', 'formattedTotalPrice'));
    }

    /**
     * Process checkout, create an order, reduce stock, and clear the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Validate shipping details
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:50',
            'shipping_address' => 'required|string',
        ]);

        // Double check stock for all items
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')->with('error', 'Stok produk "' . $item->product->name . '" tidak mencukupi. Silakan sesuaikan jumlah di keranjang.');
            }
        }

        // Calculate total price
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        try {
            // Use DB Transaction to ensure data consistency
            $order = DB::transaction(function () use ($request, $cartItems, $totalPrice) {
                // Generate a unique order number
                $orderNumber = 'ORD-' . strtoupper(Str::random(10));
                while (Order::where('order_number', $orderNumber)->exists()) {
                    $orderNumber = 'ORD-' . strtoupper(Str::random(10));
                }

                // 1. Create the Order
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'order_number' => $orderNumber,
                    'total_price' => $totalPrice,
                    'status' => Order::STATUS_PENDING,
                    'payment_status' => Order::PAYMENT_UNPAID,
                    'shipping_name' => $request->input('shipping_name'),
                    'shipping_phone' => $request->input('shipping_phone'),
                    'shipping_address' => $request->input('shipping_address'),
                ]);

                // 2. Create Order Items and update stock
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);

                    // Decrement product stock
                    $item->product->decrement('stock', $item->quantity);
                }

                // 3. Clear the user's cart
                Cart::where('user_id', auth()->id())->delete();

                return $order;
            });

            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan Anda berhasil dibuat! Silakan lanjutkan pembayaran.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }
}
