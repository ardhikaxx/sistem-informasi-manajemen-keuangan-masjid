# Sistem Informasi Manajemen Keuangan Masjid
Masjid Jami' Al-Muttaqiin — Jember

[![Blade](https://img.shields.io/badge/Template-Blade-red)](https://laravel.com/docs/blade)
[![PHP](https://img.shields.io/badge/PHP-%3E%3D8.2-777bb4)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-12-ff2d20)](https://laravel.com/)
[![License](https://img.shields.io/badge/License-TBD-lightgrey)]()

## Ringkasan
Aplikasi web untuk membantu pengelolaan keuangan Masjid Jami' Al-Muttaqiin Jember. Fokus pada pencatatan pemasukan/pengeluaran, pemantauan saldo kas, pembuatan laporan periodik, serta statistik pada dashboard untuk bendahara dan pengurus masjid.

## Fitur utama
- Pencatatan transaksi pemasukan dan pengeluaran.
- Rekap saldo kas dan ringkasan transaksi.
- Laporan periodik yang dapat disiapkan untuk kebutuhan administrasi.
- Dashboard ringkas untuk memantau kondisi keuangan.

## Teknologi
- PHP 8.2+
- Laravel 12
- Blade Template + Vite
- MySQL/MariaDB (dapat disesuaikan di `.env`)

## Persyaratan
- PHP 8.2+ dan Composer
- Node.js + NPM
- Database (MySQL/MariaDB/SQLite sesuai kebutuhan)

## Instalasi (lokal)
1. Clone repo:
```bash
git clone https://github.com/ardhikaxx/sistem-informasi-manajemen-keuangan-masjid.git
cd sistem-informasi-manajemen-keuangan-masjid
```

2. Instal dependensi:
```bash
composer install
npm install
```

3. Siapkan environment:
```bash
cp .env.example .env
# lalu edit .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL, dsb.
php artisan key:generate
```

4. Migrasi database (dan seed jika tersedia):
```bash
php artisan migrate
php artisan db:seed   # opsional, hanya bila ada seeder
```

5. (Jika upload file/bukti transaksi dipakai) buat symbolic link storage:
```bash
php artisan storage:link
```

6. Jalankan aplikasi:
```bash
php artisan serve
npm run dev
# buka http://127.0.0.1:8000
```

## Quick start (opsional)
Jika ingin setup sekaligus, jalankan skrip Composer berikut:
```bash
composer run setup
```

## Perintah berguna
- Menjalankan aplikasi + Vite:
  ```bash
  composer run dev
  ```
- Menjalankan test:
  ```bash
  composer run test
  ```

## Struktur direktori (ringkas)
- `app/` — logika aplikasi (Controller, Model, Service).
- `resources/views/` — tampilan Blade.
- `routes/` — definisi routing.
- `database/` — migrasi, factory, dan seeder.

## Lisensi
Lisensi belum ditentukan.
