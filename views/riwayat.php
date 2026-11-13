<?php $id_user = (int)$_SESSION['id']; ?>
<?php
$perHalaman = 10;
$halaman = max(1, (int)($_GET['hal'] ?? 1));
$stmtHitung = $koneksi->prepare("SELECT COUNT(*) AS total FROM hasil_pemeriksaan WHERE id_user = ?");
$stmtHitung->bind_param('i', $id_user);
$stmtHitung->execute();
$total = (int)($stmtHitung->get_result()->fetch_assoc()['total'] ?? 0);
$stmtHitung->close();
$totalHalaman = max(1, (int)ceil($total / $perHalaman));
if ($halaman > $totalHalaman) {
    $halaman = $totalHalaman;
}
$offset = ($halaman - 1) * $perHalaman;
?>
<div class="container">
    <h3 class="mt-2"> <i class="fa fa-file-o"></i> Riwayat</h3>
    <hr class="mt-0 bg-biru">

    <?php
    $stmt = $koneksi->prepare(
        'SELECT id_hasil_pemeriksaan, tanggal_pemeriksaan FROM hasil_pemeriksaan'
        . ' WHERE id_user = ? ORDER BY tanggal_pemeriksaan DESC, id_hasil_pemeriksaan DESC'
        . ' LIMIT ? OFFSET ?'
    );
    $stmt->bind_param('iii', $id_user, $perHalaman, $offset);
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

    <?php if ($totalHalaman > 1) : ?>
    <nav aria-label="Halaman riwayat">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= $halaman <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="index.php?page=riwayat&hal=<?= $halaman - 1 ?>">Sebelumnya</a>
            </li>
            <li class="page-item disabled"><span class="page-link">Hal <?= $halaman ?> dari <?= $totalHalaman ?></span></li>
            <li class="page-item <?= $halaman >= $totalHalaman ? 'disabled' : '' ?>">
                <a class="page-link" href="index.php?page=riwayat&hal=<?= $halaman + 1 ?>">Berikutnya</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>
