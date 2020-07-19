<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['admin']['user'])) {
    echo "<script>alert('Login Dulu');location='../login/'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Penyakit Gigi dan Mulut</title>

    <!-- Bootstrap Core CSS -->
    <link type="text/css" href="../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link type="text/css" href="../assets/css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link type="text/css" href="../assets/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Datatable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="">Admin</a>
            </div>

            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?= $_SESSION['admin']['user'] ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li><a href="index.php?page=user"><i class="fa fa-fw fa-user"></i> Daftar User</a></li>
                    <li><a href="index.php?page=penyakit"><i class="fa fa-fw fa-th-list"></i> Jenis Penyakit</a></li>
                    <li><a href="index.php?page=gejala"><i class="fa fa-fw fa-th-list"></i> Gejala Penyakit</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->

        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <?php
                if (isset($_GET['page'])) {
                    switch ($_GET['page']) {
                        case 'user':
                            include "user.php";
                            break;
                        case 'penyakit':
                            include "penyakit.php";
                            break;
                        case 'penyakit-ubah':
                            include "penyakit-ubah.php";
                            break;
                        case 'penyakit-hapus':
                            include "penyakit-hapus.php";
                            break;
                        case 'penyakit-detail':
                            include "penyakit-detail.php";
                            break;
                        case 'gejala':
                            include "gejala.php";
                            break;
                        case 'user-riwayat';
                            include "user-riwayat.php";
                        break;
                        default:
                            include "user.php";
                            break;
                    }
                } else {
                    include "user.php";
                }
                ?>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../assets/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../assets/js/bootstrap.min.js"></script>

    <!-- datatable -->
    <!--  <script type="text/javascript" charset="utf8" src="css/jquery.dataTables.min.js"></script> -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>



    <script>
        $(document).ready(function() {
            $('#tabelid').DataTable();
        });
    </script>


    <!-- Morris Charts JavaScript -->
    <script src="../assets/js/plugins/morris/raphael.min.js"></script>
    <script src="../assets/js/plugins/morris/morris.min.js"></script>
    <script src="../assets/js/plugins/morris/morris-data.js"></script>

</body>

</html>