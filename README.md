# Sistem Informasi Manajemen Keuangan Masjid
Masjid Jami' Al-Muttaqiin — Jember

[![Blade](https://img.shields.io/badge/Template-Blade-red)](https://laravel.com/docs/blade)
[![PHP](https://img.shields.io/badge/PHP-%3E%3D8.0-777bb4)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-%E2%89%A5-ff2d20)](https://laravel.com/)
[![License](https://img.shields.io/badge/License-TBD-lightgrey)]()

Ringkasan
--------
Aplikasi web sederhana untuk membantu pengelolaan keuangan Masjid Jami' Al-Muttaqiin Jember. Mempermudah pencatatan pemasukan/pengeluaran, melihat saldo, membuat laporan, dan menampilkan statistik/dashboard untuk administrasi bendahara dan pengurus masjid.

Instalasi (lokal)
-----------------
1. Clone repo:
```bash
git clone https://github.com/ardhikaxx/sistem-informasi-manajemen-keuangan-masjid.git
cd sistem-informasi-manajemen-keuangan-masjid
```

2. Install dependensi PHP:
```bash
composer install
```

3. (Opsional) Install dependensi frontend bila ada:
```bash
npm install
npm run dev    # atau `npm run build` untuk produksi
```

4. Copy environment dan sesuaikan:
```bash
cp .env.example .env
# lalu edit .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL, dsb.
```

5. Generate app key:
```bash
php artisan key:generate
```

6. Migrasi & seed (jika tersedia seeder untuk membuat user/admin):
```bash
php artisan migrate
php artisan db:seed   # opsional, hanya bila ada seeder
```

7. (Jika upload file/bukti transaksi dipakai) buat symbolic link storage:
```bash
php artisan storage:link
```

8. Jalankan server lokal:
```bash
php artisan serve
# buka http://127.0.0.1:8000
```