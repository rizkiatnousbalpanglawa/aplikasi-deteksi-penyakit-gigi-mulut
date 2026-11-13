<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_user();

$title = 'Diagnosa Penyakit Gigi dan Mulut';
include __DIR__ . '/layout/header.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'diagnosa';
switch ($page) {
    case 'diagnosa':
        include 'diagnosa.php';
        break;
    case 'mulai-diagnosa':
        include 'diagnosa-pertanyaan.php';
        break;
    case 'riwayat':
        include 'riwayat.php';
        break;
    case 'akun':
        include 'akun.php';
        break;
    default:
        include 'diagnosa.php';
        break;
}
?>

    <br><br><br>
    <!-- footer -->
    <nav class="navbar fixed-bottom bg-biru text-center ">
        <div class="col-4 ">
            <a class="text-dark text-decoration-none" href="index.php?page=diagnosa"><i class="fa fa-stethoscope"></i>
                <div class="h6 mb-0">Diagnosa</div>
            </a>
        </div>
        <div class="col-4 ">
            <a class="text-dark text-decoration-none" href="index.php?page=riwayat"><i class="fa fa-file-o"></i>
                <div class="h6 mb-0">Riwayat</div>
            </a>
        </div>
        <div class="col-4 ">
            <a class="text-dark text-decoration-none" href="index.php?page=akun"><i class="fa fa-user"></i>
                <div class="h6 mb-0">Akun</div>
            </a>
        </div>
    </nav>

<?php include __DIR__ . '/layout/footer.php'; ?>
