<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title')@yield('title') - Yassui@elseYassui@endif</title>
    
    <!-- Google Fonts: Cormorant Garamond & Instrument Sans & Space Grotesk & Zen Old Mincho -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=Instrument+Sans:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&family=Zen+Old+Mincho:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet" integrity="sha384-c9MVH4yRDZMY+bSlECVISp9U4xBl1dKb5z4x8IgF6lBKTHsh1AtxHBfHiiA+S/Nr" crossorigin="anonymous">

    
    <style>
        :root {
            --primary-color: #1e1e1d; /* Obsidian */
            --primary-hover: #2f2f2e;
            --accent-color: #a2384a; /* Sakura Rose / Deep Wine */
            --accent-hover: #852b3a;
            --text-main: #2b2a27; /* Charcoal */
            --text-muted: #75726a; /* Warm Gray */
            --border-color: #e7e4dc; /* Soft Hairline */
            --bg-main: #fbfaf7; /* Warm Paper */
            --bg-subtle: #f6f4ee; /* Subtle Cream */
            --transition-base: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            color: var(--text-main);
            background-color: var(--bg-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.01em;
            line-height: 1.6;
        }

        /* Minimalist Navbar */
        .navbar {
            background-color: var(--bg-main);
            border-bottom: 1px solid var(--border-color);
            padding-top: 1rem;
            padding-bottom: 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--primary-color) !important;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-muted) !important;
            font-size: 0.9rem;
            transition: var(--transition-base);
            padding: 6px 12px !important;
            letter-spacing: -0.01em;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent-color) !important;
        }

        /* Minimalist Buttons - Stark Flat & Sharp */
        .btn-minimal-primary {
            background-color: var(--primary-color);
            color: var(--bg-main);
            border: 1px solid var(--primary-color);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-radius: 3px;
            padding: 10px 22px;
            transition: var(--transition-base);
        }

        .btn-minimal-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: var(--bg-main);
        }

        .btn-minimal-accent {
            background-color: var(--accent-color);
            color: #ffffff;
            border: 1px solid var(--accent-color);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-radius: 3px;
            padding: 10px 22px;
            transition: var(--transition-base);
        }

        .btn-minimal-accent:hover {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
            color: #ffffff;
        }

        .btn-minimal-secondary {
            background-color: transparent;
            color: var(--text-main);
            border: 1px solid var(--border-color);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-radius: 3px;
            padding: 10px 22px;
            transition: var(--transition-base);
        }

        .btn-minimal-secondary:hover {
            background-color: var(--bg-subtle);
            border-color: var(--text-main);
            color: var(--text-main);
        }


        /* Search input - Flat, Crisp */
        .search-input {
            border: 1px solid var(--border-color);
            border-radius: 3px;
            font-size: 0.85rem;
            padding: 8px 12px;
            background-color: var(--bg-subtle);
            transition: var(--transition-base);
            width: 220px;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            background-color: var(--bg-main);
            box-shadow: none;
            outline: none;
        }

        /* Dropdown styling - Flat, Zero Heavy Shadows */
        .dropdown-menu {
            border-radius: 4px;
            border: 1px solid var(--border-color);
            background-color: var(--bg-main);
            box-shadow: 0 4px 20px -5px rgba(30, 30, 29, 0.08);
            font-size: 0.85rem;
            padding: 4px 0;
        }

        .dropdown-item {
            padding: 8px 16px;
            color: var(--text-main);
            transition: var(--transition-base);
        }

        .dropdown-item:hover {
            background-color: var(--bg-subtle);
            color: var(--accent-color);
        }

        /* Footer styling - Editorial Stark */
        footer {
            margin-top: auto;
            background-color: var(--bg-subtle);
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        footer a {
            color: var(--text-muted);
            text-decoration: none;
            transition: var(--transition-base);
        }

        footer a:hover {
            color: var(--primary-color);
        }

        /* Custom Scrollbar - Editorial Minimal */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-main);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }

        /* Unified Custom Pagination Style */
        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            font-size: 0.875rem;
        }

        .page-link:hover {
            color: var(--primary-color);
            background-color: var(--bg-subtle);
            border-color: var(--border-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #ffffff;
        }

        .page-item.disabled .page-link {
            color: #cbd5e1;
            background-color: #ffffff;
            border-color: var(--border-color);
        }

        /* Unified Admin Navigation Styling */
        .admin-nav .nav-link {
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem 1.25rem;
            border-bottom: 2px solid transparent;
            color: var(--text-muted);
        }
        .admin-nav .nav-link.active {
            color: var(--accent-color);
            border-bottom-color: var(--accent-color);
            background: transparent;
        }

        /* Unified Status Badges & Payment Badges */
        .badge-status, .badge-payment {
            font-size: 0.725rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 5px 10px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            line-height: 1;
        }
        .badge-status.pending { background-color: #fef3c7; color: #d97706; }
        .badge-status.processing { background-color: #dbeafe; color: #2563eb; }
        .badge-status.shipped { background-color: #fae8ff; color: #c026d3; }
        .badge-status.completed { background-color: #dcfce7; color: #16a34a; }
        .badge-status.cancelled { background-color: #fee2e2; color: #dc2626; }

        .badge-payment.unpaid { background-color: #fee2e2; color: #dc2626; }
        .badge-payment.paid { background-color: #dcfce7; color: #16a34a; }
        .badge-payment.failed { background-color: #f3f4f6; color: #4b5563; }

        /* Elegant Global Animations & Transitions */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Improved Navigation Link Styles */
        .navbar-nav .nav-link {
            position: relative;
            padding: 6px 0 !important;
            margin: 0 12px;
        }
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: var(--accent-color);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .navbar-nav .nav-link:hover::after, 
        .navbar-nav .nav-link.active::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* Stark flat buttons micro-interactions */
        .btn-minimal-primary, .btn-minimal-accent, .btn-minimal-secondary {
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 3px !important; /* Standard sharp */
        }
        .btn-minimal-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 30, 29, 0.08);
        }
        .btn-minimal-primary:active {
            transform: translateY(0);
        }
        .btn-minimal-accent:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(162, 56, 74, 0.15);
        }
        .btn-minimal-accent:active {
            transform: translateY(0);
        }
        .btn-minimal-secondary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(117, 114, 106, 0.06);
        }
        .btn-minimal-secondary:active {
            transform: translateY(0);
        }

        /* Responsive and animated search input */
        .search-input {
            width: 180px;
            transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s, background-color 0.3s;
        }
        .search-input:focus {
            width: 240px;
            border-color: var(--accent-color) !important;
        }

        /* Staggered load animation helper */
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
    </style>
    @yield('styles')
    
    <!-- Google Analytics 4 -->
    @if(config('services.google.analytics_id') && config('services.google.analytics_id') !== 'G-placeholder')
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.analytics_id') }}" nonce="{{ app('csp-nonce') }}"></script>
        <script nonce="{{ app('csp-nonce') }}">
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '{{ config('services.google.analytics_id') }}');
        </script>

    @endif
</head>
<body>

    <!-- Minimalist Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <i class="bi bi-shop" style="color: var(--accent-color);"></i>
                <span>YAS<span style="color: var(--accent-color);">SUI</span></span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active text-dark fw-semibold' : '' }}" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('products*') ? 'active text-dark fw-semibold' : '' }}" href="{{ url('/products') }}">Produk</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Search Bar Minimal -->
                    @if(!auth()->check() || !auth()->user()->isAdmin())
                    <form action="{{ url('/products') }}" method="GET" class="d-none d-md-flex">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control search-input" placeholder="Cari produk...">
                    </form>
                    @endif
                    
                    @auth
                        @if(!auth()->user()->isAdmin())
                            <!-- Shopping Cart Link -->
                            <a href="{{ url('/cart') }}" class="text-secondary position-relative p-2 hover-opacity" style="transition: var(--transition-base);">
                                <i class="bi bi-bag fs-5 text-dark"></i>
                                @php
                                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                                @endphp
                                @if($cartCount > 0)
                                    <span class="position-absolute top-1 start-75 translate-middle badge rounded-circle" style="font-size: 0.65rem; padding: 3px 6px; background-color: var(--accent-color) !important; color: white;">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        @endif
                        
                        <!-- User Dropdown Menu -->
                        <div class="dropdown">
                            <button class="btn btn-link nav-link dropdown-toggle text-dark fw-semibold border-0 bg-transparent px-2 d-flex align-items-center gap-2" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>{{ explode(' ', auth()->user()->name)[0] }}</span>
                            </button>
                            
                            <ul class="dropdown-menu dropdown-menu-end border-1 mt-2 py-1" aria-labelledby="userMenu">
                                <li class="px-3 py-2 border-bottom text-muted" style="font-size: 0.8rem;">
                                    <span class="d-block fw-bold text-dark">{{ auth()->user()->name }}</span>
                                    <span class="d-block text-truncate" style="max-width: 150px;">{{ auth()->user()->email }}</span>
                                </li>
                                
                                @if(auth()->user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item fw-semibold text-danger" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Kelola Toko (Admin)
                                        </a>
                                    </li>
                                @endif
                                
                                @if(!auth()->user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('orders.index') }}">
                                            <i class="bi bi-receipt me-2"></i>Riwayat Pesanan
                                        </a>
                                    </li>
                                @endif
                                
                                <li><hr class="dropdown-divider my-1"></li>
                                
                                <li>
                                    <form action="{{ url('/logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center w-100 border-0 bg-transparent">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ url('/login') }}" class="btn-minimal-secondary text-decoration-none">Masuk</a>
                        <a href="{{ url('/register') }}" class="btn-minimal-accent text-decoration-none ms-1">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="py-5">
        <div class="container">
            <!-- Flash Message -->
            <x-flash-message />
            
            @yield('content')
        </div>
    </main>

    <!-- Minimalist Footer -->
    <footer class="py-5 mt-auto" style="background-color: var(--bg-subtle); border-top: 1px solid var(--border-color); position: relative;">
        <!-- Delicate poetry stamp on footer background (right aligned, subtle watermarked) -->
        <div class="d-none d-lg-block" style="position: absolute; right: 4rem; top: 3rem; font-family: 'Zen Old Mincho', serif; font-size: 4rem; color: rgba(162, 56, 74, 0.02); writing-mode: vertical-rl; pointer-events: none; user-select: none;">
            美意識を蒐集する
        </div>

        <div class="container">
            <div class="row g-5 justify-content-between">
                <div class="col-lg-5 animate-fade-in">
                    <!-- Premium Serif Title identical to header -->
                    <a class="navbar-brand d-flex align-items-center gap-2 mb-3 text-decoration-none" href="{{ url('/') }}" style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 1.8rem; letter-spacing: 0.05em; color: var(--primary-color) !important;">
                        <i class="bi bi-shop" style="color: var(--accent-color);"></i>
                        <span>YAS<span style="color: var(--accent-color);">SUI</span></span>
                    </a>
                    
                    <p class="small text-muted mb-4" style="line-height: 1.8; font-size: 0.85rem; max-width: 440px;">
                        美意識を蒐集する — Menghadirkan kurasi merchandise pop-kultur Jepang berstandar museum langsung ke genggaman Anda. Kami percaya pada keindahan detail yang abadi, keaslian rancangan, dan apresiasi karya yang mendalam.
                    </p>

                    <!-- Small Authentic Red Seal Stamp -->
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 border border-1" style="border-radius: 3px; background-color: var(--bg-main); border-color: rgba(162, 56, 74, 0.2) !important;">
                        <span class="d-block bg-danger rounded-circle animate-pulse" style="width: 8px; height: 8px; background-color: var(--accent-color) !important;"></span>
                        <span class="small font-mincho fw-bold text-uppercase" style="font-size: 0.65rem; color: var(--accent-color); letter-spacing: 0.1em;">Yasui Curated Guild</span>
                    </div>
                </div>
                
                <div class="col-md-3 col-lg-2 animate-fade-in stagger-1">
                    <h6 class="text-dark fw-bold mb-3 small" style="font-family: 'Zen Old Mincho', serif; letter-spacing: 0.1em; font-size: 0.85rem; text-transform: uppercase; line-height: 1.4;">
                        ナビゲーション<br>
                        <span style="font-family: 'Instrument Sans', sans-serif; font-size: 0.7rem; font-weight: 500; letter-spacing: 0.05em; color: var(--text-muted); display: block; margin-top: 2px;">Navigasi</span>
                    </h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="{{ url('/') }}" class="text-muted text-decoration-none hover-opacity" style="transition: var(--transition-base);">Beranda</a></li>
                        <li><a href="{{ url('/products') }}" class="text-muted text-decoration-none hover-opacity" style="transition: var(--transition-base);">Katalog Produk</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 col-lg-2 animate-fade-in stagger-2">
                    <h6 class="text-dark fw-bold mb-3 small" style="font-family: 'Zen Old Mincho', serif; letter-spacing: 0.1em; font-size: 0.85rem; text-transform: uppercase; line-height: 1.4;">
                        ポリシー<br>
                        <span style="font-family: 'Instrument Sans', sans-serif; font-size: 0.7rem; font-weight: 500; letter-spacing: 0.05em; color: var(--text-muted); display: block; margin-top: 2px;">Kebijakan</span>
                    </h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="{{ url('/terms-of-service') }}" class="text-muted text-decoration-none hover-opacity" style="transition: var(--transition-base);">Terms of Service</a></li>
                        <li><a href="{{ url('/privacy-policy') }}" class="text-muted text-decoration-none hover-opacity" style="transition: var(--transition-base);">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4" style="border-color: var(--border-color); opacity: 0.6;">
            
            <div class="row align-items-center justify-content-between g-3">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small mb-0 text-muted" style="font-size: 0.775rem;">
                        &copy; {{ date('Y') }} Yassui. Hak cipta dilindungi. — 創立二〇二六
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    
    @yield('scripts')
</body>
</html>
