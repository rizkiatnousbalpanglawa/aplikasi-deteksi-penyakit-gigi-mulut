<?php
session_start();
$id_user = $_SESSION['id'];

include "../config/koneksi.php";

if (isset($_POST['simpan'])) {
    $get = $koneksi->query("SELECT * FROM gejala");
    $no = 1;

    /* simpan di tabel sementara */
    while ($set = $get->fetch_assoc()) {
        ${'no' . $no} = $_POST["idpertanyaan" . $no];

        $gejala = $set['gejala'];

        $koneksi->query("INSERT INTO `sementara`(`gejala`, `jawaban`,id_user) VALUES ('$gejala','" . ${'no' . $no} . "','$id_user')");

        $no++;
    }

    /* ambil jumlah gejala dari tabel sementara */
    $getsementara = $koneksi->query("SELECT jawaban FROM `sementara` WHERE jawaban != '0' AND id_user='$id_user'");

    /* jika jumlah gejala 1 */
    if (mysqli_num_rows($getsementara) === 1) {
        $setsementara = $getsementara->fetch_assoc();
        $id_gejala = $setsementara['jawaban'];

        /* mencocokan gejala dengan penyakit */
        $getpenyakit = $koneksi->query("SELECT * FROM penyakit_gejala WHERE id_gejala = '$id_gejala'");
        while ($setpenyakit = $getpenyakit->fetch_assoc()) {
            $id_penyakit = $setpenyakit['id_jenis_penyakit'];

            /* hasil penyakit yang cocok di tambahkan ke tabel hasil sementara */
            $koneksi->query("INSERT INTO hasil_sementara (id_user,id_jenis_penyakit,id_gejala) VALUES('$id_user','$id_penyakit','$id_gejala')");

            /* hapus data dari tabel sementara */
            $koneksi->query("DELETE FROM sementara WHERE id_user = '$id_user'");
        }
        $getkecocokan = $koneksi->query("SELECT id_jenis_penyakit , COUNT(*) AS jumlah from hasil_sementara WHERE id_user ='$id_user' GROUP BY id_jenis_penyakit ORDER BY COUNT(*) DESC ");

        while ($setkecocokan = $getkecocokan->fetch_assoc()) {
            $id_jenis_penyakit_cocok = $setkecocokan['id_jenis_penyakit'];
            $jumlah = $setkecocokan['jumlah'];

            /* ambil jumlah kemungkinan */
            $gettotal = $koneksi->query("SELECT COUNT(id_gejala) AS total_gejala FROM `penyakit_gejala` WHERE id_jenis_penyakit = '$id_jenis_penyakit_cocok'");
            $settotal = $gettotal->fetch_assoc();
            $total = $settotal['total_gejala'];

            /* presentase kemungkinan */
            $presentase = ($jumlah / $total) * 100;

            $koneksi->query("INSERT INTO hasil_kemungkinan (id_user,id_jenis_penyakit,jumlah_gejala,total_gejala,presentase) VALUES ('$id_user','$id_jenis_penyakit_cocok','$jumlah','$total','$presentase')");

            $koneksi->query("DELETE FROM hasil_sementara WHERE id_user = '$id_user'");
        }

        /* jika jumlah gejala lebih dari 1 */
    } else {

        /* perulangan sebanyak jumlah gejala */
        while ($setsementara = $getsementara->fetch_assoc()) {
            $id_gejala = $setsementara['jawaban'];

            /* mencocokan gejala dengan penyakit */
            $getpenyakit = $koneksi->query("SELECT * FROM penyakit_gejala WHERE id_gejala = '$id_gejala'");
            while ($setpenyakit = $getpenyakit->fetch_assoc()) {
                $id_penyakit = $setpenyakit['id_jenis_penyakit'];

                /* hasil penyakit yang cocok di tambahkan ke tabel hasil sementara */
                $koneksi->query("INSERT INTO hasil_sementara (id_user,id_jenis_penyakit,id_gejala) VALUES('$id_user','$id_penyakit','$id_gejala')");

                /* hapus data dari tabel sementara */
               // $koneksi->query("DELETE FROM sementara WHERE id_user = '$id_user'");
            }
        }

        $getkecocokan = $koneksi->query("SELECT id_jenis_penyakit , COUNT(*) AS jumlah from hasil_sementara WHERE id_user ='$id_user' GROUP BY id_jenis_penyakit ORDER BY COUNT(*) DESC ");

        while ($setkecocokan = $getkecocokan->fetch_assoc()) {
            $id_jenis_penyakit_cocok = $setkecocokan['id_jenis_penyakit'];
            $jumlah = $setkecocokan['jumlah'];

            /* ambil jumlah kemungkinan */
            $gettotal = $koneksi->query("SELECT COUNT(id_gejala) AS total_gejala FROM `penyakit_gejala` WHERE id_jenis_penyakit = '$id_jenis_penyakit_cocok'");
            $settotal = $gettotal->fetch_assoc();
            $total = $settotal['total_gejala'];

            /* presentase kemungkinan */
            $presentase = ($jumlah / $total) * 100;

            $koneksi->query("INSERT INTO hasil_kemungkinan (id_user,id_jenis_penyakit,jumlah_gejala,total_gejala,presentase) VALUES ('$id_user','$id_jenis_penyakit_cocok','$jumlah','$total','$presentase')");

           // $koneksi->query("DELETE FROM hasil_sementara WHERE id_user = '$id_user'");
        }
    }

    $ambil = $koneksi->query("SELECT MAX(presentase) AS presentase,id_jenis_penyakit FROM `hasil_kemungkinan` WHERE id_user='$id_user'");
    $pecah = $ambil->fetch_assoc();

    $hasilpresentase = $pecah['presentase'];
    $hasil_jenis_penyakit = $pecah['id_jenis_penyakit'];

    $tanggal = date("Y-m-d");

    $simpan = $koneksi->query("INSERT INTO hasil_pemeriksaan (id_jenis_penyakit,tanggal_pemeriksaan,presentase_hasil,id_user) VALUES ('$hasil_jenis_penyakit','$tanggal','$hasilpresentase','$id_user')");

    if ($simpan) {
        $lastinsertid = $koneksi->insert_id;
       // $koneksi->query("DELETE FROM hasil_kemungkinan WHERE id_user='$id_user'");

        echo "<script>location='diagnosa-hasil.php?id=$lastinsertid'</script>";
    }

}
