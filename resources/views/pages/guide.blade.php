@extends('layouts.app')

@section('title', 'Cara Pemesanan')

@section('styles')
<style>
    html {
        scroll-behavior: smooth;
    }

    /* Genkouyoushi (Japanese manuscript paper) grid background */
    .genkouyoushi-container {
        position: relative;
        background-color: var(--bg-main);
        background-image: 
            linear-gradient(to right, rgba(162, 56, 74, 0.015) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(162, 56, 74, 0.015) 1px, transparent 1px);
        background-size: 28px 28px;
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 4.5rem;
        box-shadow: 0 15px 50px -15px rgba(30, 30, 29, 0.04);
        overflow: hidden;
    }

    /* Vertical decorative side text */
    .vertical-deco-label {
        position: absolute;
        top: 2rem;
        left: 2rem;
        font-family: 'Zen Old Mincho', serif;
        font-size: 5rem;
        font-weight: 700;
        color: rgba(162, 56, 74, 0.012);
        writing-mode: vertical-rl;
        text-orientation: upright;
        user-select: none;
        pointer-events: none;
        line-height: 1;
    }

    .legal-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 4rem;
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
        .genkouyoushi-container {
            padding: 2.5rem 2rem;
        }
    }

    /* Sidebar Table of Contents with scroll progress */
    .legal-sidebar {
        position: sticky;
        top: 110px;
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 1.75rem;
        box-shadow: 0 4px 30px rgba(30, 30, 29, 0.02);
    }

    .toc-title {
        font-family: 'Zen Old Mincho', serif;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--primary-color);
        letter-spacing: 0.15em;
        margin-bottom: 1.25rem;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid var(--border-color);
        position: relative;
    }
    
    .toc-title::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 35px;
        height: 1.5px;
        background-color: var(--accent-color);
    }

    .toc-list-wrapper {
        position: relative;
        padding-left: 14px;
    }

    /* Scroll progress line */
    .toc-progress-line {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: var(--border-color);
    }

    .toc-progress-bar {
        position: absolute;
        left: 0;
        top: 0;
        width: 2px;
        height: 0%;
        background-color: var(--accent-color);
        transition: height 0.1s ease;
    }

    .toc-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .toc-link {
        font-size: 0.825rem;
        color: var(--text-muted);
        text-decoration: none;
        display: block;
        padding: 0.45rem 0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    .toc-link:hover, .toc-link.active {
        color: var(--accent-color);
        font-weight: 600;
        padding-left: 4px;
    }

    .toc-link::before {
        content: '•';
        position: absolute;
        left: -14px;
        opacity: 0;
        color: var(--accent-color);
        transition: opacity 0.3s ease;
    }

    .toc-link.active::before {
        opacity: 1;
    }

    /* Distressed SVG Hanko Stamp Seal Watermark: 購 (Purchase) */
    .hanko-seal-svg {
        position: absolute;
        right: 3rem;
        top: 3rem;
        width: 100px;
        height: 100px;
        transform: rotate(-8deg);
        user-select: none;
        pointer-events: none;
        z-index: 5;
        opacity: 0.8;
        animation: fadeInHanko 1.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes fadeInHanko {
        from { opacity: 0; transform: scale(1.6) rotate(-45deg); }
        to { opacity: 0.8; transform: scale(1) rotate(-8deg); }
    }

    .legal-header {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 2rem;
        margin-bottom: 3.5rem;
        position: relative;
        z-index: 1;
    }

    .legal-main-title {
        font-family: 'Zen Old Mincho', serif;
        font-weight: 600;
        font-size: 2.5rem;
        color: var(--primary-color);
        letter-spacing: -0.01em;
        line-height: 1.2;
    }

    .legal-section {
        margin-bottom: 4rem;
        scroll-margin-top: 120px;
        position: relative;
        z-index: 1;
        opacity: 0;
        transform: translateY(15px);
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    /* Premium step cards */
    .step-card {
        background-color: var(--bg-subtle);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 2.25rem;
        display: flex;
        gap: 2rem;
        align-items: flex-start;
        transition: var(--transition-base);
        position: relative;
    }

    .step-card:hover {
        border-color: var(--accent-color);
        box-shadow: 0 6px 20px -8px rgba(30, 30, 29, 0.04);
        transform: translateY(-2px);
    }

    /* Kanji numbered step circle frame */
    .step-number-kanji {
        font-family: 'Zen Old Mincho', serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--accent-color);
        line-height: 1;
        width: 52px;
        height: 52px;
        border: 2px double rgba(162, 56, 74, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--bg-main);
        flex-shrink: 0;
        position: relative;
    }

    .step-number-kanji::after {
        content: '';
        position: absolute;
        top: -4px;
        left: -4px;
        right: -4px;
        bottom: -4px;
        border: 1px dashed rgba(162, 56, 74, 0.15);
        border-radius: 50%;
    }

    .step-title {
        font-family: 'Zen Old Mincho', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .step-body p {
        margin-bottom: 0;
        font-size: 0.925rem;
        color: var(--text-main);
        line-height: 1.8;
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
<!-- Distressed Ink-Bleed Filters (declarative markup) -->
<svg style="position: absolute; width: 0; height: 0; overflow: hidden;" width="0; height: 0">
  <defs>
    <filter id="hanko-ink-bleed" x="-20%" y="-20%" width="140%" height="140%">
      <feTurbulence type="fractalNoise" baseFrequency="0.06" numOctaves="4" result="noise" />
      <feDisplacementMap in="SourceGraphic" in2="noise" scale="3" xChannelSelector="R" yChannelSelector="G" result="displaced" />
      <feGaussianBlur in="displaced" stdDeviation="0.4" result="blurred" />
      <feMerge>
        <feMergeNode in="blurred" />
        <feMergeNode in="SourceGraphic" opacity="0.25" />
      </feMerge>
    </filter>
  </defs>
</svg>

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
            <div class="toc-list-wrapper">
                <div class="toc-progress-line">
                    <div id="toc-progress-bar" class="toc-progress-bar"></div>
                </div>
                <ul class="toc-list">
                    <li><a href="#step-1" class="toc-link active">1. Pilih Produk</a></li>
                    <li><a href="#step-2" class="toc-link">2. Kelola Keranjang</a></li>
                    <li><a href="#step-3" class="toc-link">3. Isi Data Penerima</a></li>
                    <li><a href="#step-4" class="toc-link">4. Bayar Sandbox</a></li>
                    <li><a href="#step-5" class="toc-link">5. Selebrasi Sakura</a></li>
                </ul>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="genkouyoushi-container animate-fade-in-up stagger-1">
            <!-- Background vertical label -->
            <div class="vertical-deco-label">購入手順</div>

            <!-- Distressed Ink-Bleed Hanko Stamp: 購 (Purchase) -->
            <svg class="hanko-seal-svg" viewBox="0 0 100 100" style="filter: url(#hanko-ink-bleed);">
                <circle cx="50" cy="50" r="40" stroke="var(--accent-color)" stroke-width="4.5" fill="none" stroke-dasharray="115 4 8 4" />
                <text x="50" y="62" font-family="'Zen Old Mincho', serif" font-size="34" font-weight="bold" fill="var(--accent-color)" text-anchor="middle">購</text>
            </svg>

            <!-- Page Header -->
            <header class="legal-header">
                <h1 class="legal-main-title">Panduan Cara Pemesanan</h1>
                <p class="last-updated">Panduan Belanja Merchandise Anime Premium YASSUI</p>
            </header>

            <p class="lead text-muted mb-5" style="font-size: 0.975rem; line-height: 1.85;">
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

        // 2. Vertical progress indicator in Sidebar
        window.addEventListener('scroll', function() {
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrolled = (window.scrollY / docHeight) * 100;
            const progressBar = document.getElementById('toc-progress-bar');
            if (progressBar) {
                progressBar.style.height = `${scrolled}%`;
            }
        });
    });
</script>
@endsection
