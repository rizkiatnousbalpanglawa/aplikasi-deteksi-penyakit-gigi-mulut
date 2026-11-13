<?php
require_once __DIR__ . "/../config/bootstrap.php";
require_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    redirect('index.php?page=gejala');
}

$stmt = $koneksi->prepare("DELETE FROM gejala WHERE id_gejala = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

audit($koneksi, 'hapus_gejala', 'id=' . $id);
redirect('index.php?page=gejala');
