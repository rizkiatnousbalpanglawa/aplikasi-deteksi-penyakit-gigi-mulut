<?php $id_user = $_SESSION['id'] ?>
<?php $get = $koneksi->query("SELECT * FROM user WHERE id_user = '$id_user'") ?>
<?php $set = $get->fetch_assoc() ?>
<div class="container">
    <h3 class="mt-2"> <i class="fa fa-user"></i> Akun</h3>
    <hr class="mt-0 bg-biru">

    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="">Nama</label>
            <input type="text" class="form-control" placeholder="Nama Saya" name="nama" value="<?= $set['nama']?>" required>
        </div>
        <div class="form-group">
            <label for="">Tanggal Lahir</label>
            <input type="date" class="form-control" name="tgl_lahir" id="" value="<?= $set['tgl_lahir'] ?>" required>
        </div>
        <div class="form-group">
            <label for="">Alamat</label>
            <input type="text" class="form-control" placeholder="Alamat Saya" name="alamat" value="<?= $set['alamat'] ?>" required>
        </div>
        <div class="form-group">
            <label for="">No. HP</label>
            <input type="number" class="form-control" placeholder="No HP Saya" name="telp" value="<?= $set['no_hp'] ?>" required>
        </div>
        <div class="form-group">
            <label for="">Kata Sandi</label>
            <div class="input-group">
                <input type="password" class="form-control" data-toggle="password" name="katasandi" value="<?= $set['kata_sandi'] ?>">
                <div class="input-group-append">
                    <div class="input-group-text"><i class="fa fa-eye"></i></div>
                </div>
            </div>
        </div>
        <button class="btn bg-biru btn-block rounded-0"><i class="fa fa-pencil-square-o"></i> Ubah</button>
        <a href="logout.php" class="btn bg-pink btn-block rounded-0" name="keluar"><i class="fa fa-sign-out"></i> Logout</a>
    </form>
</div>

<?php

if (isset($_POST['keluar'])) {
    echo "<script>location='logout.php'</script>";
}