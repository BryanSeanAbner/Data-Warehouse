# Data Warehouse 

Tujuan utama project ini adalah membangun sebuah aplikasi berbasis Laravel yang mengimplementasikan konsep data warehouse untuk mengolah dan menganalisis data penjualan retail serta data inventory secara terstruktur.

## Tujuan Project

Project ini dibuat untuk:

- Menerapkan konsep data warehouse dengan tabel dimensi dan tabel fakta.
- Menyimpan data transaksi penjualan retail ke dalam model warehouse.
- Menyediakan data inventory yang dapat dianalisis berdasarkan waktu, produk, gudang, dan status proses.
- Mempermudah pemantauan dan analisis bisnis melalui antarmuka web.

## Fitur Utama

- Tabel dimensi seperti date, product, store, cashier, promotion, payment method, warehouse, dan vendor.
- Tabel fakta untuk data retail sales, inventory periodic, dan inventory accumulating.
- Implementasi query analitik untuk melihat alur inventory dari diterima, diperiksa, ditempatkan di bin, hingga pengiriman awal/akhir.
- Struktur project yang memanfaatkan Laravel untuk pengelolaan data dan tampilan aplikasi.

## Tools dan Komponen yang Digunakan

- PHP 8.2
- Laravel 12
- Eloquent ORM dan Query Builder
- MySQL sebagai database utama
- Blade Template untuk tampilan web
- Vite, Tailwind CSS, dan Axios untuk frontend asset
- Composer dan npm untuk dependency management
- Pest untuk testing
- pharaonic/php-hijri untuk dukungan format tanggal Hijriah

## Struktur Project

- `app/` : controller, model, dan logika aplikasi.
- `database/migrations/` : definisi skema tabel warehouse.
- `database/seeders/` : data awal dan seeder.
- `resources/views/` : tampilan halaman web.
- `routes/` : konfigurasi routing aplikasi.
