@extends('layouts.app')

@section('title', 'Ketentuan Layanan')

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

    /* Search bar styles */
    .legal-search-wrapper {
        position: relative;
        margin-bottom: 2.5rem;
    }
    
    .legal-search-input {
        width: 100%;
        padding: 12px 12px 12px 42px;
        border: 1px solid var(--border-color);
        border-radius: 3px;
        background-color: var(--bg-subtle);
        font-size: 0.875rem;
        transition: var(--transition-base);
    }
    
    .legal-search-input:focus {
        border-color: var(--accent-color);
        background-color: #ffffff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(162, 56, 74, 0.06);
    }

    .legal-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1rem;
        display: flex;
        align-items: center;
    }

    /* Distressed SVG Hanko Stamp Seal */
    .hanko-seal-svg {
        position: absolute;
        right: 3rem;
        top: 3rem;
        width: 90px;
        height: 90px;
        transform: rotate(-12deg);
        user-select: none;
        pointer-events: none;
        z-index: 5;
        opacity: 0.85;
        animation: fadeInHanko 1.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes fadeInHanko {
        from { opacity: 0; transform: scale(1.6) rotate(-45deg); }
        to { opacity: 0.85; transform: scale(1) rotate(-12deg); }
    }

    .legal-header {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 2rem;
        margin-bottom: 3rem;
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

    .last-updated {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .legal-section {
        margin-bottom: 4rem;
        scroll-margin-top: 120px;
        position: relative;
        z-index: 1;
        opacity: 0;
        transform: translateY(15px);
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        transition: border-color 0.3s, padding-left 0.3s;
        border-left: 2px solid transparent;
        padding-left: 0;
    }

    .legal-section.fade-out {
        display: none !important;
    }

    .legal-section:hover {
        border-left-color: rgba(162, 56, 74, 0.25);
        padding-left: 12px;
    }

    .sub-japanese-title {
        font-family: 'Zen Old Mincho', serif;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--accent-color);
        text-transform: uppercase;
        letter-spacing: 0.2em;
        display: block;
        margin-bottom: 0.35rem;
    }

    .legal-section-title {
        font-family: 'Zen Old Mincho', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1.25rem;
    }

    .legal-section p, .legal-section li {
        color: var(--text-main);
        line-height: 1.9;
        font-size: 0.925rem;
    }

    /* Key Takeaway scrolls styling */
    .takeaway-box {
        background-color: var(--bg-subtle);
        border: 1px solid var(--border-color);
        border-left: 3px solid var(--accent-color);
        border-radius: 3px;
        padding: 1.25rem 1.5rem;
        margin: 1.5rem 0;
        position: relative;
    }

    .takeaway-badge {
        font-family: 'Zen Old Mincho', serif;
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--accent-color);
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 0.5rem;
    }

    .takeaway-box p {
        margin: 0;
        font-size: 0.85rem !important;
        line-height: 1.6;
        color: var(--text-muted) !important;
        font-style: italic;
    }

    .legal-section ul {
        list-style: none;
        padding-left: 0;
        margin-top: 0.75rem;
    }

    .legal-section li {
        position: relative;
        padding-left: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .legal-section li::before {
        content: '🌸';
        position: absolute;
        left: 0;
        top: 2px;
        font-size: 0.8rem;
        opacity: 0.7;
    }

    .legal-section strong {
        color: var(--primary-color);
    }

    /* Highlight mark */
    mark.search-highlight {
        background-color: rgba(162, 56, 74, 0.12);
        color: var(--accent-color);
        font-weight: 600;
        padding: 0 2px;
        border-radius: 2px;
    }

    /* Agreement Sign Widget Zone */
    .agreement-zone {
        background-color: var(--bg-subtle);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 2.25rem;
        position: relative;
        z-index: 2;
    }

    .hanko-selector-group {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .btn-hanko-choice {
        background: var(--bg-main);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 8px 16px;
        font-family: 'Zen Old Mincho', serif;
        font-weight: 700;
        color: var(--text-muted);
        cursor: pointer;
        transition: var(--transition-base);
        display: flex;
        flex-direction: column;
        align-items: center;
        line-height: 1.2;
    }

    .btn-hanko-choice:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .btn-hanko-choice.active {
        background-color: var(--bg-main);
        border-color: var(--accent-color);
        color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(162, 56, 74, 0.08);
    }

    .btn-hanko-choice .small-sub {
        font-size: 0.65rem;
        font-weight: 400;
        font-family: 'Instrument Sans', sans-serif;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .btn-hanko-choice.active .small-sub {
        color: var(--accent-color);
    }

    .stamp-board {
        min-height: 140px;
        border: 1px dashed var(--border-color);
        background-color: var(--bg-main);
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .stamp-board::after {
        content: 'TEMPAT CAP PERSETUJUAN';
        font-size: 0.725rem;
        color: var(--text-muted);
        letter-spacing: 0.15em;
        pointer-events: none;
        font-family: 'Zen Old Mincho', serif;
    }

    .stamp-board.has-stamp::after {
        display: none;
    }

    /* Stamped seal animation */
    .stamped-seal {
        position: absolute;
        width: 80px;
        height: 80px;
        pointer-events: none;
        animation: stampSlam 0.4s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
    }

    @keyframes stampSlam {
        0% {
            opacity: 0;
            transform: scale(3) rotate(-35deg);
        }
        70% {
            opacity: 1;
            transform: scale(0.95) rotate(-10deg);
        }
        100% {
            opacity: 1;
            transform: scale(1) rotate(-12deg);
        }
    }

    .shake-impact {
        animation: boardShake 0.15s ease-in-out 2;
    }

    @keyframes boardShake {
        0%, 100% { transform: translate(0, 0); }
        25% { transform: translate(-2px, 2px); }
        75% { transform: translate(2px, -2px); }
    }

    /* Inline stamp-burst animation particle */
    .stamp-particle {
        position: absolute;
        pointer-events: none;
        background-color: #fbcfe8;
        border-radius: 50%;
        width: 6px;
        height: 6px;
        opacity: 1;
        animation: particleFly 0.8s cubic-bezier(0.1, 0.8, 0.3, 1) forwards;
    }

    @keyframes particleFly {
        to {
            transform: translate(var(--x), var(--y)) scale(0);
            opacity: 0;
        }
    }
</style>
@endsection

@section('content')
<!-- Distressed Ink-Bleed Filters (declarative markup) -->
<svg style="position: absolute; width: 0; height: 0; overflow: hidden;" width="0" height="0">
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
            <h5 class="toc-title">Ketentuan</h5>
            <div class="toc-list-wrapper">
                <div class="toc-progress-line">
                    <div id="toc-progress-bar" class="toc-progress-bar"></div>
                </div>
                <ul class="toc-list">
                    <li><a href="#section-1" class="toc-link active">1. Ketentuan Umum</a></li>
                    <li><a href="#section-2" class="toc-link">2. Akun & Keamanan</a></li>
                    <li><a href="#section-3" class="toc-link">3. Transaksi & Pembayaran</a></li>
                    <li><a href="#section-4" class="toc-link">4. Kebijakan Pengiriman</a></li>
                    <li><a href="#section-5" class="toc-link">5. Hak Cipta & Lisensi</a></li>
                    <li><a href="#section-6" class="toc-link">6. Perubahan Ketentuan</a></li>
                </ul>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="genkouyoushi-container animate-fade-in-up stagger-1">
            <!-- Background vertical label -->
            <div class="vertical-deco-label">利用規約</div>

            <!-- Distressed Ink-Bleed Hanko Stamp: 規 (Rules) -->
            <svg class="hanko-seal-svg" viewBox="0 0 100 100" style="filter: url(#hanko-ink-bleed);">
                <circle cx="50" cy="50" r="40" stroke="var(--accent-color)" stroke-width="4.5" fill="none" stroke-dasharray="115 4 8 4" />
                <text x="50" y="62" font-family="'Zen Old Mincho', serif" font-size="34" font-weight="bold" fill="var(--accent-color)" text-anchor="middle">規</text>
            </svg>

            <!-- Page Header -->
            <header class="legal-header">
                <h1 class="legal-main-title">Ketentuan Layanan</h1>
                <p class="last-updated"><i class="bi bi-clock-history"></i> Pembaruan Terakhir: {{ date('d M Y') }}</p>
            </header>

            <!-- Interactive Search Section -->
            <div class="legal-search-wrapper">
                <div class="legal-search-icon">
                    <i class="bi bi-search"></i>
                </div>
                <input type="text" id="policy-search" class="legal-search-input" placeholder="Cari ketetapan atau kata kunci ketentuan layanan...">
            </div>

            <!-- Sections -->
            <div class="legal-body">
                <section id="section-1" class="legal-section">
                    <span class="sub-japanese-title">01 / GENERAL TERMS / 一般条項</span>
                    <h3 class="legal-section-title">1. Ketentuan Umum</h3>
                    <p>Selamat datang di <strong>YASSUI</strong>. Sebelum Anda menggunakan layanan e-commerce kami, harap baca Ketentuan Layanan ini dengan saksama. Dengan mengakses dan berbelanja di platform kami, Anda secara sadar menyetujui seluruh ketentuan yang tercantum di bawah ini.</p>
                    <p>YASSUI adalah platform e-commerce yang didedikasikan khusus untuk penjualan produk merchandise anime premium, action figures, model kits, plushies, dan barang karakter koleksi lainnya. Seluruh transaksi di platform ini diselenggarakan di bawah pengawasan sistem simulasi lokal untuk kepentingan akademis.</p>
                    
                    <div class="takeaway-box">
                        <div class="takeaway-badge">
                            <i class="bi bi-bookmark-star-fill"></i> Ringkasan Cepat
                        </div>
                        <p>Akses belanja di platform ini menandakan Anda menyetujui bahwa seluruh kegiatan transaksi merupakan simulasi pembelajaran akademis.</p>
                    </div>
                </section>

                <section id="section-2" class="legal-section">
                    <span class="sub-japanese-title">02 / USER SECURITY / セキュリティ</span>
                    <h3 class="legal-section-title">2. Akun Pengguna & Keamanan</h3>
                    <p>Demi kenyamanan berbelanja, pengguna diwajibkan mematuhi aturan akun berikut:</p>
                    <ul>
                        <li>Anda dapat mendaftar akun secara manual atau menggunakan layanan Single Sign-On (SSO) Google OAuth secara aman.</li>
                        <li>Anda bertanggung jawab penuh atas kerahasiaan informasi kredensial akun Anda, termasuk kata sandi dan token otentikasi Google.</li>
                        <li>Kami berhak menangguhkan akun jika terdeteksi aktivitas mencurigakan atau pelanggaran terhadap ketentuan platform.</li>
                    </ul>

                    <div class="takeaway-box">
                        <div class="takeaway-badge">
                            <i class="bi bi-shield-check"></i> Ringkasan Cepat
                        </div>
                        <p>Kredensial dan data masuk dilindungi secara ketat, dan Anda memegang kendali serta tanggung jawab penuh atas akun pribadi Anda.</p>
                    </div>
                </section>

                <section id="section-3" class="legal-section">
                    <span class="sub-japanese-title">03 / PAYMENT SYSTEM / 決済システム</span>
                    <h3 class="legal-section-title">3. Kebijakan Transaksi & Pembayaran</h3>
                    <p>Ketentuan transaksi e-commerce diatur sebagai berikut:</p>
                    <ul>
                        <li>Seluruh sistem pembayaran diselenggarakan secara aman melalui integrasi <strong>Midtrans Sandbox (Simulator)</strong>.</li>
                        <li>Dalam lingkungan Sandbox ini, Anda tidak akan dikenakan biaya riil atau uang nyata. Semua simulasi transaksi menggunakan kartu kredit tes atau channel simulator bank resmi dari Midtrans.</li>
                        <li>Stok produk anime akan dikurangi secara otomatis saat order dibuat, dan akan dikembalikan secara penuh jika pembayaran gagal, kedaluwarsa, atau dibatalkan oleh pihak pengelola.</li>
                    </ul>

                    <div class="takeaway-box">
                        <div class="takeaway-badge">
                            <i class="bi bi-wallet2"></i> Ringkasan Cepat
                        </div>
                        <p>Pembayaran hanya simulasi (Sandbox) — tidak ada penarikan uang nyata atau tagihan finansial sungguhan dalam situs ini.</p>
                    </div>
                </section>

                <section id="section-4" class="legal-section">
                    <span class="sub-japanese-title">04 / LOGISTICS / 配送ポリシー</span>
                    <h3 class="legal-section-title">4. Kebijakan Pengiriman Barang</h3>
                    <p>Barang yang dibeli dalam aplikasi ini bersifat simulasi akademis. Untuk pengujian aliran logistik, status pengiriman dikontrol sepenuhnya secara internal oleh pengelola toko melalui halaman kendali admin (Pending, Processing, Shipped, Completed, Cancelled).</p>

                    <div class="takeaway-box">
                        <div class="takeaway-badge">
                            <i class="bi bi-box-seam"></i> Ringkasan Cepat
                        </div>
                        <p>Pengiriman barang hanya berupa simulasi status logistik pada sistem admin dan tidak melibatkan ekspedisi kurir nyata.</p>
                    </div>
                </section>

                <section id="section-5" class="legal-section">
                    <span class="sub-japanese-title">05 / COPYRIGHTS / 著作権保護</span>
                    <h3 class="legal-section-title">5. Hak Cipta & Lisensi</h3>
                    <p>Seluruh kekayaan intelektual, gambar merchandise, logo, nama merek, dan desain antarmuka YASSUI dilindungi oleh hak cipta. Penggunaan aset secara tidak sah untuk tujuan komersial di luar lingkup akademik dilarang keras.</p>

                    <div class="takeaway-box">
                        <div class="takeaway-badge">
                            <i class="bi bi-file-earmark-lock"></i> Ringkasan Cepat
                        </div>
                        <p>Seluruh gambar, aset grafis, dan UI dilindungi secara legal dan dilarang disalin untuk komersialisasi luar akademik.</p>
                    </div>
                </section>

                <section id="section-6" class="legal-section">
                    <span class="sub-japanese-title">06 / AMENDMENTS / 規約の変更</span>
                    <h3 class="legal-section-title">6. Perubahan Ketentuan</h3>
                    <p>Kami berhak memperbarui Ketentuan Layanan ini kapan saja demi kepatuhan akademis atau pengembangan sistem. Perubahan akan langsung berlaku setelah dipublikasikan di halaman ini.</p>
                </section>
            </div>

            <!-- Interactive Agreement Sign Widget -->
            <div class="agreement-zone mt-5">
                <h4 class="font-mincho mb-2" style="font-family: 'Zen Old Mincho', serif; font-size: 1.15rem; color: var(--primary-color);">承諾印 — Cap Persetujuan Digital</h4>
                <p class="text-muted small">Pilih huruf stempel Hanko Anda di bawah, kemudian klik tombol untuk menandatangani ketetapan di atas secara sah.</p>
                
                <div class="hanko-selector-group">
                    <button type="button" class="btn-hanko-choice active" data-char="安">
                        <span>安</span>
                        <span class="small-sub">Yasui</span>
                    </button>
                    <button type="button" class="btn-hanko-choice" data-char="承">
                        <span>承</span>
                        <span class="small-sub">Setuju</span>
                    </button>
                    <button type="button" class="btn-hanko-choice" data-char="吉">
                        <span>吉</span>
                        <span class="small-sub">Fortune</span>
                    </button>
                </div>

                <div class="mb-4">
                    <button type="button" id="btn-stamp-agreement" class="btn-minimal-accent py-2.5 px-4 w-100 w-sm-auto">
                        <i class="bi bi-vector-pen me-2"></i>Bubuhkan Stempel Hanko
                    </button>
                </div>

                <div id="signature-board" class="stamp-board">
                    <!-- Stamped seals will spawn here dynamically -->
                </div>
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
        const searchInput = document.getElementById('policy-search');
        
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

        // 3. Dynamic Real-time Policy search filtering & term highlighting
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                
                sections.forEach(section => {
                    // Remove old highlights first
                    removeHighlights(section);
                    
                    if (query === '') {
                        section.classList.remove('fade-out');
                        return;
                    }
                    
                    const titleText = section.querySelector('.legal-section-title')?.textContent || '';
                    const bodyText = section.textContent || '';
                    const hasMatch = titleText.toLowerCase().includes(query) || bodyText.toLowerCase().includes(query);
                    
                    if (hasMatch) {
                        section.classList.remove('fade-out');
                        // Highlight matches
                        highlightTerm(section, query);
                    } else {
                        section.classList.add('fade-out');
                    }
                });
            });
        }

        function highlightTerm(element, term) {
            const regex = new RegExp(`(${escapeRegExp(term)})`, 'gi');
            
            // Text nodes walker to avoid breaking html tags
            const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, null, false);
            const textNodes = [];
            while (walker.nextNode()) {
                // Ignore script blocks, takeaway-badge header text and non-alpha nodes
                if (walker.currentNode.parentNode.tagName !== 'MARK' && 
                    walker.currentNode.parentNode.tagName !== 'SCRIPT' &&
                    !walker.currentNode.parentNode.classList.contains('takeaway-badge') &&
                    !walker.currentNode.parentNode.classList.contains('sub-japanese-title')) {
                    textNodes.push(walker.currentNode);
                }
            }
            
            textNodes.forEach(node => {
                const text = node.nodeValue;
                if (regex.test(text)) {
                    const span = document.createElement('span');
                    span.innerHTML = text.replace(regex, '<mark class="search-highlight">$1</mark>');
                    node.parentNode.replaceChild(span, node);
                }
            });
        }

        function removeHighlights(element) {
            const highlights = element.querySelectorAll('mark.search-highlight');
            highlights.forEach(highlight => {
                const parent = highlight.parentNode;
                parent.replaceChild(document.createTextNode(highlight.textContent), highlight);
                parent.normalize(); // merge adjacent text nodes
            });
        }

        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        // 4. Interactive Digital Hanko Agreement signing widget
        const signatureBoard = document.getElementById('signature-board');
        const stampBtn = document.getElementById('btn-stamp-agreement');
        const hankoChoices = document.querySelectorAll('.btn-hanko-choice');
        let selectedCharacter = '安';

        hankoChoices.forEach(btn => {
            btn.addEventListener('click', function() {
                hankoChoices.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                selectedCharacter = this.dataset.char;
            });
        });

        if (stampBtn && signatureBoard) {
            stampBtn.addEventListener('click', function(e) {
                // Remove previous stamps to keep board clean
                signatureBoard.innerHTML = '';
                signatureBoard.classList.remove('shake-impact');
                signatureBoard.classList.add('has-stamp');

                // Determine click click coordinates for particle burst center
                const rect = signatureBoard.getBoundingClientRect();
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                // Create Distressed SVG Seal
                const stampDiv = document.createElement('div');
                stampDiv.className = 'stamped-seal';
                stampDiv.style.left = `${centerX - 40}px`;
                stampDiv.style.top = `${centerY - 40}px`;
                
                // SVG content replicating ink bleed look
                stampDiv.innerHTML = `
                    <svg viewBox="0 0 100 100" style="width:100%; height:100%; filter: url(#hanko-ink-bleed);">
                        <circle cx="50" cy="50" r="38" stroke="var(--accent-color)" stroke-width="4.5" fill="none" stroke-dasharray="100 4 8 4" />
                        <text x="50" y="62" font-family="'Zen Old Mincho', serif" font-size="34" font-weight="bold" fill="var(--accent-color)" text-anchor="middle">${selectedCharacter}</text>
                    </svg>
                `;

                signatureBoard.appendChild(stampDiv);

                // Trigger slight wobble impact shake
                setTimeout(() => {
                    signatureBoard.classList.add('shake-impact');
                    
                    // Spawn Sakura Petal Burst Particles
                    for(let i = 0; i < 30; i++) {
                        createSakuraParticle(centerX, centerY);
                    }
                }, 100);
            });
        }

        function createSakuraParticle(x, y) {
            const p = document.createElement('div');
            p.className = 'stamp-particle';
            
            // Random direction values
            const angle = Math.random() * Math.PI * 2;
            const distance = 40 + Math.random() * 80;
            const destX = Math.cos(angle) * distance;
            const destY = Math.sin(angle) * distance;
            
            p.style.left = `${x}px`;
            p.style.top = `${y}px`;
            p.style.setProperty('--x', `${destX}px`);
            p.style.setProperty('--y', `${destY}px`);
            
            // Random rotation and pink tones
            const rotation = Math.random() * 360;
            p.style.transform = `rotate(${rotation}deg)`;
            const colors = ['#fbcfe8', '#f472b6', '#ec4899', '#fdf2f8'];
            p.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            
            signatureBoard.appendChild(p);
            
            // Remove particle after animation
            setTimeout(() => {
                p.remove();
            }, 800);
        }
    });
</script>
@endsection
