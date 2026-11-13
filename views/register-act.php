<?php
require_once __DIR__ . '/../config/helpers.php';
start_secure_session();
include "../config/koneksi.php";

if (isset($_POST['login'])) {
    echo "<script>location='login.php'</script>";
    exit;
}

if (isset($_POST['daftar'])) {
    csrf_check();

    $nama = trim((string) ($_POST['nama'] ?? ''));
    $tlahirRaw = trim((string) ($_POST['tgl_lahir'] ?? ''));
    $alamat = trim((string) ($_POST['alamat'] ?? ''));
    $telp = trim((string) ($_POST['telp'] ?? ''));
    $katasandi = (string) ($_POST['katasandi'] ?? '');

    if ($nama === '' || $tlahirRaw === '' || $alamat === '' || $telp === '' || $katasandi === '') {
        echo "<script>alert('Semua field wajib diisi');location='register.php'</script>";
        exit;
    }

    if (mb_strlen($nama) > 100 || mb_strlen($telp) > 100) {
        echo "<script>alert('Nama / No HP terlalu panjang');location='register.php'</script>";
        exit;
    }

    if (mb_strlen($katasandi) < 6) {
        echo "<script>alert('Kata sandi minimal 6 karakter');location='register.php'</script>";
        exit;
    }

    $ts = strtotime($tlahirRaw);
    if ($ts === false) {
        echo "<script>alert('Tanggal lahir tidak valid');location='register.php'</script>";
        exit;
    }
    $tlahir = date("Y-m-d", $ts);

    $stmt = $koneksi->prepare("SELECT id_user FROM user WHERE nama = ? LIMIT 1");
    $stmt->bind_param('s', $nama);
    $stmt->execute();
    $cek = $stmt->get_result();
    if ($cek && $cek->num_rows >= 1) {
        $stmt->close();
        echo "<script>alert('Username sudah dipakai');location='register.php'</script>";
        exit;
    }
    $stmt->close();

    $hash = password_hash($katasandi, PASSWORD_DEFAULT);

    $simpan = $koneksi->prepare("INSERT INTO user (nama,tgl_lahir,alamat,no_hp,kata_sandi) VALUES (?, ?, ?, ?, ?)");
    $simpan->bind_param('sssss', $nama, $tlahir, $alamat, $telp, $hash);

    if ($simpan->execute()) {
        $simpan->close();
        echo "<script>location='login.php'</script>";
        exit;
    }

    error_log('Register failed: ' . $simpan->error);
    $simpan->close();
    echo "<script>alert('Pendaftaran gagal, coba lagi');location='register.php'</script>";
    exit;
}
