@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('styles')
<style>
    .product-thumb {
        width: 45px;
        height: 45px;
        border-radius: 4px;
        object-fit: cover;
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
    }
    .fallback-thumb {
        width: 45px;
        height: 45px;
        border-radius: 4px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 1.15rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Admin Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1" style="letter-spacing: -0.03em;">Panel Pengelola Toko</h1>
            <p class="text-muted small mb-0">Halaman khusus Administrator untuk mengelola produk dan pesanan YASSUI.</p>
        </div>
        <span class="badge bg-dark text-white px-3 py-2 fw-semibold" style="font-size: 0.8rem; border-radius: 4px;">Role: Admin</span>
    </div>

    <!-- Admin Navigation Menu (Sangat Simple & Bersih) -->
    <div class="card mb-4 shadow-sm border border-slate-200">
        <div class="card-header bg-white p-0">
            <ul class="nav nav-tabs admin-nav border-0 px-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.products.index') }}">
                        <i class="bi bi-box-seam me-1"></i> Kelola Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-receipt me-1"></i> Kelola Pesanan
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Filters & Sorting Card -->
    <div class="card mb-4 shadow-sm border border-slate-200">
        <div class="card-body py-3">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="category" class="form-label small fw-semibold text-muted mb-1">Filter Kategori</label>
                    <select name="category" id="category" class="form-select form-select-sm" style="border-radius: 4px; padding: 6px 12px;" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="sort" class="form-label small fw-semibold text-muted mb-1">Urutan Produk</label>
                    <select name="sort" id="sort" class="form-select form-select-sm" style="border-radius: 4px; padding: 6px 12px;" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru (Newest / Latest)</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama (Oldest)</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold" style="font-size: 0.8rem; border-radius: 4px; padding: 7px 12px;">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products List Card -->
    <div class="card shadow-sm border border-slate-200">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;"><i class="bi bi-card-list me-1 text-muted"></i> Daftar Inventaris Produk</h5>
            
            <a href="{{ route('admin.products.create') }}" class="btn-minimal-accent btn-sm py-2 px-3 text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.8rem; border-radius: 4px;">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Produk Baru</span>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4" style="width: 70px;">Foto</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga Satuan</th>
                            <th>Sisa Stok</th>
                            <th>Tipe Tampil</th>
                            <th class="text-end pe-4" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-4">
                                    @if($product->image && file_exists(public_path($product->image)))
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-thumb">
                                    @else
                                        <div class="fallback-thumb">
                                            @if($product->category->slug === 'figures')
                                                <i class="bi bi-box-seam"></i>
                                            @elseif($product->category->slug === 'model-kits')
                                                <i class="bi bi-tools"></i>
                                            @elseif($product->category->slug === 'character-goods')
                                                <i class="bi bi-gem"></i>
                                            @else
                                                <i class="bi bi-hearts"></i>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark" style="max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $product->name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">slug: {{ $product->slug }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1" style="font-size: 0.725rem; font-weight: 600;">
                                        {{ $product->category->name }}
                                    </span>
                                </td>
                                <td class="fw-semibold text-dark">{{ $product->formatted_price }}</td>
                                <td>
                                    @if($product->stock === 0)
                                        <span class="text-danger fw-bold">Habis</span>
                                    @elseif($product->stock <= 3)
                                        <span class="text-warning fw-bold">{{ $product->stock }} (Kritis)</span>
                                    @else
                                        <span class="text-dark fw-medium">{{ $product->stock }} Pcs</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->is_featured)
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1" style="font-size: 0.725rem; font-weight: 600;">
                                            <i class="bi bi-star-fill me-0.5" style="font-size: 0.65rem;"></i> Rekomendasi
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size: 0.8rem;">Standar</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-dark btn-sm fw-semibold" style="font-size: 0.75rem; border-radius: 4px; padding: 5px 10px;">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini dari katalog?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold" style="font-size: 0.75rem; border-radius: 4px; padding: 5px 10px;">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada produk yang didaftarkan di katalog.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <!-- Pagination Section -->
        @if($products->hasPages())
            <div class="pagination-wrapper mt-5 pt-4 border-top border-light">
                {{ $products->links() }}
            </div>
        @endif
</div>
@endsection
