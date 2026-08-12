# 🍱 Risha Catering (Ketring Mama Iksan)

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS Version](https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-%3E%3D%207.0-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

**Risha Catering** (atau **Ketring Mama Iksan**) adalah sebuah platform web katering online modern dan interaktif yang dibangun menggunakan framework **Laravel 12** dan **Tailwind CSS v4**. Sistem ini dirancang untuk memudahkan pelanggan dalam melakukan pemesanan katering secara *instan*, melacak status pesanan secara *real-time*, menentukan lokasi pengiriman menggunakan peta interaktif, serta melakukan pembayaran aman secara otomatis melalui integrasi **Duitku Payment Gateway**.

Sistem ini juga dilengkapi dengan panel **Administrator** yang lengkap untuk memudahkan pengelolaan stok, menu, pelacakan pesanan masuk, verifikasi pembayaran, serta pemantauan statistik pendapatan usaha dalam bentuk laporan tahunan yang informatif.

---

## ✨ Fitur Utama

### 👥 Sisi Pelanggan (Customer)
*   **Autentikasi Pengguna**: Pendaftaran akun dan sistem masuk (*login* & *register*) yang aman untuk menyimpan riwayat transaksi.
*   **Eksplorasi Menu**: Pencarian dan pemfilteran menu berdasarkan kategori (seperti Nasi Kotak, Tumpeng, Snack Box) secara dinamis.
*   **Keranjang Belanja Interaktif**: Memungkinkan penambahan menu ke keranjang belanja dengan batas minimal pemesanan (minimal 10 porsi per menu).
*   **Checkout dengan Jarak & Ongkir Akurat**:
    *   Integrasi peta interaktif menggunakan **Leaflet.js & OpenStreetMap** untuk menentukan koordinat lokasi acara (*pin point*).
    *   Deteksi lokasi otomatis berbasis GPS.
    *   Perhitungan jarak otomatis dari dapur pusat (Parung, Bogor) menggunakan **Rumus Haversine**.
    *   Perhitungan ongkos kirim otomatis yang adil berdasarkan jarak riil (khusus area Jabodetabek).
*   **Duitku Payment Gateway**: Mendukung berbagai metode pembayaran populer di Indonesia (Virtual Account, E-Wallet seperti GoPay/OVO/ShopeePay, QRIS, dan Ritel Modern) dengan konfirmasi pembayaran otomatis via *webhook callback*.
*   **Invoice PDF & Riwayat Pesanan**: Pelacakan pesanan secara *real-time* (dengan sistem *polling* status otomatis) dan opsi mengunduh struk/invoice berformat PDF resmi.

### 🛡️ Sisi Administrator (Dashboard Admin)
*   **Dashboard Statistik Bisnis**: Grafik batang interaktif laporan pendapatan tahunan, statistik menu terlaris, pesanan pending terbaru, dan ringkasan finansial bisnis.
*   **Manajemen Menu (CRUD)**: Kelola daftar menu katering (tambah, ubah, hapus), unggah foto makanan, set harga, serta mengaktifkan/menonaktifkan ketersediaan menu secara cepat (*toggle status*).
*   **Pengelolaan Pesanan Masuk**:
    *   Sistem pemantauan pesanan yang diperbarui otomatis (*auto-refresh*).
    *   Pembaruan status pesanan (*Pending* ➔ *Diproses* ➔ *Dikirim* ➔ *Selesai*).
    *   Konfirmasi pembayaran manual jika pelanggan membayar lewat jalur alternatif.
    *   Navigasi kurir langsung terhubung ke Google Maps berdasarkan koordinat GPS yang dipilih pelanggan.
    *   Cetak atau unduh Invoice PDF untuk keperluan dapur dan kurir.
*   **Manajemen Pengguna**: Monitoring daftar pelanggan terdaftar, memantau aktivitas pengguna (*last seen*), dan menghapus pengguna bermasalah.

---

## 🛠️ Spesifikasi Teknologi & Arsitektur

*   **Framework Core**: [Laravel 12.x](https://laravel.com/) (MVC Monolithic Architecture)
*   **Frontend Engine**: [Vite](https://vite.dev/) & [Tailwind CSS v4.0](https://tailwindcss.com/)
*   **Database**: MySQL / MariaDB (atau SQLite untuk kebutuhan *development*)
*   **Peta Interaktif**: [Leaflet.js](https://leafletjs.com/) dengan OpenStreetMap API
*   **Payment Gateway**: [Duitku API](https://www.duitku.com/) (Sistem Invoice Merchant & Callbacks)
*   **PDF Engine**: [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) (DomPDF)
*   **Ikon UI**: [Lucide Icons](https://lucide.dev/)

---

## 📋 Persyaratan Sistem

Pastikan server atau mesin lokal Anda memenuhi persyaratan minimum berikut:
*   **PHP Engine** >= 8.2 (dengan ekstensi: `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `zip`, `gd`)
*   **Composer** >= 2.4
*   **Node.js** >= 18.x & **NPM** >= 9.x
*   **Web Server**: Apache / Nginx / XAMPP (untuk database server)

---

## ⚙️ Langkah Instalasi & Konfigurasi

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di lingkungan lokal Anda:

### 1. Clone Repository
```bash
git clone https://github.com/username/ketringmamaiksan.git
cd ketringmamaiksan
```

### 2. Install Dependencies
Instal semua package PHP (Composer) dan Javascript (NPM) yang dibutuhkan:
```bash
composer install
npm install
```

### 3. Salin Environment File
Salin template konfigurasi `.env` dan generates enkripsi key aplikasi:
```bash
# Untuk Windows:
copy .env.example .env

# Untuk Linux / macOS:
cp .env.example .env

# Generate Application Key
php artisan key:generate
```

### 4. Konfigurasi Database
Buka file `.env` yang baru dibuat menggunakan text editor Anda dan sesuaikan kredensial database Anda.
Contoh menggunakan **MySQL**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rishacatering
DB_USERNAME=root
DB_PASSWORD=
```
*(Catatan: Anda juga bisa menggunakan `sqlite` untuk database cepat tanpa instalasi server MySQL).*

### 5. Migrasi & Seeding Database
Jalankan migrasi tabel database beserta data *default* (termasuk menu awal dan akun admin bawaan):
```bash
php artisan migrate --seed
```

### 6. Konfigurasi Duitku Payment Gateway
Untuk mengaktifkan sistem pembayaran otomatis Duitku (khususnya di mode Sandbox untuk uji coba), isi variabel berikut di file `.env` Anda:
```env
DUITKU_MERCHANT_CODE=DSXXXXX   # Dapatkan dari dashboard Duitku Sandbox
DUITKU_API_KEY=your_api_key_here
DUITKU_ENV=sandbox             # Gunakan 'sandbox' atau 'production'
DUITKU_CALLBACK_URL=https://domain-anda.com/callback
DUITKU_RETURN_URL=https://domain-anda.com/riwayat-pemesanan
```
*(Tips: Gunakan **ngrok** atau sejenisnya di lokal komputer Anda agar endpoint callback Duitku dapat diakses secara publik oleh server Duitku).*

### 7. Compile Assets & Jalankan Aplikasi
Jalankan kompilasi frontend Vite dan jalankan server lokal Laravel:

**Terminal 1 (Build Assets):**
```bash
# Mode development (live reload)
npm run dev

# Atau compile untuk production bundle
npm run build
```

**Terminal 2 (Jalankan Server PHP):**
```bash
php artisan serve
```

Aplikasi kini dapat diakses melalui browser Anda di **`http://localhost:8000`**.

---

## 🔑 Kredensial Akun Default

Setelah menjalankan perintah `php artisan db:seed`, Anda dapat menggunakan akun bawaan berikut untuk uji coba:

### 👤 Akun Administrator (Dashboard Admin)
*   **URL Login Admin**: `http://localhost:8000/admin/login`
*   **Email**: `admin@rishacatering.com`
*   **Password**: `rishacatering123`

### 👥 Akun Pelanggan (Customer Demo)
*   Anda bisa mendaftarkan akun baru secara langsung di halaman utama menggunakan fitur **Daftar** di pojok kanan atas.

---

## 📂 Struktur Database Utama

Aplikasi ini menggunakan beberapa tabel database utama untuk menampung data:
*   `users`: Menyimpan data akun pengguna dengan kolom `role` (`admin` / `user`) serta kolom `last_seen` untuk mendeteksi status keaktifan user.
*   `menus`: Menyimpan detail menu makanan, kategori, harga, deskripsi, dan status ketersediaan (`is_active`).
*   `orders`: Menyimpan informasi utama transaksi katering, termasuk data pelanggan, total harga, detail jarak pengiriman, status pesanan (`pending`, `diproses`, `dikirim`, `selesai`, `dibatalkan`), status pembayaran (`unpaid`, `paid`, `expired`), tautan pembayaran Duitku (`payment_url`), serta data koordinat peta (`latitude` & `longitude`).
*   `order_items`: Menyimpan rincian item/menu apa saja yang dipesan dalam satu transaksi beserta jumlah porsinya (*one-to-many*).

---

---
*Proyek ini dikembangkan untuk kebutuhan operasional katering profesional berbasis otomatisasi wilayah pengiriman & sistem pembayaran instan.*
