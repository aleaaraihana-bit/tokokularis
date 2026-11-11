
<?php
require_once 'functions.php';
require_role(['pelanggan']);

$pid = ensure_customer(
    $pdo,
    (int)user()['id_user'],
    user()['nama']
);

// Pastikan pelanggan memiliki data saldo
$s = $pdo->prepare("SELECT * FROM tbl_saldo WHERE id_pelanggan=? LIMIT 1");
$s->execute([$pid]);
$wallet = $s->fetch();

if (!$wallet) {
    $pdo->prepare("INSERT INTO tbl_saldo (id_pelanggan, saldo) VALUES (?, 0)")
        ->execute([$pid]);

    $s = $pdo->prepare("SELECT * FROM tbl_saldo WHERE id_pelanggan=? LIMIT 1");
    $s->execute([$pid]);
    $wallet = $s->fetch();
}

if (!$wallet) {
    exit('Data saldo pelanggan gagal dibuat.');
}

// Proses pengajuan top up
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $amount = (float)($_POST['jumlah'] ?? 0);

    if ($amount < 10000) {
        flash('Minimal top up Rp10.000.', 'error');
        go('saldo.php');
    }

    $kode = 'TOP-' . date('YmdHis') . '-' . random_int(100, 999);

    $insert = $pdo->prepare("
        INSERT INTO tbl_topup_saldo
        (id_pelanggan, id_saldo, kode_topup, jumlah, metode, status)
        VALUES (?, ?, ?, ?, 'simulasi', 'menunggu')
    ");

    $insert->execute([
        $pid,
        $wallet['id_saldo'],
        $kode,
        $amount
    ]);

    log_activity($pdo, 'Top Up Saldo', 'Mengajukan top up ' . rupiah($amount));

    flash('Permintaan top up berhasil dibuat. Menunggu persetujuan Admin.');
    go('saldo.php');
}

// Ambil riwayat saldo
$s = $pdo->prepare("
    SELECT * FROM tbl_riwayat_saldo
    WHERE id_saldo=?
    ORDER BY id_riwayat DESC
");

$s->execute([$wallet['id_saldo']]);
$rows = $s->fetchAll();

layout_header('Saldo Saya');
?>

<div class="page-title">
    <div>
        <span class="eyebrow">DOMPET TOKOKU</span>
        <h1>Saldo pelanggan</h1>
        <p>Saldo internal untuk simulasi pembayaran di TOKOKU.</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card accent">
        <span>Saldo tersedia</span>
        <strong><?=rupiah($wallet['saldo'])?></strong>
        <small>Saldo internal, bukan uang tunai.</small>
    </div>
</div>

<div class="panel" style="margin-top:20px">
    <h3>Ajukan top up saldo</h3>
    <p>Top up pada versi demo perlu disetujui Admin.</p>

    <form method="post">
        <input type="hidden" name="_csrf" value="<?=e(csrf())?>">

        <label>Nominal top up</label>
        <input
            type="number"
            name="jumlah"
            min="10000"
            step="1000"
            placeholder="Minimal Rp10.000"
            required
        >

        <button class="btn btn-primary" type="submit">
            Ajukan Top Up
        </button>
    </form>
</div>

<div class="section-head">
    <h2>Riwayat saldo</h2>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Saldo sebelum</th>
                <th>Saldo sesudah</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($rows): ?>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?=e($r['created_at'])?></td>
                        <td><?=e($r['jenis'])?></td>
                        <td><?=rupiah($r['jumlah'])?></td>
                        <td><?=rupiah($r['saldo_sebelum'])?></td>
                        <td><?=rupiah($r['saldo_sesudah'])?></td>
                        <td><?=e($r['keterangan'])?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center">
                        Belum ada riwayat saldo.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php layout_footer(); ?>
