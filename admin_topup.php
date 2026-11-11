
<?php
require_once 'functions.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $id = (int)($_POST['id_topup'] ?? 0);
    $aksi = $_POST['aksi'] ?? '';

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM tbl_topup_saldo WHERE id_topup=? AND status='menunggu' FOR UPDATE");
        $stmt->execute([$id]);
        $topup = $stmt->fetch();

        if (!$topup) {
            throw new Exception('Pengajuan tidak ditemukan atau sudah diproses.');
        }

        if ($aksi === 'setujui') {
            $stmt = $pdo->prepare("SELECT * FROM tbl_saldo WHERE id_pelanggan=? FOR UPDATE");
            $stmt->execute([$topup['id_pelanggan']]);
            $wallet = $stmt->fetch();

            if (!$wallet) {
                throw new Exception('Saldo pelanggan tidak ditemukan.');
            }

            $sebelum = (float)$wallet['saldo'];
            $jumlah = (float)$topup['jumlah'];
            $sesudah = $sebelum + $jumlah;

            $stmt = $pdo->prepare("UPDATE tbl_saldo SET saldo=? WHERE id_saldo=?");
            $stmt->execute([$sesudah, $wallet['id_saldo']]);

            $stmt = $pdo->prepare("INSERT INTO tbl_riwayat_saldo (id_saldo,jenis,jumlah,saldo_sebelum,saldo_sesudah,keterangan) VALUES (?,'topup',?,?,?,?)");
            $stmt->execute([
                $wallet['id_saldo'],
                $jumlah,
                $sebelum,
                $sesudah,
                'Top up disetujui: '.$topup['kode_topup']
            ]);

            $stmt = $pdo->prepare("UPDATE tbl_topup_saldo SET id_saldo=?,status='berhasil',tanggal_diproses=NOW(),keterangan='Disetujui Admin' WHERE id_topup=?");
            $stmt->execute([$wallet['id_saldo'], $id]);

            log_activity($pdo, 'Persetujuan Top Up', $topup['kode_topup']);
            $pesan = 'Top up berhasil disetujui. Saldo pelanggan bertambah.';

        } elseif ($aksi === 'tolak') {
            $stmt = $pdo->prepare("UPDATE tbl_topup_saldo SET status='ditolak',tanggal_diproses=NOW(),keterangan='Ditolak Admin' WHERE id_topup=?");
            $stmt->execute([$id]);

            log_activity($pdo, 'Penolakan Top Up', $topup['kode_topup']);
            $pesan = 'Pengajuan top up berhasil ditolak.';

        } else {
            throw new Exception('Aksi tidak valid.');
        }

        $pdo->commit();
        flash($pesan);

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        flash('Gagal memproses: '.$e->getMessage(), 'error');
    }

    go('admin_topup.php');
}

$rows = $pdo->query("
    SELECT t.*, p.nama_pelanggan
    FROM tbl_topup_saldo t
    LEFT JOIN tbl_pelanggan p ON p.id_pelanggan=t.id_pelanggan
    ORDER BY t.id_topup DESC
")->fetchAll();

$menunggu = 0;
$berhasil = 0;
$ditolak = 0;

foreach ($rows as $r) {
    if ($r['status'] === 'menunggu') $menunggu++;
    if ($r['status'] === 'berhasil') $berhasil++;
    if ($r['status'] === 'ditolak') $ditolak++;
}

layout_header('Top Up Saldo');
?>

<div class="page-title">
    <div>
        <span class="eyebrow">PENGELOLAAN SALDO</span>
        <h1>Pengajuan Top Up</h1>
        <p>Kelola permintaan penambahan saldo pelanggan.</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card accent">
        <span>Menunggu</span>
        <strong><?=$menunggu?></strong>
    </div>
    <div class="stat-card">
        <span>Berhasil</span>
        <strong><?=$berhasil?></strong>
    </div>
    <div class="stat-card">
        <span>Ditolak</span>
        <strong><?=$ditolak?></strong>
    </div>
</div>

<div class="section-head">
    <h2>Daftar Pengajuan</h2>
</div>

<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Kode Top Up</th>
            <th>Pelanggan</th>
            <th>Tanggal</th>
            <th>Nominal</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($rows): ?>
        <?php foreach ($rows as $r): ?>
        <tr>
            <td><?=e($r['kode_topup'])?></td>
            <td><?=e($r['nama_pelanggan'] ?? '-')?></td>
            <td><?=e($r['tanggal_topup'])?></td>
            <td><?=rupiah($r['jumlah'])?></td>
            <td><?=e($r['metode'])?></td>
            <td><?=e(ucfirst($r['status']))?></td>
            <td>
                <?php if ($r['status'] === 'menunggu'): ?>
                <form method="post" style="display:inline" onsubmit="return confirm('Setujui top up ini?')">
                    <input type="hidden" name="_csrf" value="<?=e(csrf())?>">
                    <input type="hidden" name="id_topup" value="<?=$r['id_topup']?>">
                    <input type="hidden" name="aksi" value="setujui">
                    <button class="btn btn-primary btn-sm">Setujui</button>
                </form>

                <form method="post" style="display:inline" onsubmit="return confirm('Tolak top up ini?')">
                    <input type="hidden" name="_csrf" value="<?=e(csrf())?>">
                    <input type="hidden" name="id_topup" value="<?=$r['id_topup']?>">
                    <input type="hidden" name="aksi" value="tolak">
                    <button class="btn btn-outline btn-sm">Tolak</button>
                </form>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" style="text-align:center">Belum ada pengajuan top up.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</div>

<?php layout_footer(); ?>
