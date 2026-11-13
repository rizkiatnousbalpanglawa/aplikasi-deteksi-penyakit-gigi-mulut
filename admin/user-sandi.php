<?php
require_once __DIR__ . "/../config/bootstrap.php";
require_admin();

if (!isset($_POST['reset'])) {
    redirect('index.php?page=user');
}

csrf_check();

$id_user = (int)($_POST['id_user'] ?? 0);
if ($id_user <= 0) {
    redirect('index.php?page=user', 'User tidak valid', 'warning');
}

$alfabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
$sandiBaru = '';
for ($i = 0; $i < 8; $i++) {
    $sandiBaru .= $alfabet[random_int(0, strlen($alfabet) - 1)];
}
$hash = password_hash($sandiBaru, PASSWORD_DEFAULT);

$upd = $koneksi->prepare("UPDATE user SET kata_sandi = ? WHERE id_user = ?");
$upd->bind_param('si', $hash, $id_user);
if ($upd->execute() && $upd->affected_rows > 0) {
    $upd->close();
    $stmtNama = $koneksi->prepare("SELECT nama FROM user WHERE id_user = ? LIMIT 1");
    $stmtNama->bind_param('i', $id_user);
    $stmtNama->execute();
    $rowNama = $stmtNama->get_result()->fetch_assoc();
    $stmtNama->close();
    $nama = $rowNama ? $rowNama['nama'] : ('#' . $id_user);
    audit($koneksi, 'reset_sandi', $nama);
    redirect(
        'index.php?page=user',
        'Sandi baru ' . $nama . ': ' . $sandiBaru . ' (catat, hanya tampil sekali)',
        'success'
    );
}

error_log('Reset sandi gagal, id=' . $id_user . ': ' . $upd->error);
$upd->close();
redirect('index.php?page=user', 'Gagal mereset sandi', 'danger');
