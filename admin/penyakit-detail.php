   <!-- Page Heading -->
   <div class="row">
       <div class="col-lg-12">
           <h1 class="page-header">
               Jenis Penyakit <small>Berisi jenis - jenis penyakit gigi dan mulut</small>
           </h1>
           <ol class="breadcrumb">
               <li class="">
                   <i class="fa fa-th-list"></i> Jenis - Jenis Penyakit
               </li>
               <li class="active">
                   Detail Penyakit
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

   <?php $id = (int) ($_GET['id'] ?? 0); ?>

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
                $sql = 'SELECT pg.id_penyakit_gejala, g.gejala FROM jenis_penyakit jp'
                    . ' JOIN penyakit_gejala pg ON jp.id_jenis_penyakit = pg.id_jenis_penyakit'
                    . ' JOIN gejala g ON pg.id_gejala = g.id_gejala'
                    . ' WHERE jp.id_jenis_penyakit = ? ORDER BY g.gejala ASC';
                $stmtDetail = $koneksi->prepare($sql);
                $stmtDetail->bind_param('i', $id);
                $stmtDetail->execute();
                $get = $stmtDetail->get_result(); ?>
               <?php while ($set = $get->fetch_assoc()) : ?>
                   <tr>
                       <td style="vertical-align: middle" class="text-center"><?= $no ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= e($set['gejala']) ?></td>
                       <td style="vertical-align: middle" class="text-center">
                           <div class="btn-group-vertical" role="group" aria-label="...">
                               <a href="penyakit-detail-hapus.php?id=<?= (int) $set['id_penyakit_gejala'] ?>&idp=<?= (int) $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Hapus')"><i class="fa fa-times"></i> Hapus</a>
                           </div>
                       </td>
                   </tr>
               <?php $no++;
                endwhile;
                $stmtDetail->close(); ?>
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
                       <h4 class="modal-title" id="myModalLabel">Tambah Relasi Gejala</h4>
                   </div>
                   <div class="modal-body">
                       <div class="form-group">
                           <label for="">Gejala</label>
                           <select name="gejala" class="form-control" required>
                               <?php $getgejala = $koneksi->query(
                                    'SELECT id_gejala, gejala FROM gejala ORDER BY gejala ASC'
                                ) ?>
                               <?php while ($setgejala = $getgejala->fetch_assoc()) : ?>
                                   <option value="<?= (int) $setgejala['id_gejala'] ?>"><?= e($setgejala['gejala']) ?></option>
                               <?php endwhile ?>
                           </select>
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
        $id_gejala = (int) ($_POST['gejala'] ?? 0);
        if ($id <= 0 || $id_gejala <= 0) {
            echo "<script>alert('Relasi tidak valid');"
                . "location='index.php?page=penyakit-detail&id=" . (int) $id . "'</script>";
        } else {
            $cek = $koneksi->prepare(
                'SELECT id_penyakit_gejala FROM penyakit_gejala'
                . ' WHERE id_jenis_penyakit = ? AND id_gejala = ? LIMIT 1'
            );
            $cek->bind_param('ii', $id, $id_gejala);
            $cek->execute();
            $ada = $cek->get_result();
            if ($ada && $ada->num_rows > 0) {
                $cek->close();
                echo "<script>alert('Relasi sudah ada');"
                    . "location='index.php?page=penyakit-detail&id=" . (int) $id . "'</script>";
            } else {
                $cek->close();
                $ins = $koneksi->prepare("INSERT INTO penyakit_gejala (id_jenis_penyakit, id_gejala) VALUES (?, ?)");
                $ins->bind_param('ii', $id, $id_gejala);
                if ($ins->execute()) {
                    $ins->close();
                    echo "<script>alert('Data Berhasil Ditambahkan');"
                        . "location='index.php?page=penyakit-detail&id=" . (int) $id . "'</script>";
                } else {
                    error_log('Tambah relasi gagal: ' . $ins->error);
                    $ins->close();
                    echo "<script>alert('Gagal menyimpan relasi');"
                        . "location='index.php?page=penyakit-detail&id=" . (int) $id . "'</script>";
                }
            }
        }
    }
