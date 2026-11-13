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

   <?php $id = (int)($_GET['id'] ?? 0); ?>
   <?php
    $stmtBobot = $koneksi->prepare(
        'SELECT COUNT(*) AS total,'
        . " SUM(mb = 0.80 AND md = 0.20) AS contoh FROM penyakit_gejala WHERE id_jenis_penyakit = ?"
    );
    $stmtBobot->bind_param('i', $id);
    $stmtBobot->execute();
    $infoBobot = $stmtBobot->get_result()->fetch_assoc();
    $stmtBobot->close();
    ?>
   <?php if (($infoBobot['contoh'] ?? 0) > 0) : ?>
   <div class="alert alert-warning">
       <?= (int)$infoBobot['contoh'] ?> dari <?= (int)$infoBobot['total'] ?> relasi masih memakai
       bobot contoh (MB 0,80 / MD 0,20). Validasi ke pakar sebelum dipakai produksi.
   </div>
   <?php endif; ?>

   <div class="table-responsive">
       <table class="table table-striped table-bordered" id="tabelid" style="width:100%">
           <thead>
               <tr>
                   <th style="vertical-align: middle" class="text-center">No</th>
                   <th style="vertical-align: middle" class="text-center">Gejala</th>
                   <th style="vertical-align: middle" class="text-center">Bobot CF (MB / MD)</th>
                   <th style="vertical-align: middle" class="text-center">Aksi</th>
               </tr>
           </thead>
           <tbody>
               <?php $no = 1;
                $sql = 'SELECT pg.id_penyakit_gejala, g.gejala, pg.mb, pg.md FROM jenis_penyakit jp'
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
                           <form action="" method="post" class="form-inline justify-content-center">
                               <?= csrf_field() ?>
                               <input type="hidden" name="id_relasi" value="<?= (int)$set['id_penyakit_gejala'] ?>">
                               <input type="number" name="mb" class="form-control input-sm" style="width:80px" min="0" max="1" step="0.01" value="<?= e(isset($set['mb']) ? $set['mb'] : '') ?>" required title="Measure of Belief (0-1)">
                               <input type="number" name="md" class="form-control input-sm" style="width:80px" min="0" max="1" step="0.01" value="<?= e(isset($set['md']) ? $set['md'] : '') ?>" required title="Measure of Disbelief (0-1)">
                               <button type="submit" class="btn btn-warning btn-sm" name="ubah_bobot" title="Simpan bobot"><i class="fa fa-save"></i></button>
                           </form>
                           <small>CF: <?= e(number_format((float)(isset($set['mb']) ? $set['mb'] : 0) - (float)(isset($set['md']) ? $set['md'] : 0), 2)) ?></small>
                       </td>
                       <td style="vertical-align: middle" class="text-center">
                           <div class="btn-group-vertical" role="group" aria-label="...">
                               <a href="penyakit-detail-hapus.php?id=<?= (int)$set['id_penyakit_gejala'] ?>&idp=<?= (int)$id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Hapus')"><i class="fa fa-times"></i> Hapus</a>
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
                           <label for="">MB - Measure of Belief (0-1)</label>
                           <input type="number" name="mb" class="form-control" min="0" max="1" step="0.01" value="0.80" required>
                       </div>
                       <div class="form-group">
                           <label for="">MD - Measure of Disbelief (0-1)</label>
                           <input type="number" name="md" class="form-control" min="0" max="1" step="0.01" value="0.20" required>
                       </div>
                        <div class="form-group">
                            <label for="">Gejala</label>
                           <select name="gejala" class="form-control" required>
                               <?php $getgejala = $koneksi->query(
                                    'SELECT id_gejala, gejala FROM gejala ORDER BY gejala ASC'
                                ) ?>
                               <?php while ($setgejala = $getgejala->fetch_assoc()) : ?>
                                   <option value="<?= (int)$setgejala['id_gejala'] ?>"><?= e($setgejala['gejala']) ?></option>
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
    if (isset($_POST['ubah_bobot'])) {
        csrf_check();
        $id_relasi = (int)($_POST['id_relasi'] ?? 0);
        $mb = isset($_POST['mb']) ? (float)$_POST['mb'] : -1.0;
        $md = isset($_POST['md']) ? (float)$_POST['md'] : -1.0;
        if ($id <= 0 || $id_relasi <= 0 || $mb < 0.0 || $mb > 1.0 || $md < 0.0 || $md > 1.0) {
            redirect_js('index.php?page=penyakit-detail&id=' . (int)$id, 'Bobot tidak valid (isi 0-1)', 'warning');
        } else {
            $upd = $koneksi->prepare(
                'UPDATE penyakit_gejala SET mb = ?, md = ?'
                . ' WHERE id_penyakit_gejala = ? AND id_jenis_penyakit = ?'
            );
            $upd->bind_param('ddii', $mb, $md, $id_relasi, $id);
            if ($upd->execute()) {
                $upd->close();
                audit($koneksi, 'ubah_bobot', 'relasi=' . $id_relasi . ' mb=' . $mb . ' md=' . $md);
                redirect_js('index.php?page=penyakit-detail&id=' . (int)$id, 'Bobot berhasil diubah', 'success');
            } else {
                error_log('Ubah bobot gagal: ' . $upd->error);
                $upd->close();
                redirect_js('index.php?page=penyakit-detail&id=' . (int)$id, 'Gagal mengubah bobot', 'danger');
            }
        }
    }

    if (isset($_POST['simpan'])) {
        csrf_check();
        $id_gejala = (int)($_POST['gejala'] ?? 0);
        $mb = isset($_POST['mb']) ? (float)$_POST['mb'] : -1.0;
        $md = isset($_POST['md']) ? (float)$_POST['md'] : -1.0;
        if ($id <= 0 || $id_gejala <= 0 || $mb < 0.0 || $mb > 1.0 || $md < 0.0 || $md > 1.0) {
            redirect_js('index.php?page=penyakit-detail&id=' . (int)$id, 'Relasi tidak valid', 'warning');
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
                redirect_js('index.php?page=penyakit-detail&id=' . (int)$id, 'Relasi sudah ada', 'warning');
            } else {
                $cek->close();
                $ins = $koneksi->prepare(
                    'INSERT INTO penyakit_gejala (id_jenis_penyakit, id_gejala, mb, md) VALUES (?, ?, ?, ?)'
                );
                $ins->bind_param('iidd', $id, $id_gejala, $mb, $md);
                if ($ins->execute()) {
                    $ins->close();
                    audit($koneksi, 'tambah_relasi', 'penyakit=' . $id . ' gejala=' . $id_gejala);
                    redirect_js('index.php?page=penyakit-detail&id=' . (int)$id, 'Data Berhasil Ditambahkan', 'success');
                } else {
                    error_log('Tambah relasi gagal: ' . $ins->error);
                    $ins->close();
                    redirect_js('index.php?page=penyakit-detail&id=' . (int)$id, 'Gagal menyimpan relasi', 'danger');
                }
            }
        }
    }
