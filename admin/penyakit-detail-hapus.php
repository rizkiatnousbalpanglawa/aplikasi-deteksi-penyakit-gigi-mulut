<?php
require_once __DIR__ . "/../config/bootstrap.php";
require_admin();

$id = (int)($_GET['id'] ?? 0);
$idp = (int)($_GET['idp'] ?? 0);
if ($id <= 0 || $idp <= 0) {
    redirect('index.php?page=penyakit');
}

$stmt = $koneksi->prepare("DELETE FROM penyakit_gejala WHERE id_penyakit_gejala = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

audit($koneksi, 'hapus_relasi', 'id=' . $id . ' penyakit=' . $idp);
redirect('index.php?page=penyakit-detail&id=' . $idp);
