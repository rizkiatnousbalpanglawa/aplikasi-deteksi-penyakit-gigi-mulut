<?php
require_once __DIR__ . '/../config/helpers.php';
start_secure_session();
include "../config/koneksi.php";

if (isset($_POST['masuk'])) {
    csrf_check();

    $username = trim((string) ($_POST['username'] ?? ''));
    $katasandi = (string) ($_POST['pass'] ?? '');

    if ($username === '' || $katasandi === '') {
        echo "<script>alert('Username dan kata sandi wajib diisi');location='index.php'</script>";
        exit;
    }

    $stmt = $koneksi->prepare("SELECT username, kata_sandi FROM admin WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $cek = $stmt->get_result();

    if ($cek && $cek->num_rows === 1) {
        $row = $cek->fetch_assoc();
        $hash = (string) $row['kata_sandi'];
        $ok = false;

        if (str_starts_with($hash, '$2y$') || str_starts_with($hash, '$argon2')) {
            $ok = password_verify($katasandi, $hash);
            if ($ok && password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                $newHash = password_hash($katasandi, PASSWORD_DEFAULT);
                $upd = $koneksi->prepare("UPDATE admin SET kata_sandi = ? WHERE username = ?");
                $upd->bind_param('ss', $newHash, $username);
                $upd->execute();
                $upd->close();
            }
        } else {
            // Akun lama masih plaintext: verifikasi lalu migrasi ke hash.
            $ok = hash_equals($hash, $katasandi);
            if ($ok) {
                $newHash = password_hash($katasandi, PASSWORD_DEFAULT);
                $upd = $koneksi->prepare("UPDATE admin SET kata_sandi = ? WHERE username = ?");
                $upd->bind_param('ss', $newHash, $username);
                $upd->execute();
                $upd->close();
            }
        }

        if ($ok) {
            session_regenerate_id(true);
            $_SESSION['admin']['user'] = $row['username'];
            header("Location: ../admin/index.php");
            exit;
        }
    }

    if (isset($stmt)) {
        $stmt->close();
    }
    echo "<script>alert('Username dan katasandi tidak sesuai!');location='index.php'</script>";
    exit;
}
