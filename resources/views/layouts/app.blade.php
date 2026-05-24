<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Yasui') — E-Commerce</title>
    
    <!-- Google Fonts: Inter (Sangat bersih & profesional, standar industri) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0f172a; /* Slate 900 - Obsidian */
            --primary-hover: #1e293b; /* Slate 800 */
            --accent-color: #f43f5e; /* Sakura Rose - Elegant & Sophisticated, not norak */
            --accent-hover: #e11d48; /* Deep Sakura Rose */
            --text-main: #1e293b; /* Slate 800 */
            --text-muted: #64748b; /* Slate 500 */
            --border-color: #e2e8f0; /* Slate 200 */
            --bg-main: #ffffff;
            --bg-subtle: #f8fafc; /* Slate 50 */
            --transition-base: all 0.2s ease-in-out;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            background-color: var(--bg-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.011em;
        }

        /* Minimalist Navbar */
        .navbar {
            background-color: var(--bg-main);
            border-bottom: 1px solid var(--border-color);
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-color) !important;
            letter-spacing: -0.03em;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-muted) !important;
            font-size: 0.925rem;
            transition: var(--transition-base);
            padding: 6px 12px !important;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent-color) !important;
        }

        /* Minimalist Buttons */
        .btn-minimal-primary {
            background-color: var(--primary-color);
            color: #ffffff;
            border: 1px solid var(--primary-color);
            font-weight: 500;
            font-size: 0.9rem;
            border-radius: 6px;
            padding: 8px 18px;
            transition: var(--transition-base);
        }

        .btn-minimal-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #ffffff;
        }

        .btn-minimal-accent {
            background-color: var(--accent-color);
            color: #ffffff;
            border: 1px solid var(--accent-color);
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 6px;
            padding: 8px 18px;
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
            font-weight: 500;
            font-size: 0.9rem;
            border-radius: 6px;
            padding: 8px 18px;
            transition: var(--transition-base);
        }

        .btn-minimal-secondary:hover {
            background-color: var(--bg-subtle);
            border-color: #cbd5e1;
            color: var(--text-main);
        }


        /* Search input */
        .search-input {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.875rem;
            padding: 8px 12px;
            background-color: var(--bg-subtle);
            transition: var(--transition-base);
            width: 220px;
        }

        .search-input:focus {
            border-color: #94a3b8;
            background-color: #ffffff;
            box-shadow: none;
            outline: none;
        }

        /* Dropdown styling */
        .dropdown-menu {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            font-size: 0.875rem;
        }

        .dropdown-item {
            padding: 8px 16px;
            color: var(--text-main);
            transition: var(--transition-base);
        }

        .dropdown-item:hover {
            background-color: var(--bg-subtle);
            color: var(--primary-color);
        }

        /* Footer styling */
        footer {
            margin-top: auto;
            background-color: var(--bg-subtle);
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        footer a {
            color: var(--text-muted);
            text-decoration: none;
            transition: var(--transition-base);
        }

        footer a:hover {
            color: var(--primary-color);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #ffffff;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @yield('styles')
    
    <!-- Google Analytics 4 -->
    @if(config('services.google.analytics_id') && config('services.google.analytics_id') !== 'G-placeholder')
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.analytics_id') }}"></script>
        <script>
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
                <span>YASUI</span>
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
                        <a class="nav-link {{ Request::is('products') ? 'active text-dark fw-semibold' : '' }}" href="{{ url('/products') }}">Produk</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Search Bar Minimal -->
                    <form action="{{ url('/products') }}" method="GET" class="d-none d-md-flex">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control search-input" placeholder="Cari produk...">
                    </form>
                    
                    @auth
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
                                
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        <i class="bi bi-receipt me-2"></i>Riwayat Pesanan
                                    </a>
                                </li>
                                
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
    <footer class="py-5 mt-auto">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <div class="col-lg-5">
                    <h6 class="text-dark fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-shop"></i> YASUI
                    </h6>
                    <p class="small text-muted mb-0" style="line-height: 1.6;">Platform belanja e-commerce minimalis yang dirancang untuk kesederhanaan, kecepatan, dan kenyamanan bertransaksi.</p>
                </div>
                
                <div class="col-md-3">
                    <h6 class="text-dark fw-semibold mb-3 small uppercase">Tautan</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="{{ url('/products') }}">Produk</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3">
                    <h6 class="text-dark fw-semibold mb-3 small uppercase">Kebijakan</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="{{ url('/terms-of-service') }}">Terms of Service</a></li>
                        <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4" style="border-color: var(--border-color);">
            
            <div class="row align-items-center justify-content-between g-2">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small mb-0 text-muted">&copy; {{ date('Y') }} Yasui. Hak cipta dilindungi.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small text-muted mb-0">Project Akhir E-Commerce Monolithic MVC.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
