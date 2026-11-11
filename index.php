<?php
require_once 'functions.php';
if(user() && role()!=='pelanggan') go('dashboard.php');
$q=trim($_GET['q']??'');$cat=(int)($_GET['kategori']??0);
$sql="SELECT p.*,k.nama_kategori FROM tbl_produk p JOIN tbl_kategori k ON k.id_kategori=p.id_kategori WHERE p.status='aktif'";
$args=[];
if($q!==''){$sql.=" AND (p.nama_produk LIKE ? OR p.kode_produk LIKE ?)";$args[]="%$q%";$args[]="%$q%";}
if($cat){$sql.=" AND (p.id_kategori=? OR p.id_kategori IN (SELECT id_kategori FROM tbl_kategori WHERE id_parent=?))";$args[]=$cat;$args[]=$cat;}
$sql.=" ORDER BY p.created_at DESC";
$s=$pdo->prepare($sql);$s->execute($args);$products=$s->fetchAll();
$cats=$pdo->query("SELECT * FROM tbl_kategori WHERE id_parent IS NULL ORDER BY nama_kategori")->fetchAll();
layout_header('Belanja');
?>
<section class="hero"><div><span class="eyebrow">SELAMAT DATANG DI TOKOKU</span><h1>Semua kebutuhanmu,<br><em>ada di sini.</em></h1><p>Temukan makanan, minuman, alat tulis, kebutuhan rumah, jasa, dan produk digital dalam satu tempat.</p><a class="btn btn-light" href="#produk">Mulai Belanja ↗</a></div><div class="hero-art"><div class="hero-circle">T</div><span class="float-card">✦ Belanja praktis</span></div></section>
<div class="section-head" id="produk"><div><span class="eyebrow">PILIH SESUAI KEBUTUHAN</span><h2>Kategori pilihan</h2></div></div>
<div class="category-pills"><a class="<?=!$cat?'active':''?>" href="index.php">Semua</a><?php foreach($cats as $c):?><a class="<?=$cat==(int)$c['id_kategori']?'active':''?>" href="?kategori=<?=$c['id_kategori']?>"><?=e($c['nama_kategori'])?></a><?php endforeach;?></div>
<div class="section-head"><div><span class="eyebrow">TOKO ONLINE</span><h2>Produk untukmu</h2></div><form class="search-form" method="get"><input name="q" value="<?=e($q)?>" placeholder="Cari produk..."><button class="btn btn-primary">Cari</button></form></div>
<div class="product-grid"><?php foreach($products as $p):?><article class="product-card"><a href="detail_produk.php?id=<?=$p['id_produk']?>" class="product-photo"><img src="<?=e(product_image($p['foto']))?>" alt="<?=e($p['nama_produk'])?>"></a><div class="product-info"><span class="tag"><?=e($p['nama_kategori'])?></span><h3><?=e($p['nama_produk'])?></h3><p><?=e(mb_strimwidth((string)$p['deskripsi'],0,75,'…'))?></p><strong><?=rupiah($p['harga'])?></strong><small>Stok: <?=$p['stok']?> <?=e($p['satuan'])?></small><a class="btn btn-primary full" href="detail_produk.php?id=<?=$p['id_produk']?>">Lihat Produk</a></div></article><?php endforeach;?></div>
<?php if(!$products):?><div class="empty">Produk belum tersedia. Admin dapat menambahkan produk melalui dashboard.</div><?php endif;?>
<?php layout_footer(); ?>
