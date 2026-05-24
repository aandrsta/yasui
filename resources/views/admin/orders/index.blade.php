@extends('layouts.app')

@section('title', 'Kelola Pesanan')

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
                    <a class="nav-link" href="{{ route('admin.products.index') }}">
                        <i class="bi bi-box-seam me-1"></i> Kelola Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-receipt me-1"></i> Kelola Pesanan
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Filters & Sorting Card -->
    <div class="card mb-4 shadow-sm border border-slate-200">
        <div class="card-body py-3">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="status" class="form-label small fw-semibold text-muted mb-1">Filter Status Pengiriman</label>
                    <select name="status" id="status" class="form-select form-select-sm" style="border-radius: 4px; padding: 6px 12px;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="sort" class="form-label small fw-semibold text-muted mb-1">Urutan Pesanan</label>
                    <select name="sort" id="sort" class="form-select form-select-sm" style="border-radius: 4px; padding: 6px 12px;" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru (Newest / Latest)</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama (Oldest)</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold" style="font-size: 0.8rem; border-radius: 4px; padding: 7px 12px;">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders List Card -->
    <div class="card shadow-sm border border-slate-200">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;"><i class="bi bi-receipt-cutoff me-1 text-muted"></i> Daftar Seluruh Pesanan Pelanggan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4">No. Order</th>
                            <th>Pelanggan</th>
                            <th>Tanggal Masuk</th>
                            <th>Total Tagihan</th>
                            <th>Pembayaran</th>
                            <th>Status Pengiriman</th>
                            <th class="text-end pe-4" style="width: 250px;">Aksi Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">
                                    <a href="{{ route('orders.show', $order->id) }}" target="_blank" class="text-decoration-none text-primary">#{{ $order->order_number }}</a>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $order->shipping_name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Email: {{ $order->user->email }} · Telp: {{ $order->shipping_phone }}</div>
                                </td>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="fw-bold text-dark">{{ $order->formatted_total_price }}</td>
                                <td>
                                    <span class="badge-payment {{ $order->payment_status }}">
                                        {{ $order->payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status {{ $order->status }}">
                                        <i class="bi " style="font-size: 0.35rem; color: currentColor;"></i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <!-- Simple Fast Update Dropdown Form -->
                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="d-flex align-items-center justify-content-end gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" style="font-size: 0.8rem; border-radius: 4px; width: 130px; padding: 4px 8px;">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-dark btn-sm fw-semibold" style="font-size: 0.75rem; border-radius: 4px; padding: 5px 10px;">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada pesanan masuk dari pembeli.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
