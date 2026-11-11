
<?php
require_once 'functions.php';

// Jika sudah login, arahkan sesuai role
if (user()) {
    if (role() === 'admin') {
        go('dashboard.php');
    } elseif (role() === 'kasir') {
        go('kasir_pesanan.php');
    } else {
        go('index.php');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';

    $s = $pdo->prepare(
        "SELECT * FROM users WHERE username = ? LIMIT 1"
    );

    $s->execute([$u]);
    $row = $s->fetch();

    if ($row && password_verify($p, $row['password'])) {

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id_user' => $row['id_user'],
            'nama' => $row['nama'],
            'username' => $row['username'],
            'role' => $row['role']
        ];

        log_activity($pdo, 'Login', 'Masuk ke sistem');

        // Pengalihan berdasarkan role
        switch ($row['role']) {
            case 'admin':
                go('dashboard.php');
                break;

            case 'kasir':
                go('kasir_pesanan.php');
                break;

            case 'pelanggan':
            case 'user':
                go('index.php');
                break;

            default:
                $_SESSION = [];
                session_destroy();
                $error = 'Role akun tidak dikenali.';
        }

    } else {
        $error = 'Username atau password tidak sesuai.';
    }
}

layout_header('Masuk');
?>

<div class="auth-wrap">
    <section class="panel auth-card">

        <a class="brand auth-brand" href="index.php">
            <span class="brand-mark">T</span>
            <span>
                TOKOKU
                <small>Belanja nyaman setiap hari</small>
            </span>
        </a>

        <span class="eyebrow">SELAMAT DATANG KEMBALI</span>

        <h1>Masuk ke akunmu</h1>

        <p class="muted">
            Gunakan akun Admin, Kasir, atau Pelanggan.
        </p>

        <?php if ($error): ?>
            <div class="flash flash-error">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="_csrf" value="<?= e(csrf()) ?>">

            <label>Username</label>
            <input
                name="username"
                required
                autofocus
                placeholder="Masukkan username"
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
                required
                placeholder="Masukkan password"
            >

            <button class="btn btn-primary full">
                Masuk
            </button>
        </form>

        <p class="muted small">
            Belum punya akun pelanggan?
            <a href="register.php">Daftar sekarang</a>
        </p>

    </section>
</div>

<?php layout_footer(); ?>
