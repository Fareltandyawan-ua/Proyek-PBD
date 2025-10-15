# UAS PBD Prak

## Deskripsi Proyek
Proyek ini adalah aplikasi pengadaan yang dirancang untuk memudahkan manajemen barang, vendor, pengguna, dan transaksi pengadaan. Aplikasi ini memiliki antarmuka yang responsif dan interaktif, serta terhubung dengan database untuk menyimpan dan mengelola data.

## Struktur Proyek
Proyek ini memiliki struktur folder sebagai berikut:

```
UAS_pbdprak
├── assets
│   ├── css
│   ├── js
│   └── images
├── config
│   └── database.php
├── includes
│   ├── header.php
│   ├── sidebar.php
│   ├── footer.php
│   └── functions.php
├── modules
│   ├── auth
│   ├── dashboard
│   ├── barang
│   ├── satuan
│   ├── vendor
│   ├── user
│   ├── pengadaan
│   ├── penerimaan
│   ├── penjualan
│   ├── retur
│   ├── margin
│   └── kartu_stok
├── api
├── index.php
└── README.md
```

## Fitur Utama
- **Autentikasi Pengguna**: Pengguna dapat login dan logout untuk mengakses aplikasi.
- **Manajemen Barang**: Menambah, mengedit, dan menghapus barang dalam inventaris.
- **Manajemen Vendor**: Menambah, mengedit, dan menghapus vendor.
- **Manajemen Pengguna**: Menambah, mengedit, dan menghapus pengguna.
- **Transaksi Pengadaan**: Mencatat dan mengelola transaksi pengadaan barang.
- **Laporan**: Menampilkan laporan terkait barang, vendor, dan transaksi.

## Cara Menjalankan Proyek
1. **Kloning Repository**: Klon repositori ini ke dalam direktori lokal Anda.
2. **Konfigurasi Database**: Ubah pengaturan koneksi database di `config/database.php` sesuai dengan konfigurasi server database Anda.
3. **Jalankan Server**: Gunakan server lokal seperti XAMPP atau Laragon untuk menjalankan aplikasi.
4. **Akses Aplikasi**: Buka browser dan akses `http://localhost/UAS_pbdprak/index.php` untuk melihat aplikasi.

## Teknologi yang Digunakan
- PHP
- MySQL
- HTML/CSS
- JavaScript (jQuery)
- Bootstrap

## Kontribusi
Jika Anda ingin berkontribusi pada proyek ini, silakan fork repositori ini dan buat pull request dengan perubahan yang Anda buat.

## Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).