<?php
// Partial tampilan hasil. Variabel yang dibutuhkan:
// $koneksi, $id_hasil_pemeriksaan (int), $id_user (int)
$sql = 'SELECT hp.id_hasil_pemeriksaan, hp.presentase_hasil, hp.tanggal_pemeriksaan,'
    . ' jp.id_jenis_penyakit, jp.nama_penyakit, jp.defenisi, jp.penyebab, jp.penanganan'
    . ' FROM hasil_pemeriksaan hp'
    . ' JOIN jenis_penyakit jp ON hp.id_jenis_penyakit = jp.id_jenis_penyakit'
    . ' WHERE hp.id_hasil_pemeriksaan = ? AND hp.id_user = ? LIMIT 1';
$stmt = $koneksi->prepare($sql);
$stmt->bind_param('ii', $id_hasil_pemeriksaan, $id_user);
$stmt->execute();
$get = $stmt->get_result();
$set = $get ? $get->fetch_assoc() : null;
$stmt->close();

if (!$set) {
    echo '<div class="container"><div class="alert alert-danger mt-3">Data tidak ditemukan.</div></div>';
    return;
}
$id_penyakit_gejala = (int) $set['id_jenis_penyakit'];
?>
<div class="container">
    <div class="card mb-2">
        <div class="card-body">
            <p class="card-text mb-0">Penyakit : </p>
            <div class="text-center"><?= e($set['nama_penyakit']) ?></div>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <?php
            $stmtG = $koneksi->prepare(
                'SELECT g.gejala FROM penyakit_gejala pg'
                . ' JOIN gejala g ON pg.id_gejala = g.id_gejala'
                . ' WHERE pg.id_jenis_penyakit = ? ORDER BY g.gejala ASC'
            );
            $stmtG->bind_param('i', $id_penyakit_gejala);
            $stmtG->execute();
            $getgejala = $stmtG->get_result();
            ?>
            <p class="card-text mb-0">Gejala : </p>
            <?php while ($setgejala = $getgejala->fetch_assoc()) : ?>
                <div class="text-center"><?= e($setgejala['gejala']) ?></div>
            <?php endwhile; ?>
            <?php $stmtG->close(); ?>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <p class="card-text mb-0">Defenisi : </p>
            <div class="text-center"><?= e($set['defenisi']) ?></div>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <p class="card-text mb-0">Penyebab : </p>
            <div class="text-center"><?= e($set['penyebab']) ?></div>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <p class="card-text mb-0">Penanganan : </p>
            <div class="text-center"><?= e($set['penanganan']) ?></div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <p class="card-text mb-0">Nilai: </p>
            <div class="text-center"><?= e($set['presentase_hasil']) ?> %</div>
        </div>
    </div><br>
</div>
