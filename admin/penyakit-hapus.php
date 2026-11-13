<?php
require_once __DIR__ . "/../config/helpers.php";
require_admin();
include "../config/koneksi.php";

if (!isset($_GET['id'])) {
    echo "<script>location='index.php?page=penyakit'</script>";
    exit;
}
$id = (int) $_GET['id'];
if ($id <= 0) {
    echo "<script>location='index.php?page=penyakit'</script>";
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM jenis_penyakit WHERE id_jenis_penyakit = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

echo "<script>location='index.php?page=penyakit'</script>";
exit;
