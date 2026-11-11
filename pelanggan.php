<?php
require_once 'functions.php';require_role(['admin']);$rows=$pdo->query("SELECT p.*,u.username,s.saldo FROM tbl_pelanggan p LEFT JOIN users u ON u.id_user=p.id_user LEFT JOIN tbl_saldo s ON s.id_pelanggan=p.id_pelanggan ORDER BY p.id_pelanggan DESC")->fetchAll();layout_header('Data Pelanggan');
?>
<div class="page-title"><div><span class="eyebrow">DATA PENGGUNA TOKO</span><h1>Data pelanggan</h1></div></div><div class="table-wrap"><table><thead><tr><th>Nama</th><th>Kota</th><th>Username</th><th>Saldo</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=e($r['nama_pelanggan'])?></td><td><?=e($r['kota'])?></td><td><?=e($r['username']??'-')?></td><td><?=rupiah($r['saldo']??0)?></td></tr><?php endforeach;?></tbody></table></div>
<?php layout_footer(); ?>
