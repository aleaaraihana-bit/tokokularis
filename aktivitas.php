<?php
require_once 'functions.php';require_role(['admin']);$rows=$pdo->query("SELECT l.*,u.nama FROM tbl_log_aktivitas l LEFT JOIN users u ON u.id_user=l.id_user ORDER BY l.id_log DESC LIMIT 300")->fetchAll();layout_header('Log Aktivitas');
?>
<div class="page-title"><div><span class="eyebrow">AUDIT SISTEM</span><h1>Log aktivitas</h1><p>Catatan aktivitas pengguna TOKOKU.</p></div></div><div class="table-wrap"><table><thead><tr><th>Waktu</th><th>Pengguna</th><th>Aktivitas</th><th>Keterangan</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=e($r['created_at'])?></td><td><?=e($r['nama']??'Sistem')?></td><td><?=e($r['aktivitas'])?></td><td><?=e($r['keterangan'])?></td></tr><?php endforeach;?></tbody></table></div>
<?php layout_footer(); ?>
