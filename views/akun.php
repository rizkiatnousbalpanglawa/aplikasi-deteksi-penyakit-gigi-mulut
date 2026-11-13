<?php
$id_user = (int)$_SESSION['id'];
$stmt = $koneksi->prepare("SELECT id_user, nama, tgl_lahir, alamat, no_hp FROM user WHERE id_user = ? LIMIT 1");
$stmt->bind_param('i', $id_user);
$stmt->execute();
$set = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$set) {
    echo '<div class="container"><div class="alert alert-danger mt-3">Akun tidak ditemukan.</div></div>';
    return;
}
?>
<div class="container">
    <h3 class="mt-2"> <i class="fa fa-user"></i> Akun</h3>
    <hr class="mt-0 bg-biru">

    <form action="akun-update.php" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="">Nama</label>
            <input type="text" class="form-control" placeholder="Nama Saya" name="nama" value="<?= e($set['nama']) ?>" required maxlength="100">
        </div>
        <div class="form-group">
            <label for="">Tanggal Lahir</label>
            <input type="date" class="form-control" name="tgl_lahir" value="<?= e($set['tgl_lahir']) ?>" required>
        </div>
        <div class="form-group">
            <label for="">Alamat</label>
            <input type="text" class="form-control" placeholder="Alamat Saya" name="alamat" value="<?= e($set['alamat']) ?>" required>
        </div>
        <div class="form-group">
            <label for="">No. HP</label>
            <input type="text" class="form-control" placeholder="No HP Saya" name="telp" value="<?= e($set['no_hp']) ?>" required maxlength="100" pattern="[0-9+\-\s]{6,20}" title="Nomor HP 6-20 karakter">
        </div>
        <div class="form-group">
            <label for="">Kata Sandi Baru (kosongkan jika tidak diganti)</label>
            <div class="input-group">
                <input type="password" class="form-control" data-toggle="password" name="katasandi" autocomplete="new-password" minlength="6">
                <div class="input-group-append">
                    <div class="input-group-text"><i class="fa fa-eye"></i></div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn bg-biru btn-block rounded-0" name="ubah"><i class="fa fa-pencil-square-o"></i> Ubah</button>
        <a href="logout.php" class="btn bg-pink btn-block rounded-0"><i class="fa fa-sign-out"></i> Logout</a>
    </form>
</div>
