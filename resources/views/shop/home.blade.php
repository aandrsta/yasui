@extends('layouts.app')

@section('title', 'Beranda')

@section('styles')
<style>
    /* Clean stark hero */
    .hero-section {
        padding: 6rem 0 5rem 0;
        background-color: var(--bg-main);
        margin-bottom: 4rem;
        margin-top: -3rem; /* Align higher for a premium feel */
        position: relative;
    }

    .hero-title {
        font-family: 'Zen Old Mincho', serif;
        font-weight: 300;
        font-size: 3.8rem;
        letter-spacing: -0.02em;
        color: var(--primary-color);
        line-height: 1.15;
    }

    .hero-title em {
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        font-weight: 400;
        color: var(--accent-color);
    }

    .hero-subtitle {
        font-size: 1.1rem;
        color: var(--text-muted);
        line-height: 1.7;
        font-weight: 400;
        max-width: 600px;
    }

    /* Vertical Poetry Stamp Overlay */
    .vertical-poetry {
        position: absolute;
        left: -40px;
        top: 20%;
        writing-mode: vertical-rl;
        text-orientation: upright;
        font-family: 'Zen Old Mincho', serif;
        font-size: 0.75rem;
        letter-spacing: 0.4em;
        color: var(--text-muted);
        opacity: 0.4;
        display: none;
    }
    @media (min-width: 1400px) {
        .vertical-poetry {
            display: block;
        }
    }

    /* Red Stamp Seal */
    .stamp-seal {
        width: 48px;
        height: 48px;
        border: 2px solid var(--accent-color);
        color: var(--accent-color);
        font-family: 'Zen Old Mincho', serif;
        font-size: 0.65rem;
        font-weight: 800;
        line-height: 1.1;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transform: rotate(-8deg);
        box-shadow: 0 0 0 2px var(--bg-main);
        background-color: var(--bg-main);
    }

    /* Editorial Visual Showcase Card */
    .editorial-showcase {
        position: relative;
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
        padding: 1.25rem;
        border-radius: 4px;
        overflow: hidden;
        transition: var(--transition-base);
    }
    
    .editorial-showcase:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .editorial-image-frame {
        border: 1px solid var(--border-color);
        border-radius: 3px;
        background-color: var(--bg-main);
        overflow: hidden;
        position: relative;
        padding-top: 110%; /* museum portrait ratio */
    }

    .editorial-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .editorial-showcase:hover .editorial-image {
        transform: scale(1.04);
    }

    .editorial-stamp-overlay {
        position: absolute;
        top: 2rem;
        right: 2rem;
        z-index: 10;
    }

    .editorial-caption {
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        font-size: 1.1rem;
        color: var(--text-main);
        line-height: 1.4;
        text-align: center;
    }

    .trust-section {
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        padding: 2.5rem 0;
        margin-bottom: 5rem;
        background-color: var(--bg-main);
    }

    .trust-col {
        border-right: 1px solid var(--border-color);
    }

    .trust-col:last-child {
        border-right: none;
    }

    @media (max-width: 768px) {
        .trust-col {
            border-right: none;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .trust-col:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
    }

    .trust-title {
        font-family: 'Zen Old Mincho', serif;
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--primary-color);
        letter-spacing: -0.01em;
    }

    /* Brand Manifesto & Newsletter */
    .manifesto-section {
        background-color: var(--primary-color);
        color: var(--bg-main);
        border-radius: 4px;
        padding: 4rem 3rem;
        margin-top: 5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .manifesto-section::before {
        content: "美";
        position: absolute;
        right: -2rem;
        bottom: -4rem;
        font-family: 'Zen Old Mincho', serif;
        font-size: 20rem;
        color: rgba(251, 250, 247, 0.03);
        line-height: 1;
        pointer-events: none;
    }

    .manifesto-title {
        font-family: 'Zen Old Mincho', serif;
        font-weight: 300;
        font-size: 2.5rem;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .manifesto-subtitle {
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        font-size: 1.25rem;
        color: var(--border-color);
        opacity: 0.9;
    }

    .newsletter-form .form-control {
        background-color: var(--bg-main);
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        border-radius: 3px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: var(--transition-base);
    }

    .newsletter-form .form-control::placeholder {
        color: var(--text-muted) !important;
    }

    .newsletter-form .form-control:focus {
        background-color: #ffffff;
        border-color: var(--accent-color);
        box-shadow: none;
        color: var(--primary-color);
    }

    .newsletter-form .btn-newsletter {
        background-color: var(--bg-main);
        color: var(--primary-color);
        border: 1px solid var(--bg-main);
        font-weight: 600;
        font-size: 0.9rem;
        border-radius: 3px;
        padding: 0.75rem 2rem;
        transition: var(--transition-base);
    }

    .newsletter-form .btn-newsletter:hover {
        background-color: var(--primary-color);
        color: var(--bg-main);
        border-color: var(--bg-main);
    }

    /* Font Utility Class */
    .font-mincho {
        font-family: 'Zen Old Mincho', serif;
    }

    /* Minimalist Category Cards */
    .category-card {
        background-color: var(--bg-subtle);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        transition: var(--transition-base);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .category-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .category-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 3px;
        background-color: var(--bg-main);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        border: 1px solid var(--border-color);
    }

    /* Product Grid Cards */
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

    .product-title {
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

    .product-title:hover {
        color: var(--accent-color);
    }

    .product-price {
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--primary-color);
    }

    .section-title {
        font-family: 'Zen Old Mincho', serif;
        font-weight: 500;
        font-size: 2.2rem;
        letter-spacing: -0.02em;
        color: var(--primary-color);
    }
</style>
@endsection

@section('content')
<!-- Hero Section (Minimal Premium Stark) -->
<section class="hero-section">
    <!-- Vertical Poetry Stamp Overlay -->
    <div class="vertical-poetry">
        美意識を蒐集する — 創立二〇二六 — 日本の美
    </div>

    <div class="row align-items-center py-4">
        <div class="col-lg-7 mb-5 mb-lg-0 position-relative">
            <h1 class="hero-title mb-4 mt-2 font-mincho">Destinasi Pop Culture<br><em>Original</em> &amp; <em>Premium</em>.</h1>
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
        <div class="col-lg-5">
            <!-- Editorial Visual Showcase Card -->
            <div class="editorial-showcase shadow-sm">
                <div class="editorial-stamp-overlay">
                    <div class="stamp-seal">
                        YASSUI<br>渋谷
                    </div>
                </div>
                <div class="editorial-image-frame mb-3">
                    <img src="{{ asset('yassui_hero_banner.png') }}" alt="YASSUI Curated Art Showcase" class="editorial-image">
                </div>
                <div class="editorial-caption font-mincho">
                    “Koleksi bukan sekadar hobi, melainkan bentuk apresiasi tertinggi terhadap karya seni.”
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Value Showcase Row -->
<section class="trust-section">
    <div class="container">
        <div class="row g-4 text-center text-md-start">
            <div class="col-md-4 trust-col">
                <div class="d-flex align-items-center gap-3">
                    <div class="fs-3" style="color: var(--accent-color);"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <h4 class="trust-title mb-1">100% Otentik &amp; Asli</h4>
                        <p class="small text-muted mb-0">Semua produk diimpor langsung dari produsen resmi di Jepang.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 trust-col">
                <div class="d-flex align-items-center gap-3">
                    <div class="fs-3" style="color: var(--accent-color);"><i class="bi bi-box-seam"></i></div>
                    <div>
                        <h4 class="trust-title mb-1">Standar Kolektor</h4>
                        <p class="small text-muted mb-0">Pengemasan ekstra aman dengan proteksi gelembung ganda &amp; dus kokoh.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 trust-col">
                <div class="d-flex align-items-center gap-3">
                    <div class="fs-3" style="color: var(--accent-color);"><i class="bi bi-gem"></i></div>
                    <div>
                        <h4 class="trust-title mb-1">Kurasi Terseleksi</h4>
                        <p class="small text-muted mb-0">Setiap item dipilih khusus oleh kurator berpengalaman untuk kolektor sejati.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="kategori" class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom border-light pb-3">
        <div>
            <span class="text-uppercase text-muted fw-semibold small letter-spacing-1">Koleksi Kami</span>
            <h2 class="section-title mt-1 mb-0 font-mincho">Belanja Berdasarkan Kategori</h2>
        </div>
    </div>
    
    <div class="row g-4">
        @foreach($categories as $category)
            <div class="col-md-3">
                <div class="card category-card p-3 shadow-sm">
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
                            <div class="position-absolute top-2 start-2 bg-white rounded-2 p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; color: var(--accent-color); border: 1px solid var(--border-color);">
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
                        <h4 class="fw-bold mb-2 small text-dark fs-6 font-mincho">{{ $category->name }}</h4>
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
    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom border-light pb-3">
        <div>
            <span class="text-uppercase text-muted fw-semibold small letter-spacing-1">Pilihan Terbaik</span>
            <h2 class="section-title mt-1 mb-0 font-mincho">Produk Unggulan</h2>
        </div>
        <a href="{{ url('/products') }}" class="text-dark small fw-semibold text-decoration-none d-flex align-items-center gap-1">
            <span>Lihat Semua</span>
            <i class="bi bi-chevron-right small"></i>
        </a>
    </div>

    <div class="row g-4">
        @forelse($featuredProducts as $product)
            <div class="col-sm-6 col-md-4">
                <div class="card product-grid-card shadow-sm">
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
                                    <span class="fs-6 mt-2 text-uppercase tracking-wider small fw-bold text-muted font-mincho">{{ $product->category->name }}</span>
                                </div>
                            @endif
                        </a>
                    </div>
                    
                    <div class="product-card-body">
                        <div>
                            <div class="product-category-label">{{ $product->category->name }}</div>
                            <a href="{{ url('/products/' . $product->slug) }}" class="product-title font-mincho">
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

<!-- Brand Manifesto & Newsletter Section -->
<section class="manifesto-section shadow-sm">
    <div class="row align-items-center justify-content-between g-4">
        <div class="col-lg-6">
            <span class="text-uppercase text-muted fw-bold small letter-spacing-2" style="color: var(--accent-color) !important;">#ManifestoYassui</span>
            <h3 class="manifesto-title mt-2 mb-3">Keindahan Detail yang Abadi</h3>
            <p class="mb-4 text-light" style="opacity: 0.85; line-height: 1.7; font-size: 0.95rem;">
                YASSUI hadir untuk menjembatani kecintaan Anda terhadap budaya pop Jepang dan standar kualitas tinggi. Kami percaya bahwa setiap pajangan dan miniatur memiliki kisah, jerih payah perancang, dan keindahan yang layak dipelihara selamanya.
            </p>
            <div class="manifesto-subtitle">
                — Menghadirkan kesempurnaan langsung ke genggaman Anda.
            </div>
        </div>
        <div class="col-lg-5">
            <div class="p-4 border border-light border-opacity-10 rounded bg-white bg-opacity-5">
                <h4 class="h5 fw-bold mb-2 font-mincho text-dark">Bergabung ke Guild Kolektor</h4>
                <p class="small text-dark mb-4" style="opacity: 0.75;">Dapatkan pembaruan produk langka, penawaran kurasi khusus, dan newsletter premium langsung di inbox Anda.</p>
                <form action="#" class="newsletter-form" onsubmit="event.preventDefault(); alert('Terima kasih! Anda telah bergabung ke newsletter guild YASSUI.');">
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Masukkan alamat email Anda" required>
                    </div>
                    <button type="submit" class="btn btn-newsletter w-100">Daftar Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
