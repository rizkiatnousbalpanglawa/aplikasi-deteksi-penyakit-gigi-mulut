<?php
require_once __DIR__ . '/../config/bootstrap.php';

if (isset($_POST['login'])) {
    csrf_check();

    $nama = trim((string)($_POST['nama'] ?? ''));
    $sandi = (string)($_POST['katasandi'] ?? '');

    if ($nama === '' || $sandi === '') {
        redirect('login.php', 'Nama dan kata sandi wajib diisi', 'warning');
    }

    $stmt = $koneksi->prepare("SELECT id_user, nama, kata_sandi FROM user WHERE nama = ? LIMIT 1");
    $stmt->bind_param('s', $nama);
    $stmt->execute();
    $cek = $stmt->get_result();

    if ($cek && $cek->num_rows === 1) {
        $set = $cek->fetch_assoc();
        $hash = (string)$set['kata_sandi'];
        $ok = false;

        if (str_starts_with($hash, '$2y$') || str_starts_with($hash, '$argon2')) {
            $ok = password_verify($sandi, $hash);
            if ($ok && password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                $newHash = password_hash($sandi, PASSWORD_DEFAULT);
                $upd = $koneksi->prepare("UPDATE user SET kata_sandi = ? WHERE id_user = ?");
                $upd->bind_param('si', $newHash, $set['id_user']);
                $upd->execute();
                $upd->close();
            }
        } else {
            $ok = hash_equals($hash, $sandi);
            if ($ok) {
                $newHash = password_hash($sandi, PASSWORD_DEFAULT);
                $upd = $koneksi->prepare("UPDATE user SET kata_sandi = ? WHERE id_user = ?");
                $upd->bind_param('si', $newHash, $set['id_user']);
                $upd->execute();
                $upd->close();
            }
        }

        if ($ok) {
            session_regenerate_id(true);
            $_SESSION['nama'] = $set['nama'];
            $_SESSION['id'] = (int)$set['id_user'];
            $stmt->close();
            redirect('index.php');
        }
    }

    $stmt->close();
    redirect('login.php', 'Data Tidak Ditemukan', 'danger');
}

if (isset($_POST['daftar'])) {
    redirect('register.php');
}
