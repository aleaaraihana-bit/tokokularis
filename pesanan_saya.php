<?php
require_once 'functions.php';require_role(['pelanggan']);$pid=customer_id($pdo,(int)user()['id_user']);$rows=[];if($pid){$s=$pdo->prepare("SELECT * FROM tbl_transaksi WHERE id_pelanggan=? ORDER BY id_transaksi DESC");$s->execute([$pid]);$rows=$s->fetchAll();}layout_header('Pesanan Saya');
?>
<div class="page-title"><div><span class="eyebrow">AKTIVITAS BELANJA</span><h1>Pesanan saya</h1></div></div><div class="table-wrap"><table><thead><tr><th>Nomor pesanan</th><th>Tanggal</th><th>Total</th><th>Status</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=e($r['nomor_transaksi'])?></td><td><?=e($r['tanggal'])?></td><td><?=rupiah($r['total_bayar'])?></td><td><span class="status"><?=e($r['status'])?></span></td></tr><?php endforeach;?></tbody></table></div>
<?php layout_footer(); ?>
