# TOKOKU
Website toko online + sistem kasir menggunakan PHP Native, PDO, dan MariaDB.

## Instalasi
1. Salin folder `tokoku` ke `C:\xampp\htdocs\`.
2. Jalankan Apache dan MySQL dari XAMPP.
3. Pastikan database `db_tokoku` sudah tersedia beserta tabel dasar:
   `users`, `tbl_pelanggan`, `tbl_transaksi`, `tbl_kategori`, `tbl_produk`,
   `tbl_keranjang`, `tbl_detail_keranjang`, `tbl_detail_transaksi`,
   `tbl_pembayaran`, `tbl_diskon`, `tbl_saldo`, `tbl_riwayat_saldo`,
   `tbl_log_aktivitas`.
4. Sesuaikan username/password database pada `config/koneksi.php`.
5. Buka `http://localhost/tokoku/setup.php` dan buat akun admin.
6. Hapus `setup.php` setelah instalasi.
7. Buka `http://localhost/tokoku/login.php`.

## Akun awal
Admin dibuat melalui halaman setup. Contoh awal: `admin` / `admin123`.
Ganti password setelah login.

## Role
- admin: dashboard, pengguna, pelanggan, kategori, produk, diskon, pesanan, laporan, aktivitas.
- kasir: pesanan masuk, POS, pembayaran, riwayat, nota.
- pelanggan: katalog, detail produk, keranjang, checkout, saldo, pesanan.

## Foto produk
Foto diunggah melalui form Admin dan disimpan ke `uploads/produk/`.
Folder tersebut harus dapat ditulisi oleh Apache. Format: JPG, JPEG, PNG, WEBP, GIF. Maksimal 3 MB.

## Nota
Nota memakai lebar thermal 58 mm dan panjang mengikuti isi. Pengaturan printer dapat memilih skala 100% dan margin none.

## Catatan
Top up saldo merupakan simulasi dan menunggu persetujuan Admin pada versi awal.
Pembayaran transfer/QRIS/e-wallet dicatat sebagai metode pembayaran; integrasi gateway pembayaran sungguhan memerlukan penyedia pembayaran.
