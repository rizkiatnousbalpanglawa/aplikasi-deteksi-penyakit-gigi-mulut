<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_user();

if (!isset($_POST['ubah'])) {
    redirect('index.php?page=akun');
}

csrf_check();

$id_user = (int)$_SESSION['id'];
$nama = trim((string)($_POST['nama'] ?? ''));
$tlahirRaw = trim((string)($_POST['tgl_lahir'] ?? ''));
$alamat = trim((string)($_POST['alamat'] ?? ''));
$telp = trim((string)($_POST['telp'] ?? ''));
$katasandi = (string)($_POST['katasandi'] ?? '');

if ($nama === '' || $tlahirRaw === '' || $alamat === '' || $telp === '') {
    redirect('index.php?page=akun', 'Semua field kecuali kata sandi wajib diisi', 'warning');
}

$ts = strtotime($tlahirRaw);
if ($ts === false) {
    redirect('index.php?page=akun', 'Tanggal lahir tidak valid', 'warning');
}
$tlahir = date('Y-m-d', $ts);

// Cek nama dipakai user lain
$cek = $koneksi->prepare("SELECT id_user FROM user WHERE nama = ? AND id_user <> ? LIMIT 1");
$cek->bind_param('si', $nama, $id_user);
$cek->execute();
$ada = $cek->get_result();
if ($ada && $ada->num_rows > 0) {
    $cek->close();
    redirect('index.php?page=akun', 'Nama sudah dipakai user lain', 'danger');
}
$cek->close();

if ($katasandi !== '') {
    if (mb_strlen($katasandi) < 6) {
        redirect('index.php?page=akun', 'Kata sandi minimal 6 karakter', 'warning');
    }
    $hash = password_hash($katasandi, PASSWORD_DEFAULT);
    $upd = $koneksi->prepare(
        'UPDATE user SET nama = ?, tgl_lahir = ?, alamat = ?, no_hp = ?, kata_sandi = ?'
        . ' WHERE id_user = ?'
    );
    $upd->bind_param('sssssi', $nama, $tlahir, $alamat, $telp, $hash, $id_user);
} else {
    $upd = $koneksi->prepare("UPDATE user SET nama = ?, tgl_lahir = ?, alamat = ?, no_hp = ? WHERE id_user = ?");
    $upd->bind_param('ssssi', $nama, $tlahir, $alamat, $telp, $id_user);
}

if ($upd->execute()) {
    $upd->close();
    $_SESSION['nama'] = $nama;
    redirect('index.php?page=akun', 'Akun berhasil diubah', 'success');
}

error_log('Update akun gagal: ' . $upd->error);
$upd->close();
redirect('index.php?page=akun', 'Gagal mengubah akun', 'danger');
