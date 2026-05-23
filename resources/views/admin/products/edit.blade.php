@extends('layouts.app')

@section('title', 'Edit Produk')

@section('styles')
<style>
    .current-image-preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
    }
</style>
@endsection

@section('content')
<div class="container py-4" style="max-width: 700px;">
    <!-- Back to products index -->
    <div class="mb-4">
        <a href="{{ route('admin.products.index') }}" class="text-secondary small text-decoration-none d-inline-flex align-items-center gap-1">
            <i class="bi bi-chevron-left small"></i>
            <span>Kembali ke Kelola Produk</span>
        </a>
    </div>

    <div class="card shadow-sm border border-slate-200">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;"><i class="bi bi-pencil-square me-1 text-muted"></i> Formulir Edit Data Produk</h5>
        </div>
        <div class="card-body p-4">
            
            @if ($errors->any())
                <div class="alert alert-danger border-1 d-flex flex-column mb-4 py-3 px-4" role="alert" style="border-radius: 6px; background-color: #fef2f2; border-color: #fecaca; color: #991b1b; font-size: 0.85rem;">
                    <span class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan input:</span>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Product Name -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">Nama Produk Anime</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="form-control" placeholder="Contoh: Nendoroid Gojo Satoru" required style="border-radius: 6px; font-size: 0.9rem;">
                </div>

                <div class="row">
                    <!-- Category Selection -->
                    <div class="col-md-6 col-12 mb-3">
                        <label for="category_id" class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">Kategori Produk</label>
                        <select name="category_id" id="category_id" class="form-select" required style="border-radius: 6px; font-size: 0.9rem;">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Display Type checkbox -->
                    <div class="col-md-6 col-12 mb-3 d-flex align-items-end pb-2">
                        <div class="form-check">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="form-check-input">
                            <label for="is_featured" class="form-check-label fw-medium text-dark" style="font-size: 0.875rem;">
                                Jadikan Produk Rekomendasi (*Featured*)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Product Price -->
                    <div class="col-md-6 col-12 mb-3">
                        <label for="price" class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">Harga Jual (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background-color: var(--bg-subtle); font-size: 0.9rem; border-radius: 6px 0 0 6px;">Rp</span>
                            <input type="number" name="price" id="price" value="{{ old('price', (int) $product->price) }}" min="0" class="form-control" placeholder="700000" required style="border-radius: 0 6px 6px 0; font-size: 0.9rem;">
                        </div>
                    </div>

                    <!-- Product Stock -->
                    <div class="col-md-6 col-12 mb-3">
                        <label for="stock" class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">Jumlah Ketersediaan Stok</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" class="form-control" placeholder="10" required style="border-radius: 6px; font-size: 0.9rem;">
                    </div>
                </div>

                <!-- Product Image -->
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">Ganti Gambar Produk (Opsional)</label>
                    
                    @if($product->image && file_exists(public_path($product->image)))
                        <div class="mb-2 d-flex align-items-center gap-3">
                            <img src="{{ asset($product->image) }}" alt="Gambar Lama" class="current-image-preview">
                            <span class="text-muted small">Gambar saat ini aktif di katalog. Unggah berkas baru jika ingin menggantinya.</span>
                        </div>
                    @endif

                    <input type="file" name="image" id="image" class="form-control" accept="image/*" style="border-radius: 6px; font-size: 0.9rem;">
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Format gambar yang didukung: JPEG, JPG, PNG, WEBP. Maksimal file: 2 MB.</small>
                </div>

                <!-- Product Description -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">Deskripsi & Rincian Produk</label>
                    <textarea name="description" id="description" rows="5" class="form-control" placeholder="Tuliskan spesifikasi produk, produsen, ukuran, kondisi barang, dll secara lengkap." required style="border-radius: 6px; font-size: 0.9rem; resize: vertical;">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Action buttons -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-4 fw-semibold" style="font-size: 0.875rem; border-radius: 6px;">
                        Batal
                    </a>
                    <button type="submit" class="btn-minimal-accent px-4 py-2 d-inline-flex align-items-center gap-1 border-0" style="font-size: 0.875rem; border-radius: 6px;">
                        <i class="bi bi-check-lg"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
