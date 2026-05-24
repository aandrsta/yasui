@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="container py-4" style="max-width: 700px;">
    <!-- Back to categories index -->
    <div class="mb-4">
        <a href="{{ route('admin.categories.index') }}" class="text-secondary small text-decoration-none d-inline-flex align-items-center gap-1">
            <i class="bi bi-chevron-left small"></i>
            <span>Kembali ke Kelola Kategori</span>
        </a>
    </div>

    <div class="card shadow-sm border border-slate-200">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;"><i class="bi bi-pencil me-1 text-muted"></i> Formulir Edit Kategori</h5>
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

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Category Name -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">Nama Kategori</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="form-control" placeholder="Contoh: Figures atau Model Kits" required style="border-radius: 6px; font-size: 0.9rem;">
                </div>

                <!-- Existing Image Preview if exists -->
                @if($category->image && file_exists(public_path($category->image)))
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark d-block" style="font-size: 0.85rem;">Ikon / Gambar Kategori Saat Ini</label>
                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="img-thumbnail" style="max-height: 120px; object-fit: cover;">
                    </div>
                @endif

                <!-- Category Image -->
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">Unggah Ikon / Gambar Kategori Baru (Opsional)</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*" style="border-radius: 6px; font-size: 0.9rem;">
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Format gambar yang didukung: JPEG, JPG, PNG, WEBP. Maksimal file: 2 MB. Biarkan kosong jika tidak ingin mengubah gambar.</small>
                </div>

                <!-- Category Description -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold text-dark" style="font-size: 0.85rem;">Deskripsi Kategori</label>
                    <textarea name="description" id="description" rows="4" class="form-control" placeholder="Tuliskan penjelasan singkat mengenai jenis produk yang termasuk dalam kategori ini." style="border-radius: 6px; font-size: 0.9rem; resize: vertical;">{{ old('description', $category->description) }}</textarea>
                </div>

                <!-- Action buttons -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-4 fw-semibold" style="font-size: 0.875rem; border-radius: 6px;">
                        Batal
                    </a>
                    <button type="submit" class="btn-minimal-accent px-4 py-2 d-inline-flex align-items-center gap-1 border-0" style="font-size: 0.875rem; border-radius: 6px;">
                        <i class="bi bi-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
