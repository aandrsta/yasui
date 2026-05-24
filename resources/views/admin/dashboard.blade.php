@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<style>
    .stat-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 1.5rem;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 6px;
        background-color: var(--bg-subtle);
        color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
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
                    <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.products.index') }}">
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

    <!-- Quick Stats Row -->
    <div class="row g-3 mb-4">
        <!-- Revenue Card -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="stat-card shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Pendapatan</span>
                    <h3 class="h4 fw-bold text-dark mt-1 mb-0">{{ 'Rp ' . number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="stat-card shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase tracking-wider">Pesanan Lunas</span>
                    <h3 class="h4 fw-bold text-dark mt-1 mb-0">{{ $totalOrders }} Order</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="stat-card shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Pembeli</span>
                    <h3 class="h4 fw-bold text-dark mt-1 mb-0">{{ $totalCustomers }} Akun</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="stat-card shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Produk</span>
                    <h3 class="h4 fw-bold text-dark mt-1 mb-0">{{ $totalProducts }} Mainan</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-box2-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Row -->
    <div class="card shadow-sm border border-slate-200">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;"><i class="bi bi-clock-history me-1 text-muted"></i> 5 Transaksi Pesanan Terbaru</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4">No. Order</th>
                            <th>Pelanggan</th>
                            <th>Tanggal Pemesanan</th>
                            <th>Total Tagihan</th>
                            <th>Pembayaran</th>
                            <th>Status Order</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">#{{ $order->order_number }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $order->user->name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $order->user->email }}</div>
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
                                    <a href="{{ route('orders.show', $order->id) }}" target="_blank" class="btn btn-outline-dark btn-sm fw-semibold" style="font-size: 0.75rem; border-radius: 4px;">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada transaksi pesanan pelanggan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
