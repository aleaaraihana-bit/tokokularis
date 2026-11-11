# TOKOKULARIS
### Website Toko Online Sembako, Jajanan, dan Minuman

## 1. Deskripsi Project

TOKOKULARIS merupakan aplikasi website toko online yang dibuat untuk membantu proses pengelolaan dan penjualan berbagai kebutuhan sehari-hari, seperti sembako, makanan ringan, jajanan, minuman, dan kebutuhan rumah tangga.

Website ini dirancang dengan tampilan modern, sederhana, dan menggunakan warna burgundy sebagai identitas visual. TOKOKULARIS memiliki sistem pengelolaan produk, transaksi penjualan, keranjang belanja, saldo member, serta laporan penjualan.

Aplikasi ini dikembangkan menggunakan PHP Native dan database MySQL/MariaDB.

## 2. Tujuan Project

Tujuan pembuatan website TOKOKULARIS adalah:

1. Mempermudah pengelolaan data produk dan kategori.
2. Mempermudah proses transaksi penjualan.
3. Memudahkan pelanggan dalam berbelanja secara online.
4. Menyediakan sistem saldo untuk member.
5. Mempermudah pengelolaan akun Admin, Kasir, dan Pelanggan.
6. Membantu pencatatan transaksi dan laporan penjualan.
7. Mengurangi kesalahan dalam pencatatan stok dan transaksi.

## 3. Teknologi yang Digunakan

- PHP Native
- MySQL/MariaDB
- HTML
- CSS
- JavaScript
- XAMPP
- Visual Studio Code
- phpMyAdmin

## 4. Hak Akses Pengguna

TOKOKULARIS memiliki tiga jenis pengguna:

### A. Admin

Admin memiliki hak akses untuk mengelola seluruh data utama website.

Fitur Admin:
- Login dan logout.
- Melihat dashboard.
- Mengelola data produk.
- Menambahkan, mengedit, dan menghapus produk.
- Mengunggah gambar produk.
- Mengelola kategori produk.
- Membuat akun member.
- Mengelola akun Kasir.
- Melihat dan mengelola saldo member.
- Menyetujui atau menolak pengajuan top up.
- Mengelola diskon dan voucher.
- Melihat laporan penjualan.
- Melihat riwayat transaksi.
- Melihat log aktivitas pengguna.

### B. Kasir

Kasir bertugas melayani transaksi penjualan secara langsung.

Fitur Kasir:
- Login dan logout.
- Melihat dashboard kasir.
- Melihat daftar produk.
- Mencari produk.
- Memasukkan produk ke keranjang transaksi.
- Menghitung total belanja.
- Memilih metode pembayaran.
- Menghitung uang diterima dan kembalian.
- Menyimpan transaksi.
- Mencetak struk belanja ukuran 58 mm.
- Melihat laporan dan riwayat transaksi.

### C. Pelanggan

Pelanggan dapat menggunakan website untuk berbelanja dan mengelola akun member.

Fitur Pelanggan:
- Registrasi akun.
- Login dan logout.
- Melihat katalog produk.
- Melihat detail produk.
- Menambahkan produk ke keranjang.
- Mengubah jumlah produk dalam keranjang.
- Menghapus produk dari keranjang.
- Melakukan checkout.
- Memilih metode pembayaran.
- Melihat saldo member.
- Mengajukan top up saldo.
- Melihat riwayat top up.
- Melihat riwayat perubahan saldo.
- Melihat riwayat pesanan.
- Melihat struk transaksi.

## 5. Fitur Utama Website

### A. Manajemen Produk
Admin dapat menambahkan produk beserta nama, kategori, harga, stok, satuan, deskripsi, dan gambar produk.

### B. Manajemen Kategori
Admin dapat membuat dan mengelola kategori seperti:
- Sembako
- Makanan ringan
- Minuman
- Mie instan
- Sayur dan buah
- Kebutuhan rumah tangga

### C. Keranjang Belanja
Pelanggan dapat memilih beberapa produk, mengatur jumlah pembelian, dan melihat total belanja sebelum checkout.

### D. Sistem Saldo Member
Pelanggan memiliki saldo yang dapat digunakan untuk pembayaran. Pelanggan dapat mengajukan top up dan Admin dapat menyetujui atau menolak pengajuan tersebut.

### E. Sistem Transaksi
Website mencatat transaksi penjualan, detail produk, jumlah barang, harga, metode pembayaran, dan status transaksi.

### F. Struk Belanja
Website menyediakan struk dengan ukuran 58 mm yang berisi:
- Nama toko.
- Nomor transaksi.
- Tanggal transaksi.
- Nama kasir.
- Daftar produk.
- Jumlah barang.
- Harga produk.
- Subtotal.
- Diskon.
- Total pembayaran.
- Uang diterima.
- Kembalian.
- Metode pembayaran.

### G. Laporan Penjualan
Admin dan Kasir dapat melihat informasi transaksi dan total penjualan.

### H. Log Aktivitas
Admin dapat melihat catatan aktivitas pengguna untuk membantu memantau kegiatan di dalam website.

## 6. Struktur Database

Nama database yang digunakan:

`db_tokoku`

Tabel-tabel database yang digunakan antara lain:

