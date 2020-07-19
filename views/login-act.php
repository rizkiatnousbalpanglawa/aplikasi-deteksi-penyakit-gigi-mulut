<?php
session_start();
include "../config/koneksi.php";

if (isset($_POST['login'])) {
    $nama = $_POST['nama'];
    $sandi = $_POST['katasandi'];

    $cek = $koneksi->query("SELECT * FROM user WHERE nama='$nama' AND kata_sandi = '$sandi'");

    if (mysqli_num_rows($cek) === 1) {
        $set = $cek->fetch_assoc();
        $_SESSION['nama'] = $nama;
        $_SESSION['id'] = $set['id_user'];
        header("Location: index.php");
    }else {
        echo "<script>alert('Data Tidak Ditemukan');location='login.php'</script>";
    }
}elseif (isset($_POST['daftar'])) {
    echo "<script>location='register.php'</script>";
}