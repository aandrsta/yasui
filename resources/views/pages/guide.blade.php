@extends('layouts.app')

@section('title', 'Cara Pemesanan')

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

    /* Decorative watermarked background Kanji: 購 (Purchase) */
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
        margin-bottom: 3.5rem;
        scroll-margin-top: 100px; /* offset for sticky navbar */
        position: relative;
        z-index: 1;
        opacity: 0;
        transform: translateY(15px);
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* Guide step design card */
    .step-card {
        background-color: var(--bg-subtle);
        border: 1px solid var(--border-color);
        border-radius: 4px;
        padding: 1.75rem;
        margin-top: 1rem;
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
        transition: var(--transition-base);
    }

    .step-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 5px 15px rgba(30, 30, 29, 0.04);
        transform: translateY(-2px);
    }

    .step-number {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--accent-color);
        line-height: 1;
        width: 45px;
        height: 45px;
        border: 1px solid rgba(162, 56, 74, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--bg-main);
        flex-shrink: 0;
    }

    .step-title {
        font-family: 'Zen Old Mincho', serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .step-body p {
        margin-bottom: 0;
        font-size: 0.9rem;
        color: var(--text-main);
        line-height: 1.6;
    }

    .legal-section p, .legal-section li {
        color: var(--text-main);
        line-height: 1.8;
        font-size: 0.925rem;
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
            <h5 class="toc-title">Langkah</h5>
            <ul class="toc-list">
                <li><a href="#step-1" class="toc-link">1. Pilih Produk</a></li>
                <li><a href="#step-2" class="toc-link">2. Kelola Keranjang</a></li>
                <li><a href="#step-3" class="toc-link">3. Isi Data Penerima</a></li>
                <li><a href="#step-4" class="toc-link">4. Bayar Sandbox</a></li>
                <li><a href="#step-5" class="toc-link">5. Selebrasi Sakura</a></li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <div class="legal-content-card animate-fade-in-up">
            <!-- Decorative Kanji background watermark: 購 -->
            <div class="legal-bg-watermark">購</div>

            <!-- Page Header -->
            <header class="legal-header">
                <h1 class="legal-main-title">Panduan Cara Pemesanan</h1>
                <p class="last-updated">Panduan Belanja Merchandise Anime Premium YASSUI</p>
            </header>

            <p class="lead text-muted mb-4" style="font-size: 0.975rem; line-height: 1.8;">
                Selamat datang di panduan pemesanan YASSUI. Kami menyusun proses belanja dengan kurasi micro-interaction yang sangat ramah pengguna (UX-focused). Ikuti 5 langkah mudah di bawah ini untuk memulai koleksi anime berstandar museum Anda.
            </p>

            <!-- Steps -->
            <div class="legal-body">
                
                <!-- Step 1 -->
                <section id="step-1" class="legal-section stagger-1">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <div class="step-body">
                            <h4 class="step-title"><i class="bi bi-search me-1 text-secondary"></i> Cari & Pilih Produk Anime</h4>
                            <p>Buka halaman <a href="{{ url('/products') }}" class="text-dark fw-semibold">Katalog Produk</a>. Anda dapat memfilter merchandise berdasarkan kategori (Figures, Model Kits, Character Goods) atau menyortir berdasarkan harga/tanggal produk. Klik produk pilihan untuk melihat detail rincian, deskripsi spesifikasi, dan stok barang.</p>
                        </div>
                    </div>
                </section>

                <!-- Step 2 -->
                <section id="step-2" class="legal-section stagger-2">
                    <div class="step-card">
                        <div class="step-number">02</div>
                        <div class="step-body">
                            <h4 class="step-title"><i class="bi bi-cart3 me-1 text-secondary"></i> Masukkan & Kelola Keranjang</h4>
                            <p>Tentukan kuantitas produk lalu klik <strong>"Tambah ke Keranjang"</strong>. Buka halaman keranjang untuk memilih item tertentu yang ingin dicheckout menggunakan checkbox. Total tagihan akan diperbarui secara real-time. Anda juga dapat menghapus item dengan transisi slide row collapse yang mulus.</p>
                        </div>
                    </div>
                </section>

                <!-- Step 3 -->
                <section id="step-3" class="legal-section stagger-3">
                    <div class="step-card">
                        <div class="step-number">03</div>
                        <div class="step-body">
                            <h4 class="step-title"><i class="bi bi-geo-alt me-1 text-secondary"></i> Lengkapi Data Pengiriman (Strict Form)</h4>
                            <p>Pada halaman checkout, masukkan nama penerima dan alamat pengiriman Anda secara lengkap. Form kami dilengkapi dengan filter otomatis yang sangat ketat: **Nomor telepon hanya boleh diisi angka** (9-15 digit) dan **Nama lengkap hanya boleh berisi huruf**. Tekan "Buat Pesanan & Bayar" dan konfirmasi popup wabi-sabi kami.</p>
                        </div>
                    </div>
                </section>

                <!-- Step 4 -->
                <section id="step-4" class="legal-section stagger-4">
                    <div class="step-card">
                        <div class="step-number">04</div>
                        <div class="step-body">
                            <h4 class="step-title"><i class="bi bi-credit-card me-1 text-secondary"></i> Selesaikan Pembayaran Simulator (Sandbox)</h4>
                            <p>Sistem akan mengarahkan Anda ke gerbang pembayaran aman Midtrans. Karena ini adalah sistem akademis, **tidak ada uang nyata yang ditarik**. Pilih metode pembayaran seperti QRIS simulator, GoPay simulator, atau Transfer Virtual Account bank simulator untuk menyelesaikan pembayaran secara gratis.</p>
                        </div>
                    </div>
                </section>

                <!-- Step 5 -->
                <section id="step-5" class="legal-section stagger-5">
                    <div class="step-card">
                        <div class="step-number">05</div>
                        <div class="step-body">
                            <h4 class="step-title"><i class="bi bi-flower1 me-1 text-secondary"></i> Selebrasi Sakura & Pantau Status</h4>
                            <p>Setelah pembayaran Anda terverifikasi sukses, Anda akan disambut dengan **selebrasi kelopak sakura berguguran** dan **stempel Hanko YASSUI** yang slam-animate di layar. Anda dapat memantau proses pengemasan barang Anda secara langsung di halaman detail pesanan atau riwayat pesanan akun Anda.</p>
                        </div>
                    </div>
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
