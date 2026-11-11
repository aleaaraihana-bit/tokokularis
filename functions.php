
<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once __DIR__.'/config/koneksi.php';

function e($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function rupiah($v): string {
    return 'Rp'.number_format((float)$v, 0, ',', '.');
}

function go(string $url): never {
    header("Location: $url");
    exit;
}

function user(): ?array {
    return $_SESSION['user'] ?? null;
}

function role(): string {
    return user()['role'] ?? '';
}

function require_login(): void {
    if (!user()) go('login.php');
}

function require_role(array $roles): void {
    require_login();
    if (!in_array(role(), $roles, true)) {
        http_response_code(403);
        exit('Akses ditolak.');
    }
}

function flash(string $message, string $type='success'): void {
    $_SESSION['flash'] = ['message'=>$message, 'type'=>$type];
}

function csrf(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function check_csrf(): void {
    if (!hash_equals(csrf(), $_POST['_csrf'] ?? '')) {
        http_response_code(419);
        exit('Token formulir tidak valid. Muat ulang halaman.');
    }
}

function log_activity(PDO $pdo, string $activity, string $detail=''): void {
    try {
        $st = $pdo->prepare("INSERT INTO tbl_log_aktivitas (id_user,aktivitas,keterangan) VALUES (?,?,?)");
        $st->execute([user()['id_user'] ?? null, $activity, $detail]);
    } catch (Throwable $e) {}
}

function money_input($v): float {
    return max(0,(float)str_replace(['. ','.',','],['','','.'],(string)$v));
}

function active_cart(PDO $pdo, int $uid): int {
    $s = $pdo->prepare("SELECT id_keranjang FROM tbl_keranjang WHERE id_user=? AND status='aktif' ORDER BY id_keranjang DESC LIMIT 1");
    $s->execute([$uid]);
    $id = $s->fetchColumn();

    if ($id) return (int)$id;

    $pdo->prepare("INSERT INTO tbl_keranjang(id_user,status) VALUES(?,'aktif')")->execute([$uid]);
    return (int)$pdo->lastInsertId();
}

function cart_count(PDO $pdo): int {
    if (!user()) return 0;

    $s = $pdo->prepare("SELECT COALESCE(SUM(d.jumlah),0) FROM tbl_detail_keranjang d JOIN tbl_keranjang k ON k.id_keranjang=d.id_keranjang WHERE k.id_user=? AND k.status='aktif'");
    $s->execute([user()['id_user']]);

    return (int)$s->fetchColumn();
}

function customer_id(PDO $pdo, int $uid): ?int {
    $s = $pdo->prepare("SELECT id_pelanggan FROM tbl_pelanggan WHERE id_user=? LIMIT 1");
    $s->execute([$uid]);
    $id = $s->fetchColumn();

    return $id ? (int)$id : null;
}

function ensure_customer(PDO $pdo, int $uid, string $name): int {
    $id = customer_id($pdo, $uid);
    if ($id) return $id;

    $pdo->prepare("INSERT INTO tbl_pelanggan(id_user,nama_pelanggan,kota) VALUES(?,?,?)")->execute([$uid,$name,'-']);
    $id = (int)$pdo->lastInsertId();

    $pdo->prepare("INSERT IGNORE INTO tbl_saldo(id_pelanggan,saldo) VALUES(?,0)")->execute([$id]);

    return $id;
}

function layout_header(string $title='TOKOKU'): void {
    global $pdo;

    $u = user();
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    if (!$u) {
        $home = 'index.php';
    } elseif (role() === 'admin') {
        $home = 'dashboard.php';
    } elseif (role() === 'kasir') {
        $home = 'kasir_pesanan.php';
    } else {
        $home = 'index.php';
    }
    ?>
    <!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?=e($title)?> | TOKOKU</title>
        <link rel="stylesheet" href="style.css">
        <script defer src="script.js"></script>
    </head>
    <body>

    <header class="topbar">
        <a class="brand" href="<?=e($home)?>">
            <span class="brand-mark">T</span>
            <span>TOKOKU<small>Semua kebutuhan, satu toko</small></span>
        </a>

        <nav class="top-actions">
            <?php if ($u): ?>
                <span class="hello">Halo, <?=e($u['nama'])?></span>

                <?php if (role() === 'pelanggan'): ?>
                    <a class="nav-link" href="keranjang.php">Keranjang <b><?=cart_count($pdo)?></b></a>
                <?php endif; ?>

                <a class="nav-link" href="<?=e($home)?>">Dashboard</a>
                <a class="btn btn-outline btn-sm" href="logout.php">Keluar</a>
            <?php else: ?>
                <a class="nav-link" href="index.php">Beranda</a>
                <a class="btn btn-primary btn-sm" href="login.php">Masuk</a>
            <?php endif; ?>
        </nav>
    </header>

    <?php if ($f): ?>
        <div class="flash <?=$f['type']==='error'?'flash-error':''?>">
            <?=e($f['message'])?>
        </div>
    <?php endif; ?>

    <div class="app-shell">
        <?php if ($u): ?>
            <aside class="sidebar">
                <div class="side-label">MENU UTAMA</div>
                <a href="<?=e($home)?>">⌂ <span>Dashboard</span></a>

                <?php if (role() === 'admin'): ?>
                    <div class="side-label">PENGELOLAAN</div>
                    <a href="pengguna.php">♙ <span>Pengguna</span></a>
                    <a href="pelanggan.php">♡ <span>Pelanggan</span></a>
                    <a href="kategori.php">▦ <span>Kategori</span></a>
                    <a href="produk.php">▣ <span>Produk & Stok</span></a>
                    <a href="diskon.php">％ <span>Diskon & Voucher</span></a>
                    <a href="admin_topup.php">◉ <span>Top Up Saldo</span></a>
                    <a href="pesanan.php">▤ <span>Pesanan</span></a>
                    <a href="laporan.php">▥ <span>Laporan</span></a>
                    <a href="aktivitas.php">◷ <span>Log Aktivitas</span></a>

                <?php elseif (role() === 'kasir'): ?>
                    <div class="side-label">OPERASIONAL</div>
                    <a href="kasir_pesanan.php">▤ <span>Pesanan Masuk</span></a>
                    <a href="kasir_pos.php">▣ <span>Kasir / POS</span></a>
                    <a href="kasir_riwayat.php">◷ <span>Riwayat Transaksi</span></a>

                <?php else: ?>
                    <div class="side-label">BELANJA</div>
                    <a href="index.php">▦ <span>Belanja Produk</span></a>
                    <a href="keranjang.php">🛒 <span>Keranjang</span></a>
                    <a href="pesanan_saya.php">▤ <span>Pesanan Saya</span></a>
                    <a href="saldo.php">◉ <span>Saldo Saya</span></a>
                    <a href="favorit.php">♡ <span>Favorit</span></a>
                    <a href="profil.php">♙ <span>Profil</span></a>
                <?php endif; ?>
            </aside>
        <?php endif; ?>

        <main class="main-content">
    <?php
}

function layout_footer(): void {
    ?>
        </main>
    </div>
    <footer class="footer">© <?=date('Y')?> TOKOKU · Belanja mudah, transaksi nyaman.</footer>
    </body>
    </html>
    <?php
}

function product_image(?string $path): string {
    return $path && is_file(__DIR__.'/'.$path) ? $path : 'assets/img/placeholder.svg';
}
