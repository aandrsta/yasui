@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('styles')
<style>
    /* Premium Minimalist Cart Styling */
    .cart-wrapper {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2.5rem;
        align-items: start;
    }

    @media (max-width: 991.98px) {
        .cart-wrapper {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }

    .cart-items-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 8px;
    }

    .cart-item-row {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        transition: var(--transition-base);
    }

    .cart-item-row:last-child {
        border-bottom: none;
    }

    .cart-item-row:hover {
        background-color: var(--bg-subtle);
    }

    .cart-image-wrapper {
        width: 90px;
        height: 90px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-fallback-icon {
        font-size: 2.25rem;
        color: var(--text-muted);
    }

    .cart-info {
        flex-grow: 1;
    }

    .cart-item-category {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.05em;
        margin-bottom: 0.15rem;
    }

    .cart-item-name {
        font-size: 0.975rem;
        font-weight: 600;
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition-base);
        display: block;
        margin-bottom: 0.25rem;
    }

    .cart-item-name:hover {
        color: var(--accent-color);
    }

    .cart-item-price {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .cart-qty-form {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .cart-qty-input {
        width: 65px;
        text-align: center;
        font-weight: 600;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        padding: 6px;
        font-size: 0.875rem;
        background-color: #ffffff;
    }

    .cart-qty-input:focus {
        outline: none;
        border-color: #94a3b8;
    }

    .btn-update-qty {
        border: 1px solid var(--border-color);
        background-color: #ffffff;
        color: var(--text-main);
        border-radius: 6px;
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition-base);
    }

    .btn-update-qty:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #ffffff;
    }

    .cart-subtotal-price {
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--primary-color);
        min-width: 120px;
        text-align: right;
    }

    .btn-cart-remove {
        background: transparent;
        border: none;
        color: var(--text-muted);
        transition: var(--transition-base);
        padding: 8px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-cart-remove:hover {
        color: #ef4444;
        background-color: #fef2f2;
    }

    /* Cart Summary Card */
    .summary-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 2rem;
        position: sticky;
        top: 100px;
    }

    .summary-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--primary-color);
        letter-spacing: -0.02em;
        margin-bottom: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        color: var(--text-muted);
    }

    .summary-row.total-row {
        border-top: 1px solid var(--border-color);
        padding-top: 1.25rem;
        margin-top: 0.5rem;
        margin-bottom: 2rem;
        font-weight: 700;
        font-size: 1.15rem;
        color: var(--primary-color);
    }
</style>
@endsection

@section('content')
<!-- Back to Shopping -->
<div class="mb-4">
    <a href="{{ url('/products') }}" class="text-secondary small text-decoration-none d-inline-flex align-items-center gap-1">
        <i class="bi bi-chevron-left small"></i>
        <span>Kembali Belanja</span>
    </a>
</div>

<h1 class="h3 fw-bold text-dark mb-4 pb-2" style="letter-spacing: -0.03em;">Keranjang Belanja</h1>

@if($cartItems->isEmpty())
    <div class="card border border-light text-center py-5 px-4 shadow-sm" style="border-radius: 8px;">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 bg-light text-muted" style="width: 72px; height: 72px;">
            <i class="bi bi-bag-x fs-2"></i>
        </div>
        <h3 class="h5 fw-bold text-dark mb-2">Keranjang Belanja Anda Kosong</h3>
        <p class="text-muted small mx-auto mb-4" style="max-width: 400px;">
            Anda belum menambahkan produk apa pun ke dalam keranjang belanja. Temukan berbagai figure, model kit, dan merchandise anime favorit Anda sekarang!
        </p>
        <div>
            <a href="{{ url('/products') }}" class="btn-minimal-accent text-decoration-none px-4 py-2">Jelajahi Produk</a>
        </div>
    </div>
@else
    <div class="cart-wrapper">
        <!-- Left Side: Cart Items List -->
        <div class="cart-items-card">
            @foreach($cartItems as $item)
                <div class="cart-item-row">
                    <!-- Product Image -->
                    <div class="cart-image-wrapper">
                        @if($item->product->image && file_exists(public_path($item->product->image)))
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="cart-fallback-icon">
                                @if($item->product->category->slug === 'figures')
                                    <i class="bi bi-box-seam"></i>
                                @elseif($item->product->category->slug === 'model-kits')
                                    <i class="bi bi-tools"></i>
                                @elseif($item->product->category->slug === 'character-goods')
                                    <i class="bi bi-gem"></i>
                                @else
                                    <i class="bi bi-hearts"></i>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Product Description -->
                    <div class="cart-info">
                        <span class="cart-item-category">{{ $item->product->category->name }}</span>
                        <a href="{{ url('/products/' . $item->product->slug) }}" class="cart-item-name">
                            {{ $item->product->name }}
                        </a>
                        <span class="cart-item-price">{{ 'Rp ' . number_format($item->product->price, 0, ',', '.') }}</span>
                        
                        <!-- Stock warnings if low -->
                        @if($item->product->stock < 5)
                            <div class="text-warning small mt-1" style="font-size: 0.75rem;">
                                <i class="bi bi-exclamation-triangle"></i> Sisa stok sedikit! (Sisa: {{ $item->product->stock }})
                            </div>
                        @endif
                    </div>

                    <!-- Quantity Input Form -->
                    <div>
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="cart-qty-form">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control cart-qty-input">
                            <button type="submit" class="btn btn-update-qty shadow-sm" title="Perbarui Jumlah">
                                <i class="bi bi-check2"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Subtotal for this Item -->
                    <div class="cart-subtotal-price">
                        {{ $item->formatted_subtotal }}
                    </div>

                    <!-- Delete Button Form -->
                    <div>
                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-cart-remove shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')" title="Hapus Barang">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Right Side: Order Summary Panel -->
        <div class="summary-card shadow-sm">
            <h3 class="summary-title">Ringkasan Belanja</h3>
            
            <div class="summary-row">
                <span>Subtotal Barang ({{ $cartItems->sum('quantity') }} unit)</span>
                <span class="fw-semibold text-dark">{{ $formattedTotalPrice }}</span>
            </div>
            
            <div class="summary-row">
                <span>Estimasi Biaya Pengiriman</span>
                <span class="text-success fw-semibold">Gratis</span>
            </div>
            
            <div class="summary-row">
                <span>Pajak & Biaya Tambahan</span>
                <span>Rp 0</span>
            </div>

            <div class="summary-row total-row">
                <span>Total Harga</span>
                <span>{{ $formattedTotalPrice }}</span>
            </div>

            <a href="{{ route('checkout.index') }}" class="btn-minimal-accent w-100 py-3 text-center text-decoration-none fw-semibold d-inline-block shadow-sm">
                Lanjutkan ke Checkout
            </a>
            
            <div class="text-center mt-3 small text-muted">
                <i class="bi bi-shield-check text-success"></i> Transaksi dijamin aman & tepercaya
            </div>
        </div>
    </div>
@endif
@endsection
