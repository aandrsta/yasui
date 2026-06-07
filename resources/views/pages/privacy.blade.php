@extends('layouts.app')

@section('title', 'Kebijakan Privasi')

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

    /* Decorative watermarked background Kanji: 密 (Privacy/Secret) */
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
                <li><a href="#section-1" class="toc-link">1. Informasi Pengumpulan</a></li>
                <li><a href="#section-2" class="toc-link">2. Penggunaan Informasi</a></li>
                <li><a href="#section-3" class="toc-link">3. Keamanan Data</a></li>
                <li><a href="#section-4" class="toc-link">4. Cookies & GA4</a></li>
                <li><a href="#section-5" class="toc-link">5. Hak-Hak Pengguna</a></li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <div class="legal-content-card animate-fade-in-up stagger-1">
            <!-- Decorative Kanji background watermark: 密 -->
            <div class="legal-bg-watermark">密</div>

            <!-- Page Header -->
            <header class="legal-header">
                <h1 class="legal-main-title">Kebijakan Privasi</h1>
                <p class="last-updated">Pembaruan Terakhir: {{ date('d M Y') }}</p>
            </header>

            <!-- Sections -->
            <div class="legal-body">
                <section id="section-1" class="legal-section stagger-1">
                    <h3 class="legal-section-title">1. Informasi yang Kami Kumpulkan</h3>
                    <p>Di <strong>YASSUI</strong>, kami sangat menghormati privasi Anda. Kami mengumpulkan data pribadi Anda semata-mata untuk mendukung kelancaran layanan belanja dan analisis perilaku pengguna:</p>
                    <ul>
                        <li><strong>Data Pendaftaran Akun:</strong> Nama lengkap, alamat email, kata sandi terenkripsi, nomor telepon, dan alamat lengkap pengiriman saat melakukan registrasi.</li>
                        <li><strong>Kredensial SSO Google:</strong> Saat Anda menggunakan login cepat Google, kami mengambil email, nama, dan foto profil publik Anda secara aman sesuai otorisasi Google OAuth.</li>
                        <li><strong>Informasi Transaksi:</strong> Data keranjang belanja, rincian pembayaran simulasi Midtrans Sandbox, dan riwayat nomor pesanan Anda.</li>
                        <li><strong>Data Pelacakan (GA4):</strong> Halaman produk yang Anda lihat, interaksi menambah keranjang, konversi transaksi sukses, tipe browser, dan pola browsing yang dipantau melalui Google Analytics 4.</li>
                    </ul>
                </section>

                <section id="section-2" class="legal-section stagger-2">
                    <h3 class="legal-section-title">2. Penggunaan Informasi</h3>
                    <p>Data yang dikumpulkan di platform YASSUI digunakan untuk tujuan berikut:</p>
                    <ul>
                        <li>Membuat akun pembeli dan mengelola otentikasi login secara aman.</li>
                        <li>Memproses checkout belanja dan mengelola pengiriman simulasi ke alamat rumah Anda.</li>
                        <li>Melakukan integrasi verifikasi status pembayaran secara real-time dengan server Midtrans API secara asinkron.</li>
                        <li><strong>Keperluan Evaluasi Akademis:</strong> Memberikan data analisis perilaku belanja pengguna kepada dosen penguji melalui dashboard GA4 saat simulasi belanja diselenggarakan.</li>
                    </ul>
                </section>

                <section id="section-3" class="legal-section stagger-3">
                    <h3 class="legal-section-title">3. Kerahasiaan & Keamanan Data</h3>
                    <p>Kata sandi pengguna dienkripsi secara ketat di server kami menggunakan algoritma <strong>Bcrypt</strong> bawaan Laravel. Kami menjamin seluruh data pribadi Anda (termasuk email login Google) tidak akan pernah dijual, disebarkan, atau disalahgunakan di luar kepentingan akademis proyek kuliah ini.</p>
                </section>

                <section id="section-4" class="legal-section stagger-4">
                    <h3 class="legal-section-title">4. Penggunaan Cookies & Google Analytics</h3>
                    <p>Website ini menggunakan cookies untuk mengingat barang di keranjang belanja Anda dan mendeteksi sesi masuk. Kami juga memanfaatkan layanan Google Analytics 4 untuk event tracking perilaku belanja yang aman.</p>
                </section>

                <section id="section-5" class="legal-section stagger-5">
                    <h3 class="legal-section-title">5. Hak-Hak Pengguna</h3>
                    <p>Anda memiliki hak penuh untuk memperbarui alamat profil pengiriman Anda atau menghapus data belanja Anda dengan menghubungi Administrator e-commerce YASSUI kapan saja.</p>
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
