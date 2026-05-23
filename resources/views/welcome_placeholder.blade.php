@extends('layouts.app')

@section('title', 'Beranda')

@section('styles')
<style>
    .welcome-card {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: #ffffff;
    }
    .user-details {
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-lg-8 text-center mb-5">
        <h1 class="display-5 fw-bold text-dark mb-3" style="letter-spacing: -0.04em;">Yasui E-Commerce</h1>
        <p class="text-muted fs-5 mx-auto" style="max-width: 600px; line-height: 1.5; font-weight: 400;">
            Platform belanja minimalis yang fokus pada esensi kemudahan berbelanja online. Dibuat dengan arsitektur bersih Laravel MVC.
        </p>
        
        <div class="mt-4 d-flex justify-content-center gap-2">
            <a href="{{ url('/products') }}" class="btn-minimal-primary text-decoration-none">
                Lihat Katalog Produk
            </a>
        </div>
    </div>

    <div class="col-md-6 col-lg-5">
        @auth
            <!-- Logged In State Card -->
            <div class="card welcome-card p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light text-dark rounded-circle mb-3" style="width: 54px; height: 54px;">
                            <i class="bi bi-person-check fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="letter-spacing: -0.02em;">Sesi Aktif</h4>
                        <p class="text-success small mb-0 d-inline-flex align-items-center gap-1">
                            <span class="d-inline-block rounded-circle bg-success" style="width: 6px; height: 6px;"></span>
                            Terhubung
                        </p>
                    </div>

                    <div class="user-details py-3 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Nama</span>
                            <span class="fw-semibold text-dark small">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Email</span>
                            <span class="fw-semibold text-dark small">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Peran</span>
                            <span class="fw-semibold text-dark small">{{ strtoupper(auth()->user()->role) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Metode Masuk</span>
                            @if(auth()->user()->google_id)
                                <span class="badge bg-light text-dark border small fw-medium d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-google text-danger"></i> Google SSO
                                </span>
                            @else
                                <span class="badge bg-light text-dark border small fw-medium">
                                    Email & Sandi
                                </span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ url('/logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-minimal-secondary w-100 py-2">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar Akun
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- Guest State Card -->
            <div class="card welcome-card p-4">
                <div class="card-body text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light text-dark rounded-circle mb-3" style="width: 54px; height: 54px;">
                        <i class="bi bi-shield-lock fs-4"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="letter-spacing: -0.02em;">Belum Masuk</h4>
                    <p class="text-muted small mb-4">Silakan masuk ke akun Anda atau daftar baru untuk mulai berbelanja.</p>

                    <div class="d-grid gap-2">
                        <a href="{{ url('/login') }}" class="btn-minimal-primary text-decoration-none">Masuk ke Akun</a>
                        <a href="{{ url('/register') }}" class="btn-minimal-secondary text-decoration-none">Daftar Akun Baru</a>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</div>
@endsection
