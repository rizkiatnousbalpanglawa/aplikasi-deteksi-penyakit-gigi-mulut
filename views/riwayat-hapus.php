<?php
require_once __DIR__ . '/../config/helpers.php';
require_user();
include "../config/koneksi.php";

$id = (int) ($_GET['id'] ?? 0);
$id_user = (int) $_SESSION['id'];

if ($id <= 0) {
    echo "<script>location='index.php?page=riwayat'</script>";
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM hasil_pemeriksaan WHERE id_hasil_pemeriksaan = ? AND id_user = ?");
$stmt->bind_param('ii', $id, $id_user);
$stmt->execute();
$stmt->close();

echo "<script>location='index.php?page=riwayat'</script>";
exit;
