   <?php $id = (int) ($_GET['id'] ?? 0); ?>
   <!-- Page Heading -->
   <div class="row">
       <div class="col-lg-12">
           <h1 class="page-header">
               Riwayat User <small>Berisi riwayat diagnosa User</small>
           </h1>
           <ol class="breadcrumb">
               <li class="active">
                   <i class="fa fa-th-list"></i> Riwayat Diagnosa
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
                   <th style="vertical-align: middle" class="text-center">Tanggal Pemeriksaan</th>
                   <th style="vertical-align: middle" class="text-center">Jenis Penyakit</th>
                   <th style="vertical-align: middle" class="text-center">Presentase</th>
               </tr>
           </thead>
           <tbody>
               <?php $no = 1;
                $sql = 'SELECT hp.tanggal_pemeriksaan, jp.nama_penyakit, hp.presentase_hasil'
                    . ' FROM hasil_pemeriksaan hp'
                    . ' JOIN jenis_penyakit jp ON hp.id_jenis_penyakit = jp.id_jenis_penyakit'
                    . ' WHERE hp.id_user = ?'
                    . ' ORDER BY hp.tanggal_pemeriksaan DESC, hp.id_hasil_pemeriksaan DESC';
                $stmtRiwayat = $koneksi->prepare($sql);
                $stmtRiwayat->bind_param('i', $id);
                $stmtRiwayat->execute();
                $get = $stmtRiwayat->get_result(); ?>
               <?php while ($set = $get->fetch_assoc()) : ?>
                   <tr>
                       <td style="vertical-align: middle" class="text-center"><?= $no ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= e(date("d M Y", strtotime($set['tanggal_pemeriksaan']))) ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= e($set['nama_penyakit']) ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= e($set['presentase_hasil']) ?> %</td>
                   </tr>
               <?php $no++;
                endwhile;
                $stmtRiwayat->close(); ?>
           </tbody>
       </table>
   </div>
