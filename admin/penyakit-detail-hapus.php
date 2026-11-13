<?php
require_once __DIR__ . "/../config/helpers.php";
require_admin();
include "../config/koneksi.php";

$id = (int) ($_GET['id'] ?? 0);
$idp = (int) ($_GET['idp'] ?? 0);
if ($id <= 0 || $idp <= 0) {
    echo "<script>location='index.php?page=penyakit'</script>";
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM penyakit_gejala WHERE id_penyakit_gejala = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

header("Location: index.php?page=penyakit-detail&id=" . $idp);
exit;
