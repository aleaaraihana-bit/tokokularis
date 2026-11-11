<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/config/koneksi.php';

try {
    $akun = [
        ['Administrator', 'admin', 'admin123', 'admin'],
        ['Kasir TOKOKU', 'kasir', 'kasir123', 'kasir'],
        ['Pelanggan TOKOKU', 'pelanggan', 'pelanggan123', 'pelanggan']
    ];

    $cek = $pdo->prepare(
        "SELECT id_user FROM users WHERE username = ? LIMIT 1"
    );

    $tambah = $pdo->prepare(
        "INSERT INTO users (nama, username, password, role)
         VALUES (?, ?, ?, ?)"
    );

    foreach ($akun as $data) {
        $cek->execute([$data[1]]);

        if (!$cek->fetch()) {
            $tambah->execute([
                $data[0],
                $data[1],
                password_hash($data[2], PASSWORD_DEFAULT),
                $data[3]
            ]);

            echo "Berhasil membuat akun: " . htmlspecialchars($data[1]) . "<br>";
        } else {
            echo "Akun sudah tersedia: " . htmlspecialchars($data[1]) . "<br>";
        }
    }

    echo "<h3>Proses pembuatan akun selesai.</h3>";

} catch (Throwable $e) {
    echo "ERROR: " . htmlspecialchars($e->getMessage());
}
