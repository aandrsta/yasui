@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('styles')
<style>
    /* Premium Minimalist Order Details Styling */
    .order-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2.5rem;
        align-items: start;
    }

    @media (max-width: 991.98px) {
        .order-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }

    .details-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 2rem;
    }
    .order-section-title {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 1.3rem;
        color: var(--primary-color);
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.5rem;
        margin-top: 2rem;
        margin-bottom: 1.25rem;
        letter-spacing: -0.02em;
    }

    .order-section-title:first-of-type {
        margin-top: 0;
    }

    .item-list-row {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--bg-subtle);
    }

    .item-list-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .item-img-box {
        width: 60px;
        height: 60px;
        border-radius: 3px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .item-fallback-icon {
        font-size: 1.5rem;
        color: var(--text-muted);
    }

    .item-detail-info {
        flex-grow: 1;
        min-width: 0;
    }

    .item-title-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--primary-color);
        text-decoration: none;
    }

    .item-price-quantity {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.15rem;
    }

    .item-total-sub {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--primary-color);
        text-align: right;
    }

    /* Side payment card */
    .payment-action-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 2rem;
        position: sticky;
        top: 100px;
    }

    .meta-data-box {
        background-color: var(--bg-subtle);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .meta-row:last-child {
        margin-bottom: 0;
    }

    .order-detail-title {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 2.2rem;
        letter-spacing: -0.01em;
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

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="order-detail-title text-dark mb-1">Detail Pesanan</h1>
        <p class="text-muted small mb-0">Nomor Pesanan: <strong class="text-dark">{{ $order->order_number }}</strong> · Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <!-- Order status badge -->
        <span class="badge-status {{ $order->status }}">
            <span class="indicator-dot" style="width:6px; height:6px; border-radius:50%; background-color:currentColor;"></span>
            {{ ucfirst($order->status) }}
        </span>
        
        <!-- Payment status badge -->
        <span class="badge-payment {{ $order->payment_status }}">
            {{ $order->payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
        </span>
    </div>
</div>

<div class="order-grid">
    <!-- Left Column: Ordered Items & Shipping Details -->
    <div class="details-card shadow-sm">
        <!-- Ordered Items -->
        <h3 class="order-section-title mt-0"><i class="bi bi-cart3 me-2 text-secondary"></i>Daftar Barang Belanjaan</h3>
        
        <div class="mb-2">
            @foreach($order->items as $item)
                <div class="item-list-row">
                    <!-- Image -->
                    <div class="item-img-box">
                        @if($item->product && $item->product->image && file_exists(public_path($item->product->image)))
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product_name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="item-fallback-icon">
                                @if($item->product && $item->product->category->slug === 'figures')
                                    <i class="bi bi-box-seam"></i>
                                @elseif($item->product && $item->product->category->slug === 'model-kits')
                                    <i class="bi bi-tools"></i>
                                @elseif($item->product && $item->product->category->slug === 'character-goods')
                                    <i class="bi bi-gem"></i>
                                @else
                                    <i class="bi bi-hearts"></i>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <!-- Info -->
                    <div class="item-detail-info">
                        @if($item->product)
                            <a href="{{ url('/products/' . $item->product->slug) }}" class="item-title-name">{{ $item->product_name }}</a>
                        @else
                            <span class="item-title-name text-muted">{{ $item->product_name }}</span>
                        @endif
                        <div class="item-price-quantity">
                            {{ 'Rp ' . number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}
                        </div>
                    </div>
                    
                    <!-- Subtotal -->
                    <div class="item-total-sub">
                        {{ $item->formatted_subtotal }}
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Shipping Details -->
        <h3 class="order-section-title"><i class="bi bi-geo-alt me-2 text-secondary"></i>Informasi Pengiriman</h3>
        
        <div class="row g-3" style="font-size: 0.9rem;">
            <div class="col-sm-6">
                <span class="d-block text-muted small fw-semibold uppercase tracking-wider mb-1">Nama Penerima</span>
                <span class="text-dark fw-bold">{{ $order->shipping_name }}</span>
            </div>
            
            <div class="col-sm-6">
                <span class="d-block text-muted small fw-semibold uppercase tracking-wider mb-1">Nomor Telepon</span>
                <span class="text-dark">{{ $order->shipping_phone }}</span>
            </div>
            
            <div class="col-12">
                <span class="d-block text-muted small fw-semibold uppercase tracking-wider mb-1">Alamat Lengkap</span>
                <span class="text-dark d-block bg-light p-3 rounded" style="line-height: 1.6; border: 1px solid var(--border-color);">{{ $order->shipping_address }}</span>
            </div>
        </div>
    </div>

    <!-- Right Column: Total Price and Payment Actions (Midtrans integration ready) -->
    <div class="payment-action-card shadow-sm">
        <h3 class="summary-title">Ringkasan Pembayaran</h3>
        
        <div class="meta-data-box">
            <div class="meta-row">
                <span class="text-muted">Total Harga Barang</span>
                <span class="fw-bold text-dark">{{ $order->formatted_total_price }}</span>
            </div>
            <div class="meta-row">
                <span class="text-muted">Ongkos Pengiriman</span>
                <span class="text-success fw-bold">Gratis</span>
            </div>
            <hr class="my-2" style="border-color: var(--border-color);">
            <div class="meta-row" style="font-size: 1rem; font-weight: 700;">
                <span class="text-dark">Total Tagihan</span>
                <span class="text-dark">{{ $order->formatted_total_price }}</span>
            </div>
        </div>

        @if($order->payment_status === 'unpaid' && $order->status !== \App\Models\Order::STATUS_CANCELLED)
            <!-- Midtrans Snap Payment Box with Countdown Timer -->
            <div class="text-center p-3 border border-warning rounded mb-4" style="background-color: #fffbeb; border-style: dashed !important;">
                <i class="bi bi-clock-history fs-3 text-warning mb-2 d-block"></i>
                <span class="small fw-semibold text-warning d-block">Menunggu Pembayaran</span>
                
                <!-- Countdown Timer -->
                <div class="my-3 p-2 bg-white rounded border border-warning border-opacity-35 shadow-sm">
                    <span class="d-block small text-muted text-uppercase tracking-wider" style="font-size: 0.7rem; letter-spacing: 0.05em; font-weight: 500;">Batas Waktu Pembayaran</span>
                    <span id="countdown" class="fs-4 fw-bold" style="color: var(--accent-color); font-family: 'Space Grotesk', sans-serif;">00:00:00</span>
                </div>

                <p class="small text-muted mb-0 mt-2" style="font-size: 0.75rem; line-height: 1.4;">Lakukan pembayaran aman menggunakan simulator Midtrans Sandbox. Jika sudah membayar, klik tombol cek status di bawah.</p>
            </div>
            
            @if($snapToken)
                <!-- Pay Button (Live Snap) -->
                <button id="pay-button" class="btn-minimal-accent w-100 py-3 d-inline-flex align-items-center justify-content-center gap-2 shadow-sm">
                    <i class="bi bi-credit-card-2-back"></i>
                    <span>Bayar Sekarang (Sandbox)</span>
                </button>

                <!-- Check Status Button -->
                <a href="{{ route('orders.check-status', $order->id) }}" class="btn-minimal-secondary w-100 py-3 mt-3 d-inline-flex align-items-center justify-content-center gap-2 shadow-sm text-decoration-none">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Cek Status Pembayaran</span>
                </a>
            @else
                <button disabled class="btn-minimal-secondary w-100 py-3 d-inline-flex align-items-center justify-content-center gap-2 opacity-75 cursor-not-allowed">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span>Gagal memuat pembayaran</span>
                </button>
            @endif
        @elseif($order->status === \App\Models\Order::STATUS_CANCELLED)
            <div class="text-center p-4 border border-danger rounded bg-danger bg-opacity-10 mb-0">
                <i class="bi bi-x-circle-fill fs-2 text-danger mb-2 d-block"></i>
                <span class="fw-bold text-danger d-block">Pesanan Dibatalkan</span>
                <p class="small text-muted mb-0 mt-2" style="font-size: 0.75rem; line-height: 1.4;">Pesanan ini telah dibatalkan karena kegagalan pembayaran atau pembatalan manual.</p>
            </div>
        @else
            <div class="text-center p-4 border border-success rounded bg-success bg-opacity-10 mb-0">
                <i class="bi bi-patch-check-fill fs-2 text-success mb-2 d-block"></i>
                <span class="fw-bold text-success d-block">Pembayaran Berhasil!</span>
                <p class="small text-muted mb-0 mt-2" style="font-size: 0.75rem; line-height: 1.4;">Terima kasih atas pembelian Anda. Pesanan Anda akan segera diproses oleh penjual.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
@if($order->payment_status === 'unpaid')
    @if($snapToken)
        <!-- Midtrans Snap.js official library -->
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function(){
                // Trigger Snap popup window
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result){
                        window.location.href = "{{ route('orders.check-status', $order->id) }}";
                    },
                    onPending: function(result){
                        window.location.href = "{{ route('orders.check-status', $order->id) }}";
                    },
                    onError: function(result){
                        window.location.href = "{{ route('orders.check-status', $order->id) }}";
                    },
                    onClose: function(){
                        // User closed the popup without paying
                    }
                });
            };
        </script>
    @endif

    <script type="text/javascript">
        // Countdown Timer Logic
        (function() {
            const expiryTimestamp = {{ $order->created_at->addHours(24)->timestamp }} * 1000;
            const countdownEl = document.getElementById('countdown');
            const payButton = document.getElementById('pay-button');
            
            function updateCountdown() {
                const now = new Date().getTime();
                const distance = expiryTimestamp - now;
                
                if (distance < 0) {
                    if (countdownEl) countdownEl.innerHTML = "WAKTU HABIS (EXPIRED)";
                    if (payButton) payButton.disabled = true;
                    return;
                }
                
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                const formattedHours = String(hours).padStart(2, '0');
                const formattedMinutes = String(minutes).padStart(2, '0');
                const formattedSeconds = String(seconds).padStart(2, '0');
                
                if (countdownEl) {
                    countdownEl.innerHTML = `${formattedHours}:${formattedMinutes}:${formattedSeconds}`;
                }
            }
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
        })();
    </script>
@endif

@if($order->payment_status === 'paid')
    <script type="text/javascript">
        // GA4 E-Commerce Tracking: purchase Event
        if (typeof gtag === 'function') {
            gtag("event", "purchase", {
                transaction_id: "{{ $order->order_number }}",
                value: {{ $order->total_price }},
                currency: "IDR",
                items: [
                    @foreach($order->items as $item)
                    {
                        item_id: "{{ $item->product_id }}",
                        item_name: "{{ $item->product_name }}",
                        price: {{ $item->price }},
                        quantity: {{ $item->quantity }}
                    },
                    @endforeach
                ]
            });
        }
    </script>
@endif
@endsection
