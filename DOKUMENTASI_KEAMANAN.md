# Dokumentasi Implementasi Keamanan Web (Standar Mozilla HTTP Observatory)

Dokumen ini menjelaskan langkah-langkah implementasi keamanan yang telah diterapkan pada aplikasi **Yasui E-Commerce** untuk memenuhi standar audit **Mozilla HTTP Observatory**.

Setiap aspek keamanan di bawah ini diatur secara dinamis melalui sistem Laravel sehingga aman digunakan baik di lingkungan pengembangan lokal (*local development*) maupun lingkungan produksi (*production*).

---

## 1. Content Security Policy (CSP)
* **Kategori Observatory**: `Content Security Policy (CSP)` (-25 Failed)
* **Masalah Awal**: Header CSP tidak diterapkan pada web, membuat aplikasi rentan terhadap serangan Cross-Site Scripting (XSS).
* **Solusi & Cara Implementasi**:
  Kami menerapkan **CSP berbasis Nonce** secara dinamis melalui middleware. Setiap kali halaman web dimuat, sistem akan membuat string acak unik (*nonce*) sekali pakai. Hanya script yang memiliki atribut `nonce` yang cocok dengan header CSP yang diizinkan untuk berjalan oleh browser.
  Untuk menghindari error pada halaman administrasi (Filament/Livewire) yang seringkali menyuntikkan script dinamis secara otomatis, kami memisahkan kebijakannya:
  * **Halaman Publik**: Menggunakan kebijakan ketat berbasis *nonce* pada tag `<script>`.
  * **Halaman Admin & Livewire (`/admin*`, `/livewire*`)**: Menggunakan kebijakan yang lebih fleksibel (`'unsafe-inline'` dan `'unsafe-eval'`) agar fitur dashboard tidak terganggu.
