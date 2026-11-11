<?php
require_once __DIR__.'/functions.php';
$messages=[];
$sql=[
"CREATE TABLE IF NOT EXISTS tbl_foto_produk (id_foto INT AUTO_INCREMENT PRIMARY KEY,id_produk INT NOT NULL,nama_file VARCHAR(255) NOT NULL,utama TINYINT(1) DEFAULT 0,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(id_produk) REFERENCES tbl_produk(id_produk) ON DELETE CASCADE) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_varian_produk (id_varian INT AUTO_INCREMENT PRIMARY KEY,id_produk INT NOT NULL,nama_varian VARCHAR(100) NOT NULL,nilai_varian VARCHAR(100) NOT NULL,tambahan_harga DECIMAL(12,2) DEFAULT 0,stok INT DEFAULT 0,FOREIGN KEY(id_produk) REFERENCES tbl_produk(id_produk) ON DELETE CASCADE) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_favorit (id_favorit INT AUTO_INCREMENT PRIMARY KEY,id_user INT NOT NULL,id_produk INT NOT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,UNIQUE KEY uq_fav(id_user,id_produk),FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE,FOREIGN KEY(id_produk) REFERENCES tbl_produk(id_produk) ON DELETE CASCADE) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_alamat (id_alamat INT AUTO_INCREMENT PRIMARY KEY,id_user INT NOT NULL,label VARCHAR(50) DEFAULT 'Rumah',alamat TEXT NOT NULL,kota VARCHAR(100),kode_pos VARCHAR(10),utama TINYINT(1) DEFAULT 0,FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_ulasan_produk (id_ulasan INT AUTO_INCREMENT PRIMARY KEY,id_user INT NOT NULL,id_produk INT NOT NULL,rating TINYINT NOT NULL,ulasan TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE,FOREIGN KEY(id_produk) REFERENCES tbl_produk(id_produk) ON DELETE CASCADE) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_notifikasi (id_notifikasi INT AUTO_INCREMENT PRIMARY KEY,id_user INT NOT NULL,judul VARCHAR(150) NOT NULL,pesan TEXT,dibaca TINYINT(1) DEFAULT 0,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_riwayat_stok (id_riwayat INT AUTO_INCREMENT PRIMARY KEY,id_produk INT NOT NULL,jenis ENUM('masuk','keluar','penyesuaian') NOT NULL,jumlah INT NOT NULL,stok_sebelum INT NOT NULL,stok_sesudah INT NOT NULL,keterangan VARCHAR(255),id_user INT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(id_produk) REFERENCES tbl_produk(id_produk),FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE SET NULL) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_topup_saldo (id_topup INT AUTO_INCREMENT PRIMARY KEY,id_saldo INT NOT NULL,kode_topup VARCHAR(50) UNIQUE NOT NULL,jumlah DECIMAL(14,2) NOT NULL,metode VARCHAR(40) DEFAULT 'tunai',status ENUM('menunggu','disetujui','ditolak') DEFAULT 'menunggu',catatan TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(id_saldo) REFERENCES tbl_saldo(id_saldo)) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_shift_kasir (id_shift INT AUTO_INCREMENT PRIMARY KEY,id_user INT NOT NULL,modal_awal DECIMAL(12,2) DEFAULT 0,total_tunai DECIMAL(12,2) DEFAULT 0,status ENUM('buka','tutup') DEFAULT 'buka',mulai TIMESTAMP DEFAULT CURRENT_TIMESTAMP,selesai TIMESTAMP NULL,FOREIGN KEY(id_user) REFERENCES users(id_user)) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_pengeluaran (id_pengeluaran INT AUTO_INCREMENT PRIMARY KEY,tanggal DATE NOT NULL,kategori VARCHAR(100) NOT NULL,keterangan TEXT,jumlah DECIMAL(12,2) NOT NULL,id_user INT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE SET NULL) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_pemasok (id_pemasok INT AUTO_INCREMENT PRIMARY KEY,nama_pemasok VARCHAR(150) NOT NULL,no_telepon VARCHAR(30),alamat TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_pembelian_stok (id_pembelian INT AUTO_INCREMENT PRIMARY KEY,id_pemasok INT NULL,nomor_pembelian VARCHAR(50) UNIQUE,tanggal DATE NOT NULL,total DECIMAL(12,2) DEFAULT 0,status ENUM('draft','diterima') DEFAULT 'draft',id_user INT NULL,FOREIGN KEY(id_pemasok) REFERENCES tbl_pemasok(id_pemasok) ON DELETE SET NULL,FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE SET NULL) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_detail_pembelian (id_detail_pembelian INT AUTO_INCREMENT PRIMARY KEY,id_pembelian INT NOT NULL,id_produk INT NOT NULL,jumlah INT NOT NULL,harga_beli DECIMAL(12,2) NOT NULL,subtotal DECIMAL(12,2) NOT NULL,FOREIGN KEY(id_pembelian) REFERENCES tbl_pembelian_stok(id_pembelian) ON DELETE CASCADE,FOREIGN KEY(id_produk) REFERENCES tbl_produk(id_produk)) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_struk (id_struk INT AUTO_INCREMENT PRIMARY KEY,id_transaksi INT NOT NULL UNIQUE,nomor_struk VARCHAR(60) NOT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(id_transaksi) REFERENCES tbl_transaksi(id_transaksi) ON DELETE CASCADE) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS tbl_pengembalian_dana (id_refund INT AUTO_INCREMENT PRIMARY KEY,id_transaksi INT NOT NULL,jumlah DECIMAL(12,2) NOT NULL,alasan TEXT,status ENUM('menunggu','selesai','ditolak') DEFAULT 'menunggu',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(id_transaksi) REFERENCES tbl_transaksi(id_transaksi)) ENGINE=InnoDB"
];
foreach($sql as $q){try{$pdo->exec($q);}catch(Throwable $e){$messages[]=$e->getMessage();}}
try {
  $pdo->exec("ALTER TABLE tbl_pelanggan ADD COLUMN IF NOT EXISTS id_user INT NULL");
  $pdo->exec("ALTER TABLE tbl_transaksi ADD COLUMN IF NOT EXISTS id_user INT NULL");
  $pdo->exec("ALTER TABLE tbl_transaksi ADD COLUMN IF NOT EXISTS nomor_transaksi VARCHAR(50) NULL");
  $pdo->exec("ALTER TABLE tbl_transaksi ADD COLUMN IF NOT EXISTS subtotal DECIMAL(12,2) DEFAULT 0");
  $pdo->exec("ALTER TABLE tbl_transaksi ADD COLUMN IF NOT EXISTS diskon DECIMAL(12,2) DEFAULT 0");
  $pdo->exec("ALTER TABLE tbl_transaksi ADD COLUMN IF NOT EXISTS total_bayar DECIMAL(12,2) DEFAULT 0");
  $pdo->exec("ALTER TABLE tbl_transaksi ADD COLUMN IF NOT EXISTS status ENUM('menunggu','diproses','selesai','dibatalkan') DEFAULT 'menunggu'");
  $pdo->exec("ALTER TABLE tbl_transaksi ADD COLUMN IF NOT EXISTS catatan TEXT");
} catch(Throwable $e){$messages[]=$e->getMessage();}
try {
  $pdo->exec("INSERT IGNORE INTO tbl_kategori(nama_kategori,id_parent,deskripsi) VALUES
  ('Makanan',NULL,'Aneka makanan'),('Minuman',NULL,'Aneka minuman'),('Alat Tulis',NULL,'Perlengkapan sekolah'),('Kebutuhan Rumah',NULL,'Kebutuhan rumah tangga'),('Perabotan',NULL,'Perabot rumah'),('Elektronik',NULL,'Perangkat elektronik'),('Fashion',NULL,'Pakaian dan aksesori'),('Jasa',NULL,'Layanan jasa'),('Top Up',NULL,'Produk digital')");
} catch(Throwable $e){$messages[]=$e->getMessage();}
if($_SERVER['REQUEST_METHOD']==='POST'){
 check_csrf();
 $name=trim($_POST['nama']??'Administrator');$username=trim($_POST['username']??'admin');$password=$_POST['password']??'admin123';
 $s=$pdo->prepare("SELECT id_user FROM users WHERE username=?");$s->execute([$username]);
 if(!$s->fetch()){$pdo->prepare("INSERT INTO users(nama,username,password,role) VALUES(?,?,?,'admin')")->execute([$name,$username,password_hash($password,PASSWORD_DEFAULT)]);}
 $messages[]="Akun admin diperiksa/dibuat. Username: ".$username;
}
layout_header('Setup TOKOKU');
?>
<div class="auth-wrap"><section class="panel auth-card"><span class="eyebrow">INSTALASI AWAL</span><h1>Siapkan TOKOKU</h1><p>Jalankan setup sekali untuk menyiapkan tabel tambahan dan akun admin.</p>
<?php if($messages): ?><div class="notice"><?=e(implode(' | ',$messages))?></div><?php endif;?>
<form method="post"><input type="hidden" name="_csrf" value="<?=e(csrf())?>"><label>Nama Admin</label><input name="nama" value="Administrator" required><label>Username Admin</label><input name="username" value="admin" required><label>Password Admin</label><input type="password" name="password" value="admin123" required><button class="btn btn-primary full">Buat / Periksa Akun Admin</button></form>
<p class="muted small">Setelah berhasil, hapus file setup.php. Ganti password admin setelah login.</p><a href="login.php">Ke halaman login</a></section></div>
<?php layout_footer(); ?>
