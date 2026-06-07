@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number . ' - Admin')

@section('styles')
<style>
    /* Premium Admin Order Detail Layout */
    .admin-detail-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2.5rem;
        align-items: start;
    }

    @media (max-width: 991.98px) {
        .admin-detail-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }

    .detail-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 2rem;
    }

    .admin-section-title {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 1.35rem;
        color: var(--primary-color);
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.5rem;
        margin-top: 2rem;
        margin-bottom: 1.25rem;
        letter-spacing: -0.01em;
    }

    .admin-section-title:first-of-type {
        margin-top: 0;
    }

    .item-row {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--bg-subtle);
    }

    .item-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .item-img {
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

    .item-fallback {
        font-size: 1.5rem;
        color: var(--text-muted);
    }

    .item-info {
        flex-grow: 1;
        min-width: 0;
    }

    .item-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--primary-color);
        text-decoration: none;
    }

    .item-meta {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.15rem;
    }

    .item-subtotal {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--primary-color);
        text-align: right;
    }

    .admin-action-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 2rem;
        position: sticky;
        top: 100px;
    }

    .summary-box {
        background-color: var(--bg-subtle);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }

    .summary-row-data {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .summary-row-data:last-child {
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Back to Admin Orders list -->
    <div class="mb-4">
        <a href="{{ route('admin.orders.index') }}" class="text-secondary small text-decoration-none d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar Pesanan</span>
        </a>
    </div>

    <!-- Admin Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1" style="letter-spacing: -0.03em;">Detail Pesanan #{{ $order->order_number }}</h1>
            <p class="text-muted small mb-0">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }} · Pelanggan: <strong class="text-dark">{{ $order->user->name }}</strong> ({{ $order->user->email }})</p>
        </div>
        
        <div class="d-flex align-items-center gap-2">
            <!-- Status badges -->
            <span class="badge-status {{ $order->status }}">
                <span class="indicator-dot" style="width:6px; height:6px; border-radius:50%; background-color:currentColor;"></span>
                {{ ucfirst($order->status) }}
            </span>
            
            <span class="badge-payment {{ $order->payment_status }}">
                {{ $order->payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
            </span>
        </div>
    </div>

    <div class="admin-detail-grid">
        <!-- Left Column: Ordered items and shipping address -->
        <div class="detail-card shadow-sm">
            <!-- Ordered items list -->
            <h3 class="admin-section-title mt-0"><i class="bi bi-cart3 me-2 text-secondary"></i>Daftar Barang Belanjaan</h3>
            
            <div class="mb-2">
                @foreach($order->items as $item)
                    <div class="item-row">
                        <div class="item-img">
                            @if($item->product && $item->product->image && file_exists(public_path($item->product->image)))
                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product_name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="item-fallback">
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
                        
                        <div class="item-info">
                            @if($item->product)
                                <a href="{{ url('/products/' . $item->product->slug) }}" target="_blank" class="item-name">{{ $item->product_name }}</a>
                            @else
                                <span class="item-name text-muted">{{ $item->product_name }}</span>
                            @endif
                            <div class="item-meta">
                                {{ 'Rp ' . number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}
                            </div>
                        </div>
                        
                        <div class="item-subtotal">
                            {{ $item->formatted_subtotal }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Shipping information -->
            <h3 class="admin-section-title"><i class="bi bi-geo-alt me-2 text-secondary"></i>Informasi Pengiriman</h3>
            
            <div class="row g-3" style="font-size: 0.9rem;">
                <div class="col-sm-6">
                    <span class="d-block text-muted small fw-semibold text-uppercase tracking-wider mb-1">Nama Penerima</span>
                    <span class="text-dark fw-bold">{{ $order->shipping_name }}</span>
                </div>
                
                <div class="col-sm-6">
                    <span class="d-block text-muted small fw-semibold text-uppercase tracking-wider mb-1">Nomor Telepon</span>
                    <span class="text-dark">{{ $order->shipping_phone }}</span>
                </div>
                
                <div class="col-12">
                    <span class="d-block text-muted small fw-semibold text-uppercase tracking-wider mb-1">Alamat Lengkap</span>
                    <span class="text-dark d-block bg-light p-3 rounded" style="line-height: 1.6; border: 1px solid var(--border-color);">{{ $order->shipping_address }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Shipment status control and payment summary -->
        <div class="admin-action-card shadow-sm">
            <h3 class="summary-title" style="font-size:1.1rem; font-weight:700; margin-bottom:1.5rem;">Faktur & Status</h3>
            
            <!-- Price Summary -->
            <div class="summary-box">
                <div class="summary-row-data">
                    <span class="text-muted">Total Harga</span>
                    <strong class="text-dark">{{ $order->formatted_total_price }}</strong>
                </div>
                <div class="summary-row-data">
                    <span class="text-muted">Ongkir</span>
                    <span class="text-success fw-bold">Gratis</span>
                </div>
                <hr class="my-2" style="border-color: var(--border-color);">
                <div class="summary-row-data" style="font-size: 1rem; font-weight: 700;">
                    <span class="text-dark">Total Tagihan</span>
                    <span class="text-dark">{{ $order->formatted_total_price }}</span>
                </div>
            </div>

            <!-- Payment Logs Detail -->
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
                <div class="p-3 border rounded bg-light mb-4" style="font-size: 0.825rem; border-color: var(--border-color) !important;">
                    <span class="d-block text-muted small fw-semibold text-uppercase tracking-wider mb-2" style="letter-spacing:0.05em;">Log Pembayaran</span>
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

            <!-- Update Order Status Form -->
            <div class="p-3 border rounded bg-white" style="border-color: var(--border-color) !important;">
                <span class="d-block text-muted small fw-semibold text-uppercase tracking-wider mb-2" style="letter-spacing:0.05em;">Perbarui Status Pesanan</span>
                
                @if(in_array($order->status, [\App\Models\Order::STATUS_CANCELLED, \App\Models\Order::STATUS_COMPLETED]))
                    <div class="alert alert-light border text-muted small mb-0 px-2 py-2 text-center" style="font-size: 0.8rem;">
                        <i class="bi bi-lock-fill text-secondary me-1"></i> Status pesanan ini telah terkunci ({{ ucfirst($order->status) }}) dan tidak dapat diubah lagi.
                    </div>
                @else
                    <form id="update-status-detail-form" action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <select name="status" class="form-select form-select-sm" style="font-size: 0.875rem; border-radius:3px; padding: 8px;">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-minimal-accent w-100 py-2.5 text-center fw-semibold text-decoration-none shadow-sm" style="font-size: 0.8rem;">
                            Simpan Perubahan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script nonce="{{ app('csp-nonce') }}">
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('update-status-detail-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (form.dataset.confirmed === 'true') {
                    return;
                }
                
                e.preventDefault();
                const select = form.querySelector('select[name="status"]');
                const statusText = select.options[select.selectedIndex].text;
                
                window.premiumConfirm(
                    `Apakah Anda yakin ingin mengubah status pesanan ini menjadi "${statusText}"?`,
                    'Konfirmasi Perubahan Status'
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
        }
    });
</script>
@endsection
