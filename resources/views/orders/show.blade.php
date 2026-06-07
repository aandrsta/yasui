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

    /* Animated Japanese Hanko Stamp Styles */
    @keyframes hankoStamp {
        0% {
            opacity: 0;
            transform: scale(5) rotate(-35deg);
            filter: blur(8px);
        }
        70% {
            opacity: 1;
            filter: none;
        }
        100% {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
    }
    @keyframes hankoRing {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes cardImpact {
        0% { transform: scale(1) translateY(0); }
        40% { transform: scale(0.98) translateY(4px); }
        100% { transform: scale(1) translateY(0); }
    }

    .hanko-stamp-wrapper {
        box-shadow: 0 0 20px rgba(162, 56, 74, 0.05);
        border-radius: 50%;
        display: inline-block;
    }
</style>
@endsection

@section('content')
@if(session('success') && strpos(session('success'), 'berhasil dikonfirmasi') !== false)
    <!-- Checkout Success Celebration Overlay -->
    <div id="checkout-success-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: var(--bg-main); z-index: 10200; display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 1; transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
        <canvas id="confetti-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;"></canvas>
        
        <div class="text-center animate-fade-in-up" style="max-width: 480px; padding: 2rem; z-index: 10205; transition: transform 0.15s ease-in-out;">
            <!-- Animated Japanese Hanko Seal (印鑑) -->
            <div class="hanko-container mb-4" style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                <!-- Red ink splatter / ring behind -->
                <div class="hanko-ring" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 2px dashed rgba(162, 56, 74, 0.2); border-radius: 50%; animation: hankoRing 2.5s linear infinite;"></div>
                
                <!-- The actual stamp -->
                <div class="hanko-stamp-wrapper" style="animation: hankoStamp 0.5s cubic-bezier(0.25, 1, 0.5, 1.2) forwards; transform-origin: center;">
                    <svg class="hanko-seal" viewBox="0 0 100 100" style="width: 120px; height: 120px; color: var(--accent-color);">
                        <defs>
                            <filter id="hanko-rough-ink">
                                <feTurbulence type="fractalNoise" baseFrequency="0.15" numOctaves="3" result="noise" />
                                <feDisplacementMap in="SourceGraphic" in2="noise" scale="2.5" xChannelSelector="R" yChannelSelector="G" />
                            </filter>
                        </defs>
                        <g filter="url(#hanko-rough-ink)" fill="none" stroke="currentColor">
                            <!-- Outlined circular frame representing traditional round seal -->
                            <circle cx="50" cy="50" r="42" stroke-width="5" />
                            <circle cx="50" cy="50" r="37" stroke-width="1.5" />
                            <!-- Kanji right: "安" (Yasui / peace) -->
                            <path d="M68 28 h-14 M60 28 v10 M52 38 h16 M54 46 c3-3 6-7 8-10 M57 46 l10 10 M54 66 c4-3 9-9 11-15 M60 55 v18" stroke-width="4.5" stroke-linecap="round" />
                            <!-- Kanji left: "水" (Sui / water) -->
                            <path d="M36 24 v48 c0 3-1.5 5-5 5 M36 46 c-3-3-8-6-10-6 M26 62 c3-2 6-5 8-8 M36 44 c3 3 8 8 11 11 M45 31 c-3 3-6 6-8 8" stroke-width="4.5" stroke-linecap="round" />
                        </g>
                    </svg>
                </div>
            </div>
            
            <h2 style="font-family: 'Zen Old Mincho', serif; font-size: 2.20rem; color: var(--primary-color); margin-bottom: 1rem;">Pembayaran Berhasil!</h2>
            
            <!-- Red Japanese Seal Stamp visual -->
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-4 border border-danger border-opacity-25" style="border-radius: 3px; background-color: rgba(162, 56, 74, 0.03); border-color: rgba(162, 56, 74, 0.2) !important;">
                <span class="d-block rounded-circle" style="width: 6px; height: 6px; background-color: var(--accent-color);"></span>
                <span class="small font-mincho fw-bold text-uppercase" style="font-size: 0.65rem; color: var(--accent-color); letter-spacing: 0.15rem;">YASSUI 創立二〇二六</span>
            </div>

            <p class="text-muted mb-4" style="line-height: 1.7; font-size: 0.95rem;">
                Terima kasih atas pembayaran Anda. Transaksi pembayaran untuk pesanan <strong class="text-dark">#{{ $order->order_number }}</strong> telah berhasil terkonfirmasi secara aman. Kami akan memproses pesanan Anda sesegera mungkin.
            </p>
            
            <button id="close-success-overlay" class="btn-minimal-accent px-5 py-3 w-100 fw-semibold" style="letter-spacing: 0.1em;">
                TUTUP DAN LIHAT DETAIL
            </button>
        </div>
    </div>
@endif

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

                @if($order->status === \App\Models\Order::STATUS_PENDING)
                    <!-- Cancel Order Button -->
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="mt-3" id="cancel-order-form">
                        @csrf
                        <button type="submit" class="btn btn-link text-danger small w-100 text-decoration-none fw-semibold text-center border-0 p-0" style="font-size: 0.825rem; transition: var(--transition-base);">
                            <i class="bi bi-x-circle me-1"></i> Batalkan Pesanan
                        </button>
                    </form>
                @endif
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
            @if($order->status === \App\Models\Order::STATUS_SHIPPED)
                <div class="text-center p-4 border border-info rounded bg-info bg-opacity-10 mb-3">
                    <i class="bi bi-truck fs-2 text-info mb-2 d-block"></i>
                    <span class="fw-bold text-dark d-block">Pesanan Sedang Dikirim</span>
                    <p class="small text-muted mb-3 mt-2" style="font-size: 0.75rem; line-height: 1.4;">Pesanan Anda telah diserahkan ke kurir dan sedang dalam perjalanan. Silakan klik tombol di bawah untuk menyelesaikan pesanan jika Anda sudah menerima barang belanjaan Anda.</p>
                    
                    <form action="{{ route('orders.complete', $order->id) }}" method="POST" id="complete-order-form">
                        @csrf
                        <button type="submit" class="btn-minimal-accent w-100 py-2.5 border-0 d-inline-flex align-items-center justify-content-center gap-2 shadow-sm fw-semibold">
                            <i class="bi bi-check-circle"></i>
                            <span>Konfirmasi Pesanan Diterima</span>
                        </button>
                    </form>
                </div>
            @elseif($order->status === \App\Models\Order::STATUS_COMPLETED)
                <div class="text-center p-4 border border-success rounded bg-success bg-opacity-10 mb-3">
                    <i class="bi bi-patch-check-fill fs-2 text-success mb-2 d-block"></i>
                    <span class="fw-bold text-success d-block">Pesanan Selesai!</span>
                    <p class="small text-muted mb-0 mt-2" style="font-size: 0.75rem; line-height: 1.4;">Transaksi telah selesai. Terima kasih telah berbelanja di YASSUI!</p>
                </div>
            @else
                <div class="text-center p-4 border border-success rounded bg-success bg-opacity-10 mb-3">
                    <i class="bi bi-patch-check-fill fs-2 text-success mb-2 d-block"></i>
                    <span class="fw-bold text-success d-block">Pembayaran Berhasil!</span>
                    <p class="small text-muted mb-0 mt-2" style="font-size: 0.75rem; line-height: 1.4;">Terima kasih atas pembelian Anda. Pesanan Anda sedang diproses oleh penjual.</p>
                </div>
            @endif

            @if($order->payment)
                @php
                    $paymentMethods = [
                        'credit_card' => 'Kartu Kredit',
                        'gopay' => 'GoPay',
                        'qris' => 'QRIS',
                        'shopeepay' => 'ShopeePay',
                        'bank_transfer' => 'Transfer Bank (VA)',
                        'echannel' => 'Mandiri Bill Payment',
                        'bca_klikpay' => 'BCA KlikPay',
                        'brizzi' => 'BRIZZI',
                        'akulaku' => 'Akulaku PayLater',
                        'cstore' => 'Gerai Retail (Indomaret/Alfamart)',
                    ];
                    $friendlyMethod = $paymentMethods[$order->payment->payment_type] ?? strtoupper(str_replace('_', ' ', $order->payment->payment_type));
                @endphp
                <div class="p-3 border rounded bg-light" style="font-size: 0.825rem; border-color: var(--border-color) !important;">
                    <span class="d-block text-muted small fw-semibold text-uppercase tracking-wider mb-2" style="letter-spacing: 0.05em;">Detail Pembayaran</span>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Metode:</span>
                        <strong class="text-dark">{{ $friendlyMethod }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">ID Transaksi:</span>
                        <span class="text-dark text-truncate ms-2" style="max-width: 155px;" title="{{ $order->payment->transaction_id }}">{{ $order->payment->transaction_id }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Waktu:</span>
                        <span class="text-dark">{{ $order->payment->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@section('scripts')
@if($order->payment_status === 'unpaid')
    @if($snapToken)
        <!-- Midtrans Snap.js official library -->
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}" nonce="{{ app('csp-nonce') }}"></script>
        <script type="text/javascript" nonce="{{ app('csp-nonce') }}">
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

    <script type="text/javascript" nonce="{{ app('csp-nonce') }}">
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
    <script type="text/javascript" nonce="{{ app('csp-nonce') }}">
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

@if(session('success') && strpos(session('success'), 'berhasil dikonfirmasi') !== false)
    <script type="text/javascript" nonce="{{ app('csp-nonce') }}">
        document.addEventListener('DOMContentLoaded', function() {
            // Dismiss Overlay
            const successOverlay = document.getElementById('checkout-success-overlay');
            const closeBtn = document.getElementById('close-success-overlay');
            if (successOverlay && closeBtn) {
                closeBtn.addEventListener('click', () => {
                    successOverlay.style.opacity = '0';
                    setTimeout(() => {
                        successOverlay.remove();
                    }, 600);
                });
            }

            // Japanese Sakura Petals Falling & Bursting Animation
            const canvas = document.getElementById('confetti-canvas');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                let width = canvas.width = canvas.offsetWidth;
                let height = canvas.height = canvas.offsetHeight;
                
                window.addEventListener('resize', () => {
                    width = canvas.width = canvas.offsetWidth;
                    height = canvas.height = canvas.offsetHeight;
                });
                
                // Delicate sakura palette: deep crimson, sakura pinks, white
                const colors = ['#ffb7c5', '#ffa6b9', '#f08080', '#a2384a', '#ffffff'];
                const particles = [];
                
                class SakuraParticle {
                    constructor(x, y, type) {
                        this.type = type; // 'burst' or 'drift'
                        this.x = x;
                        this.y = y;
                        this.size = Math.random() * 6 + 4;
                        this.color = colors[Math.floor(Math.random() * colors.length)];
                        this.opacity = 1;
                        this.rotation = Math.random() * 360;
                        this.rotationSpeed = Math.random() * 4 - 2;
                        
                        if (this.type === 'burst') {
                            const angle = Math.random() * Math.PI * 2;
                            const force = Math.random() * 8 + 3;
                            this.vx = Math.cos(angle) * force;
                            this.vy = Math.sin(angle) * force - 2; // initial upward velocity
                            this.gravity = 0.12;
                            this.drag = 0.95;
                            this.decay = Math.random() * 0.007 + 0.005;
                        } else {
                            // Gentle background drift
                            this.vx = Math.random() * 0.8 - 0.4;
                            this.vy = Math.random() * 1.2 + 0.8; // fall speed
                            this.swayTime = Math.random() * 100;
                            this.swaySpeed = Math.random() * 0.02 + 0.01;
                            this.swayAmount = Math.random() * 1.2 + 0.4;
                            this.decay = 0;
                        }
                    }
                    
                    update() {
                        if (this.type === 'burst') {
                            this.vx *= this.drag;
                            this.vy *= this.drag;
                            this.vy += this.gravity;
                            this.x += this.vx;
                            this.y += this.vy;
                            this.opacity -= this.decay;
                            this.rotation += this.rotationSpeed;
                        } else {
                            // Background drift sway physics
                            this.swayTime += this.swaySpeed;
                            this.x += this.vx + Math.sin(this.swayTime) * this.swayAmount;
                            this.y += this.vy;
                            this.rotation += this.rotationSpeed * 0.2;
                            
                            // Re-wrap background drifting petals to the top
                            if (this.y > height + 20) {
                                this.y = -20;
                                this.x = Math.random() * width;
                            }
                        }
                    }
                    
                    draw() {
                        ctx.save();
                        ctx.translate(this.x, this.y);
                        ctx.rotate(this.rotation * Math.PI / 180);
                        ctx.globalAlpha = Math.max(0, this.opacity);
                        ctx.fillStyle = this.color;
                        
                        // Draw Japanese Sakura Petal
                        ctx.beginPath();
                        ctx.moveTo(0, -this.size);
                        ctx.bezierCurveTo(-this.size * 1.2, -this.size * 0.5, -this.size * 0.8, this.size * 0.8, 0, this.size);
                        ctx.bezierCurveTo(this.size * 0.8, this.size * 0.8, this.size * 1.2, -this.size * 0.5, 0, -this.size);
                        ctx.closePath();
                        ctx.fill();
                        
                        ctx.restore();
                    }
                }
                
                // 1. Pre-initialize background drifting sakura petals
                for (let i = 0; i < 35; i++) {
                    particles.push(new SakuraParticle(Math.random() * width, Math.random() * height, 'drift'));
                }
                
                function animate() {
                    ctx.clearRect(0, 0, width, height);
                    
                    let alive = false;
                    particles.forEach(p => {
                        p.update();
                        p.draw();
                        // If there are burst particles, we keep animating. Drifting background always stays alive
                        if (p.type === 'burst' && p.opacity > 0) {
                            alive = true;
                        }
                    });
                    
                    // Always animate drifting background petals, or if bursts are still alive
                    if (alive || particles.some(p => p.type === 'drift')) {
                        requestAnimationFrame(animate);
                    }
                }
                
                // Start animation immediately for background drift
                animate();
                
                // 2. Trigger Hanko stamp thud card shake and sakura burst at impact (500ms)
                setTimeout(() => {
                    // Impact shake on card container
                    const overlayContent = document.querySelector('#checkout-success-overlay .text-center');
                    if (overlayContent) {
                        overlayContent.style.animation = 'cardImpact 0.25s ease-in-out';
                    }
                    
                    // Spawn exploding sakura confetti particles
                    const burstCount = 100;
                    const centerPointY = (height / 2) - 80;
                    for (let i = 0; i < burstCount; i++) {
                        particles.push(new SakuraParticle(width / 2, centerPointY, 'burst'));
                    }
                }, 500);
            }
        });
    </script>
@endif

<script type="text/javascript" nonce="{{ app('csp-nonce') }}">
    document.addEventListener('DOMContentLoaded', function() {
        const cancelForm = document.getElementById('cancel-order-form');
        if (cancelForm) {
            cancelForm.addEventListener('submit', function(e) {
                e.preventDefault();
                window.premiumConfirm(
                    'Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.',
                    'Batalkan Pesanan'
                ).then(confirmed => {
                    if (confirmed) {
                        if (typeof cancelForm.requestSubmit === 'function') {
                            cancelForm.requestSubmit();
                        } else {
                            cancelForm.submit();
                        }
                    }
                });
            });
        }

        const completeForm = document.getElementById('complete-order-form');
        if (completeForm) {
            completeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                window.premiumConfirm(
                    'Apakah Anda yakin barang belanjaan Anda sudah diterima dengan baik? Tindakan ini akan menyelesaikan transaksi.',
                    'Konfirmasi Penerimaan'
                ).then(confirmed => {
                    if (confirmed) {
                        if (typeof completeForm.requestSubmit === 'function') {
                            completeForm.requestSubmit();
                        } else {
                            completeForm.submit();
                        }
                    }
                });
            });
        }
    });
</script>
@endsection
