# Risha Catering 🍱

Selamat datang di repositori **Risha Catering**, sebuah aplikasi web e-commerce *full-stack* yang dirancang khusus untuk manajemen pemesanan katering. Aplikasi ini dibangun menggunakan framework **Laravel 12** (PHP 8.2+) dan menawarkan pengalaman berbelanja makanan yang mulus dengan integrasi pembayaran otomatis.

---

## 🏗️ Struktur Direktori Utama

Sebagai *full-stack developer*, berikut adalah panduan cepat mengenai struktur file dan direktori penting dalam proyek ini:

```text
risha-catering/
├── app/
│   ├── Http/
│   │   └── Controllers/       # Logika bisnis aplikasi (Admin, Cart, Auth, Invoice, Duitku)
│   └── Models/                # Representasi data entitas (User, Menu, Order, OrderItem)
├── config/                    # Konfigurasi sistem, database, dan layanan pihak ketiga (Duitku)
├── database/
│   ├── migrations/            # Skema database (tabel users, menus, orders, dll)
│   └── seeders/               # Data awal (dummy data / admin user)
├── public/                    # Root document web (Asset statis: CSS, JS, Gambar)
│   └── css/
│       └── style.css          # Styling kustom (Vanilla CSS / Tailwind jika dikonfigurasi)
├── resources/
│   └── views/                 # Tampilan antarmuka aplikasi (Blade Templates)
│       ├── admin/             # Tampilan halaman dashboard admin
│       ├── auth/              # Tampilan halaman login/register
│       ├── invoice/           # Template PDF untuk cetak struk (dompdf)
│       └── components/        # Komponen UI modular
├── routes/
│   └── web.php                # Jantung perutean aplikasi (Public, Cart, Auth, Admin)
├── .env                       # Environment variables (DB_*, DUITKU_*)
└── composer.json              # Dependensi PHP (Laravel, laravel-dompdf, dll)
```

---

## ⚙️ Cara Kerja Aplikasi (Alur Sistem)

Aplikasi ini membagi layanannya menjadi dua sisi utama: **User (Pelanggan)** dan **Admin (Pemilik Usaha)**.

### 1. Sisi Pengguna (Pelanggan)
- **Browsing & Keranjang (Cart):** Pengguna dapat melihat daftar menu makanan (`PageController@menu`) dan menambahkannya ke keranjang belanja (`CartController@add`). Data keranjang dikelola menggunakan *Session/Database* sebelum checkout.
- **Autentikasi & Checkout:** Untuk membuat pesanan, pelanggan harus login/mendaftar (`UserAuthController`). Saat proses *checkout*, aplikasi mengkalkulasi total harga dan membuat entri di tabel `orders` dan `order_items`.
- **Pembayaran (Payment Gateway):** Sistem terintegrasi dengan **Duitku Payment Gateway** (`DuitkuCallbackController`). Pelanggan akan diarahkan untuk membayar, dan status pesanan (misal: "Pending" menjadi "Lunas") diperbarui secara otomatis via *webhook/callback* dari Duitku.
- **Pelacakan & Invoice:** Pelanggan dapat melacak status pesanan secara *real-time* dan mengunduh struk/invoice dalam format PDF yang di-generate menggunakan `barryvdh/laravel-dompdf` (`InvoiceController`).

### 2. Sisi Admin (Pemilik Usaha)
- **Dashboard Autentikasi:** Admin login melalui route khusus (`/admin/login`).
- **Manajemen Menu & Stok:** Admin dapat melakukan operasi CRUD (Create, Read, Update, Delete) pada data menu makanan (`AdminController@storeMenu`, dll) beserta manajemen ketersediaan stoknya.
- **Manajemen Pesanan:** Admin dapat melihat daftar pesanan masuk, mengonfirmasi pembayaran (jika manual), serta memodifikasi status pesanan (misalnya dari "Diproses" menjadi "Dikirim" atau "Selesai").

---

## 🚀 Tech Stack (Sorotan Full-Stack)

Aplikasi ini menggunakan teknologi modern untuk memastikan performa, keamanan, dan *maintainability*:

*   **Backend:** PHP 8.2+ dengan Framework **Laravel 12**.
*   **Database:** MySQL / MariaDB dengan Eloquent ORM.
*   **Frontend:** Blade Templating Engine HTML5, dan CSS kustom.
*   **Payment Gateway:** Duitku API.
*   **PDF Generator:** DomPDF (via `barryvdh/laravel-dompdf`).
*   **Real-time Feature:** Menggunakan *polling* AJAX untuk mengecek status pesanan (`api/order-status`).

## 🛠️ Instalasi & Setup Lokal

Jika Anda ingin menjalankan aplikasi ini di lokal:

1. Clone repositori ini.
2. Jalankan `composer install` untuk mengunduh dependensi backend.
3. Salin file `.env.example` menjadi `.env` dan konfigurasikan `DB_*` serta API Key Duitku (`DUITKU_*`).
4. Jalankan `php artisan key:generate`.
5. Jalankan migrasi database: `php artisan migrate --seed`.
6. (Opsional) Jalankan `npm install` dan `npm run dev` jika ada kompilasi asset Vite.
7. Jalankan server lokal: `php artisan serve`.
8. Buka browser: `http://localhost:8000`.

---
*Dikembangkan dengan ❤️ untuk kemudahan operasional Risha Catering.*
