<?php $id_user = (int)$_SESSION['id']; ?>
<div class="container">
    <h3 class="mt-2"> <i class="fa fa-file-o"></i> Riwayat</h3>
    <hr class="mt-0 bg-biru">

    <?php
    $stmt = $koneksi->prepare(
        'SELECT id_hasil_pemeriksaan, tanggal_pemeriksaan FROM hasil_pemeriksaan'
        . ' WHERE id_user = ? ORDER BY tanggal_pemeriksaan DESC, id_hasil_pemeriksaan DESC'
    );
    $stmt->bind_param('i', $id_user);
    $stmt->execute();
    $get = $stmt->get_result();
    ?>
    <?php while ($set = $get->fetch_assoc()) : ?>
        <?php $id_hasil_pemeriksaan = (int)$set['id_hasil_pemeriksaan']; ?>
        <div class="card shadow mb-2">
            <div class="card-body">
                <div class="text-center h4"><?= e(date("d M Y", strtotime($set['tanggal_pemeriksaan']))) ?></div>
                <br>
                <a href="riwayat-lihat.php?id=<?= $id_hasil_pemeriksaan ?>" class="btn bg-biru btn-block rounded-0"><i class="fa fa-search"></i> Lihat</a>
                <a href="riwayat-hapus.php?id=<?= $id_hasil_pemeriksaan ?>" class="btn bg-rose btn-block rounded-0" onclick="return confirm('Yakin hapus riwayat ini?')"><i class="fa fa-times"></i> Hapus</a>
            </div>
        </div>
    <?php endwhile; ?>
    <?php $stmt->close(); ?>
</div>
