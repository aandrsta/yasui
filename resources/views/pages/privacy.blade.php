@extends('layouts.app')

@section('title', 'Kebijakan Privasi')

@section('styles')
<style>
    .legal-container {
        max-width: 800px;
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 2.5rem;
    }
    .legal-title {
        font-weight: 800;
        letter-spacing: -0.04em;
        color: var(--primary-color);
        border-bottom: 2px solid var(--accent-color);
        display: inline-block;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .legal-content h3 {
        font-weight: 700;
        font-size: 1.15rem;
        color: var(--primary-color);
        margin-top: 1.75rem;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }
    .legal-content p, .legal-content li {
        color: var(--text-main);
        line-height: 1.7;
        font-size: 0.95rem;
    }
    .legal-content ul {
        padding-left: 1.25rem;
    }
    .last-updated {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
</style>
@endsection

@section('content')
<div class="container py-5 d-flex justify-content-center">
    <div class="legal-container shadow-sm">
        <h1 class="legal-title">Kebijakan Privasi</h1>
        <p class="last-updated mb-4">Pembaruan Terakhir: {{ date('d M Y') }}</p>

        <div class="legal-content">
            <p>Di <strong>YASUI</strong>, kami sangat menghormati privasi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda saat Anda mengunjungi platform belanja anime online kami.</p>

            <h3>1. Informasi yang Kami Kumpulkan</h3>
            <p>Kami mengumpulkan data pribadi Anda untuk mendukung kelancaran layanan dan proses pelacakan analitik:</p>
            <ul>
                <li><strong>Data Pendaftaran Akun:</strong> Nama lengkap, alamat email, kata sandi terenkripsi, nomor telepon, dan alamat lengkap pengiriman saat melakukan registrasi.</li>
                <li><strong>Kredensial SSO Google:</strong> Saat Anda menggunakan login cepat, kami mengambil email, nama, dan foto profil publik Anda secara aman sesuai dengan izin autentikasi Google OAuth.</li>
                <li><strong>Informasi Transaksi:</strong> Data keranjang belanja, rincian pembayaran simulasi Midtrans Sandbox, dan nomor pesanan.</li>
                <li><strong>Data Pelacakan (GA4):</strong> Halaman produk yang Anda lihat, interaksi menambah keranjang, konversi transaksi sukses, alamat IP, tipe browser, dan pola browsing yang dipantau melalui Google Analytics 4.</li>
            </ul>

            <h3>2. Penggunaan Informasi</h3>
            <p>Data yang dikumpulkan di YASUI digunakan untuk tujuan berikut:</p>
            <ul>
                <li>Membuat akun pembeli dan mengelola autentikasi login yang aman.</li>
                <li>Memproses checkout belanja dan mengelola pengiriman simulasi ke alamat Anda.</li>
                <li>Melakukan integrasi verifikasi status pembayaran aman secara asinkron dengan server Midtrans.</li>
                <li><strong>Keperluan Evaluasi Akademis:</strong> Memberikan data analisis perilaku pengguna secara *real-time* kepada dosen penguji melalui dashboard GA4 saat simulasi belanja antar kelompok diselenggarakan.</li>
            </ul>

            <h3>3. Kerahasiaan & Keamanan Data</h3>
            <p>Kata sandi pengguna dienkripsi secara ketat di server kami menggunakan algoritma *Bcrypt* bawaan Laravel. Kami menjamin data pribadi Anda (termasuk email login Google) tidak akan pernah dijual, disebarkan, atau disalahgunakan di luar kepentingan akademis proyek kuliah ini.</p>

            <h3>4. Penggunaan Cookies & Google Analytics</h3>
            <p>Website ini menggunakan cookies untuk mengingat barang di keranjang belanja Anda dan mendeteksi sesi masuk. Kami juga memanfaatkan layanan Google Analytics 4 untuk event tracking perilaku belanja yang aman.</p>

            <h3>5. Hak Pengguna</h3>
            <p>Anda berhak memperbarui alamat profil pengiriman Anda atau menghapus data belanja Anda dengan menghubungi Administrator e-commerce YASUI.</p>
        </div>

        <hr class="my-4" style="border-color: var(--border-color);">
        
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ url('/') }}" class="btn-minimal-secondary text-decoration-none py-2 px-3 small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
            </a>
            <span class="small text-muted">© {{ date('Y') }} YASUI. All rights reserved.</span>
        </div>
    </div>
</div>
@endsection
