@extends('layouts.app')

@section('title', 'Kebijakan Privasi')

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

    .last-updated {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Sections */
    .legal-section {
        margin-bottom: 4.5rem;
        scroll-margin-top: 120px;
        max-width: 70ch; /* Optimal line length */
    }

    .sub-japanese-title {
        font-family: 'Zen Old Mincho', serif;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--accent-color);
        text-transform: uppercase;
        letter-spacing: 0.2em;
        display: block;
        margin-bottom: 0.5rem;
    }

    .legal-section-title {
        font-family: 'Zen Old Mincho', serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        line-height: 1.4;
    }

    .legal-section p, .legal-section li {
        color: var(--text-main);
        line-height: 1.95;
        font-size: 0.95rem;
    }

    .legal-section p {
        margin-bottom: 1.5rem;
    }

    /* Takeaways Box (Full borders, no side-stripe) */
    .takeaway-box {
        background-color: var(--bg-subtle);
        border: 1px solid var(--border-color);
        border-radius: 3px;
        padding: 1.5rem 1.75rem;
        margin: 2rem 0;
    }

    .takeaway-badge {
        font-family: 'Zen Old Mincho', serif;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--accent-color);
        letter-spacing: 0.08em;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0.75rem;
    }

    .takeaway-box p {
        margin: 0;
        font-size: 0.875rem !important;
        line-height: 1.65;
        color: var(--text-muted) !important;
    }

    .legal-section ul {
        list-style: none;
        padding-left: 0;
        margin-top: 1rem;
        margin-bottom: 1.5rem;
    }

    .legal-section li {
        position: relative;
        padding-left: 1.75rem;
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
        font-weight: 600;
    }

    /* Search wrapper styling */
    .search-container {
        margin-bottom: 3.5rem;
        max-width: 450px;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper input {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-subtle);
        border-radius: 3px;
        font-size: 0.875rem;
        transition: var(--transition-base);
    }

    .search-input-wrapper input:focus {
        border-color: var(--accent-color);
        background-color: #ffffff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(162, 56, 74, 0.06);
    }

    .search-input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1rem;
        display: flex;
        align-items: center;
    }

    .legal-section.fade-out {
        display: none !important;
    }

    mark.search-highlight {
        background-color: rgba(162, 56, 74, 0.08);
        color: var(--accent-color);
        font-weight: 600;
        padding: 0 2px;
        border-radius: 2px;
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
            <h5 class="toc-title">Kebijakan</h5>
            <ul class="toc-list">
                <li><a href="#section-1" class="toc-link active">1. Pengumpulan Data</a></li>
                <li><a href="#section-2" class="toc-link">2. Penggunaan Data</a></li>
                <li><a href="#section-3" class="toc-link">3. Keamanan Data</a></li>
                <li><a href="#section-4" class="toc-link">4. Cookies & GA4</a></li>
                <li><a href="#section-5" class="toc-link">5. Hak Pengguna</a></li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <div class="legal-document-wrapper animate-fade-in-up">
            <!-- Page Header -->
            <header class="legal-header">
                <h1 class="legal-main-title">Kebijakan Privasi</h1>
                <span class="legal-sub-title-jp">個人情報保護方針 / Privacy Policy</span>
                <p class="last-updated"><i class="bi bi-clock-history"></i> Pembaruan Terakhir: {{ date('d M Y') }}</p>
            </header>

            <!-- Search Section -->
            <div class="search-container">
                <div class="search-input-wrapper">
                    <span class="search-input-icon"><i class="bi bi-search"></i></span>
                    <input type="text" id="policy-search" placeholder="Cari ketetapan atau kata kunci kebijakan privasi...">
                </div>
            </div>

            <!-- Sections -->
            <div class="legal-body">
                <section id="section-1" class="legal-section">
                    <span class="sub-japanese-title">01 / DATA COLLECTION / 個人情報の収集</span>
                    <h3 class="legal-section-title">1. Informasi yang Kami Kumpulkan</h3>
                    <p>Di <strong>YASSUI</strong>, kami sangat menghormati privasi Anda. Kami mengumpulkan data pribadi Anda semata-mata untuk mendukung kelancaran layanan belanja dan analisis perilaku pengguna:</p>
                    <ul>
                        <li><strong>Data Pendaftaran Akun:</strong> Nama lengkap, alamat email, kata sandi terenkripsi, nomor telepon, dan alamat lengkap pengiriman saat melakukan registrasi.</li>
                        <li><strong>Kredensial SSO Google:</strong> Saat Anda menggunakan login cepat Google, kami mengambil email, nama, dan foto profil publik Anda secara aman sesuai otorisasi Google OAuth.</li>
                        <li><strong>Informasi Transaksi:</strong> Data keranjang belanja, rincian pembayaran simulasi Midtrans Sandbox, dan riwayat nomor pesanan Anda.</li>
                        <li><strong>Data Pelacakan (GA4):</strong> Halaman produk yang Anda lihat, interaksi menambah keranjang, konversi transaksi sukses, tipe browser, dan pola browsing yang dipantau melalui Google Analytics 4.</li>
                    </ul>
                    
                    <div class="takeaway-box">
                        <div class="takeaway-badge">
                            <i class="bi bi-bookmark-star-fill"></i> Ringkasan Cepat
                        </div>
                        <p>Kami mengumpulkan data akun, email Google SSO, detail belanja, dan log analitis (GA4) untuk keperluan simulasi e-commerce.</p>
                    </div>
                </section>

                <section id="section-2" class="legal-section">
                    <span class="sub-japanese-title">02 / PROCESSING / 情報の使用目的</span>
                    <h3 class="legal-section-title">2. Penggunaan Informasi</h3>
                    <p>Data yang dikumpulkan di platform YASSUI digunakan untuk tujuan berikut:</p>
                    <ul>
                        <li>Membuat akun pembeli dan mengelola otentikasi login secara aman.</li>
                        <li>Memproses checkout belanja dan mengelola pengiriman simulasi ke alamat rumah Anda.</li>
                        <li>Melakukan integrasi verifikasi status pembayaran secara real-time dengan server Midtrans API secara asinkron.</li>
                        <li><strong>Keperluan Evaluasi Akademis:</strong> Memberikan data analisis perilaku belanja pengguna kepada dosen penguji melalui dashboard GA4 saat simulasi belanja diselenggarakan.</li>
                    </ul>

                    <div class="takeaway-box">
                        <div class="takeaway-badge">
                            <i class="bi bi-shield-check"></i> Ringkasan Cepat
                        </div>
                        <p>Data Anda hanya digunakan untuk memproses pengiriman simulasi, validasi Midtrans, dan penilaian evaluasi analitik akademis.</p>
                    </div>
                </section>

                <section id="section-3" class="legal-section">
                    <span class="sub-japanese-title">03 / CONFIDENTIALITY / データの安全管理</span>
                    <h3 class="legal-section-title">3. Kerahasiaan & Keamanan Data</h3>
                    <p>Kata sandi pengguna dienkripsi secara ketat di server kami menggunakan algoritma <strong>Bcrypt</strong> bawaan Laravel. Kami menjamin seluruh data pribadi Anda (termasuk email login Google) tidak akan pernah dijual, disebarkan, atau disalahgunakan di luar kepentingan akademis proyek kuliah ini.</p>

                    <div class="takeaway-box">
                        <div class="takeaway-badge">
                            <i class="bi bi-wallet2"></i> Ringkasan Cepat
                        </div>
                        <p>Semua kata sandi dienkripsi dengan Bcrypt. Kami menjamin data pribadi Anda tidak disebarkan untuk kepentingan komersial.</p>
                    </div>
                </section>

                <section id="section-4" class="legal-section">
                    <span class="sub-japanese-title">04 / ANALYTICS / クッキーと分析</span>
                    <h3 class="legal-section-title">4. Penggunaan Cookies & Google Analytics</h3>
                    <p>Website ini menggunakan cookies untuk mengingat barang di keranjang belanja Anda dan mendeteksi sesi masuk. Kami juga memanfaatkan layanan Google Analytics 4 untuk event tracking perilaku belanja yang aman.</p>
                </section>

                <section id="section-5" class="legal-section">
                    <span class="sub-japanese-title">05 / USER RIGHTS / 利用者の権利</span>
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

        // 2. Dynamic Real-time Policy search filtering & term highlighting
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
    });
</script>
@endsection
