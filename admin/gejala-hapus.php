<?php

include "../config/koneksi.php";

$id = $_GET['id'];

$koneksi->query("DELETE FROM gejala WHERE id_gejala = '$id'");

echo "<script>location='index.php?page=gejala'</script>";