# Risha Catering

Selamat datang di repositori **Risha Catering**, aplikasi web e-commerce untuk manajemen pemesanan katering. Aplikasi ini dibangun dengan **Laravel 12** dan mendukung alur menu, keranjang, pesanan, pembayaran Duitku, invoice PDF, serta dashboard admin.

---

## Struktur Direktori Utama

```text
risha-catering/
|- app/
|  |- Http/Controllers/   # Logika bisnis aplikasi
|  `- Models/             # Entitas User, Menu, Order, OrderItem
|- config/                # Konfigurasi sistem dan layanan pihak ketiga
|- database/
|  |- migrations/         # Skema database
|  `- seeders/            # Data awal
|- public/                # Asset statis
|- resources/views/       # Blade templates
|- routes/web.php         # Routing aplikasi
|- .env                   # Environment variables
`- composer.json          # Dependensi PHP
```

## Cara Kerja Aplikasi

### Sisi Pengguna
- Pengguna melihat menu, menambahkan item ke keranjang, lalu checkout.
- Pesanan disimpan ke tabel `orders` dan `order_items`.
- Pembayaran diproses melalui Duitku.
- Status pesanan dapat dipantau secara real-time melalui polling.
- Invoice dapat diunduh dalam format PDF.

### Sisi Admin
- Admin login melalui `/admin/login`.
- Admin mengelola menu, stok, pesanan, status pesanan, dan konfirmasi pembayaran.
- Dashboard menampilkan ringkasan pesanan, pendapatan, dan menu terlaris.

## Tech Stack

- **Backend:** PHP 8.2+ dan Laravel 12.
- **Database:** MySQL / MariaDB dengan Eloquent ORM.
- **Frontend:** Blade, HTML, CSS kustom, dan JavaScript.
- **Payment Gateway:** Duitku API.
- **PDF Generator:** DomPDF.
- **Real-time:** Polling AJAX untuk status pesanan.

## Instalasi Lokal

1. Jalankan `composer install`.
2. Salin `.env.example` menjadi `.env`.
3. Atur konfigurasi `DB_*` dan `DUITKU_*`.
4. Jalankan `php artisan key:generate`.
5. Jalankan `php artisan migrate --seed`.
6. Jalankan `npm install` dan `npm run dev` jika diperlukan.
7. Jalankan `php artisan serve`.
8. Buka `http://localhost:8000`.

---

Dikembangkan untuk kemudahan operasional Risha Catering.
