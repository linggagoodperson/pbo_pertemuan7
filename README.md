# Manajemen Buku

Aplikasi Laravel 13 untuk latihan Routing, Controller, dan Blade pada mata kuliah Pemrograman Berorientasi Objek (TI033015), Pertemuan 7. Aplikasi hanya menyediakan halaman daftar buku dan detail buku; tidak ada fitur tambah, edit, atau hapus.

## Menjalankan aplikasi

```bash
composer install
```

Salin `.env.example` menjadi `.env`, lalu buat kunci aplikasi:

```bash
php artisan key:generate
```

Jalankan server lokal:

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000/buku`. Data contoh disiapkan di Controller dan tidak memerlukan database.

Jalankan pengujian dengan:

```bash
php artisan test
```

## Alur Route -> Controller -> Blade

Route menghubungkan URL ke method `BukuController`. Controller menyiapkan data contoh untuk view daftar atau mencari buku berdasarkan ID untuk view detail. Blade menampilkan data dengan output yang di-escape.

## Daftar route

| Method | URI | Nama route | Controller/action |
| --- | --- | --- | --- |
| GET | `/` | — | Redirect ke `buku.index` |
| GET | `/buku` | `buku.index` | `BukuController@index` |
| GET | `/buku/{id}` | `buku.show` | `BukuController@show` |

Periksa route buku dengan `php artisan route:list --path=buku`.

## Additions beyond the material

- `SESSION_DRIVER=file` menghindari kebutuhan tabel database untuk session HTTP.
