<?php $id_user = $_SESSION['id'] ?>
<div class="container">
    <h3 class="mt-2"> <i class="fa fa-file-o"></i> Riwayat</h3>
    <hr class="mt-0 bg-biru">

    <?php $get = $koneksi->query("SELECT * FROM hasil_pemeriksaan WHERE id_user = '$id_user'") ?>
    <?php while ($set = $get->fetch_assoc()) : ?>
        <?php $id_hasil_pemeriksaan = $set['id_hasil_pemeriksaan'] ?>
        <div class="card shadow mb-2">
            <div class="card-body">
                <div class="text-center h4"><?= date("d M Y", strtotime($set['tanggal_pemeriksaan'])) ?></div>
                <br>
                <a href="riwayat-lihat.php?id=<?= $id_hasil_pemeriksaan ?>" class="btn bg-biru btn-block rounded-0"><i class="fa fa-search"></i> Lihat</a>
                <a href="riwayat-hapus.php?id=<?= $id_hasil_pemeriksaan ?>" class="btn bg-rose btn-block rounded-0"><i class="fa fa-times"></i> Hapus</a>
            </div>
        </div>
    <?php endwhile ?>
</div>