| No. | Nama Tabel | Fungsi |
|---|---|---|
| 1 | users | Menyimpan akun pengguna |
| 2 | tbl_pelanggan | Menyimpan data pelanggan |
| 3 | tbl_produk | Menyimpan data produk |
| 4 | tbl_kategori | Menyimpan kategori produk |
| 5 | tbl_keranjang | Menyimpan keranjang pelanggan |
| 6 | tbl_detail_keranjang | Menyimpan detail produk dalam keranjang |
| 7 | tbl_transaksi | Menyimpan transaksi penjualan |
| 8 | tbl_detail_transaksi | Menyimpan detail barang transaksi |
| 9 | tbl_pembayaran | Menyimpan data pembayaran |
| 10 | tbl_saldo | Menyimpan saldo member |
| 11 | tbl_riwayat_saldo | Menyimpan riwayat perubahan saldo |
| 12 | tbl_topup_saldo | Menyimpan pengajuan top up |
| 13 | tbl_diskon | Menyimpan data diskon dan voucher |
| 14 | tbl_log_aktivitas | Menyimpan aktivitas pengguna |

Database juga dapat memiliki view, trigger, stored procedure, dan function sesuai implementasi database yang digunakan.

## 7. Cara Instalasi dan Menjalankan Website

### Langkah 1: Persiapan

Pastikan sudah menginstal:
- XAMPP
- Visual Studio Code
- Browser
- phpMyAdmin

### Langkah 2: Menempatkan Project

1. Salin folder `tokokularis`.
2. Letakkan folder di dalam direktori:

`C:\xampp\htdocs\`

3. Pastikan struktur project berada di:

`C:\xampp\htdocs\tokokularis`

### Langkah 3: Menjalankan XAMPP

1. Buka XAMPP Control Panel.
2. Aktifkan Apache.
3. Aktifkan MySQL.

### Langkah 4: Membuat Database

1. Buka browser.
2. Masuk ke `http://localhost/phpmyadmin`.
3. Buat database dengan nama `db_tokoku`.
4. Import file database SQL yang sudah disiapkan.

### Langkah 5: Konfigurasi Koneksi

Buka file:

`config/koneksi.php`

Sesuaikan konfigurasi database dengan komputer yang digunakan.

Contoh konfigurasi:

- Host: localhost
- Database: db_tokoku
- Username: root
- Password: kosong (sesuaikan dengan konfigurasi MySQL).

### Langkah 6: Menjalankan Website

Buka browser dan akses:

`http://localhost/tokokularis/`

## 8. Akun Pengujian

Contoh akun awal yang disediakan:

| Role | Username | Password |
|---|---|---|
| Admin | admin | admin123 |
| Kasir | kasir | kasir123 |

Pelanggan dapat melakukan registrasi melalui halaman pendaftaran atau dibuat melalui menu pengelolaan member oleh Admin.

Catatan: Password akun disimpan dalam bentuk hash menggunakan `password_hash()`.

## 9. Pengujian Sistem

Pengujian dilakukan untuk memastikan fitur website berjalan sesuai fungsinya.

Contoh pengujian:

| No. | Fitur | Hasil yang Diharapkan |
|---|---|---|
| 1 | Login Admin | Admin berhasil masuk |
| 2 | Login Kasir | Kasir berhasil masuk |
| 3 | Registrasi Pelanggan | Akun pelanggan berhasil dibuat |
| 4 | Tambah Produk | Produk tersimpan |
| 5 | Upload Gambar | Gambar produk tampil |
| 6 | Tambah Kategori | Kategori tersimpan |
| 7 | Tambah ke Keranjang | Produk masuk keranjang |
| 8 | Checkout | Pesanan berhasil dibuat |
| 9 | Pembayaran Saldo | Saldo berkurang sesuai total |
| 10 | Top Up Saldo | Pengajuan masuk ke Admin |
| 11 | Persetujuan Top Up | Saldo bertambah setelah disetujui |
| 12 | Transaksi Kasir | Transaksi berhasil dicatat |
| 13 | Cetak Struk | Struk dapat dicetak |
| 14 | Laporan Penjualan | Data transaksi tampil |
| 15 | Log Aktivitas | Aktivitas pengguna tercatat |

Status pengujian akhir diisi berdasarkan hasil uji coba langsung pada aplikasi.

## 10. Keamanan Sistem

Beberapa mekanisme keamanan yang digunakan:
- Password disimpan menggunakan password hashing.
- Penggunaan prepared statement PDO untuk query database.
- Validasi input pengguna.
- Pembatasan akses berdasarkan role.
- Penggunaan session untuk autentikasi.
- Penggunaan token CSRF pada form yang memproses data.

## 11. Pengembangan Selanjutnya

Pengembangan yang dapat dilakukan:
- Integrasi payment gateway untuk pembayaran digital.
- Upload bukti pembayaran.
- Notifikasi pesanan dan top up.
- Filter laporan berdasarkan tanggal.
- Ekspor laporan ke PDF atau Excel.
- Penggunaan kode voucher saat checkout.
- Peningkatan keamanan dan validasi transaksi.

## 12. Penutup

TOKOKULARIS merupakan website toko online yang dirancang untuk membantu proses penjualan dan pengelolaan toko kebutuhan sehari-hari. Dengan adanya tiga hak akses, sistem pengelolaan produk, transaksi, saldo member, dan laporan penjualan, diharapkan aplikasi ini dapat membuat kegiatan operasional toko menjadi lebih teratur, praktis, dan efisien.

**TOKOKULARIS — Belanja Mudah, Kebutuhan Lengkap!**
