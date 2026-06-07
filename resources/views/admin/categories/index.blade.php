@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('styles')
<style>
    .category-thumb {
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
            <p class="text-muted small mb-0">Halaman khusus Administrator untuk mengelola produk, kategori, dan pesanan YASSUI.</p>
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
                    <a class="nav-link" href="{{ route('admin.products.index') }}">
                        <i class="bi bi-box-seam me-1"></i> Kelola Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.categories.index') }}">
                        <i class="bi bi-tags me-1"></i> Kelola Kategori
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
            <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-10">
                    <label for="sort" class="form-label small fw-semibold text-muted mb-1">Urutan Kategori</label>
                    <select name="sort" id="sort" class="form-select form-select-sm" style="border-radius: 4px; padding: 6px 12px;">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru (Newest / Latest)</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama (Oldest)</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold" style="font-size: 0.8rem; border-radius: 4px; padding: 7px 12px;">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories List Card -->
    <div class="card shadow-sm border border-slate-200">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;"><i class="bi bi-tags me-1 text-muted"></i> Daftar Kategori Produk</h5>
            
            <a href="{{ route('admin.categories.create') }}" class="btn-minimal-accent btn-sm py-2 px-3 text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.8rem; border-radius: 4px;">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Kategori Baru</span>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4" style="width: 70px;">Ikon / Foto</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Produk</th>
                            <th class="text-end pe-4" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr class="animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                                <td class="ps-4">
                                    @if($category->image && file_exists(public_path($category->image)))
                                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="category-thumb">
                                    @else
                                        <div class="fallback-thumb">
                                            @if($category->slug === 'figures')
                                                <i class="bi bi-box-seam"></i>
                                            @elseif($category->slug === 'model-kits')
                                                <i class="bi bi-tools"></i>
                                            @elseif($category->slug === 'character-goods')
                                                <i class="bi bi-gem"></i>
                                            @else
                                                <i class="bi bi-tag-fill"></i>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $category->name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">slug: {{ $category->slug }}</div>
                                </td>
                                <td>
                                    <div class="text-muted" style="max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $category->description ?: '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1" style="font-size: 0.725rem; font-weight: 600;">
                                        {{ $category->products_count }} Produk
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-dark btn-sm fw-semibold" style="font-size: 0.75rem; border-radius: 4px; padding: 5px 10px;">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline delete-category-form">
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
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada kategori yang ditambahkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination Section -->
    @if($categories->hasPages())
        <div class="pagination-wrapper mt-5 pt-4 border-top border-light">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script nonce="{{ app('csp-nonce') }}">
    document.addEventListener('DOMContentLoaded', function() {
        // Handle custom confirmation modals on category deletions
        document.querySelectorAll('.delete-category-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (form.dataset.confirmed === 'true') {
                    return;
                }
                
                e.preventDefault();
                window.premiumConfirm(
                    'Apakah Anda yakin ingin menghapus kategori ini?',
                    'Hapus Kategori'
                ).then(confirmed => {
                    if (confirmed) {
                        form.dataset.confirmed = 'true';
                        if (typeof form.requestSubmit === 'function') {
                            form.requestSubmit();
                        } else {
                            form.submit();
                        }
                    }
                });
            });
        });

        // Filter sort select listener to avoid inline handlers
        const sortSelect = document.getElementById('sort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const form = this.form;
                if (form) {
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                }
            });
        }
    });
</script>
@endsection
