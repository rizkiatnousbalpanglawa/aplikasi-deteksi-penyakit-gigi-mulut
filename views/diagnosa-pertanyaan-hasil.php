<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../lib/cf.php';
require_user();

if (!isset($_POST['simpan'])) {
    redirect('index.php?page=mulai-diagnosa');
}

csrf_check();

$id_user = (int)$_SESSION['id'];

// Ambil daftar gejala valid agar mapping tidak geser (bug lama pakai counter 1..N).
$gejalaRows = [];
$res = $koneksi->query("SELECT id_gejala FROM gejala ORDER BY id_gejala ASC");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $gejalaRows[] = (int)$r['id_gejala'];
    }
}

$dipilih = [];
foreach ($gejalaRows as $id_gejala) {
    $key = 'idpertanyaan' . $id_gejala;
    if (!isset($_POST[$key])) {
        continue;
    }
    $val = (int)$_POST[$key];
    if ($val === $id_gejala) {
        $dipilih[] = $id_gejala;
    }
}
$dipilih = array_values(array_unique($dipilih));

if (count($dipilih) === 0) {
    redirect('index.php?page=mulai-diagnosa', 'Pilih minimal satu gejala (Ya)', 'warning');
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
        $jawaban = (string)$id_gejala;
        $insSementara->bind_param('ssi', $namaGejala, $jawaban, $id_user);
        $insSementara->execute();
    }
    $getNama->close();
    $insSementara->close();

    // Cocokkan setiap gejala ke penyakit beserta bobot pakarnya.
    // Hanya relasi milik penyakit terdaftar (JOIN master) yang dipakai.
    $getPenyakit = $koneksi->prepare(
        'SELECT pg.id_jenis_penyakit, pg.mb, pg.md FROM penyakit_gejala pg'
        . ' JOIN jenis_penyakit jp ON jp.id_jenis_penyakit = pg.id_jenis_penyakit'
        . ' WHERE pg.id_gejala = ?'
    );
    $insSementara2 = $koneksi->prepare(
        'INSERT INTO hasil_sementara (id_user, id_jenis_penyakit, id_gejala) VALUES (?, ?, ?)'
    );
    // Kumpulkan CF tiap relasi yang cocok, dikelompokkan per penyakit.
    $cfPerPenyakit = [];
    foreach ($dipilih as $id_gejala) {
        $getPenyakit->bind_param('i', $id_gejala);
        $getPenyakit->execute();
        $rp = $getPenyakit->get_result();
        while ($rowP = $rp->fetch_assoc()) {
            $id_penyakit = (int)$rowP['id_jenis_penyakit'];
            $insSementara2->bind_param('iii', $id_user, $id_penyakit, $id_gejala);
            $insSementara2->execute();

            $mb = isset($rowP['mb']) ? (float)$rowP['mb'] : 0.0;
            $md = isset($rowP['md']) ? (float)$rowP['md'] : 0.0;
            if (!isset($cfPerPenyakit[$id_penyakit])) {
                $cfPerPenyakit[$id_penyakit] = [];
            }
            $cfPerPenyakit[$id_penyakit][] = $mb - $md;
        }
    }
    $getPenyakit->close();
    $insSementara2->close();

    // Gabungkan CF tiap penyakit. Kolom jumlah_gejala/total_gejala tetap
    // disimpan sebagai info pendukung; skor utama = presentase dari CF.
    $insKemungkinan = $koneksi->prepare(
        'INSERT INTO hasil_kemungkinan'
        . ' (id_user, id_jenis_penyakit, jumlah_gejala, total_gejala, presentase)'
        . ' VALUES (?, ?, ?, ?, ?)'
    );
    $getTotal = $koneksi->prepare("SELECT COUNT(*) AS total_gejala FROM penyakit_gejala WHERE id_jenis_penyakit = ?");
    $adaCocok = false;
    foreach ($cfPerPenyakit as $id_cocok => $cfs) {
        $adaCocok = true;
        $jumlah = count($cfs);
        $cf = cf_kombinasi($cfs);

        $getTotal->bind_param('i', $id_cocok);
        $getTotal->execute();
        $rt = $getTotal->get_result()->fetch_assoc();
        $total = max(1, (int)($rt['total_gejala'] ?? 1));
        $presentase = round($cf * 100, 1);

        $insKemungkinan->bind_param('iiiid', $id_user, $id_cocok, $jumlah, $total, $presentase);
        $insKemungkinan->execute();
    }
    $insKemungkinan->close();
    $getTotal->close();

    if (!$adaCocok) {
        throw new Exception('Tidak ada penyakit yang cocok dengan gejala tersebut.');
    }

    // Ambil kandidat terbaik secara deterministik: presentase tertinggi,
    // lalu jumlah gejala terbanyak, lalu id terkecil.
    $ambil = $koneksi->prepare(
        'SELECT id_jenis_penyakit, presentase FROM hasil_kemungkinan'
        . ' WHERE id_user = ? ORDER BY presentase DESC, jumlah_gejala DESC, id_jenis_penyakit ASC LIMIT 1'
    );
    $ambil->bind_param('i', $id_user);
    $ambil->execute();
    $pecah = $ambil->get_result()->fetch_assoc();
    $ambil->close();

    if (!$pecah || $pecah['id_jenis_penyakit'] === null) {
        throw new Exception('Hasil tidak dapat dihitung.');
    }

    $hasilpresentase = (float)$pecah['presentase'];
    $hasil_jenis_penyakit = (int)$pecah['id_jenis_penyakit'];
    $tanggal = date("Y-m-d");

    $simpan = $koneksi->prepare(
        'INSERT INTO hasil_pemeriksaan'
        . ' (id_jenis_penyakit, tanggal_pemeriksaan, presentase_hasil, id_user)'
        . ' VALUES (?, ?, ?, ?)'
    );
    $presentaseStr = (string)$hasilpresentase;
    $simpan->bind_param('issi', $hasil_jenis_penyakit, $tanggal, $presentaseStr, $id_user);
    if (!$simpan->execute()) {
        $dbError = $simpan->error;
        $simpan->close();
        throw new Exception('Gagal menyimpan hasil pemeriksaan: ' . $dbError);
    }
    $lastinsertid = $koneksi->insert_id;
    $simpan->close();

    if ($lastinsertid <= 0) {
        throw new Exception('Gagal menyimpan hasil pemeriksaan.');
    }

    // Bersihkan tabel kerja agar tidak menumpuk antar diagnosa.
    foreach (["sementara", "hasil_sementara", "hasil_kemungkinan"] as $t) {
        $del = $koneksi->prepare("DELETE FROM `$t` WHERE id_user = ?");
        $del->bind_param('i', $id_user);
        $del->execute();
        $del->close();
    }

    $koneksi->commit();
    redirect('diagnosa-hasil.php?id=' . (int)$lastinsertid);
} catch (Throwable $ex) {
    $koneksi->rollback();
    error_log('Diagnosa gagal: ' . $ex->getMessage());
    redirect('index.php?page=mulai-diagnosa', 'Diagnosa gagal diproses. Coba lagi.', 'danger');
}
