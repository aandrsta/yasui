@extends('layouts.app')

@section('title', 'Ketentuan Layanan')

@section('styles')
<style>
    html {
        scroll-behavior: smooth;
    }

    .legal-layout {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 3.5rem;
        align-items: start;
    }

    @media (max-width: 991.98px) {
        .legal-layout {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
        
        .legal-sidebar {
            position: static !important;
            margin-bottom: 1rem;
        }
    }

    /* Sidebar Table of Contents */
    .legal-sidebar {
        position: sticky;
        top: 100px;
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px -5px rgba(30, 30, 29, 0.03);
    }

    .toc-title {
        font-family: 'Zen Old Mincho', serif;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--primary-color);
        letter-spacing: 0.1em;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .toc-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }

    .toc-link {
        font-size: 0.825rem;
        color: var(--text-muted);
        text-decoration: none;
        display: block;
        padding: 0.4rem 0;
        transition: var(--transition-base);
        border-left: 2px solid transparent;
        padding-left: 8px;
    }

    .toc-link:hover {
        color: var(--accent-color);
        border-left-color: var(--accent-color);
        padding-left: 12px;
    }

    /* Main Legal Content Container */
    .legal-content-card {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 3.5rem;
        box-shadow: 0 10px 40px -10px rgba(30, 30, 29, 0.04);
        position: relative;
        overflow: hidden;
    }

    /* Decorative watermarked background Kanji */
    .legal-bg-watermark {
        position: absolute;
        right: -2rem;
        top: 1rem;
        font-family: 'Zen Old Mincho', serif;
        font-size: 15rem;
        color: rgba(162, 56, 74, 0.012);
        pointer-events: none;
        user-select: none;
        line-height: 1;
        z-index: 0;
    }

    .legal-header {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1.5rem;
        margin-bottom: 2.5rem;
        position: relative;
        z-index: 1;
    }

    .legal-main-title {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 2.75rem;
        color: var(--primary-color);
        letter-spacing: -0.02em;
        line-height: 1.1;
    }

    .last-updated {
        font-size: 0.775rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .legal-section {
        margin-bottom: 3rem;
        scroll-margin-top: 100px; /* offset for sticky navbar */
        position: relative;
        z-index: 1;
        opacity: 0;
        transform: translateY(15px);
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .legal-section-title {
        font-family: 'Zen Old Mincho', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1.15rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
        border-bottom: 1px solid var(--bg-subtle);
        padding-bottom: 0.5rem;
    }

    .legal-section-title::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: var(--accent-color);
        border-radius: 50%;
    }

    .legal-section p, .legal-section li {
        color: var(--text-main);
        line-height: 1.8;
        font-size: 0.925rem;
    }

    .legal-section ul {
        padding-left: 1.25rem;
        margin-top: 0.5rem;
    }

    .legal-section li {
        margin-bottom: 0.65rem;
    }

    .legal-section strong {
        color: var(--primary-color);
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumbs -->
    <div class="mb-4">
        <a href="{{ url('/') }}" class="text-secondary small text-decoration-none d-inline-flex align-items-center gap-1">
            <i class="bi bi-chevron-left small"></i>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <div class="legal-layout">
        <!-- Sticky Sidebar Navigation -->
        <aside class="legal-sidebar animate-fade-in">
            <h5 class="toc-title">Navigasi</h5>
            <ul class="toc-list">
                <li><a href="#section-1" class="toc-link">1. Ketentuan Umum</a></li>
                <li><a href="#section-2" class="toc-link">2. Akun & Keamanan</a></li>
                <li><a href="#section-3" class="toc-link">3. Transaksi & Pembayaran</a></li>
                <li><a href="#section-4" class="toc-link">4. Kebijakan Pengiriman</a></li>
                <li><a href="#section-5" class="toc-link">5. Hak Cipta & Lisensi</a></li>
                <li><a href="#section-6" class="toc-link">6. Perubahan Ketentuan</a></li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <div class="legal-content-card animate-fade-in-up stagger-1">
            <!-- Decorative Kanji background watermark: 規 (Rule) -->
            <div class="legal-bg-watermark">規</div>

            <!-- Page Header -->
            <header class="legal-header">
                <h1 class="legal-main-title">Ketentuan Layanan</h1>
                <p class="last-updated">Pembaruan Terakhir: {{ date('d M Y') }}</p>
            </header>

            <!-- Sections -->
            <div class="legal-body">
                <section id="section-1" class="legal-section stagger-1">
                    <h3 class="legal-section-title">1. Ketentuan Umum</h3>
                    <p>Selamat datang di <strong>YASSUI</strong>. Sebelum Anda menggunakan layanan e-commerce kami, harap baca Ketentuan Layanan ini dengan saksama. Dengan mengakses dan berbelanja di platform kami, Anda secara sadar menyetujui seluruh ketentuan yang tercantum di bawah ini.</p>
                    <p>YASSUI adalah platform e-commerce yang didedikasikan khusus untuk penjualan produk merchandise anime premium, action figures, model kits, plushies, dan barang karakter koleksi lainnya. Seluruh transaksi di platform ini diselenggarakan di bawah pengawasan sistem simulasi lokal untuk kepentingan akademis.</p>
                </section>

                <section id="section-2" class="legal-section stagger-2">
                    <h3 class="legal-section-title">2. Akun Pengguna & Keamanan</h3>
                    <p>Demi kenyamanan berbelanja, pengguna diwajibkan mematuhi aturan akun berikut:</p>
                    <ul>
                        <li>Anda dapat mendaftar akun secara manual atau menggunakan layanan Single Sign-On (SSO) Google OAuth secara aman.</li>
                        <li>Anda bertanggung jawab penuh atas kerahasiaan informasi kredensial akun Anda, termasuk kata sandi dan token otentikasi Google.</li>
                        <li>Kami berhak menangguhkan akun jika terdeteksi aktivitas mencurigakan atau pelanggaran terhadap ketentuan platform.</li>
                    </ul>
                </section>

                <section id="section-3" class="legal-section stagger-3">
                    <h3 class="legal-section-title">3. Kebijakan Transaksi & Pembayaran</h3>
                    <p>Ketentuan transaksi e-commerce diatur sebagai berikut:</p>
                    <ul>
                        <li>Seluruh sistem pembayaran diselenggarakan secara aman melalui integrasi <strong>Midtrans Sandbox (Simulator)</strong>.</li>
                        <li>Dalam lingkungan Sandbox ini, Anda tidak akan dikenakan biaya riil atau uang nyata. Semua simulasi transaksi menggunakan kartu kredit tes atau channel simulator bank resmi dari Midtrans.</li>
                        <li>Stok produk anime akan dikurangi secara otomatis saat order dibuat, dan akan dikembalikan secara penuh jika pembayaran gagal, kedaluwarsa, atau dibatalkan oleh pihak pengelola.</li>
                    </ul>
                </section>

                <section id="section-4" class="legal-section stagger-4">
                    <h3 class="legal-section-title">4. Kebijakan Pengiriman Barang</h3>
                    <p>Barang yang dibeli dalam aplikasi ini bersifat simulasi akademis. Untuk pengujian aliran logistik, status pengiriman dikontrol sepenuhnya secara internal oleh pengelola toko melalui halaman kendali admin (Pending, Processing, Shipped, Completed, Cancelled).</p>
                </section>

                <section id="section-5" class="legal-section stagger-5">
                    <h3 class="legal-section-title">5. Hak Cipta & Lisensi</h3>
                    <p>Seluruh kekayaan intelektual, gambar merchandise, logo, nama merek, dan desain antarmuka YASSUI dilindungi oleh hak cipta. Penggunaan aset secara tidak sah untuk tujuan komersial di luar lingkup akademik dilarang keras.</p>
                </section>

                <section id="section-6" class="legal-section stagger-5" style="animation-delay: 0.6s;">
                    <h3 class="legal-section-title">6. Perubahan Ketentuan</h3>
                    <p>Kami berhak memperbarui Ketentuan Layanan ini kapan saja demi kepatuhan akademis atau pengembangan sistem. Perubahan akan langsung berlaku setelah dipublikasikan di halaman ini.</p>
                </section>
            </div>

            <hr class="my-5" style="border-color: var(--border-color);">

            <footer class="d-flex justify-content-between align-items-center flex-wrap gap-3" style="font-size: 0.8rem;">
                <a href="{{ url('/') }}" class="btn-minimal-secondary text-decoration-none py-2 px-3">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
                <span class="text-muted">© {{ date('Y') }} YASSUI. Hak Cipta Dilindungi.</span>
            </footer>
        </div>
    </div>
</div>
@endsection
