@extends('layouts.app')

@section('title', $product->name)

@section('styles')
<style>
    /* Detail View Styling */
    .product-detail-image-wrapper {
        position: relative;
        padding-top: 100%; /* 1:1 Aspect Ratio */
        background-color: var(--bg-subtle);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        overflow: hidden;
    }

    .product-detail-image-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-detail-image-container img {
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .product-detail-image-wrapper:hover .product-detail-image-container img {
        transform: scale(1.04);
    }

    .detail-fallback-img {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 5rem;
        background: var(--bg-subtle);
    }

    .product-meta-category {
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.08em;
    }

    .product-detail-title {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 2.8rem;
        letter-spacing: -0.01em;
        color: var(--primary-color);
        line-height: 1.1;
    }

    .product-detail-price {
        font-family: 'Zen Old Mincho', serif;
        font-weight: 700;
        font-size: 1.75rem;
        color: var(--accent-color);
        letter-spacing: -0.02em;
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .indicator-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .description-box {
        font-size: 0.95rem;
        line-height: 1.7;
        color: var(--text-main);
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }

    /* Qty Input Minimalist styling */
    .qty-input {
        max-width: 80px;
        text-align: center;
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
        border-radius: 3px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .qty-input:focus {
        outline: none;
        border-color: var(--primary-color);
        background-color: var(--bg-main);
    }

    /* Related Products Grid */
    .product-grid-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        overflow: hidden;
        transition: var(--transition-base);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-grid-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .related-image-wrapper {
        position: relative;
        padding-top: 100%;
        background-color: var(--bg-subtle);
        border-bottom: 1px solid var(--border-color);
    }

    .related-image-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .related-fallback-img {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 2rem;
        background: var(--bg-subtle);
    }

    .product-card-body {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-category-label {
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .product-title-link {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        line-height: 1.4;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.8rem;
    }

    .product-title-link:hover {
        color: var(--accent-color);
    }

    .product-price {
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--primary-color);
    }

    .related-products-title {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 2.2rem;
        letter-spacing: -0.01em;
        color: var(--primary-color);
    }
</style>
@endsection

@section('content')
<!-- Back Button and breadcrumbs -->
<div class="mb-4">
    <a href="{{ url('/products') }}" class="text-secondary small text-decoration-none d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Katalog</span>
    </a>
</div>

<div class="row g-5 mb-5 pb-4">
    <!-- Left Column: Product Image -->
    <div class="col-md-6 animate-fade-in-up">
        <div class="product-detail-image-wrapper shadow-sm">
            <div class="product-detail-image-container">
                @if($product->image && file_exists(public_path($product->image)))
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                @else
                    <div class="detail-fallback-img">
                        @if($product->category->slug === 'figures')
                            <i class="bi bi-box-seam text-secondary"></i>
                        @elseif($product->category->slug === 'model-kits')
                            <i class="bi bi-tools text-secondary"></i>
                        @elseif($product->category->slug === 'character-goods')
                            <i class="bi bi-gem text-secondary"></i>
                        @else
                            <i class="bi bi-hearts text-secondary"></i>
                        @endif
                        <span class="fs-6 mt-3 text-uppercase tracking-wider small fw-bold text-muted">{{ $product->category->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Product Info & Add to Cart -->
    <div class="col-md-6 d-flex flex-column justify-content-between animate-fade-in-up stagger-1">
        <div>
            <!-- Category and Availability Badge -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ url('/products?category=' . $product->category->slug) }}" class="product-meta-category text-decoration-none">
                    {{ $product->category->name }}
                </a>
                
                <div class="status-indicator">
                    @if($product->stock > 0)
                        <span class="indicator-dot bg-success"></span>
                        <span class="text-success">Tersedia (Stok: {{ $product->stock }})</span>
                    @else
                        <span class="indicator-dot bg-danger"></span>
                        <span class="text-danger">Stok Habis</span>
                    @endif
                </div>
            </div>

            <!-- Product Title -->
            <h1 class="product-detail-title mb-3">{{ $product->name }}</h1>

            <!-- Price -->
            <div class="product-detail-price mb-4">{{ $product->formatted_price }}</div>

            <!-- Description -->
            <div class="description-box py-4 mb-4">
                <h5 class="fw-bold text-dark mb-2 small text-uppercase tracking-wider">Deskripsi Produk</h5>
                <p class="mb-0 text-muted" style="line-height: 1.7;">{{ $product->description }}</p>
            </div>
        </div>

        <!-- Add to Cart Form Section -->
        <div>
            @if($product->stock > 0)
                @auth
                    <form id="add-to-cart-form" action="{{ url('/cart') }}" method="POST" class="d-flex flex-column gap-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="d-flex align-items-center gap-3">
                            <label for="quantity" class="small fw-semibold text-muted mb-0">Jumlah:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control qty-input px-2 py-2">
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2 mt-2">
                            <button type="submit" class="btn-minimal-secondary py-3 flex-fill d-inline-flex align-items-center justify-content-center gap-2">

                                <span class="fw-semibold">Tambah ke Keranjang</span>
                            </button>
                            <button type="submit" name="buy_now" value="1" class="btn-minimal-accent py-3 flex-fill d-inline-flex align-items-center justify-content-center gap-2">
                          
                                <span class="fw-semibold">Beli Sekarang</span>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <label class="small fw-semibold text-muted mb-0">Jumlah:</label>
                            <input type="number" value="1" disabled class="form-control qty-input px-2 py-2">
                        </div>
                        
                        <a href="{{ url('/login') }}" class="btn-minimal-accent py-3 w-100 text-center text-decoration-none fw-semibold d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-box-arrow-in-right fs-5"></i>
                            <span>Masuk untuk Membeli</span>
                        </a>
                    </div>
                @endauth
            @else
                <button disabled class="btn-minimal-secondary py-3 w-100 d-inline-flex align-items-center justify-content-center gap-2 cursor-not-allowed">
                    <i class="bi bi-exclamation-octagon fs-5"></i>
                    <span class="fw-semibold">Stok Tidak Tersedia</span>
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Related Products Section -->
@if($relatedProducts->count() > 0)
    <section class="mt-5 pt-5 border-top border-light">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase text-muted fw-semibold small letter-spacing-1">Produk Sejenis</span>
                <h3 class="related-products-title mt-1 mb-0">Produk Terkait</h3>
            </div>
        </div>

        <div class="row g-4">
            @foreach($relatedProducts as $related)
                <div class="col-sm-6 col-md-3">
                    <div class="card product-grid-card">
                        <div class="related-image-wrapper">
                            <a href="{{ url('/products/' . $related->slug) }}" class="related-image-container">
                                @if($related->image && file_exists(public_path($related->image)))
                                    <img src="{{ asset($related->image) }}" alt="{{ $related->name }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="related-fallback-img">
                                        @if($related->category->slug === 'figures')
                                            <i class="bi bi-box-seam text-secondary"></i>
                                        @elseif($related->category->slug === 'model-kits')
                                            <i class="bi bi-tools text-secondary"></i>
                                        @elseif($related->category->slug === 'character-goods')
                                            <i class="bi bi-gem text-secondary"></i>
                                        @else
                                            <i class="bi bi-hearts text-secondary"></i>
                                        @endif
                                    </div>
                                @endif
                            </a>
                        </div>
                        
                        <div class="product-card-body">
                            <div>
                                <div class="product-category-label">{{ $related->category->name }}</div>
                                <a href="{{ url('/products/' . $related->slug) }}" class="product-title-link">
                                    {{ $related->name }}
                                </a>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-light">
                                <span class="product-price">{{ $related->formatted_price }}</span>
                                <a href="{{ url('/products/' . $related->slug) }}" class="btn btn-minimal-secondary btn-sm py-1 px-3" style="font-size: 0.8rem;">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection

@section('scripts')
<script>
    // GA4 E-Commerce Tracking: view_item Event
    if (typeof gtag === 'function') {
        gtag("event", "view_item", {
            currency: "IDR",
            value: {{ $product->price }},
            items: [{
                item_id: "{{ $product->id }}",
                item_name: "{{ $product->name }}",
                price: {{ $product->price }},
                item_category: "{{ $product->category->name }}",
                quantity: 1
            }]
        });
    }

    // GA4 E-Commerce Tracking: add_to_cart Event
    document.getElementById('add-to-cart-form')?.addEventListener('submit', function() {
        if (typeof gtag === 'function') {
            const quantity = parseInt(document.getElementById('quantity').value) || 1;
            gtag("event", "add_to_cart", {
                currency: "IDR",
                value: {{ $product->price }} * quantity,
                items: [{
                    item_id: "{{ $product->id }}",
                    item_name: "{{ $product->name }}",
                    price: {{ $product->price }},
                    item_category: "{{ $product->category->name }}",
                    quantity: quantity
                }]
            });
        }
    });
</script>

