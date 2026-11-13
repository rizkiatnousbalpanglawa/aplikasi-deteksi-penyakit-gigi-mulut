<?php
require_once __DIR__ . '/../config/helpers.php';
require_user();
include "../config/koneksi.php";

if (!isset($_POST['ubah'])) {
    header('Location: index.php?page=akun');
    exit;
}

csrf_check();

$id_user = (int) $_SESSION['id'];
$nama = trim((string) ($_POST['nama'] ?? ''));
$tlahirRaw = trim((string) ($_POST['tgl_lahir'] ?? ''));
$alamat = trim((string) ($_POST['alamat'] ?? ''));
$telp = trim((string) ($_POST['telp'] ?? ''));
$katasandi = (string) ($_POST['katasandi'] ?? '');

if ($nama === '' || $tlahirRaw === '' || $alamat === '' || $telp === '') {
    echo "<script>alert('Semua field kecuali kata sandi wajib diisi');location='index.php?page=akun'</script>";
    exit;
}

$ts = strtotime($tlahirRaw);
if ($ts === false) {
    echo "<script>alert('Tanggal lahir tidak valid');location='index.php?page=akun'</script>";
    exit;
}
$tlahir = date('Y-m-d', $ts);

// Cek nama dipakai user lain
$cek = $koneksi->prepare("SELECT id_user FROM user WHERE nama = ? AND id_user <> ? LIMIT 1");
$cek->bind_param('si', $nama, $id_user);
$cek->execute();
$ada = $cek->get_result();
if ($ada && $ada->num_rows > 0) {
    $cek->close();
    echo "<script>alert('Nama sudah dipakai user lain');location='index.php?page=akun'</script>";
    exit;
}
$cek->close();

if ($katasandi !== '') {
    if (mb_strlen($katasandi) < 6) {
        echo "<script>alert('Kata sandi minimal 6 karakter');location='index.php?page=akun'</script>";
        exit;
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
    echo "<script>alert('Akun berhasil diubah');location='index.php?page=akun'</script>";
    exit;
}

error_log('Update akun gagal: ' . $upd->error);
$upd->close();
echo "<script>alert('Gagal mengubah akun');location='index.php?page=akun'</script>";
exit;
