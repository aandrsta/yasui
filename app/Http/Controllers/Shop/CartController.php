<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the authenticated user's shopping cart.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all cart items for the authenticated user with their products
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product.category')
            ->get();

        // Calculate total cart price
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        $formattedTotalPrice = 'Rp ' . number_format($totalPrice, 0, ',', '.');

        return view('shop.cart', compact('cartItems', 'totalPrice', 'formattedTotalPrice'));
    }

    /**
     * Store a newly created resource in storage (Add to cart).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity');

        $product = Product::findOrFail($productId);

        // Check if there is enough stock
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        // Check if the product already exists in this user's cart
        $existingCartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();

        if ($existingCartItem) {
            $newQuantity = $existingCartItem->quantity + $quantity;

            // Make sure the combined quantity does not exceed product stock
            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 'Jumlah total di keranjang melebihi stok tersedia. Stok tersedia: ' . $product->stock);
            }

            $existingCartItem->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        if ($request->input('buy_now') === '1') {
            return redirect()->route('checkout.index')->with('success', 'Lanjutkan pembelian produk ' . $product->name . '!');
        }

        return redirect()->route('cart.index')->with('success', $product->name . ' berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Cart $cart)
    {
        // Security check: ensure the cart item belongs to the authenticated user
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = (int) $request->input('quantity');
        $product = $cart->product;

        // Check if there is enough stock
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk memperbarui jumlah. Stok tersedia: ' . $product->stock);
        }

        $cart->update([
            'quantity' => $quantity,
        ]);

        return redirect()->back()->with('success', 'Jumlah produk ' . $product->name . ' berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cart $cart)
    {
        // Security check: ensure the cart item belongs to the authenticated user
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $productName = $cart->product ? $cart->product->name : 'Produk';
        $cart->delete();

        return redirect()->back()->with('success', $productName . ' berhasil dihapus dari keranjang.');
    }
}
