-- Migrasi 004: rapikan master yatim + aktifkan FK penyakit_gejala.
-- Jalankan sekali pada database lama (SETELAH 002 dan 003):
--   mysql -u USAMU DBMU < database/migrations/004_master_fk.sql
--
-- Latar: seed bawaan lama mereferensikan id penyakit/gejala yang tidak ada
-- di tabel master (penyakit 1,3,4,5,6,9,11,12,13,15; gejala 1,2). Relasi
-- yang penyakitnya tidak ada di master tidak pernah bisa tampil (JOIN
-- memfilternya) sehingga aman dihapus. Dua gejala yang hilang (id 1,2)
-- dikembalikan dengan nama dari data legacy tabel `sementara`
-- ('Gusi Bengkak', 'Gusi Berdarah') karena masih dipakai penyakit
-- Gingivitis (id 17) yang terdaftar.

INSERT INTO `gejala` (`id_gejala`, `gejala`)
SELECT * FROM (SELECT 1 AS id_gejala, 'Gusi Bengkak' AS gejala) AS x
WHERE NOT EXISTS (SELECT 1 FROM `gejala` WHERE `id_gejala` = 1);

INSERT INTO `gejala` (`id_gejala`, `gejala`)
SELECT * FROM (SELECT 2 AS id_gejala, 'Gusi Berdarah' AS gejala) AS x
WHERE NOT EXISTS (SELECT 1 FROM `gejala` WHERE `id_gejala` = 2);

DELETE `pg` FROM `penyakit_gejala` AS `pg`
LEFT JOIN `jenis_penyakit` AS `jp` ON `jp`.`id_jenis_penyakit` = `pg`.`id_jenis_penyakit`
WHERE `jp`.`id_jenis_penyakit` IS NULL;

DELETE `pg` FROM `penyakit_gejala` AS `pg`
LEFT JOIN `gejala` AS `g` ON `g`.`id_gejala` = `pg`.`id_gejala`
WHERE `g`.`id_gejala` IS NULL;

ALTER TABLE `penyakit_gejala`
  ADD CONSTRAINT `fk_pg_penyakit` FOREIGN KEY (`id_jenis_penyakit`) REFERENCES `jenis_penyakit` (`id_jenis_penyakit`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pg_gejala` FOREIGN KEY (`id_gejala`) REFERENCES `gejala` (`id_gejala`) ON DELETE CASCADE;
