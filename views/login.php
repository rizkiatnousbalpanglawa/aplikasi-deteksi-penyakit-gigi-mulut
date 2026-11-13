<?php
require_once __DIR__ . '/../config/bootstrap.php';

$title = 'Login';
$bodyAttrs = 'style="background-color: #f7f7f7"';
include __DIR__ . '/layout/header.php';
?>
    <nav class="navbar text-dark shadow-sm" style="background-color: #aad0d9">
        <span class="navbar-brand mb-0 h1 mx-auto">Login</span>
    </nav>

    <div class="text-center">
        <i class="fa fa-unlock-alt fa-5x mt-5"></i>
    </div>

    <div class="container">

        <form action="login-act.php" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
                <input type="text" class="form-control rounded-0" name="nama" placeholder="username">
            </div>
            <div class="form-group">
                <input type="password" class="form-control rounded-0" name="katasandi" placeholder="katasandi">
            </div>
            <div class="form-group text-center">
                <button class="btn rounded-0 btn-block" style="background-color: #aad0d9" name="login"> <i class="fa fa-sign-in"></i> LOGIN</button>
                <button class="btn btn-secondary rounded-0 btn-block" name="daftar"> <i class="fa fa-pencil-square-o"></i> DAFTAR</button>
            </div>
        </form>

    </div>

<?php include __DIR__ . '/layout/footer.php'; ?>