* **Berkas yang Terkait**:
  * [SecurityHeadersMiddleware.php](file:///c:/laragon/www/yasui/app/Http/Middleware/SecurityHeadersMiddleware.php) (Penyusunan header CSP & pemisahan route)
  * [AppServiceProvider.php](file:///c:/laragon/www/yasui/app/Providers/AppServiceProvider.php) (Inisialisasi *nonce* di container aplikasi)
  * [app.blade.php](file:///c:/laragon/www/yasui/resources/views/layouts/app.blade.php) (Menyisipkan atribut `nonce` ke script Google Analytics)
  * [show.blade.php](file:///c:/laragon/www/yasui/resources/views/orders/show.blade.php) (Menyisipkan atribut `nonce` pada script Midtrans & Countdown)
  * [product-detail.blade.php](file:///c:/laragon/www/yasui/resources/views/shop/product-detail.blade.php) (Menyisipkan atribut `nonce` pada script event tracking GA4)

---

## 2. Cookies (Secure Flag)
* **Kategori Observatory**: `Cookies` (-40 Failed)
* **Masalah Awal**: Cookie sesi (session cookie) diatur tanpa flag `Secure` atau berjalan di atas koneksi HTTP biasa, sehingga berisiko disadap (Session Hijacking).
* **Solusi & Cara Implementasi**:
  Kami memetakan flag `secure` milik session cookie Laravel ke variabel lingkungan `.env` (`SESSION_SECURE_COOKIE`).
  * **Di Lokal**: `SESSION_SECURE_COOKIE` diset ke `false` agar sesi login tidak rusak di localhost (HTTP).
  * **Di Produksi**: `SESSION_SECURE_COOKIE` wajib diset ke `true`. Dengan begitu, browser hanya akan mengirimkan cookie sesi jika koneksi terenkripsi menggunakan HTTPS.
* **Berkas yang Terkait**:
  * [config/session.php](file:///c:/laragon/www/yasui/config/session.php) (Mengaitkan `'secure' => env('SESSION_SECURE_COOKIE')`)
  * [.env](file:///c:/laragon/www/yasui/.env) dan [.env.example](file:///c:/laragon/www/yasui/.env.example)

---

## 3. Redirection (HTTP -> HTTPS)
* **Kategori Observatory**: `Redirection` (-20 Failed)
* **Masalah Awal**: Web tidak otomatis mengalihkan (redirect) pengguna dari alamat HTTP (tidak aman) ke HTTPS (aman) secara berurutan.
* **Solusi & Cara Implementasi**:
  Kami menambahkan logika deteksi protokol di awal request pada middleware. Jika web diakses menggunakan HTTP dan variabel `ENFORCE_HTTPS` di `.env` bernilai `true`, sistem akan mengalihkan pengguna ke alamat HTTPS yang setara menggunakan redirect 301.
* **Berkas yang Terkait**:
  * [SecurityHeadersMiddleware.php](file:///c:/laragon/www/yasui/app/Http/Middleware/SecurityHeadersMiddleware.php) (Logika pengalihan otomatis)
  * [.env](file:///c:/laragon/www/yasui/.env) (Parameter `ENFORCE_HTTPS`)

---

## 4. Referrer Policy
* **Kategori Observatory**: `Referrer Policy` (Failed)
* **Masalah Awal**: Header `Referrer-Policy` tidak diimplementasikan, sehingga informasi URL halaman asal (referrer) bisa bocor saat berpindah ke luar situs.
* **Solusi & Cara Implementasi**:
  Kami menambahkan header respons HTTP `Referrer-Policy: strict-origin-when-cross-origin` pada seluruh respons aplikasi web melalui middleware. Aturan ini memastikan browser hanya mengirimkan domain asal saja (bukan path URL penuh) ke situs luar, dan hanya jika perpindahan terjadi dari HTTPS ke HTTPS.
* **Berkas yang Terkait**:
  * [SecurityHeadersMiddleware.php](file:///c:/laragon/www/yasui/app/Http/Middleware/SecurityHeadersMiddleware.php)

---

## 5. Strict Transport Security (HSTS)
* **Kategori Observatory**: `Strict Transport Security (HSTS)` (-20 Failed)
* **Masalah Awal**: Header HSTS tidak diimplementasikan, sehingga browser tidak dipaksa untuk selalu mengakses web lewat HTTPS pada kunjungan berikutnya.
* **Solusi & Cara Implementasi**:
  Melalui middleware, jika koneksi yang masuk terdeteksi aman (HTTPS) atau lingkungan aplikasi adalah produksi (`production`), aplikasi akan mengirimkan header:
  `Strict-Transport-Security: max-age=31536000; includeSubDomains; preload`
  Header ini memerintahkan browser untuk memblokir akses HTTP biasa ke domain utama maupun sub-domain selama 1 tahun ke depan dan meregistrasikan domain untuk daftar preload HTTPS.
* **Berkas yang Terkait**:
  * [SecurityHeadersMiddleware.php](file:///c:/laragon/www/yasui/app/Http/Middleware/SecurityHeadersMiddleware.php)
  * [.env](file:///c:/laragon/www/yasui/.env) (Variabel `HSTS_MAX_AGE` untuk durasi HSTS)

---

## 6. Subresource Integrity (SRI)
* **Kategori Observatory**: `Subresource Integrity` (-5 Failed)
* **Masalah Awal**: Berkas script/CSS dari CDN eksternal (Bootstrap) dimuat tanpa menggunakan verifikasi hash integritas (SRI). Jika CDN disusupi hacker, script jahat bisa berjalan di web kita.
* **Solusi & Cara Implementasi**:
  Kami mengunduh dan menghitung nilai hash SHA-384 resmi dari berkas Bootstrap CSS, Bootstrap Icons CSS, dan Bootstrap JS Bundle yang digunakan, kemudian menyematkannya ke atribut `integrity` dan menambahkan atribut `crossorigin="anonymous"` pada berkas layout utama:
  * **Bootstrap 5.3.2 CSS**: `sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN`
  * **Bootstrap Icons 1.11.2**: `sha384-c9MVH4yRDZMY+bSlECVISp9U4xBl1dKb5z4x8IgF6lBKTHsh1AtxHBfHiiA+S/Nr`
  * **Bootstrap 5 Bundle JS**: `sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL`
* **Berkas yang Terkait**:
  * [app.blade.php](file:///c:/laragon/www/yasui/resources/views/layouts/app.blade.php) (Pembaruan tag link & script eksternal)

---

## 7. Perlindungan Tambahan (CORP, XFO, nosniff)
Kami juga menambahkan header pelindung standar lainnya untuk meminimalkan risiko eksploitasi browser modern:
* **Cross-Origin-Resource-Policy (CORP)** set ke `same-origin`: Melindungi data gambar dan media dari pembacaan cross-origin yang tidak sah.
* **X-Content-Type-Options** set ke `nosniff`: Memaksa browser menghormati tipe MIME yang dideklarasikan oleh server (mencegah eksploitasi tipe file).
* **X-Frame-Options** set ke `SAMEORIGIN`: Melindungi aplikasi web dari serangan Clickjacking (mencegah web dibungkus di dalam frame/iframe situs lain).

---

## ⚙️ Cara Mengaktifkan di Server Produksi
Ketika Anda mendeploy perubahan ini ke server hosting/VPS produksi:
1. Buka file `.env` di server produksi Anda.
2. Tambahkan atau sesuaikan variabel berikut:
   ```env
   # Mengaktifkan pengalihan otomatis HTTP ke HTTPS
   ENFORCE_HTTPS=true

   # Mengaktifkan keamanan tingkat cookie sesi (hanya lewat HTTPS)
   SESSION_SECURE_COOKIE=true

   # Durasi HSTS dalam detik (31536000 detik = 1 tahun)
   HSTS_MAX_AGE=31536000
   ```
3. Bersihkan cache konfigurasi agar Laravel membaca nilai baru:
   ```bash
   php artisan config:cache
   ```
