<?php
require_once __DIR__ . "/../config/helpers.php";
require_admin();
include "../config/koneksi.php";

if (isset($_POST['simpan'])) {
    csrf_check();

    $nama = trim((string) ($_POST['nama'] ?? ''));
    $defenisi = trim((string) ($_POST['defenisi'] ?? ''));
    $penyebab = trim((string) ($_POST['penyebab'] ?? ''));
    $penanganan = trim((string) ($_POST['penanganan'] ?? ''));

    if ($nama === '' || $defenisi === '' || $penyebab === '' || $penanganan === '') {
        echo "<script>alert('Semua field wajib diisi');location='index.php?page=penyakit'</script>";
        exit;
    }

    $simpan = $koneksi->prepare(
        'INSERT INTO jenis_penyakit (nama_penyakit, defenisi, penyebab, penanganan)'
        . ' VALUES (?, ?, ?, ?)'
    );
    $simpan->bind_param('ssss', $nama, $defenisi, $penyebab, $penanganan);

    if ($simpan->execute()) {
        $simpan->close();
        echo "<script>alert('Data Berhasil Ditambahkan');location='index.php?page=penyakit'</script>";
        exit;
    }

    error_log('Tambah penyakit gagal: ' . $simpan->error);
    $simpan->close();
    echo "<script>alert('Gagal menyimpan data');location='index.php?page=penyakit'</script>";
    exit;
}

header('Location: index.php?page=penyakit');
exit;
