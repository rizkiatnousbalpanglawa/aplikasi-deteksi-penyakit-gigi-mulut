<?php

$id = $_GET['id'];

$koneksi->query("DELETE FROM jenis_penyakit WHERE id_jenis_penyakit = '$id'");

echo "<script>location='index.php?page=penyakit'</script>";