<?php

include "../config/koneksi.php";
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $defenisi = $_POST['defenisi'];
    $penyebab = $_POST['penyebab'];
    $penanganan = $_POST['penanganan'];

    $simpan = $koneksi->query("INSERT INTO jenis_penyakit (nama_penyakit,defenisi,penyebab,penanganan) VALUES ('$nama','$defenisi','$penyebab','$penanganan')");

    if ($simpan) {
        echo "<script>alert('Data Berhasil Ditambahkan');location='index.php?page=penyakit'</script>";
        $koneksi->close();
    }
}