<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Custom Fonts -->
    <link href="../assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css">
    <?php include "../config/koneksi.php" ?>
</head>

<body class="bg-putih">
    <div class="preloader">
        <div class="loading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    
    <div class="container">
        <h3 class="mt-2"> <i class="fa fa-th-list"></i> Daftar</h3>
        <hr class="mt-0 bg-biru">

        <form action="register-act.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="">Nama</label>
                <input type="text" class="form-control" placeholder="Nama Saya" name="nama" required>
            </div>
            <div class="form-group">
                <label for="">Tanggal Lahir</label>
                <input type="datetime-local" class="form-control" name="tgl_lahir" id="" required>
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
    <!-- bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="../assets/js/bootstrap-show-password.min.js"></script>

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        $(window).load(function() {
            $(".preloader").fadeOut();
        })
    </script>
</body>

</html>