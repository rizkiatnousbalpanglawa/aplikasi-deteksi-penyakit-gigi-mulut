<?php

include "../config/koneksi.php";

$id = $_GET['id'];
$idp = $_GET['idp'];

$koneksi->query("DELETE FROM penyakit_gejala WHERE id_penyakit_gejala = '$id'");

echo "<script>location='index.php?page=penyakit-detail&id=$idp'</script>";