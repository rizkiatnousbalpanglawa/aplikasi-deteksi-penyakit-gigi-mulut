<?php
require_once __DIR__ . "/../config/helpers.php";
require_admin();
include "../config/koneksi.php";

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<script>location='index.php?page=gejala'</script>";
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM gejala WHERE id_gejala = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

echo "<script>location='index.php?page=gejala'</script>";
exit;
