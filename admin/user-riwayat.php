   <?php $id = $_GET['id'] ?>
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
                $get = $koneksi->query("SELECT * FROM hasil_pemeriksaan JOIN jenis_penyakit ON hasil_pemeriksaan.id_jenis_penyakit = jenis_penyakit.id_jenis_penyakit WHERE id_user = '$id'") ?>
               <?php while ($set = $get->fetch_assoc()) : ?>
                   <tr>
                       <td style="vertical-align: middle" class="text-center"><?= $no ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= date("d M Y",strtotime($set['tanggal_pemeriksaan'])) ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= $set['nama_penyakit'] ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= $set['presentase_hasil'] ?> %</td>
                   </tr>
               <?php $no++;
                endwhile ?>
           </tbody>
       </table>
   </div>


   <!-- Button trigger modal -->


   <!-- Modal -->
   <form action="penyakit-tambah.php" method="post" enctype="multipart/form-data">

       <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel">Tambah Jenis Penyakit</h4>
                   </div>
                   <div class="modal-body">

                       <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
                       <div class="form-group">
                           <label for="">Nama</label>
                           <input type="text" class="form-control" name="nama">
                       </div>
                       <div class="form-group">
                           <label for="">Defenisi</label>
                           <textarea id="tek" rows="5" class="form-control" name="defenisi"></textarea>
                           <!-- <script>
                               CKEDITOR.replace('defenisi');
                           </script> -->
                       </div>
                       <div class="form-group">
                           <label for="">Penyebab</label>
                           <textarea id="" rows="5" class="form-control" name="penyebab"></textarea>
                           <!-- <script>
                               CKEDITOR.replace('penyebab');
                           </script> -->
                       </div>
                       <div class="form-group">
                           <label for="">Penanganan</label>
                           <textarea id="" rows="5" class="form-control" name="penanganan"></textarea>
                          <!--  <script>
                               CKEDITOR.replace('penanganan');
                           </script> -->
                       </div>

                   </div>
                   <div class="modal-footer">
                       <button class="btn btn-default" data-dismiss="modal">Close</button>
                       <button class="btn btn-primary" name="simpan">Tambah</button>
                   </div>
               </div>
           </div>
       </div>

   </form>
