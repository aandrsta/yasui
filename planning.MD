# Implementation Plan — Yasui E-Commerce (Simplified)

> Laravel 10 · Monolithic MVC · One-Man Project  
> **Timeline:** 10 hari | **Stack:** Laravel 10 + MySQL + Blade + Bootstrap 5 | **Deploy:** VPS Dosen

---

## 1. Ringkasan Proyek

Website e-commerce general (multi-kategori produk) menggunakan Laravel 10 dengan arsitektur Monolithic MVC. Dikerjakan solo dalam 10 hari, dimulai dari local environment sebelum deploy ke VPS dosen.

### Keputusan Arsitektur

| Keputusan | Pilihan | Alasan |
|---|---|---|
| Arsitektur | Monolithic MVC | Solo developer, timeline 10 hari, single VPS — tidak perlu microservices |
| Framework | Laravel 10 | PHP native background, ekosistem mature, Socialite + Midtrans resmi |
| Frontend | Blade + Bootstrap 5 | SSR, tidak perlu build step React/Vue |
| Database | MySQL 8 | Relasional, tersedia di VPS dosen |
| Auth / SSO | Laravel Socialite | OAuth Gmail, package resmi Laravel |
| Payment | Midtrans Sandbox | Library PHP resmi, sandbox gratis, nilai tambah |
| Analytics | Google Analytics 4 | Gratis, realtime dashboard untuk simulasi |

---

## 2. Kriteria Wajib Dosen

| Kriteria | Implementasi |
|---|---|
| Arsitektur + alasan | Monolithic MVC Laravel — justifikasi: 1 developer, timeline pendek, single server |
| Data analytic | Google Analytics 4 + event tracking (view_item, add_to_cart, purchase) |
| Simulasi pembelian antar kelompok | 3–5 akun Gmail test melakukan pembelian sandbox |
| SSO OAuth Gmail | Laravel Socialite → `/auth/google/callback` |
| Term of Service | Static Blade page `/terms-of-service` |
| Privacy Policy | Static Blade page `/privacy-policy` |
| Payment Gateway | Midtrans Snap sandbox (nilai tambah) |
| Slide + Laporan | Dikerjakan setelah development selesai |

---

## 3. Fitur Aplikasi

### Fitur Publik
- Beranda dengan produk featured & kategori
- Katalog produk dengan filter & search
- Halaman detail produk
- Halaman Term of Service & Privacy Policy

### Fitur User (setelah login)
- Register & login email/password
- Login SSO via Google OAuth
- Cart: tambah, update, hapus item
- Checkout dengan form alamat pengiriman
- Pembayaran via Midtrans Snap (sandbox)
- Riwayat pesanan & status order

### Fitur Admin (Blade sederhana)
- Dashboard: total order, revenue
- CRUD produk
- Manage order & update status

---

## 4. Tech Stack & Package

### Stack Utama

| Layer | Teknologi |
|---|---|
| Backend | Laravel 10 |
| Frontend | Blade + Bootstrap 5.3 |
| Database | MySQL 8.0 |
| PHP | PHP 8.1+ |
| Web server (local) | Laragon |

### Package Laravel (Hanya 2 tambahan!)

| Package | Fungsi |
|---|---|
| `laravel/socialite` | OAuth Gmail (SSO) |
| `midtrans/midtrans-php` | Payment gateway Midtrans |

### Package yang DIHAPUS dari plan lama

| Package | Alasan Dihapus |
|---|---|
| `filament/filament` | Terlalu complex, pakai admin Blade manual |
| `darryldecode/laravelshoppingcart` | Pakai DB cart (model Cart biasa) |
| `intervention/image` | Pakai basic `store()` + CSS resize |

---

## 5. Struktur Database

| Tabel | Kolom Utama | Relasi |
|---|---|---|
| `users` | id, name, email, password, google_id, role | hasMany orders, carts |
| `categories` | id, name, slug, description, image | hasMany products |
| `products` | id, category_id, name, slug, price, stock, image, is_featured | belongsTo category |
| `carts` | id, user_id, product_id, quantity | belongsTo user & product |
| `orders` | id, user_id, order_number, total_price, status, payment_status, snap_token, shipping_* | hasMany order_items, hasOne payment |
| `order_items` | id, order_id, product_id, product_name, quantity, price | belongsTo order & product |
| `payments` | id, order_id, transaction_id, payment_type, amount, status | belongsTo order |

---

## 6. Struktur Folder

