-- Migrasi 003: kandidat diagnosis banding di hasil_pemeriksaan.
-- Jalankan sekali pada database lama:
--   mysql -u USAMU DBMU < database/migrations/003_kandidat_banding.sql
--
-- Menyimpan 2 kandidat teratas selain pemenang agar halaman hasil bisa
-- menampilkan "Diagnosis banding" (transparansi bila skor seri/rapat).

ALTER TABLE `hasil_pemeriksaan`
  ADD COLUMN `kandidat2_id` int(11) NULL DEFAULT NULL AFTER `presentase_hasil`,
  ADD COLUMN `kandidat2_skor` varchar(100) NULL DEFAULT NULL AFTER `kandidat2_id`,
  ADD COLUMN `kandidat3_id` int(11) NULL DEFAULT NULL AFTER `kandidat2_skor`,
  ADD COLUMN `kandidat3_skor` varchar(100) NULL DEFAULT NULL AFTER `kandidat3_id`;

ALTER TABLE `hasil_pemeriksaan`
  ADD CONSTRAINT `fk_hp_kand2` FOREIGN KEY (`kandidat2_id`) REFERENCES `jenis_penyakit` (`id_jenis_penyakit`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_hp_kand3` FOREIGN KEY (`kandidat3_id`) REFERENCES `jenis_penyakit` (`id_jenis_penyakit`) ON DELETE RESTRICT;
