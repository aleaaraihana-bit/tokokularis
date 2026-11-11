<?php
require_once 'functions.php';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){check_csrf();$nama=trim($_POST['nama']??'');$username=trim($_POST['username']??'');$password=$_POST['password']??'';$kota=trim($_POST['kota']??'-');
 if(strlen($password)<6)$error='Password minimal 6 karakter.';
 else {try{$pdo->beginTransaction();$s=$pdo->prepare("INSERT INTO users(nama,username,password,role) VALUES(?,?,?,'pelanggan')");$s->execute([$nama,$username,password_hash($password,PASSWORD_DEFAULT)]);$uid=(int)$pdo->lastInsertId();$pdo->prepare("INSERT INTO tbl_pelanggan(id_user,nama_pelanggan,kota) VALUES(?,?,?)")->execute([$uid,$nama,$kota]);$pid=(int)$pdo->lastInsertId();$pdo->prepare("INSERT INTO tbl_saldo(id_pelanggan,saldo) VALUES(?,0)")->execute([$pid]);$pdo->commit();flash('Pendaftaran berhasil. Silakan masuk.');go('login.php');}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$error='Pendaftaran gagal. Username mungkin sudah digunakan.';}}
}
layout_header('Daftar');
?>
<div class="auth-wrap"><section class="panel auth-card"><span class="eyebrow">GABUNG TOKOKU</span><h1>Buat akun pelanggan</h1><?php if($error):?><div class="flash flash-error"><?=e($error)?></div><?php endif;?><form method="post"><input type="hidden" name="_csrf" value="<?=e(csrf())?>"><label>Nama lengkap</label><input name="nama" required><label>Username</label><input name="username" required><label>Kota</label><input name="kota" placeholder="Contoh: Ponorogo"><label>Password</label><input type="password" name="password" minlength="6" required><button class="btn btn-primary full">Daftar</button></form><p class="small muted">Sudah punya akun? <a href="login.php">Masuk</a></p></section></div>
<?php layout_footer(); ?>
