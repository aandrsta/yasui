@extends('layouts.app')

@section('title', 'Daftar')

@section('styles')
<style>
    .auth-card {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: #ffffff;
    }
    .btn-google {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        color: var(--text-main);
        font-weight: 500;
        font-size: 0.9rem;
        border-radius: 6px;
        transition: var(--transition-base);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-google:hover {
        background-color: var(--bg-subtle);
        border-color: #cbd5e1;
    }
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.8rem;
        margin: 24px 0;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid var(--border-color);
    }
    .divider:not(:empty)::before {
        margin-right: .5em;
    }
    .divider:not(:empty)::after {
        margin-left: .5em;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-6 col-lg-5 col-xl-4">
        <div class="card auth-card p-4 p-md-5">
            <div class="card-body p-0">
                <!-- Header -->
                <div class="mb-4">
                    <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.03em;">Daftar</h3>
                    <p class="text-muted small mb-0">Silakan isi detail akun Anda di bawah ini</p>
                </div>
                
                <!-- Google OAuth SSO Button (Wajib Dosen) -->
                <a href="{{ url('/auth/google') }}" class="btn btn-google w-100 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.5 24c0-1.55-.15-3.24-.47-4.77H24v9.03h12.75c-.55 2.87-2.22 5.38-4.72 7.04l7.33 5.68C43.64 36.6 46.5 30.9 46.5 24z"/>
                        <path fill="#FBBC05" d="M10.54 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.98-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.33-5.68c-2.11 1.42-4.8 2.3-8.56 2.3-6.26 0-11.57-4.22-13.46-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    Daftar dengan Google
                </a>
                
                <div class="divider">atau email</div>
                
                <!-- Registration Form -->
                <form action="{{ url('/register') }}" method="POST">
                    @csrf
                    
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-semibold text-secondary">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus placeholder="Nama lengkap Anda" style="border-radius: 6px; padding: 8px 12px; font-size: 0.9rem; border-color: var(--border-color);">
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-semibold text-secondary">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="nama@domain.com" style="border-radius: 6px; padding: 8px 12px; font-size: 0.9rem; border-color: var(--border-color);">
                        @error('email')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label small fw-semibold text-secondary">Kata Sandi</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimal 8 karakter" style="border-radius: 6px; padding: 8px 12px; font-size: 0.9rem; border-color: var(--border-color);">
                        @error('password')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Password Confirmation -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Ulangi Kata Sandi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Ulangi kata sandi" style="border-radius: 6px; padding: 8px 12px; font-size: 0.9rem; border-color: var(--border-color);">
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-minimal-primary w-100 py-2 fw-medium mb-3">Daftar</button>
                </form>
                
                <!-- Footer Links -->
                <p class="text-center small text-muted mb-0">
                    Sudah punya akun? <a href="{{ url('/login') }}" class="text-dark fw-semibold text-decoration-none border-bottom border-dark">Masuk sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
