   <!-- Page Heading -->
   <div class="row">
       <div class="col-lg-12">
           <h1 class="page-header">
               Jenis Penyakit <small>Berisi jenis - jenis penyakit gigi dan mulut</small>
           </h1>
           <ol class="breadcrumb">
               <li class="active">
                   <i class="fa fa-th-list"></i> Jenis - Jenis Penyakit

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
                   <th style="vertical-align: middle" class="text-center">Nama Penyakit</th>
                   <th style="vertical-align: middle" class="text-center">Defenisi</th>
                   <th style="vertical-align: middle" class="text-center">Penyebab</th>
                   <th style="vertical-align: middle" class="text-center">Penanganan</th>
                   <th style="vertical-align: middle" class="text-center">Aksi</th>
               </tr>
           </thead>
           <tbody>
               <?php $no = 1;
                $get = $koneksi->query("SELECT * FROM jenis_penyakit") ?>
               <?php while ($set = $get->fetch_assoc()) : ?>
                   <tr>
                       <td style="vertical-align: middle" class="text-center"><?= $no ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= $set['nama_penyakit'] ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= $set['defenisi'] ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= $set['penyebab'] ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= $set['penanganan'] ?></td>
                       <td style="vertical-align: middle" class="text-center">
                           <div class="btn-group-vertical" role="group" aria-label="...">
                               <a href="index.php?page=penyakit-detail&id=<?= $set['id_jenis_penyakit'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-file-o"></i> Detail</a>
                               <a href="index.php?page=penyakit-ubah&id=<?= $set['id_jenis_penyakit'] ?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Ubah</a>
                               <a href="index.php?page=penyakit-hapus&id=<?= $set['id_jenis_penyakit'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Hapus')"><i class="fa fa-times"></i> Hapus</a>
                           </div>
                       </td>
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
                       </div>
                       <div class="form-group">
                           <label for="">Penyebab</label>
                           <textarea id="" rows="5" class="form-control" name="penyebab"></textarea>
                       </div>
                       <div class="form-group">
                           <label for="">Penanganan</label>
                           <textarea id="" rows="5" class="form-control" name="penanganan"></textarea>
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

   <?php
