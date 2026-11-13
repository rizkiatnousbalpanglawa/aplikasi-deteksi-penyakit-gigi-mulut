   <!-- Page Heading -->
   <div class="row">
       <div class="col-lg-12">
           <h1 class="page-header">
               Audit Log <small>Berisi jejak aktivitas admin dan user</small>
           </h1>
           <ol class="breadcrumb">
               <li class="active">
                   <i class="fa fa-list"></i> Audit Log
               </li>
           </ol>
       </div>
   </div>
   <!-- /.row -->

   <div class="table-responsive">
       <table class="table table-striped table-bordered" id="tabelid" style="width:100%">
           <thead>
               <tr>
                   <th style="vertical-align: middle" class="text-center">Waktu</th>
                   <th style="vertical-align: middle" class="text-center">Aktor</th>
                   <th style="vertical-align: middle" class="text-center">Peran</th>
                   <th style="vertical-align: middle" class="text-center">Aksi</th>
                   <th style="vertical-align: middle" class="text-center">Detail</th>
                   <th style="vertical-align: middle" class="text-center">IP</th>
               </tr>
           </thead>
           <tbody>
               <?php $get = $koneksi->query(
                    'SELECT waktu, aktor, peran, aksi, detail, ip FROM audit_log'
                    . ' ORDER BY id_audit DESC LIMIT 200'
                ) ?>
               <?php if ($get) : ?>
               <?php while ($set = $get->fetch_assoc()) : ?>
                   <tr>
                       <td style="vertical-align: middle" class="text-center"><?= e($set['waktu']) ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= e($set['aktor']) ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= e($set['peran']) ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= e($set['aksi']) ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= e($set['detail'] ?? '') ?></td>
                       <td style="vertical-align: middle" class="text-center"><?= e($set['ip'] ?? '') ?></td>
                   </tr>
               <?php endwhile; ?>
               <?php endif; ?>
           </tbody>
       </table>
   </div>
