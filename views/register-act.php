<?php
require_once __DIR__ . '/../config/bootstrap.php';

if (isset($_POST['login'])) {
    redirect('login.php');
}

if (isset($_POST['daftar'])) {
    csrf_check();

    $nama = trim((string)($_POST['nama'] ?? ''));
    $tlahirRaw = trim((string)($_POST['tgl_lahir'] ?? ''));
    $alamat = trim((string)($_POST['alamat'] ?? ''));
    $telp = trim((string)($_POST['telp'] ?? ''));
    $katasandi = (string)($_POST['katasandi'] ?? '');

    if ($nama === '' || $tlahirRaw === '' || $alamat === '' || $telp === '' || $katasandi === '') {
        redirect('register.php', 'Semua field wajib diisi', 'warning');
    }

    if (mb_strlen($nama) > 100 || mb_strlen($telp) > 100) {
        redirect('register.php', 'Nama / No HP terlalu panjang', 'warning');
    }

    if (mb_strlen($katasandi) < 6) {
        redirect('register.php', 'Kata sandi minimal 6 karakter', 'warning');
    }

    $ts = strtotime($tlahirRaw);
    if ($ts === false) {
        redirect('register.php', 'Tanggal lahir tidak valid', 'warning');
    }
    $tlahir = date("Y-m-d", $ts);

    $stmt = $koneksi->prepare("SELECT id_user FROM user WHERE nama = ? LIMIT 1");
    $stmt->bind_param('s', $nama);
    $stmt->execute();
    $cek = $stmt->get_result();
    if ($cek && $cek->num_rows >= 1) {
        $stmt->close();
        redirect('register.php', 'Username sudah dipakai', 'danger');
    }
    $stmt->close();

    $hash = password_hash($katasandi, PASSWORD_DEFAULT);

    $simpan = $koneksi->prepare("INSERT INTO user (nama,tgl_lahir,alamat,no_hp,kata_sandi) VALUES (?, ?, ?, ?, ?)");
    $simpan->bind_param('sssss', $nama, $tlahir, $alamat, $telp, $hash);

    if ($simpan->execute()) {
        $simpan->close();
        redirect('login.php', 'Pendaftaran berhasil. Silakan login.', 'success');
    }

    error_log('Register failed: ' . $simpan->error);
    $simpan->close();
    redirect('register.php', 'Pendaftaran gagal, coba lagi', 'danger');
}
