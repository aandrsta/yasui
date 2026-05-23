@extends('layouts.app')

@section('title', 'Beranda')

@section('styles')
<style>
    /* Clean stark hero */
    .hero-section {
        padding: 5rem 0;
        background-color: var(--bg-main);
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 4rem;
        margin-top: -3rem; /* Align higher for a premium feel */
    }

    .hero-title {
        font-weight: 800;
        font-size: 3rem;
        letter-spacing: -0.04em;
        color: var(--primary-color);
        line-height: 1.1;
    }

    .hero-subtitle {
        font-size: 1.125rem;
        color: var(--text-muted);
        line-height: 1.6;
        font-weight: 400;
        max-width: 600px;
    }

    /* Minimalist Category Cards */
    .category-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        transition: var(--transition-base);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .category-card:hover {
        border-color: #94a3b8;
        transform: translateY(-2px);
    }

    .category-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 6px;
        background-color: var(--bg-subtle);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Product Grid Cards */
    .product-grid-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
        transition: var(--transition-base);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-grid-card:hover {
        border-color: #94a3b8;
        transform: translateY(-2px);
    }

    .product-image-wrapper {
        position: relative;
        padding-top: 100%; /* 1:1 Aspect Ratio */
        background-color: var(--bg-subtle);
        border-bottom: 1px solid var(--border-color);
    }

    .product-image-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-fallback-img {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 2.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .product-card-body {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-category-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .product-title {
        font-size: 1rem;
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

    .product-title:hover {
        color: var(--primary-hover);
    }

    .product-price {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--primary-color);
    }

    .section-title {
        font-weight: 700;
        font-size: 1.5rem;
        letter-spacing: -0.03em;
        color: var(--primary-color);
    }
</style>
@endsection

@section('content')
<!-- Hero Section (Minimal Premium Stark) -->
<section class="hero-section">
    <div class="row align-items-center py-4">
        <div class="col-lg-7">
            <span class="text-uppercase text-muted fw-bold small letter-spacing-2" style="color: var(--accent-color) !important;">#YasuiHobbyShop</span>
            <h1 class="hero-title mb-4 mt-2">Destinasi Pop Culture<br>Original & Premium.</h1>
            <p class="hero-subtitle mb-4">
                Temukan koleksi kurasi anime figures, model kits, character goods, dan plushies otentik Jepang dengan standar kualitas terbaik untuk koleksi premium Anda.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ url('/products') }}" class="btn-minimal-accent text-decoration-none px-4 py-2">
                    Jelajahi Produk
                </a>
                <a href="#kategori" class="btn-minimal-secondary text-decoration-none px-4 py-2">
                    Lihat Kategori
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="kategori" class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-uppercase text-muted fw-semibold small letter-spacing-1">Koleksi Kami</span>
            <h2 class="section-title mt-1 mb-0">Belanja Berdasarkan Kategori</h2>
        </div>
    </div>
    
    <div class="row g-4">
        @foreach($categories as $category)
            <div class="col-md-3">
                <div class="card category-card p-3">
                    <div>
                        <!-- Category Image Wrapper with Floating Icon Badge -->
                        <div class="category-image-wrapper mb-3" style="height: 160px; border-radius: 6px; overflow: hidden; border: 1px solid var(--border-color); position: relative;">
                            @if($category->image && file_exists(public_path($category->image)))
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="w-100 h-100 object-fit-cover" style="transition: var(--transition-base);">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted">
                                    <i class="bi bi-image fs-3"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-2 start-2 bg-white rounded-2 p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; color: var(--accent-color);">
                                @if($category->slug === 'figures')
                                    <i class="bi bi-box-seam"></i>
                                @elseif($category->slug === 'model-kits')
                                    <i class="bi bi-tools"></i>
                                @elseif($category->slug === 'character-goods')
                                    <i class="bi bi-gem"></i>
                                @else
                                    <i class="bi bi-hearts"></i>
                                @endif
                            </div>
                        </div>
                        <h4 class="fw-bold mb-2 small text-dark fs-6">{{ $category->name }}</h4>
                        <p class="text-muted small mb-3" style="line-height: 1.5; font-size: 0.8rem; height: 3.6rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">{{ $category->description }}</p>
                    </div>
                    <div>
                        <a href="{{ url('/products?category=' . $category->slug) }}" class="text-dark small fw-bold text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.825rem;">
                            <span>Lihat Koleksi</span>
                            <i class="bi bi-arrow-right small"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Featured Products Section -->
<section class="mb-5 py-4">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-uppercase text-muted fw-semibold small letter-spacing-1">Pilihan Terbaik</span>
            <h2 class="section-title mt-1 mb-0">Produk Unggulan</h2>
        </div>
        <a href="{{ url('/products') }}" class="text-dark small fw-semibold text-decoration-none d-flex align-items-center gap-1">
            <span>Lihat Semua</span>
            <i class="bi bi-chevron-right small"></i>
        </a>
    </div>

    <div class="row g-4">
        @forelse($featuredProducts as $product)
            <div class="col-sm-6 col-md-4">
                <div class="card product-grid-card">
                    <div class="product-image-wrapper">
                        <a href="{{ url('/products/' . $product->slug) }}" class="product-image-container">
                            @if($product->image && file_exists(public_path($product->image)))
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="product-fallback-img">
                                    @if($product->category->slug === 'figures')
                                        <i class="bi bi-box-seam text-secondary"></i>
                                    @elseif($product->category->slug === 'model-kits')
                                        <i class="bi bi-tools text-secondary"></i>
                                    @elseif($product->category->slug === 'character-goods')
                                        <i class="bi bi-gem text-secondary"></i>
                                    @else
                                        <i class="bi bi-hearts text-secondary"></i>
                                    @endif
                                    <span class="fs-6 mt-2 text-uppercase tracking-wider small fw-bold text-muted">{{ $product->category->name }}</span>
                                </div>
                            @endif
                        </a>
                    </div>
                    
                    <div class="product-card-body">
                        <div>
                            <div class="product-category-label">{{ $product->category->name }}</div>
                            <a href="{{ url('/products/' . $product->slug) }}" class="product-title">
                                {{ $product->name }}
                            </a>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-light">
                            <span class="product-price">{{ $product->formatted_price }}</span>
                            <a href="{{ url('/products/' . $product->slug) }}" class="btn btn-minimal-secondary btn-sm py-1 px-3" style="font-size: 0.8rem;">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Tidak ada produk unggulan saat ini.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
