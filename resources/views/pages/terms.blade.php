@extends('layouts.app')

@section('title', 'Ketentuan Layanan')

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
        <h1 class="legal-title">Ketentuan Layanan</h1>
        <p class="last-updated mb-4">Pembaruan Terakhir: {{ date('d M Y') }}</p>

        <div class="legal-content">
            <p>Selamat datang di <strong>YASSUI</strong>. Sebelum Anda menggunakan layanan e-commerce kami, harap baca Ketentuan Layanan ini dengan saksama. Dengan mengakses dan berbelanja di platform kami, Anda menyetujui seluruh ketentuan yang tercantum di bawah ini.</p>

            <h3>1. Ketentuan Umum</h3>
            <p>YASSUI adalah platform e-commerce yang didedikasikan untuk penjualan produk merchandise anime premium, action figures, model kits, plushies, dan barang karakter koleksi lainnya. Seluruh transaksi di platform ini diselenggarakan di bawah pengawasan sistem simulasi lokal untuk kepentingan akademis.</p>

            <h3>2. Akun Pengguna & Keamanan</h3>
            <ul>
                <li>Anda dapat mendaftar akun secara manual atau menggunakan layanan Single Sign-On (SSO) Google OAuth secara aman.</li>
                <li>Anda bertanggung jawab atas kerahasiaan informasi akun Anda, termasuk kata sandi dan kredensial Google.</li>
                <li>Kami berhak menangguhkan akun jika terdeteksi aktivitas mencurigakan atau pelanggaran terhadap ketentuan ini.</li>
            </ul>

            <h3>3. Kebijakan Transaksi & Pembayaran</h3>
            <ul>
                <li>Seluruh sistem pembayaran diselenggarakan secara aman melalui integrasi **Midtrans Sandbox**.</li>
                <li>Dalam lingkungan Sandbox ini, Anda tidak akan dikenakan biaya riil atau uang nyata. Semua simulasi transaksi menggunakan kartu kredit tes atau channel bank simulator yang disediakan secara resmi oleh Midtrans.</li>
                <li>Stok produk anime akan dikurangi secara otomatis saat order dibuat, dan akan dikembalikan secara penuh jika pembayaran gagal, kedaluwarsa, atau dibatalkan oleh pihak pengelola.</li>
            </ul>

            <h3>4. Pengiriman Barang</h3>
            <p>Barang yang dibeli dalam aplikasi ini bersifat simulasi akademis. Untuk pengujian aliran logistik, status pengiriman dikontrol sepenuhnya secara internal oleh pengelola toko melalui halaman kendali admin (Pending, Processing, Shipped, Completed, Cancelled).</p>

            <h3>5. Hak Cipta & Lisensi</h3>
            <p>Seluruh kekayaan intelektual, gambar merchandise, logo, nama merek, dan desain antarmuka YASSUI dilindungi oleh hak cipta. Penggunaan aset secara tidak sah untuk tujuan komersial di luar lingkup akademik dilarang keras.</p>

            <h3>6. Perubahan Ketentuan</h3>
            <p>Kami berhak memperbarui Ketentuan Layanan ini kapan saja demi kepatuhan akademis atau pengembangan sistem. Perubahan akan langsung berlaku setelah dipublikasikan di halaman ini.</p>
        </div>

        <hr class="my-4" style="border-color: var(--border-color);">
        
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ url('/') }}" class="btn-minimal-secondary text-decoration-none py-2 px-3 small">
                 Kembali ke Beranda
            </a>
            <span class="small text-muted">© {{ date('Y') }} YASSUI. All rights reserved.</span>
        </div>
    </div>
</div>
@endsection
