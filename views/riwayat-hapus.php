<?php

include "../config/koneksi.php";
$id = $_GET['id'];

$koneksi->query("DELETE FROM hasil_pemeriksaan WHERE id_hasil_pemeriksaan = '$id'");

echo "<script>location='index.php?page=riwayat'</script>";