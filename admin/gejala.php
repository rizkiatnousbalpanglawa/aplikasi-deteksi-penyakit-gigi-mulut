   <!-- Page Heading -->
   <div class="row">
       <div class="col-lg-12">
           <h1 class="page-header">
               Gejala Penyakit <small>Berisi jenis - jenis gejala penyakit gigi dan mulut</small>
           </h1>
           <ol class="breadcrumb">
               <li class="">
                   <i class="fa fa-th-list"></i> Gejala
               </li>
               <li>
                   <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                       <i class="fa fa-plus"></i>
                   </button>
               </li>
           </ol>
       </div>
   </div>
   <!-- /.row -->

   <div class="table-responsive">
       <table class="table table-striped table-bordered" id="tabelid" style="width:100%">
           <thead>
               <tr>
                   <th style="vertical-align: middle" class="text-center">No</th>
                   <th style="vertical-align: middle" class="text-center">Gejala</th>
                   <th style="vertical-align: middle" class="text-center">Aksi</th>
               </tr>
           </thead>
           <tbody>
               <?php $no = 1;
                $get = $koneksi->query("SELECT id_gejala, gejala FROM gejala ORDER BY id_gejala ASC") ?>
               <?php while ($set = $get->fetch_assoc()) : ?>
                   <tr>
                       <td style="vertical-align: middle" class="text-center"><?= $no ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= e($set['gejala']) ?></td>
                       <td style="vertical-align: middle" class="text-center">
                           <div class="btn-group-vertical" role="group" aria-label="...">
                               <a href="gejala-hapus.php?id=<?= (int) $set['id_gejala'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Hapus')"><i class="fa fa-times"></i> Hapus</a>
                           </div>
                       </td>
                   </tr>
               <?php $no++;
                endwhile ?>
           </tbody>
       </table>
   </div>

   <!-- Modal -->
   <form action="" method="post" enctype="multipart/form-data">
       <?= csrf_field() ?>
       <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel">Tambah Gejala</h4>
                   </div>
                   <div class="modal-body">
                       <div class="form-group">
                           <label for="">Gejala</label>
                           <input type="text" class="form-control" name="nama" required maxlength="255">
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       <button type="submit" class="btn btn-primary" name="simpan">Tambah</button>
                   </div>
               </div>
           </div>
       </div>
   </form>

   <?php
    if (isset($_POST['simpan'])) {
       csrf_check();
       $nama = trim((string) ($_POST['nama'] ?? ''));
       if ($nama === '') {
           echo "<script>alert('Gejala wajib diisi');location='index.php?page=gejala'</script>";
       } else {
           $stmt = $koneksi->prepare("INSERT INTO gejala (gejala) VALUES (?)");
           $stmt->bind_param('s', $nama);
           if ($stmt->execute()) {
               $stmt->close();
               echo "<script>alert('Data Berhasil Ditambahkan');location='index.php?page=gejala'</script>";
           } else {
               error_log('Tambah gejala gagal: ' . $stmt->error);
               $stmt->close();
               echo "<script>alert('Gagal menyimpan data');location='index.php?page=gejala'</script>";
           }
       }
    }
