-- Migrasi 002: bobot Certainty Factor untuk relasi penyakit-gejala.
-- Jalankan sekali pada database lama:
--   mysql -u USamu DBMU < database/migrations/002_cf_bobot.sql
--
-- Kolom mb (measure of belief) dan md (measure of disbelief), rentang 0.00-1.00.
-- Nilai CF per relasi = mb - md.
--
-- PENTING: nilai awal di bawah ini adalah CONTOH agar aplikasi langsung jalan.
-- Ganti dengan nilai hasil konsultasi pakar (dokter gigi / dosen pembimbing)
-- melalui panel admin (Jenis Penyakit > Detail) sebelum dipakai produksi.

ALTER TABLE `penyakit_gejala`
  ADD COLUMN `mb` DECIMAL(3,2) NULL DEFAULT NULL AFTER `id_gejala`,
  ADD COLUMN `md` DECIMAL(3,2) NULL DEFAULT NULL AFTER `mb`;

UPDATE `penyakit_gejala` SET `mb` = 0.80, `md` = 0.20 WHERE `mb` IS NULL OR `md` IS NULL;
