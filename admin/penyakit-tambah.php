<?php
require_once __DIR__ . "/../config/bootstrap.php";
require_admin();

if (isset($_POST['simpan'])) {
    csrf_check();

    $nama = trim((string)($_POST['nama'] ?? ''));
    $defenisi = trim((string)($_POST['defenisi'] ?? ''));
    $penyebab = trim((string)($_POST['penyebab'] ?? ''));
    $penanganan = trim((string)($_POST['penanganan'] ?? ''));

    if ($nama === '' || $defenisi === '' || $penyebab === '' || $penanganan === '') {
        redirect('index.php?page=penyakit', 'Semua field wajib diisi', 'warning');
    }

    $simpan = $koneksi->prepare(
        'INSERT INTO jenis_penyakit (nama_penyakit, defenisi, penyebab, penanganan)'
        . ' VALUES (?, ?, ?, ?)'
    );
    $simpan->bind_param('ssss', $nama, $defenisi, $penyebab, $penanganan);

    if ($simpan->execute()) {
        $simpan->close();
        audit($koneksi, 'tambah_penyakit', $nama);
        redirect('index.php?page=penyakit', 'Data Berhasil Ditambahkan', 'success');
    }

    error_log('Tambah penyakit gagal: ' . $simpan->error);
    $simpan->close();
    redirect('index.php?page=penyakit', 'Gagal menyimpan data', 'danger');
}

redirect('index.php?page=penyakit');
