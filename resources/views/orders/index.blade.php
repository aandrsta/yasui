@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya')

@section('styles')
<style>
    .order-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        transition: var(--transition-base);
    }
    .order-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 20px -5px rgba(30, 30, 29, 0.05);
    }
    .orders-title {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 2.2rem;
        letter-spacing: -0.01em;
    }
</style>
@endsection

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <h1 class="orders-title text-dark mb-1">Riwayat Pesanan Saya</h1>
    <p class="text-muted small mb-4">Pantau riwayat belanja merchandise anime Anda dan selesaikan pembayaran tertunda di sini.</p>

    @if($orders->count() > 0)
        <div class="d-flex flex-column gap-3 mb-4">
            @foreach($orders as $order)
                <div class="order-card p-4 shadow-sm">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 border-bottom pb-3 mb-3">
                        <div>
                            <span class="text-muted small">Nomor Pesanan</span>
                            <span class="d-block text-dark fw-bold" style="font-size: 1.05rem;">#{{ $order->order_number }}</span>
                        </div>
                        <div class="text-sm-end">
                            <span class="text-muted small d-block">Tanggal Pemesanan</span>
                            <span class="text-dark fw-medium small">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <div class="row align-items-center g-3">
                        <div class="col-md-6 col-12">
                            <div class="d-flex gap-2 align-items-center">
                                <span class="badge-status {{ $order->status }}">
                                    <i class="bi " style="font-size: 0.4rem; color: currentColor;"></i>
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="badge-payment {{ $order->payment_status }}">
                                    {{ $order->payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
                                </span>
                            </div>
                            <div class="text-muted small mt-2">
                                Penerima: <strong class="text-dark">{{ $order->shipping_name }}</strong> · {{ $order->shipping_phone }}
                            </div>
                        </div>
                        
                        <div class="col-md-4 col-sm-6 col-12 text-md-end">
                            <span class="text-muted small d-block">Total Tagihan</span>
                            <span class="text-dark fw-bold" style="font-size: 1.15rem;">{{ $order->formatted_total_price }}</span>
                        </div>

                        <div class="col-md-2 col-sm-6 col-12 text-end">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn-minimal-primary w-100 text-center py-2 text-decoration-none d-block">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center p-5 border rounded" style="background-color: var(--bg-subtle); border-style: dashed !important; border-radius: 8px;">
            <i class="bi bi-bag-x fs-1 text-muted mb-3 d-block"></i>
            <h3 class="h5 fw-bold text-dark mb-2">Anda Belum Memiliki Pesanan</h3>
            <p class="text-muted small mb-4">Mulai menjelajah katalog merchandise anime premium kami sekarang dan lakukan pemesanan pertama Anda!</p>
            <a href="{{ url('/products') }}" class="btn-minimal-accent text-decoration-none d-inline-flex align-items-center gap-1">
                <span>Belanja Sekarang</span>
                <i class="bi bi-arrow-right small"></i>
            </a>
        </div>
    @endif
</div>
@endsection
