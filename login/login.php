<?php
session_start();
include "../config/koneksi.php";

if (isset($_POST['masuk'])) {
    $username = $_POST['username'];
    $katasandi = $_POST['pass'];

    $cek = $koneksi->query("SELECT * FROM admin WHERE username='$username' AND kata_sandi='$katasandi'");

    if (mysqli_num_rows($cek)===1) {
        $_SESSION['admin']['user']= $username;
        header("Location: ../admin/index.php");
    }else {
        echo "<script>alert('Username dan Katasandi tidak sesuai!');location='index.php'</script>";
    }
}