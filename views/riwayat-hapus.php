<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_user();

$id = (int)($_GET['id'] ?? 0);
$id_user = (int)$_SESSION['id'];

if ($id <= 0) {
    redirect('index.php?page=riwayat');
}

$stmt = $koneksi->prepare("DELETE FROM hasil_pemeriksaan WHERE id_hasil_pemeriksaan = ? AND id_user = ?");
$stmt->bind_param('ii', $id, $id_user);
$stmt->execute();
$stmt->close();

audit($koneksi, 'hapus_riwayat', 'id=' . $id);
redirect('index.php?page=riwayat', 'Riwayat dihapus', 'success');
