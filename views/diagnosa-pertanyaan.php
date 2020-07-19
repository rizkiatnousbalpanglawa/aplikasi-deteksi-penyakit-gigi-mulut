<div class="container">
    <h3 class="mt-2"> <i class="fa fa-stethoscope"></i> Diagnosa</h3>
    <hr class="mt-0 bg-biru">

    <div class="card shadow">
        <div class="card-body">
            <h5>Bantu kami mengetahui apa yang anda alami!</h5><br>

            <form action="diagnosa-pertanyaan-hasil.php" method="post" enctype="multipart/form-data">

                <?php $get = $koneksi->query("SELECT * FROM gejala") ?>
                <?php while ($set = $get->fetch_assoc()) : ?>
                    <?php $id_pertanyaan = $set['id_gejala'] ?>
                    <p class="text-center mb-0"><?= $set['gejala'] ?></p>
                    <div class="text-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="idpertanyaan<?= $id_pertanyaan ?>" id="inlineRadio1_<?= $id_pertanyaan ?>" value="<?= $set['id_gejala'] ?>" required>
                            <label class="form-check-label" for="inlineRadio1_<?= $id_pertanyaan ?>">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="idpertanyaan<?= $id_pertanyaan ?>" id="inlineRadio2_<?= $id_pertanyaan ?>" value="0">
                            <label class="form-check-label" for="inlineRadio2_<?= $id_pertanyaan ?>">Tidak</label>
                        </div>
                    </div>
                    <hr>
                <?php endwhile ?>

                <div class="form-group">
                    <button class="btn bg-biru btn-block rounded-0" name="simpan"><i class="fa fa-stethoscope"></i> SIMPAN</button>

                </div>
            </form>
        </div>
    </div>
    
</div>
<br><br><br>