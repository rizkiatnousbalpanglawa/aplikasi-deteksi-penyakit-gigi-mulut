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
                   Ubah Penyakit
               </li>
           </ol>
       </div>
   </div>
   <!-- /.row -->

   <!-- ambil data -->
   <?php
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $koneksi->prepare(
        'SELECT id_jenis_penyakit, nama_penyakit, defenisi, penyebab, penanganan'
        . ' FROM jenis_penyakit WHERE id_jenis_penyakit = ? LIMIT 1'
    );
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $get = $stmt->get_result();
    $set = $get ? $get->fetch_assoc() : null;
    $stmt->close();
    if (!$set) {
        redirect_js('index.php?page=penyakit', 'Data tidak ditemukan', 'danger');
        return;
    }
    ?>

   <form action="" method="post">
       <?= csrf_field() ?>
       <div class="form-group">
           <label for="">Nama</label>
           <input type="text" class="form-control" name="nama" value="<?= e($set['nama_penyakit']) ?>" required maxlength="100">
       </div>
       <div class="form-group">
           <label for="">Defenisi</label>
           <textarea rows="5" class="form-control" name="defenisi" required><?= e($set['defenisi']) ?></textarea>
       </div>
       <div class="form-group">
           <label for="">Penyebab</label>
           <textarea rows="5" class="form-control" name="penyebab" required><?= e($set['penyebab']) ?></textarea>
       </div>
       <div class="form-group">
           <label for="">Penanganan</label>
           <textarea rows="5" class="form-control" name="penanganan" required><?= e($set['penanganan']) ?></textarea>
       </div>
       <div class="form-group text-right">
           <button type="submit" class="btn btn-secondary" name="kembali" formnovalidate>Kembali</button>
           <button type="submit" class="btn btn-primary" name="ubah">Ubah</button>
       </div>
   </form>

   <?php
    if (isset($_POST['ubah'])) {
        csrf_check();
        $nama = trim((string)($_POST['nama'] ?? ''));
        $defenisi = trim((string)($_POST['defenisi'] ?? ''));
        $penyebab = trim((string)($_POST['penyebab'] ?? ''));
        $penanganan = trim((string)($_POST['penanganan'] ?? ''));

        if ($nama === '' || $defenisi === '' || $penyebab === '' || $penanganan === '') {
            redirect_js('index.php?page=penyakit-ubah&id=' . (int)$id, 'Semua field wajib diisi', 'warning');
        } else {
            $upd = $koneksi->prepare(
                'UPDATE jenis_penyakit SET nama_penyakit = ?, defenisi = ?,'
                . ' penyebab = ?, penanganan = ? WHERE id_jenis_penyakit = ?'
            );
            $upd->bind_param('ssssi', $nama, $defenisi, $penyebab, $penanganan, $id);
            if ($upd->execute()) {
                $upd->close();
                redirect_js('index.php?page=penyakit', 'Data Telah Diubah', 'success');
            } else {
                error_log('Ubah penyakit gagal: ' . $upd->error);
                $upd->close();
                redirect_js('index.php?page=penyakit-ubah&id=' . (int)$id, 'Gagal mengubah data', 'danger');
            }
        }
    } elseif (isset($_POST['kembali'])) {
        redirect_js('index.php?page=penyakit');
    }
