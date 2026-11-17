# Denteeth

Denteeth adalah proyek kuliah Rekayasa Kebutuhan Perangkat Lunak. Repository ini memuat fondasi Laravel, halaman awal Denteeth, dan endpoint `GET /api/user` yang membaca profil pemilik token Sanctum. Dokumen kebutuhan serta fitur Denteeth belum tersedia di sini.

## Menjalankan di komputer sendiri

Siapkan PHP versi 8.3 sampai 8.5, Composer 2, serta ekstensi PHP yang [dibutuhkan Laravel](https://laravel.com/docs/13.x/deployment#server-requirements) dan `pdo_sqlite`. PHP CLI untuk PHPUnit juga memerlukan ekstensi `dom`, `mbstring`, `xml`, dan `xmlwriter`.

Dari folder repository, jalankan:

```sh
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate
php artisan serve
```

Buka [halaman awal Denteeth](http://127.0.0.1:8000). Jalankan `key:generate` hanya saat menyiapkan `.env` baru. Untuk memakai database yang sudah ada, pertahankan `APP_KEY` dan pengaturan koneksi database dari `.env` sebelumnya.

Jika konfigurasi lama memakai `DATABASE_URL`, pindahkan nilainya ke `DB_URL`. Laravel 13 memakai `CACHE_STORE` dan `BROADCAST_CONNECTION` untuk menggantikan `CACHE_DRIVER` dan `BROADCAST_DRIVER`.

## Menjalankan tes

```sh
php artisan config:clear
php artisan test
composer audit --locked
```

Tes memeriksa halaman awal serta respons API untuk pengunjung tanpa token, token aktif, token yang sudah dicabut, dan token kedaluwarsa. Tes memakai database SQLite sementara di memori.
