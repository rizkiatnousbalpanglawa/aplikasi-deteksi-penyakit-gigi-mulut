<?php
require_once __DIR__ . '/../config/bootstrap.php';

$title = 'Daftar';
include __DIR__ . '/layout/header.php';
?>

    <div class="container">
        <h3 class="mt-2"> <i class="fa fa-th-list"></i> Daftar</h3>
        <hr class="mt-0 bg-biru">

        <form action="register-act.php" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="">Nama</label>
                <input type="text" class="form-control" placeholder="Nama Saya" name="nama" required>
            </div>
            <div class="form-group">
                <label for="">Tanggal Lahir</label>
                <input type="date" class="form-control" name="tgl_lahir" id="" required>
            </div>
            <div class="form-group">
                <label for="">Alamat</label>
                <input type="text" class="form-control" placeholder="Alamat Saya" name="alamat" required>
            </div>
            <div class="form-group">
                <label for="">No. HP</label>
                <input type="number" class="form-control" placeholder="No HP Saya" name="telp" required>
            </div>
            <div class="form-group">
                <label for="">Kata Sandi</label>
                <div class="input-group">
                    <input type="password" class="form-control" data-toggle="password" name="katasandi" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fa fa-eye"></i></div>
                    </div>
                </div>
            </div>
            <button class="btn bg-biru btn-block rounded-0" name="daftar"><i class="fa fa-pencil-square-o"></i> Daftar</button>
            <button class="btn bg-pink btn-block rounded-0" name="login"><i class="fa fa-sign-in"></i> Login</button>
        </form>
    </div>
<?php include __DIR__ . '/layout/footer.php'; ?>
