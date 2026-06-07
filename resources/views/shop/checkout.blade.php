@extends('layouts.app')

@section('title', 'Checkout')

@section('styles')
<style>
    /* Checkout Visual Layout Styling */
    .checkout-wrapper {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2.5rem;
        align-items: start;
    }

    @media (max-width: 991.98px) {
        .checkout-wrapper {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }

    .form-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 2rem;
    }

    .section-subtitle {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        letter-spacing: -0.02em;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.75rem;
    }

    /* Checkout summary list */
    .order-summary-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 2rem;
        position: sticky;
        top: 100px;
    }

    .summary-item-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--bg-subtle);
    }

    .summary-item-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .summary-item-img {
        width: 50px;
        height: 50px;
        border-radius: 3px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .summary-fallback-icon {
        font-size: 1.25rem;
        color: var(--text-muted);
    }

    .summary-item-details {
        flex-grow: 1;
        min-width: 0;
    }

    .summary-item-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--primary-color);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        margin-bottom: 0.15rem;
    }

    .summary-item-meta {
        font-size: 0.75rem;
        color: var(--text-muted);
        display: flex;
        justify-content: space-between;
    }

    .calc-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        color: var(--text-muted);
    }

    .calc-row.total-row {
        border-top: 1px solid var(--border-color);
        padding-top: 1.25rem;
        margin-top: 0.75rem;
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--primary-color);
    }

    /* Checkout Loading Overlay */
    #checkout-loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(251, 250, 247, 0.96); /* Warm paper with slight transparency */
        z-index: 10100;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.5s ease;
    }
    #checkout-loading-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }
    .loading-floral-icon {
        width: 60px;
        height: 60px;
        color: var(--accent-color);
        animation: spin 4s linear infinite;
        margin-bottom: 2rem;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .loading-text {
        font-family: 'Zen Old Mincho', serif;
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
        text-align: center;
    }
    .loading-subtext {
        font-size: 0.875rem;
        color: var(--text-muted);
        text-align: center;
        max-width: 320px;
        line-height: 1.6;
    }
</style>
@endsection

@section('content')
<!-- Back to Cart -->
<div class="mb-4">
    <a href="{{ url('/cart') }}" class="text-secondary small text-decoration-none d-inline-flex align-items-center gap-1">
        <i class="bi bi-chevron-left small"></i>
        <span>Kembali ke Keranjang</span>
    </a>
</div>

<h1 class="h3 fw-bold text-dark mb-4 pb-2" style="letter-spacing: -0.03em;">Checkout Pesanan</h1>

<form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
    @csrf
    @foreach($selectedIds as $selectedId)
        <input type="hidden" name="items[]" value="{{ $selectedId }}">
    @endforeach
    
    <div class="checkout-wrapper animate-fade-in-up">
        <!-- Left Side: Shipping Address Form -->
        <div class="form-card shadow-sm">
            <h3 class="section-subtitle"><i class="bi bi-truck me-2 text-secondary"></i>Informasi Pengiriman</h3>
            
            <!-- Shipping Name -->
            <div class="mb-3">
                <label for="shipping_name" class="form-label small fw-semibold text-secondary">Nama Lengkap Penerima</label>
                <input type="text" name="shipping_name" id="shipping_name" class="form-control @error('shipping_name') is-invalid @enderror" value="{{ old('shipping_name', auth()->user()->name) }}" required minlength="3" maxlength="100" pattern="[A-Za-z\s]+" title="Nama hanya boleh berisi huruf dan spasi" placeholder="Masukkan nama lengkap penerima" style="border-radius: 3px; padding: 10px; border-color: var(--border-color); background-color: var(--bg-subtle); font-size: 0.9rem;">
                @error('shipping_name')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Shipping Phone -->
            <div class="mb-3">
                <label for="shipping_phone" class="form-label small fw-semibold text-secondary">Nomor Telepon Penerima</label>
                <input type="tel" name="shipping_phone" id="shipping_phone" class="form-control @error('shipping_phone') is-invalid @enderror" value="{{ old('shipping_phone') }}" required inputmode="numeric" pattern="[0-9]{9,15}" title="Nomor telepon harus berupa angka antara 9 sampai 15 digit" placeholder="Contoh: 081234567890" style="border-radius: 3px; padding: 10px; border-color: var(--border-color); background-color: var(--bg-subtle); font-size: 0.9rem;">
                @error('shipping_phone')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Shipping Address -->
            <div class="mb-3">
                <label for="shipping_address" class="form-label small fw-semibold text-secondary">Alamat Lengkap Pengiriman</label>
                <textarea name="shipping_address" id="shipping_address" rows="4" class="form-control @error('shipping_address') is-invalid @enderror" required placeholder="Tuliskan alamat jalan, RT/RW, kelurahan, kecamatan, kota/kabupaten, provinsi, dan kode pos" style="border-radius: 3px; padding: 10px; border-color: var(--border-color); background-color: var(--bg-subtle); font-size: 0.9rem;">{{ old('shipping_address') }}</textarea>
                @error('shipping_address')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Right Side: Order Summary Panel -->
        <div class="order-summary-card shadow-sm">
            <h3 class="summary-title mb-4">Tinjau Pesanan</h3>
            
            <!-- Items summary list -->
            <div class="mb-4" style="max-height: 240px; overflow-y: auto; border: 1px solid var(--border-color); padding: 1rem; border-radius: 3px; background-color: var(--bg-subtle);">
                @foreach($cartItems as $item)
                    <div class="summary-item-row">
                        <!-- Tiny image -->
                        <div class="summary-item-img">
                            @if($item->product->image && file_exists(public_path($item->product->image)))
                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="summary-fallback-icon">
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
                        
                        <!-- Details -->
                        <div class="summary-item-details">
                            <span class="summary-item-title" title="{{ $item->product->name }}">{{ $item->product->name }}</span>
                            <div class="summary-item-meta">
                                <span>Qty: <strong>{{ $item->quantity }}</strong></span>
                                <span class="fw-semibold text-dark">{{ 'Rp ' . number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Price Calculations -->
            <div class="calc-row">
                <span>Subtotal ({{ $cartItems->sum('quantity') }} barang)</span>
                <span class="text-dark fw-semibold">{{ $formattedTotalPrice }}</span>
            </div>
            
            <div class="calc-row">
                <span>Ongkos Kirim</span>
                <span class="text-success fw-semibold">Gratis</span>
            </div>

            <div class="calc-row total-row">
                <span>Total Bayar</span>
                <span>{{ $formattedTotalPrice }}</span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-minimal-accent w-100 py-3 mt-4 text-center border-0 fw-semibold shadow-sm d-inline-block">
                Buat Pesanan & Bayar
            </button>
            
            <p class="text-muted small text-center mt-3 mb-0" style="font-size: 0.75rem; line-height: 1.4;">
                Dengan menekan tombol, Anda menyetujui seluruh syarat & ketentuan pembelian produk di toko kami.
            </p>
        </div>
    </div>
</form>

<!-- Secure Gateway Loading Overlay -->
<div id="checkout-loading-overlay">
    <!-- Beautiful rotating cherry blossom or custom geometric floral icon using SVG -->
    <svg class="loading-floral-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
        <circle cx="12" cy="12" r="5"/>
    </svg>
    <h3 class="loading-text">Menghubungkan Saluran Pembayaran</h3>
    <p class="loading-subtext font-mincho">美意識を蒐集する — Mohon tunggu sebentar selagi kami mengarahkan Anda ke gerbang pembayaran aman Midtrans.</p>
</div>

@endsection

@section('scripts')
<script nonce="{{ app('csp-nonce') }}">
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('checkout-form');
        const overlay = document.getElementById('checkout-loading-overlay');
        const phoneInput = document.getElementById('shipping_phone');
        const nameInput = document.getElementById('shipping_name');
        
        // Stricter real-time inputs restriction (Client-side)
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        if (nameInput) {
            nameInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
            });
        }

        if (form && overlay) {
            form.addEventListener('submit', function(e) {
                if (form.dataset.confirmed === 'true') {
                    return;
                }
                
                e.preventDefault();
                
                // Check native HTML5 validation
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                // Uniform custom pop up confirmation
                window.premiumConfirm(
                    'Apakah Anda yakin data pengiriman sudah benar dan ingin memproses pesanan ini?',
                    'Konfirmasi Pesanan'
                ).then(confirmed => {
                    if (confirmed) {
                        form.dataset.confirmed = 'true';
                        overlay.classList.add('active');
                        if (typeof form.requestSubmit === 'function') {
                            form.requestSubmit();
                        } else {
                            form.submit();
                        }
                    }
                });
            });
        }
    });
</script>
@endsection
