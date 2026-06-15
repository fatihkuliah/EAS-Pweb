# MieME Native PHP

Refactor dari HTML statis ke PHP native dengan struktur MVC sederhana, service layer ringan, dan asset publik yang rapi.

## Cara menjalankan

### 1. Jalankan PHP Dev Server
```bash
php -S localhost:8000 -t public public/index.php
```

Lalu buka di browser:
```text
http://localhost:8000
```
Jangan buka `public/index.php` langsung dari file manager, dan jangan jadikan root project sebagai document root. Asset CSS/gambar disiapkan untuk dilayani dari folder `public`.

### 2. Setup Database (MySQL/MariaDB) & Environment Variables (.env)
1. Salin file `.env.example` menjadi `.env` di root proyek:
   ```bash
   cp .env.example .env
   ```
2. Buka `.env` dan sesuaikan kredensial koneksi database Anda (host, database name, username, password).
3. Jalankan perintah migrasi dan seeder untuk menginisialisasi database dan data awal menggunakan CLI helper `db.php` di root proyek:
   ```bash
   php db.php reset
   ```
   *Perintah `reset` akan melakukan rollback tabel lama (jika ada), menjalankan migrasi tabel baru, dan mengisinya dengan data seeder.*

   **Perintah CLI Database Lainnya:**
   * Run Migrations (`up`): `php db.php migrate`
   * Rollback Migrations (`down`): `php db.php rollback`
   * Run Seeders: `php db.php seed`

## Struktur folder

```text
.
├── README.md
├── db.php
├── .env
├── .env.example
├── .gitignore
├── app
│   ├── Controllers
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── HomeController.php
│   │   ├── MenuController.php
│   │   ├── OrderController.php
│   │   ├── PaymentController.php
│   │   └── ProfileController.php
│   ├── Core
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   └── Env.php
│   ├── Helpers
│   │   ├── env.php
│   │   ├── format.php
│   │   ├── http.php
│   │   ├── path.php
│   │   ├── session.php
│   │   └── upload.php
│   ├── Models
│   │   ├── Cart.php
│   │   ├── Menu.php
│   │   ├── Order.php
│   │   ├── Payment.php
│   │   └── User.php
│   ├── Services
│   │   ├── CartService.php
│   │   ├── CheckoutService.php
│   │   ├── OrderService.php
│   │   └── PaymentService.php
│   ├── Views
│   │   ├── auth
│   │   │   └── page.php
│   │   ├── checkout
│   │   │   └── page.php
│   │   ├── errors
│   │   │   └── not-found.php
│   │   ├── favorites
│   │   │   └── page.php
│   │   ├── home
│   │   │   └── page.php
│   │   ├── layout.php
│   │   ├── menu
│   │   │   ├── page.php
│   │   │   └── detail
│   │   │       └── page.php
│   │   ├── orders
│   │   │   ├── detail.php
│   │   │   └── page.php
│   │   ├── partials
│   │   │   ├── menu_card.php
│   │   │   └── page_header.php
│   │   ├── payment
│   │   │   ├── page.php
│   │   │   ├── qris.php
│   │   │   └── upload.php
│   │   ├── profile
│   │   │   └── page.php
│   │   └── review
│   │       └── page.php
│   └── helpers.php
├── archive
│   └── legacy-static
│       ├── *.html
│       ├── *.js
│       ├── img
│       ├── style.css
│       └── mieme.zip
├── config
│   ├── app.php
│   ├── database.php
│   └── routes.php
├── database
│   ├── migrations
│   │   ├── 2026_06_15_000001_create_users_table.php
│   │   └── ...
│   └── seeds
│       └── seeder.php
├── public
│   ├── .htaccess
│   ├── assets
│   │   ├── css
│   │   │   └── style.css
│   │   ├── images
│   │   │   ├── favicon
│   │   │   └── payment
│   │   └── js
│   │       ├── core
│   │       │   └── mieme-app.js
│   │       └── features
│   │           ├── auth
│   │           │   └── auth.js
│   │           ├── checkout
│   │           │   └── checkout.js
│   │           ├── favorites
│   │           │   └── favorites.js
│   │           ├── home
│   │           │   └── home.js
│   │           ├── menu
│   │           │   └── menu-detail.js
│   │           ├── order
│   │           │   ├── detail-order.js
│   │           │   ├── order.js
│   │           │   └── orders.js
│   │           ├── payment
│   │           │   ├── payment.js
│   │           │   ├── qris.js
│   │           │   └── upload-payment.js
│   │           ├── profile
│   │           │   └── profile.js
│   │           └── review
│   │               └── review.js
│   └── index.php
└── storage
    ├── exports
    │   └── .gitkeep
    ├── invoices
    │   └── .gitkeep
    └── uploads
        └── .gitkeep
```

Catatan: isi gambar di `public/assets/images` dan file legacy di `archive/legacy-static` dipersingkat agar README tidak terlalu panjang.

## Catatan

- Menggunakan database MySQL/MariaDB dengan sistem migrasi berbasis class PHP (ala Laravel) dan data seeder otomatis.
- Konfigurasi aplikasi, database, dan routing ada di folder `config/`.
- Proses bisnis seperti cart, checkout, order, dan payment ada di `app/Services`.
- Model (`User`, `Menu`, `Order`, `Cart`) telah terintegrasi dengan database MySQL menggunakan PDO, dengan fallback otomatis ke mock data jika koneksi database belum disetup.
- Upload disimpan di `storage/uploads`, bukan di `public/uploads`.
- `public/` hanya untuk front controller dan asset yang memang boleh diakses browser.
- `archive/legacy-static` hanya arsip HTML/JS lama, bukan bagian runtime aplikasi.
- Struktur view tetap mempertahankan `layout.php` dan `partials/`, dengan halaman berbasis route sederhana.
