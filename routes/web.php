<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Google OAuth (SSO)
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Order Routes (Daftar & Detail pesanan)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Rute Cek Status Pembayaran (Real-time Status Polling dari Midtrans API)
    Route::get('/orders/{order}/check-status', [PaymentController::class, 'checkStatus'])->name('orders.check-status');

    // Rute Khusus Admin (Kelola Toko - Hanya bisa diakses oleh admin)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
        
        // CRUD Produk Sederhana
        Route::get('/products', [\App\Http\Controllers\Admin\AdminController::class, 'productsIndex'])->name('products.index');
        Route::get('/products/create', [\App\Http\Controllers\Admin\AdminController::class, 'productsCreate'])->name('products.create');
        Route::post('/products', [\App\Http\Controllers\Admin\AdminController::class, 'productsStore'])->name('products.store');
        Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\AdminController::class, 'productsEdit'])->name('products.edit');
        Route::put('/products/{product}', [\App\Http\Controllers\Admin\AdminController::class, 'productsUpdate'])->name('products.update');
        Route::delete('/products/{product}', [\App\Http\Controllers\Admin\AdminController::class, 'productsDestroy'])->name('products.destroy');

        // Kelola Pesanan Sederhana
        Route::get('/orders', [\App\Http\Controllers\Admin\AdminController::class, 'ordersIndex'])->name('orders.index');
        Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\AdminController::class, 'ordersUpdateStatus'])->name('orders.update-status');
    });
});

// Rute Webhook Midtrans (Publik, dipanggil oleh server Midtrans)
Route::post('/payment/notification', [PaymentController::class, 'handleNotification'])->name('payment.notification');

// Rute Halaman Kebijakan Hukum (Wajib Dosen)
Route::get('/terms-of-service', [\App\Http\Controllers\Shop\HomeController::class, 'terms'])->name('pages.terms');
Route::get('/privacy-policy', [\App\Http\Controllers\Shop\HomeController::class, 'privacy'])->name('pages.privacy');
