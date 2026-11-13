<?php
require_once __DIR__ . "/../config/bootstrap.php";
require_admin();

if (!isset($_GET['id'])) {
    redirect('index.php?page=penyakit');
}
$id = (int)$_GET['id'];
if ($id <= 0) {
    redirect('index.php?page=penyakit');
}

$stmt = $koneksi->prepare("DELETE FROM jenis_penyakit WHERE id_jenis_penyakit = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

redirect('index.php?page=penyakit');