```
app/
  Http/Controllers/
    Auth/         → RegisterController, LoginController, GoogleController
    Shop/         → HomeController, ProductController, CartController,
                     CheckoutController, PaymentController, OrderController
    Admin/        → AdminController
  Models/         → User, Product, Category, Cart, Order, OrderItem, Payment
  Services/       → MidtransService.php

database/
  migrations/     → semua file migration
  seeders/        → CategorySeeder, ProductSeeder, UserSeeder

resources/views/
  layouts/        → app.blade.php (master layout)
  shop/           → home, products, product-detail, cart, checkout, payment
  orders/         → index, show
  auth/           → login, register
  admin/          → dashboard, products/, orders/
  pages/          → terms, privacy

routes/
  web.php         → semua route
```

---

## 7. Timeline 10 Hari

### Phase 1 — Setup + DB + Models (Hari 1)
- Install packages: `composer require laravel/socialite midtrans/midtrans-php`
- Konfigurasi `.env`, `config/services.php`, `config/midtrans.php`
- Buat semua migration & model (7 tabel)
- Buat seeders: 3 kategori, 12 produk, 4 user
- Run `php artisan migrate:fresh --seed`

### Phase 2 — Layout + Auth + OAuth (Hari 2–3)
- Master layout Blade + Bootstrap 5 (navbar, footer, flash)
- Auth: register, login, logout (manual controller)
- OAuth Google via Socialite (redirect + callback)
- Tombol "Login with Google" di halaman login

### Phase 3 — Product Catalog (Hari 4)
- Homepage: hero, featured products, category cards
- Product listing: search + filter kategori + pagination
- Product detail: info lengkap + tombol add to cart

### Phase 4 — Cart + Checkout (Hari 5–6)
- Cart DB-based: add, update qty, remove
- Cart badge di navbar
- Checkout: form shipping + order creation
- Decrement stock + clear cart

### Phase 5 — Midtrans Payment (Hari 7)
- MidtransService: generate snap_token
- Payment page dengan Snap.js popup
- Webhook: `/payment/notification` → update status

### Phase 6 — Orders + Admin (Hari 8)
- Order history user: list + detail
- Admin dashboard: statistik sederhana
- Admin CRUD produk
- Admin manage status order

### Phase 7 — Legal Pages + GA4 (Hari 9)
- Terms of Service & Privacy Policy (static Blade)
- GA4 script di master layout
- Event tracking: view_item, add_to_cart, purchase

### Phase 8 — Simulasi + Polish (Hari 10)
- Simulasi pembelian 3–5 akun Gmail
- Screenshot GA4 Realtime & Events
- Bug fixing & UI polish
- Persiapan slide & laporan

---

## 8. Rincian Biaya

> **Kesimpulan:** Seluruh development 100% gratis.

| Komponen | Tool | Biaya |
|---|---|---|
| Local dev | Laragon | Gratis |
| Framework | Laravel 10 + package | Gratis |
| OAuth SSO | Google Cloud Console | Gratis |
| Analytics | Google Analytics 4 | Gratis |
| Payment dev | Midtrans Sandbox | Gratis |
| Server | VPS Dosen | Gratis |

---

## 9. Simulasi Pembelian Antar Kelompok

### Cara Eksekusi
1. Siapkan 3–5 akun Gmail berbeda
2. Masing-masing login via OAuth Google
3. Setiap akun browsing produk berbeda, add to cart, checkout
4. Gunakan kartu test Midtrans sandbox
5. Cek GA4 → Realtime → Events
6. Screenshot GA4 untuk bahan laporan

> **Kartu test Midtrans Sandbox:**  
> No: `4811 1111 1111 1114` | CVV: `123` | Expiry: `01/25` | OTP: `112233`

### Yang Harus Ada di Laporan (dari GA4)
- Screenshot GA4 Realtime: active user saat simulasi
- Screenshot GA4 Events: view_item, add_to_cart, purchase
- Tabel rekap: jumlah transaksi, total nilai order, jumlah user berbeda

---

## 10. Checklist Akhir

### Fitur Wajib
- [ ] Arsitektur Monolithic MVC + alasan tertulis
- [ ] Login OAuth Google works
- [ ] GA4 tracking terpasang + event fire
- [ ] Simulasi minimal 3 akun berbeda
- [ ] Terms of Service accessible
- [ ] Privacy Policy accessible
- [ ] Midtrans Snap popup + pembayaran sandbox

### Fitur E-Commerce
- [ ] Login email/password
- [ ] Katalog produk + search + filter
- [ ] Cart: add, update, remove
- [ ] Checkout flow sampai order dibuat
- [ ] Payment Midtrans
- [ ] Riwayat order user
- [ ] Admin CRUD produk + manage order

### Output
- [ ] Laporan selesai
- [ ] Slide presentasi
- [ ] Demo live berjalan

---

*Simplified plan — 8 phase, 10 hari, 2 package tambahan.*
