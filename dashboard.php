
<?php
require_once 'functions.php';

// Khusus Admin
require_role(['admin']);

// Statistik toko
$stats = [
    'produk' => (int)$pdo->query(
        "SELECT COUNT(*) FROM tbl_produk"
    )->fetchColumn(),

    'pelanggan' => (int)$pdo->query(
        "SELECT COUNT(*) FROM tbl_pelanggan"
    )->fetchColumn(),

    'transaksi' => (int)$pdo->query(
        "SELECT COUNT(*) FROM tbl_transaksi"
    )->fetchColumn(),

    'pendapatan' => (float)$pdo->query(
        "SELECT COALESCE(SUM(total_bayar), 0)
         FROM tbl_transaksi
         WHERE status = 'selesai'"
    )->fetchColumn()
];

// Transaksi terbaru
$recent = $pdo->query("
    SELECT
        t.*,
        p.nama_pelanggan,
        u.nama AS kasir
    FROM tbl_transaksi t
    LEFT JOIN tbl_pelanggan p
        ON p.id_pelanggan = t.id_pelanggan
    LEFT JOIN users u
        ON u.id_user = t.id_user
    ORDER BY t.id_transaksi DESC
    LIMIT 8
")->fetchAll();

layout_header('Dashboard Admin');
?>

<div class="page-title">
    <div>
        <span class="eyebrow">RINGKASAN TOKO</span>
        <h1>Dashboard Admin</h1>
        <p>Selamat datang, <?= e(user()['nama']) ?>. Ini ringkasan TOKOKU hari ini.</p>
    </div>

    <a class="btn btn-primary" href="produk.php">
        ＋ Tambah Produk
    </a>
</div>

<div class="stats-grid">

    <div class="stat-card">
        <span>Produk</span>
        <strong><?= $stats['produk'] ?></strong>
        <small>Produk di katalog</small>
    </div>

    <div class="stat-card">
        <span>Pelanggan</span>
        <strong><?= $stats['pelanggan'] ?></strong>
        <small>Akun pelanggan terdaftar</small>
    </div>

    <div class="stat-card">
        <span>Transaksi</span>
        <strong><?= $stats['transaksi'] ?></strong>
        <small>Seluruh transaksi</small>
    </div>

    <div class="stat-card accent">
        <span>Pendapatan selesai</span>
        <strong><?= rupiah($stats['pendapatan']) ?></strong>
        <small>Akumulasi transaksi selesai</small>
    </div>

</div>

<div class="section-head">
    <div>
        <span class="eyebrow">AKTIVITAS TERBARU</span>
        <h2>Transaksi terbaru</h2>
    </div>

    <a href="laporan.php" class="text-link">
        Lihat laporan →
    </a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Kasir</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($recent)): ?>
                <tr>
                    <td colspan="6" style="text-align:center">
                        Belum ada transaksi.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($recent as $r): ?>
                    <tr>
                        <td>
                            <?= e($r['nomor_transaksi'] ?: 'TRX-'.$r['id_transaksi']) ?>
                        </td>

                        <td><?= e($r['tanggal'] ?? '-') ?></td>

                        <td><?= e($r['nama_pelanggan'] ?? '-') ?></td>

                        <td><?= e($r['kasir'] ?? '-') ?></td>

                        <td>
                            <?= rupiah($r['total_bayar'] ?: $r['total_belanja']) ?>
                        </td>

                        <td>
                            <span class="status">
                                <?= e($r['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php layout_footer(); ?>
