<?php
require_once __DIR__ . '/../config/helpers.php';
require_user();
include "../config/koneksi.php";

if (!isset($_POST['simpan'])) {
    header('Location: index.php?page=mulai-diagnosa');
    exit;
}

csrf_check();

$id_user = (int) $_SESSION['id'];

// Ambil daftar gejala valid agar mapping tidak geser (bug lama pakai counter 1..N).
$gejalaRows = [];
$res = $koneksi->query("SELECT id_gejala FROM gejala ORDER BY id_gejala ASC");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $gejalaRows[] = (int) $r['id_gejala'];
    }
}

$dipilih = [];
foreach ($gejalaRows as $id_gejala) {
    $key = 'idpertanyaan' . $id_gejala;
    if (!isset($_POST[$key])) {
        continue;
    }
    $val = (int) $_POST[$key];
    if ($val === $id_gejala) {
        $dipilih[] = $id_gejala;
    }
}
$dipilih = array_values(array_unique($dipilih));

if (count($dipilih) === 0) {
    echo "<script>alert('Pilih minimal satu gejala (Ya)');location='index.php?page=mulai-diagnosa'</script>";
    exit;
}

$koneksi->begin_transaction();
try {
    // Bersihkan sisa diagnosa sebelumnya milik user ini agar MAX() tidak mengambil riwayat lama.
    $stmt = $koneksi->prepare("DELETE FROM sementara WHERE id_user = ?");
    $stmt->bind_param('i', $id_user);
    $stmt->execute();
    $stmt->close();

    $stmt = $koneksi->prepare("DELETE FROM hasil_sementara WHERE id_user = ?");
    $stmt->bind_param('i', $id_user);
    $stmt->execute();
    $stmt->close();

    $stmt = $koneksi->prepare("DELETE FROM hasil_kemungkinan WHERE id_user = ?");
    $stmt->bind_param('i', $id_user);
    $stmt->execute();
    $stmt->close();

    // Catat jawaban user (hanya yang Ya) ke tabel sementara untuk audit.
    $insSementara = $koneksi->prepare("INSERT INTO sementara (gejala, jawaban, id_user) VALUES (?, ?, ?)");
    $getNama = $koneksi->prepare("SELECT gejala FROM gejala WHERE id_gejala = ? LIMIT 1");
    foreach ($dipilih as $id_gejala) {
        $getNama->bind_param('i', $id_gejala);
        $getNama->execute();
        $rNama = $getNama->get_result()->fetch_assoc();
        $namaGejala = $rNama['gejala'] ?? ('Gejala #' . $id_gejala);
        $jawaban = (string) $id_gejala;
        $insSementara->bind_param('ssi', $namaGejala, $jawaban, $id_user);
        $insSementara->execute();
    }
    $getNama->close();
    $insSementara->close();

    // Cocokkan setiap gejala ke penyakit.
    $getPenyakit = $koneksi->prepare("SELECT id_jenis_penyakit FROM penyakit_gejala WHERE id_gejala = ?");
    $insSementara2 = $koneksi->prepare(
        'INSERT INTO hasil_sementara (id_user, id_jenis_penyakit, id_gejala) VALUES (?, ?, ?)'
    );
    foreach ($dipilih as $id_gejala) {
        $getPenyakit->bind_param('i', $id_gejala);
        $getPenyakit->execute();
        $rp = $getPenyakit->get_result();
        while ($rowP = $rp->fetch_assoc()) {
            $id_penyakit = (int) $rowP['id_jenis_penyakit'];
            $insSementara2->bind_param('iii', $id_user, $id_penyakit, $id_gejala);
            $insSementara2->execute();
        }
    }
    $getPenyakit->close();
    $insSementara2->close();

    // Hitung kecocokan per penyakit.
    $getCocok = $koneksi->prepare(
        'SELECT id_jenis_penyakit, COUNT(*) AS jumlah FROM hasil_sementara'
        . ' WHERE id_user = ? GROUP BY id_jenis_penyakit ORDER BY COUNT(*) DESC'
    );
    $getCocok->bind_param('i', $id_user);
    $getCocok->execute();
    $rc = $getCocok->get_result();

    $insKemungkinan = $koneksi->prepare(
        'INSERT INTO hasil_kemungkinan'
        . ' (id_user, id_jenis_penyakit, jumlah_gejala, total_gejala, presentase)'
        . ' VALUES (?, ?, ?, ?, ?)'
    );
    $getTotal = $koneksi->prepare("SELECT COUNT(*) AS total_gejala FROM penyakit_gejala WHERE id_jenis_penyakit = ?");
    $adaCocok = false;
    while ($rowC = $rc->fetch_assoc()) {
        $adaCocok = true;
        $id_cocok = (int) $rowC['id_jenis_penyakit'];
        $jumlah = (int) $rowC['jumlah'];

        $getTotal->bind_param('i', $id_cocok);
        $getTotal->execute();
        $rt = $getTotal->get_result()->fetch_assoc();
        $total = max(1, (int) ($rt['total_gejala'] ?? 1));
        $presentase = round(($jumlah / $total) * 100, 1);

        $insKemungkinan->bind_param('iiiid', $id_user, $id_cocok, $jumlah, $total, $presentase);
        $insKemungkinan->execute();
    }
    $getCocok->close();
    $insKemungkinan->close();
    $getTotal->close();

    if (!$adaCocok) {
        throw new Exception('Tidak ada penyakit yang cocok dengan gejala tersebut.');
    }

    // Ambil presentase tertinggi milik diagnosa ini.
    $ambil = $koneksi->prepare(
        'SELECT MAX(presentase) AS presentase, id_jenis_penyakit'
        . ' FROM hasil_kemungkinan WHERE id_user = ?'
    );
    $ambil->bind_param('i', $id_user);
    $ambil->execute();
    $pecah = $ambil->get_result()->fetch_assoc();
    $ambil->close();

    if (!$pecah || $pecah['id_jenis_penyakit'] === null) {
        throw new Exception('Hasil tidak dapat dihitung.');
    }

    $hasilpresentase = (float) $pecah['presentase'];
    $hasil_jenis_penyakit = (int) $pecah['id_jenis_penyakit'];
    $tanggal = date("Y-m-d");

    $simpan = $koneksi->prepare(
        'INSERT INTO hasil_pemeriksaan'
        . ' (id_jenis_penyakit, tanggal_pemeriksaan, presentase_hasil, id_user)'
        . ' VALUES (?, ?, ?, ?)'
    );
    $presentaseStr = (string) $hasilpresentase;
    $simpan->bind_param('issi', $hasil_jenis_penyakit, $tanggal, $presentaseStr, $id_user);
    $simpan->execute();
    $lastinsertid = $koneksi->insert_id;
    $simpan->close();

    // Bersihkan tabel kerja agar tidak menumpuk antar diagnosa.
    foreach (["sementara", "hasil_sementara", "hasil_kemungkinan"] as $t) {
        $del = $koneksi->prepare("DELETE FROM `$t` WHERE id_user = ?");
        $del->bind_param('i', $id_user);
        $del->execute();
        $del->close();
    }

    $koneksi->commit();
    echo "<script>location='diagnosa-hasil.php?id=" . (int) $lastinsertid . "'</script>";
    exit;
} catch (Throwable $ex) {
    $koneksi->rollback();
    error_log('Diagnosa gagal: ' . $ex->getMessage());
    echo "<script>alert('Diagnosa gagal diproses. Coba lagi.');location='index.php?page=mulai-diagnosa'</script>";
    exit;
}
