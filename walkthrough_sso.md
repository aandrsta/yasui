# Panduan Lengkap Setup Google SSO (OAuth & Socialite) - Yasui E-Commerce

Panduan ini berisi langkah-demi-langkah mendetail untuk melakukan konfigurasi **Google Single Sign-On (SSO)** menggunakan **Laravel Socialite**, baik untuk lingkungan lokal (Laragon/Windows) maupun saat sudah di-deploy ke produksi (VPS Dosen/Cloudflare).

---

## 🛠️ BAGIAN 1: Konfigurasi di Google Cloud Console

Anda harus mendaftarkan aplikasi Anda di Google Cloud Console untuk mendapatkan **Client ID** dan **Client Secret**.

### Langkah 1: Buat Project Baru
1. Buka [Google Cloud Console](https://console.cloud.google.com/).
2. Login menggunakan akun Google Anda.
3. Di pojok kiri atas (sebelah logo Google Cloud), klik menu dropdown project, lalu klik **"New Project"**.
4. Beri nama project (misal: `Yasui E-Commerce`), lalu klik **"Create"**. Setelah selesai, pastikan Anda sedang aktif di project tersebut.

### Langkah 2: Setup OAuth Consent Screen (Layar Persetujuan)
Sebelum membuat API Credentials, Anda harus menentukan bagaimana layar persetujuan OAuth akan ditampilkan ke user.

1. Di menu sidebar kiri, cari dan klik **APIs & Services** > **OAuth consent screen**.
2. Pilih User Type: **External** (agar semua akun Gmail bisa masuk), lalu klik **"Create"**.
3. Isi informasi aplikasi wajib:
   - **App name:** `Yasui E-Commerce`
   - **User support email:** (Pilih email Gmail Anda)
   - **Developer contact information:** (Masukkan email Anda)
   - Klik **"Save and Continue"**.
4. **Scopes (Cakupan):**
   - Klik **"Add or Remove Scopes"**.
   - Centang opsi `.../auth/userinfo.email`, `.../auth/userinfo.profile`, dan `openid`.
   - Klik **"Update"** di bagian bawah, lalu klik **"Save and Continue"**.
5. **Test Users (PENTING untuk status Testing):**
   - Karena aplikasi masih dalam status *Testing/Sandbox*, hanya email yang didaftarkan di sini yang bisa login.
   - Klik **"Add Users"**, lalu masukkan email Gmail Anda sendiri, serta **3–5 email akun Gmail milik teman kelompok Anda** (untuk simulasi pembelian antar kelompok).
   - Klik **"Save and Continue"**, lalu tinjau ringkasannya dan klik **"Back to Dashboard"**.

### Langkah 3: Buat OAuth Client ID (Credentials)
1. Di sidebar kiri, klik **APIs & Services** > **Credentials**.
2. Di bagian atas, klik **"+ Create Credentials"** > **OAuth client ID**.
3. Pilih Application Type: **Web application**.
4. Beri nama: `Yasui Web App`.
5. Konfigurasikan **URIs** (sangat krusial untuk mencocokkan environment Anda):

   #### A. Authorized JavaScript origins (Origin URL Aplikasi)
   *Masukkan URL dasar tanpa path callback:*
   - **Lokal (Laragon/Localhost):**
     - `http://localhost:8000`
     - `http://127.0.0.1:8000` (atau `http://yasui.test` jika menggunakan custom domain Laragon)
   - **Produksi (VPS Dosen - Fase 1):**
     - `http://43.157.229.161:8001`
   - **Produksi (Domain Cloudflare - Fase 2):**
     - `https://yassui.my.id`

   #### B. Authorized redirect URIs (URL Callback Aplikasi)
   *Masukkan URL tujuan setelah user sukses login via Google. Harus sama persis dengan route di Laravel:*
   - **Lokal (Laragon/Localhost):**
     - `http://localhost:8000/auth/google/callback`
     - `http://127.0.0.1:8000/auth/google/callback` (atau `http://yasui.test/auth/google/callback`)
   - **Produksi (VPS Dosen - Fase 1):**
     - `http://43.157.229.161:8001/auth/google/callback`
   - **Produksi (Domain Cloudflare - Fase 2):**
     - `https://yassui.my.id/auth/google/callback`

6. Klik **"Create"**.
7. Pop-up baru akan muncul menampilkan **Your Client ID** dan **Your Client Secret**. Salin kedua string ini ke notepad atau langsung ke file `.env` Anda!

---

## 💻 BAGIAN 2: Integrasi Kode di Laravel 10

Berikut adalah ringkasan kode yang telah kita pasang di codebase Yasui agar sistem SSO ini berjalan otomatis.

### Langkah 1: Pengaturan Dependensi (`composer.json`)
Socialite diinstal menggunakan composer:
```bash
composer require laravel/socialite
```

### Langkah 2: Konfigurasi Services (`config/services.php`)
Di file [config/services.php](file:///c:/laragon/www/yasui/config/services.php), kita menambahkan adapter `google` agar membaca data dari berkas `.env`:
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

### Langkah 3: Database & Model (`users` Table)
Kita menambahkan kolom `google_id` dan membiarkan `password` nullable di migration tabel `users`, lalu mengungkapkannya di fillable model [User.php](file:///c:/laragon/www/yasui/app/Models/User.php):
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'google_id',
    'role',
];
```

### Langkah 4: Rute Web (`routes/web.php`)
Rute masuk dan rute callback didefinisikan sebagai berikut:
```php
use App\Http\Controllers\Auth\GoogleController;

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
```

### Langkah 5: Google Controller (`app/Http/Controllers/Auth/GoogleController.php`)
Logika utama login SSO berada di [GoogleController.php](file:///c:/laragon/www/yasui/app/Http/Controllers/Auth/GoogleController.php).
*   **`redirect()`**: Mengarahkan user ke halaman login Google OAuth.
*   **`callback()`**: Menerima data user dari Google.
    - Jika user dengan `google_id` tersebut sudah ada, langsung login.
    - Jika email sudah ada di database tapi `google_id` kosong, tautkan `google_id` ke user tersebut secara otomatis untuk menghindari duplikasi akun.
    - Jika benar-benar baru, daftarkan akun baru dengan password acak yang aman.
    - *Spesial:* Jika akun yang login adalah **Admin**, skrip akan membersihkan sisa keranjang dan data transaksi lama demi kenyamanan pengujian berulang.

---

## 🔌 BAGIAN 3: Konfigurasi Environment (`.env`)

Konfigurasikan kunci Google OAuth di file `.env` masing-masing environment.

### 🏠 1. Pengaturan Lokal (Laragon / PC Anda)
Buka file `.env` lokal Anda, lalu masukkan Client ID dan Client Secret yang didapatkan dari Google Cloud Console:
```env
# Google OAuth Lokal
GOOGLE_CLIENT_ID=GANTI_DENGAN_CLIENT_ID_ANDA.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GANTI_DENGAN_CLIENT_SECRET_ANDA
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```
*Catatan: Pastikan port di `GOOGLE_REDIRECT_URI` sama dengan port server lokal Anda (`php artisan serve` biasanya port `8000`, atau sesuaikan dengan konfigurasi Laragon).*

### ☁️ 2. Pengaturan Produksi VPS (Langkah 5 di Walkthrough Setup)
Saat melakukan SSH ke VPS, pada file `.env` di server `/home/yasui/yasui/.env`, masukkan:

#### Jika menggunakan IP VPS (Fase 1):
```env
GOOGLE_CLIENT_ID=GANTI_DENGAN_CLIENT_ID_ANDA.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GANTI_DENGAN_CLIENT_SECRET_ANDA
GOOGLE_REDIRECT_URI=http://43.157.229.161:8001/auth/google/callback
```

#### Jika menggunakan Domain Cloudflare (Fase 2):
```env
GOOGLE_CLIENT_ID=GANTI_DENGAN_CLIENT_ID_ANDA.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GANTI_DENGAN_CLIENT_SECRET_ANDA
GOOGLE_REDIRECT_URI=https://yassui.my.id/auth/google/callback
```

> [!IMPORTANT]
> Jangan lupa jalankan perintah **`php artisan config:cache`** setiap kali Anda mengubah isi file `.env` di VPS agar konfigurasi baru terbaca oleh Laravel!

---

## 🔒 BAGIAN 4: Mengatasi Isu cURL Error 60 di Windows (Laragon)

Di Windows/Laragon, Anda sering kali akan menghadapi error:
`cURL error 60: SSL certificate problem: unable to get local issuer certificate`
saat Socialite mencoba berkomunikasi dengan server Google.

Kita telah menerapkan solusi cerdas di [AppServiceProvider.php](file:///c:/laragon/www/yasui/app/Providers/AppServiceProvider.php#L31-L40):
```php
// Bypass SSL verification untuk Socialite Google di env local (mengatasi cURL error 60 di Windows/Laragon)
if (config('app.env') === 'local') {
    $socialite = $this->app->make(\Laravel\Socialite\Contracts\Factory::class);
    $socialite->extend('google', function ($app) use ($socialite) {
        $config = $app['config']['services.google'];
        return $socialite->buildProvider(\Laravel\Socialite\Two\GoogleProvider::class, $config)
            ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
    });
}
```
**Mengapa ini aman?**
1. Skrip ini hanya aktif jika `APP_ENV=local`.
2. Di lingkungan `production` (VPS), verifikasi SSL tetap berjalan penuh secara standar demi keamanan tingkat tinggi.
3. Anda tidak perlu repot mendownload file `cacert.pem` atau memodifikasi file `php.ini` di Laragon lokal Anda. Sistem langsung berfungsi begitu saja (*out-of-the-box*)!

---

## 🧪 BAGIAN 5: Langkah Pengujian (Simulasi Dosen)

1. Pastikan Anda sudah menambahkan akun-akun Gmail uji coba di **OAuth Consent Screen > Test Users** pada Bagian 1.
2. Buka aplikasi, masuk ke halaman `/login`.
3. Klik tombol **"Login dengan Google"** (berwarna merah/putih dengan ikon Google).
4. Anda akan diarahkan ke layar pemilihan akun Google.
5. Pilih akun Gmail yang telah Anda daftarkan sebagai test user.
6. Setelah sukses login, Anda akan diarahkan kembali ke beranda dengan pesan sukses: *"Pendaftaran berhasil menggunakan akun Google..."* atau *"Selamat datang kembali..."*.
