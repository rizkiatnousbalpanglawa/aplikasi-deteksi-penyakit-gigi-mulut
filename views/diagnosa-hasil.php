<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_user();

$id_hasil_pemeriksaan = (int)(isset($_GET['id']) ? $_GET['id'] : 0);
$id_user = (int)$_SESSION['id'];

if ($id_hasil_pemeriksaan <= 0) {
    redirect('index.php');
}

$title = 'Hasil Analisa';
include __DIR__ . '/layout/header.php';
?>

    <nav class="navbar sticky-top text-dark bg-biru shadow">
        <a href="index.php" class="text-dark float-left" style="font-size: 25px"><i class="fa fa-arrow-left fa-x2"></i></a>
        <span class="navbar-brand mx-auto" href="#">Hasil Analisa</a>
    </nav><br>

    <?php include __DIR__ . '/_hasil.php'; ?>

    <div class="container">
        <a href="index.php" class="btn bg-rose btn-block"> COBA LAGI</a>
        <br>
    </div>

<?php include __DIR__ . '/layout/footer.php'; ?>
