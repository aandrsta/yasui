@extends('layouts.app')

@section('title', 'Cara Pemesanan')

@section('styles')
<style>
    html {
        scroll-behavior: smooth;
    }

    .legal-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 5rem;
        align-items: start;
    }

    @media (max-width: 991.98px) {
        .legal-layout {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
        .legal-sidebar {
            position: static !important;
            margin-bottom: 2rem;
        }
    }

    /* Sidebar Table of Contents */
    .legal-sidebar {
        position: sticky;
        top: 110px;
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 2rem;
    }

    .toc-title {
        font-family: 'Zen Old Mincho', serif;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--primary-color);
        letter-spacing: 0.15em;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .toc-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .toc-link {
        font-size: 0.85rem;
        color: var(--text-muted);
        text-decoration: none;
        display: block;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .toc-link:hover, .toc-link.active {
        color: var(--accent-color);
        font-weight: 600;
        transform: translateX(4px);
    }

    /* Main Content Area */
    .legal-document-wrapper {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 4.5rem 5rem;
        position: relative;
    }

    @media (max-width: 575.98px) {
        .legal-document-wrapper {
            padding: 2.5rem 1.5rem;
        }
    }

    .legal-header {
        border-bottom: 1.5px solid var(--primary-color);
        padding-bottom: 2.5rem;
        margin-bottom: 3.5rem;
    }

    .legal-main-title {
        font-family: 'Zen Old Mincho', serif;
        font-weight: 500;
        font-size: 2.5rem;
        color: var(--primary-color);
        letter-spacing: -0.02em;
        line-height: 1.25;
        margin-bottom: 0.5rem;
    }

    .legal-sub-title-jp {
        font-family: 'Zen Old Mincho', serif;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--text-muted);
        letter-spacing: 0.25em;
        text-transform: uppercase;
        display: block;
        margin-bottom: 1.5rem;
    }

    /* Steps and sections */
    .legal-section {
        margin-bottom: 4.5rem;
        scroll-margin-top: 120px;
        max-width: 70ch;
    }

    /* Step box layout */
    .step-card {
        background-color: var(--bg-subtle);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 2rem;
        display: flex;
        gap: 1.75rem;
        align-items: flex-start;
        transition: var(--transition-base);
    }

    .step-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(30, 30, 29, 0.02);
    }

    .step-number-kanji {
        font-family: 'Zen Old Mincho', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--accent-color);
        line-height: 1;
        width: 48px;
        height: 48px;
        border: 1px solid var(--border-color);
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
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .step-body p {
        margin-bottom: 0;
        font-size: 0.925rem;
        color: var(--text-main);
        line-height: 1.75;
    }

    .step-body a {
        color: var(--accent-color);
        text-decoration: none;
        font-weight: 600;
        border-bottom: 1px solid transparent;
        transition: var(--transition-base);
    }

    .step-body a:hover {
        border-bottom-color: var(--accent-color);
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
                <li><a href="#step-1" class="toc-link active">1. Pilih Produk</a></li>
                <li><a href="#step-2" class="toc-link">2. Kelola Keranjang</a></li>
                <li><a href="#step-3" class="toc-link">3. Isi Data Penerima</a></li>
                <li><a href="#step-4" class="toc-link">4. Selesaikan Bayar</a></li>
                <li><a href="#step-5" class="toc-link">5. Selebrasi Status</a></li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <div class="legal-document-wrapper animate-fade-in-up">
            <!-- Page Header -->
            <header class="legal-header">
                <h1 class="legal-main-title">Panduan Cara Pemesanan</h1>
                <span class="legal-sub-title-jp">購入手順 / Shopping Guide</span>
            </header>

            <p class="lead text-muted mb-5" style="font-size: 0.975rem; line-height: 1.85; max-width: 70ch;">
                Selamat datang di panduan pemesanan YASSUI. Kami menyusun proses belanja dengan kurasi micro-interaction yang sangat ramah pengguna (UX-focused). Ikuti 5 langkah mudah di bawah ini untuk memulai koleksi anime berstandar museum Anda.
            </p>

            <!-- Steps -->
            <div class="legal-body">
                
                <!-- Step 1 -->
                <section id="step-1" class="legal-section">
                    <div class="step-card">
                        <div class="step-number-kanji">一</div>
                        <div class="step-body">
                            <h4 class="step-title"><i class="bi bi-search text-secondary" style="font-size: 0.95rem;"></i> Cari & Pilih Produk Anime</h4>
                            <p>Buka halaman <a href="{{ url('/products') }}">Katalog Produk</a>. Anda dapat memfilter merchandise berdasarkan kategori (Figures, Model Kits, Character Goods) atau menyortir berdasarkan harga/tanggal produk. Klik produk pilihan untuk melihat detail rincian, deskripsi spesifikasi, dan stok barang.</p>
                        </div>
                    </div>
                </section>

                <!-- Step 2 -->
                <section id="step-2" class="legal-section">
                    <div class="step-card">
                        <div class="step-number-kanji">二</div>
                        <div class="step-body">
                            <h4 class="step-title"><i class="bi bi-bag-check text-secondary" style="font-size: 0.95rem;"></i> Masukkan & Kelola Keranjang</h4>
                            <p>Tentukan kuantitas produk lalu klik <strong>"Tambah ke Keranjang"</strong>. Buka halaman keranjang untuk memilih item tertentu yang ingin dicheckout menggunakan checkbox. Total tagihan akan diperbarui secara real-time. Anda juga dapat menghapus item dengan transisi slide row collapse yang mulus.</p>
                        </div>
                    </div>
                </section>

                <!-- Step 3 -->
                <section id="step-3" class="legal-section">
                    <div class="step-card">
                        <div class="step-number-kanji">三</div>
                        <div class="step-body">
                            <h4 class="step-title"><i class="bi bi-card-checklist text-secondary" style="font-size: 0.95rem;"></i> Lengkapi Data Pengiriman (Strict Form)</h4>
                            <p>Pada halaman checkout, masukkan nama penerima dan alamat pengiriman Anda secara lengkap. Form kami dilengkapi dengan filter otomatis yang sangat ketat: **Nomor telepon hanya boleh diisi angka** (9-15 digit) dan **Nama lengkap hanya boleh berisi huruf**. Tekan "Buat Pesanan & Bayar" dan konfirmasi popup wabi-sabi kami.</p>
                        </div>
                    </div>
                </section>

                <!-- Step 4 -->
                <section id="step-4" class="legal-section">
                    <div class="step-card">
                        <div class="step-number-kanji">四</div>
                        <div class="step-body">
                            <h4 class="step-title"><i class="bi bi-credit-card text-secondary" style="font-size: 0.95rem;"></i> Selesaikan Pembayaran Simulator (Sandbox)</h4>
                            <p>Sistem akan mengarahkan Anda ke gerbang pembayaran aman Midtrans. Karena ini adalah sistem akademis, **tidak ada uang nyata yang ditarik**. Pilih metode pembayaran seperti QRIS simulator, GoPay simulator, atau Transfer Virtual Account bank simulator untuk menyelesaikan pembayaran secara gratis.</p>
                        </div>
                    </div>
                </section>

                <!-- Step 5 -->
                <section id="step-5" class="legal-section">
                    <div class="step-card">
                        <div class="step-number-kanji">五</div>
                        <div class="step-body">
                            <h4 class="step-title"><i class="bi bi-flower1 text-secondary" style="font-size: 0.95rem;"></i> Selebrasi Sakura & Pantau Status</h4>
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

@section('scripts')
<script nonce="{{ app('csp-nonce') }}">
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('.legal-section');
        const tocLinks = document.querySelectorAll('.toc-link');

        // 1. Dynamic Table of Contents scrolling highlighting (premium IntersectionObserver)
        const observerOptions = {
            root: null,
            rootMargin: '-20% 0px -55% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    tocLinks.forEach(link => {
                        if (link.getAttribute('href') === `#${id}`) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    });
                }
            });
        }, observerOptions);

        sections.forEach(section => observer.observe(section));
    });
</script>
@endsection
