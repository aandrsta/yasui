<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Display the simple admin dashboard with basic stats.
     */
    public function dashboard()
    {
        // 1. Calculate basic totals for the quick stats
        $totalRevenue = Order::where('payment_status', Order::PAYMENT_PAID)->sum('total_price');
        $totalOrders = Order::where('payment_status', Order::PAYMENT_PAID)->count();
        $totalCustomers = User::where('role', 'user')->count();
        $totalProducts = Product::count();

        // 2. Fetch the 5 most recent orders
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'recentOrders'
        ));
    }

    /**
     * Display a listing of all products for admin management.
     */
    public function productsIndex()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function productsCreate()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in database.
     */
    public function productsStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_featured' => 'nullable|boolean',
        ]);

        $data = $request->only(['category_id', 'name', 'description', 'price', 'stock']);
        $data['is_featured'] = $request->has('is_featured');
        $data['slug'] = Str::slug($request->name);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $imageFile->getClientOriginalExtension();
            
            // Ensure products folder exists in public/images
            $destinationPath = public_path('images/products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $imageFile->move($destinationPath, $imageName);
            $data['image'] = 'images/products/' . $imageName;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan ke katalog!');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function productsEdit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in database.
     */
    public function productsUpdate(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_featured' => 'nullable|boolean',
        ]);

        $data = $request->only(['category_id', 'name', 'description', 'price', 'stock']);
        $data['is_featured'] = $request->has('is_featured');
        $data['slug'] = Str::slug($request->name);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image file if exists
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }

            $imageFile = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $imageFile->getClientOriginalExtension();
            
            $destinationPath = public_path('images/products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $imageFile->move($destinationPath, $imageName);
            $data['image'] = 'images/products/' . $imageName;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified product from database.
     */
    public function productsDestroy(Product $product)
    {
        // Delete image file if exists
        if ($product->image && file_exists(public_path($product->image))) {
            @unlink(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus dari katalog!');
    }

    /**
     * Display a listing of all customer orders.
     */
    public function ordersIndex()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Update the shipment/fulfillment status of an order.
     */
    public function ordersUpdateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return redirect()->back();
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            // If order status is changed to CANCELLED, revert product stock!
            if ($newStatus === Order::STATUS_CANCELLED && $oldStatus !== Order::STATUS_CANCELLED) {
                $order->load('items.product');
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
                $order->update([
                    'status' => $newStatus,
                    'payment_status' => Order::PAYMENT_FAILED
                ]);
            } else {
                $order->update([
                    'status' => $newStatus
                ]);
            }
        });

        return redirect()->back()->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diubah menjadi: ' . ucfirst($newStatus));
    }
}
