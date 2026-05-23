@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('styles')
<style>
    /* Catalog Layout */
    .filter-sidebar {
        border-right: 1px solid var(--border-color);
        padding-right: 2rem;
    }

    @media (max-width: 991.98px) {
        .filter-sidebar {
            border-right: none;
            border-bottom: 1px solid var(--border-color);
            padding-right: 0;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }
    }

    .filter-title {
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--primary-color);
        margin-bottom: 1.25rem;
    }

    .category-filter-link {
        font-size: 0.9rem;
        color: var(--text-muted);
        text-decoration: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        transition: var(--transition-base);
    }

    .category-filter-link:hover, .category-filter-link.active {
        color: var(--primary-color);
        font-weight: 600;
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

    /* Minimalist Form Select */
    .minimal-select {
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
        border-radius: 6px;
        font-size: 0.875rem;
        padding: 8px 12px;
        transition: var(--transition-base);
    }

    .minimal-select:focus {
        outline: none;
        border-color: #94a3b8;
        background-color: #ffffff;
    }

    /* Pagination design styling */
    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        color: var(--text-muted);
        border: 1px solid var(--border-color);
        padding: 8px 16px;
        font-size: 0.875rem;
    }

    .page-link:hover {
        color: var(--primary-color);
        background-color: var(--bg-subtle);
        border-color: var(--border-color);
    }

    .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #ffffff;
    }

    .page-item.disabled .page-link {
        color: #cbd5e1;
        background-color: #ffffff;
        border-color: var(--border-color);
    }
</style>
@endsection

@section('content')
<div class="row">
    <!-- Filter Sidebar (Left Column) -->
    <div class="col-lg-3">
        <div class="filter-sidebar">
            <h2 class="h5 fw-bold text-dark mb-4" style="letter-spacing: -0.03em;">Filter Produk</h2>
            
            <form action="{{ url('/products') }}" method="GET" id="filterForm">
                <!-- Retain current filters during submit -->
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                
                <!-- Search bar -->
                <div class="mb-4">
                    <label class="filter-title">Pencarian</label>
                    <div class="input-group">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama produk..." style="font-size: 0.875rem; padding: 10px; border-radius: 6px; border: 1px solid var(--border-color);">
                        @if(request('q'))
                            <a href="{{ url('/products') . (request('category') ? '?category='.request('category') : '') . (request('sort') ? (request('category') ? '&' : '?').'sort='.request('sort') : '') }}" class="btn btn-outline-secondary d-flex align-items-center" style="border-color: var(--border-color);">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Sort Order -->
                <div class="mb-4">
                    <label class="filter-title">Urutkan</label>
                    <select name="sort" class="form-select minimal-select w-100" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                    </select>
                </div>
            </form>

            <!-- Category Filter Links -->
            <div class="mb-4">
                <label class="filter-title">Kategori</label>
                <div class="d-flex flex-column">
                    <a href="{{ url('/products') . (request('q') ? '?q='.request('q') : '') . (request('sort') ? (request('q') ? '&' : '?').'sort='.request('sort') : '') }}" class="category-filter-link {{ !request('category') ? 'active' : '' }}">
                        <span>Semua Kategori</span>
                        <span class="badge bg-light text-dark border small fw-normal">{{ \App\Models\Product::count() }}</span>
                    </a>
                    
                    @foreach($categories as $category)
                        <a href="{{ url('/products?category=' . $category->slug) . (request('q') ? '&q='.request('q') : '') . (request('sort') ? '&sort='.request('sort') : '') }}" class="category-filter-link {{ request('category') == $category->slug ? 'active' : '' }}">
                            <span>{{ $category->name }}</span>
                            <span class="badge bg-light text-dark border small fw-normal">{{ $category->products()->count() }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            
            <!-- Clear Filters Button -->
            @if(request('q') || request('category') || request('sort'))
                <a href="{{ url('/products') }}" class="btn btn-minimal-secondary w-100 py-2 small d-inline-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    <span>Hapus Semua Filter</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Product Grid (Right Column) -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light">
            <div>
                <h1 class="h3 fw-bold text-dark mb-0" style="letter-spacing: -0.03em;">Katalog Produk</h1>
                <p class="text-muted small mb-0 mt-1">Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($products as $product)
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
                            
                            <div>
                                <div class="small text-muted mb-2">
                                    @if($product->stock > 0)
                                        <span>Stok: <strong class="text-dark">{{ $product->stock }}</strong></span>
                                    @else
                                        <span class="text-danger fw-semibold">Habis</span>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top border-light">
                                    <span class="product-price">{{ $product->formatted_price }}</span>
                                    <a href="{{ url('/products/' . $product->slug) }}" class="btn btn-minimal-secondary btn-sm py-1 px-3" style="font-size: 0.8rem;">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-search fs-3"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark">Produk Tidak Ditemukan</h3>
                    <p class="text-muted small mx-auto" style="max-width: 360px;">Kami tidak dapat menemukan produk yang sesuai dengan filter pencarian Anda. Coba kata kunci atau filter lain.</p>
                    <a href="{{ url('/products') }}" class="btn btn-minimal-primary mt-2">Lihat Semua Produk</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination Section -->
        @if($products->hasPages())
            <div class="d-flex justify-content-center mt-5 pt-4 border-top border-light">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
