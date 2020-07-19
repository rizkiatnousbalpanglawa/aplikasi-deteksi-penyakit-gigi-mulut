   <!-- Page Heading -->
   <div class="row">
       <div class="col-lg-12">
           <h1 class="page-header">
               User <small>Halaman User</small>
           </h1>
           <ol class="breadcrumb">
               <li class="active">
                   <i class="fa fa-user"></i> User
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
                   <th style="vertical-align: middle" class="text-center">Nama</th>
                   <th style="vertical-align: middle" class="text-center">Tanggal Lahir</th>
                   <th style="vertical-align: middle" class="text-center">Alamat</th>
                   <th style="vertical-align: middle" class="text-center">No. HP</th>
                   <th style="vertical-align: middle" class="text-center">Aksi</th>
               </tr>
           </thead>
           <tbody>
               <?php $no = 1;
                $get = $koneksi->query("SELECT * FROM user") ?>
               <?php while ($set = $get->fetch_assoc()) : ?>
                   <tr>
                       <td style="vertical-align: middle" class="text-center"><?= $no ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= $set['nama'] ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= date("d M Y",strtotime($set['tgl_lahir'])) ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= $set['alamat'] ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= $set['no_hp'] ?></td>
                       <td style="vertical-align: middle" class="text-center">
                           <a href="index.php?page=user-riwayat&id=<?= $set['id_user'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-file-o"></i> Riwayat</a>
                           <a href="" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Hapus</a>
                       </td>
                   </tr>
               <?php $no++; endwhile ?>
           </tbody>
       </table>
   </div>