-- Skema bersih aplikasi deteksi penyakit gigi & mulut.
-- Tanpa data PII. Password admin memakai hash bcrypt untuk 'admin123' — segera ganti setelah login.
-- Import: mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS gigi_mulut CHARACTER SET utf8mb4" && mysql -u root -p gigi_mulut < database/gigi_mulut.sql

SET NAMES utf8mb4;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `admin` (
  `username` varchar(100) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `gejala` (
  `id_gejala` int(11) NOT NULL AUTO_INCREMENT,
  `gejala` text NOT NULL,
  PRIMARY KEY (`id_gejala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `jenis_penyakit` (
  `id_jenis_penyakit` int(11) NOT NULL AUTO_INCREMENT,
  `nama_penyakit` varchar(100) NOT NULL,
  `defenisi` text NOT NULL,
  `penyebab` text NOT NULL,
  `penanganan` text NOT NULL,
  PRIMARY KEY (`id_jenis_penyakit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `penyakit_gejala` (
  `id_penyakit_gejala` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis_penyakit` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  `mb` DECIMAL(3,2) NULL DEFAULT NULL COMMENT 'Measure of belief pakar (contoh, wajib divalidasi)',
  `md` DECIMAL(3,2) NULL DEFAULT NULL COMMENT 'Measure of disbelief pakar (contoh, wajib divalidasi)',
  PRIMARY KEY (`id_penyakit_gejala`),
  UNIQUE KEY `uq_relasi` (`id_jenis_penyakit`, `id_gejala`),
  KEY `idx_pg_penyakit` (`id_jenis_penyakit`),
  KEY `idx_pg_gejala` (`id_gejala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(100) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `uq_user_nama` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `hasil_pemeriksaan` (
  `id_hasil_pemeriksaan` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis_penyakit` int(11) NOT NULL,
  `tanggal_pemeriksaan` date NOT NULL,
  `presentase_hasil` varchar(100) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_hasil_pemeriksaan`),
  KEY `idx_hp_user` (`id_user`),
  KEY `idx_hp_penyakit` (`id_jenis_penyakit`),
  CONSTRAINT `fk_hp_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `fk_hp_penyakit` FOREIGN KEY (`id_jenis_penyakit`) REFERENCES `jenis_penyakit` (`id_jenis_penyakit`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `hasil_kemungkinan` (
  `id_hasil_kemungkinan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_jenis_penyakit` int(11) NOT NULL,
  `jumlah_gejala` int(11) NOT NULL,
  `total_gejala` int(11) NOT NULL,
  `presentase` decimal(4,1) NOT NULL,
  PRIMARY KEY (`id_hasil_kemungkinan`),
  KEY `idx_hk_user` (`id_user`),
  CONSTRAINT `fk_hk_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `hasil_sementara` (
  `id_hasil_sementara` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_jenis_penyakit` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  PRIMARY KEY (`id_hasil_sementara`),
  KEY `idx_hs_user` (`id_user`),
  CONSTRAINT `fk_hs_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `sementara` (
  `id_sementara` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `gejala` text NOT NULL,
  `jawaban` text NOT NULL,
  PRIMARY KEY (`id_sementara`),
  KEY `idx_s_user` (`id_user`),
  CONSTRAINT `fk_s_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed admin (ganti password setelah login via DB / fitur admin)
INSERT INTO `admin` (`username`, `kata_sandi`) VALUES ('admin', '$2y$12$OWB9kOL7ELW3Ec3E3//u7uqdb1Y5yMIwxZC95yQWxT2b./omEMKIG')
ON DUPLICATE KEY UPDATE `kata_sandi` = VALUES(`kata_sandi`);

-- Catatan integritas: data bawaan lama mengandung id penyakit/gejala yang tidak ada di tabel master,
-- sehingga FK di penyakit_gejala sengaja tidak dipasang. Rapikan master via panel admin,
-- lalu aktifkan FK bila data sudah konsisten.
-- Seed master (tanpa data pasien / hasil)
INSERT INTO `gejala` (`id_gejala`, `gejala`) VALUES
(3, 'Gusi Sakit Jika Disentuh'),
(4, 'Radang Gusi'),
(5, 'Gusi Mengkilat'),
(6, 'Gigi Berlubang'),
(7, 'Gigi Nyeri'),
(8, 'Gigi Ngilu'),
(9, 'Gigi Patah'),
(10, 'Bau Napas Tidak Sedap'),
(11, 'Gigi sakit jika makan/minum panas atau dingin'),
(12, 'Mulut terasa pahit'),
(13, 'Gigi terasa goyang'),
(14, 'Nyeri saat melepaskan tekanan gigitan pada objek'),
(15, 'Benjolan pada gusi'),
(16, 'Sakit disekitar sendi rahang'),
(17, 'Sakit disekitar telinga'),
(18, 'Kesulitan menelan makanan'),
(19, 'Sakit disekitar wajah'),
(20, 'Ada suara clicking ketika mengunyah makanan atau membuka mulut  '),
(21, 'Rahang bagian mulut sulit dibuka atau ditutup'),
(22, 'Sakit disekitar kepala'),
(23, 'Gigitan yang terasa tidak pas'),
(24, 'Sakit dan bengkak pada leher'),
(25, 'Leher menjadi merah'),
(26, 'Badan menjadi demam'),
(27, 'Badan terasa lemah'),
(28, 'Badan terasa lesu'),
(29, 'Badan menjadi mudah capek'),
(30, 'Pikiran terasa bingung atau linglung '),
(31, 'Perubahan mental dan kesulitan bernapas'),
(32, 'Gusi merah terang atau keunguan '),
(33, 'Gusi terasa tebal ketika disentuh'),
(34, 'Gusi yang terdorong maju membuat gigi terlihat lebih Panjang'),
(35, 'Jarak yang timbul diantara gigi'),
(36, 'Rasa tidak enak pada mulut'),
(37, 'Gigi tanggal'),
(38, 'Perubahan pada bentuk barisan gigi'),
(39, 'Mulut menjadi kering'),
(40, 'Adanya lapisan pada lidah')
ON DUPLICATE KEY UPDATE `gejala` = VALUES(`gejala`);

INSERT INTO `jenis_penyakit` (`id_jenis_penyakit`, `nama_penyakit`, `defenisi`, `penyebab`, `penanganan`) VALUES
(10, 'Halitosis (Bau Nafas tidak Sedap)', 'Halitosis adalah bau nafas yang tidak sedap. Halitosis terutama adalah hasil dari fermentasi anaerobik partikel makanan oleh bakteri gram negatif di dalam mulut yang menghasilkan senyawa belerang atsiri seperti hidrogen sulfida dan merkaptan metil.', '1. Mulut kering\r\n2. Jenis makanan tertentu\r\n3. Merokok (Jika merokok)\r\n4. Kebersihan gigi yang buruk\r\n5. Infeksi di mulut\r\n', 'Bau mulut dapat hilang sendirinya jika Anda mengubah kebiasaan membersihkan mulut Anda seperti lebih sering menggosok gigi, terutama pada gusi dan lidah, gunakan dental floss dan minum lebih banyak air.'),
(14, 'Erosi Gigi (Enamel Gigi yang Terkikis)', 'Erosi gigi atau dental erosion adalah terkikisnya enamel gigi yang disebabkan oleh asam. Enamel adalah lapisan keras pelindung gigi, yang melindungi dentin yang sensitif. Apabila enamel terkikis, dentin di bawahnya akan terekspos, yang dapat menyebabkan rasa sakit dan sensitivitas.', '1. Konsumsi minuman ringan berlebih (kadar fosfor dan asam sitrat yang tinggi)\r\n2. Minuman dari buah (beberapa asam pada minuman dari buah lebih erosif daripada asam baterai)\r\n3. Mulut kering atau air liur yang sedikit (xerostomia)\r\n4. Makanan (tinggi akan gula dan pati)\r\n5. Asam lambung\r\n6. Gangguan pencernaan\r\n7. Obat-obatan (aspirin, antihistamin)\r\n8. Genetik (kondisi turunan)\r\n9. Faktor lingkungan (gesekan, keausan, stres, dan korosi)', 'Jika Anda mengalami rasa sakit atau ngilu saat makan, Anda dapat melakukan beberapa hal berikut untuk meringankan rasa sakit:\r\n1. Berkumur air hangat untuk mengeluarkan sisa makanan yang tersangkut pada lubang. Gunakan benang gigi untuk mengangkat makanan yang tersangkut pada lubang atau pada sela-sela gigi.\r\n2. Jangan meletakkan aspirin pada gigi yang sakit atau jaringan gusi. '),
(16, 'Pulpitis (Peradangan Pulpa)', 'Pulpitis adalah penyebab utama dari sakit gigi dan tanggalnya gigi pada orang-orang yang lebih muda. Pulpitis merupakan peradangan pada pulpa gigi (bagian gigi terdalam yang berisi saraf dan pembuluh darah) dan jaringan periradikular yang mengelilingi akar gigi.', 'Beberapa penyebab pulpitis adalah sebagai berikut:\r\n1. Infeksi bakteri\r\n2. Cedera saat operasi gigi dan mulut\r\n3. Trauma, misalnya akar atau crown (gigi tiruan) yang retak serta abrasi gigi. Bisa juga disebabkan oleh bruxism, yaitu kebiasaan menggemeretakkan atau menggesekkan gigi saat tidur.\r\n4. Kecacatan bentuk gigi', 'Perawatan: mengangkat karies yang ada, meletakkan pelindung pulpa yang cocok, dan restorasi permanen dilakukan.\r\nPerawatan untuk radang pulpa gigi serius: melibatkan antara perawatan saluran akar atau operasi cabut gigi.'),
(17, 'Gingivitis (Radang Gusi)', 'Gingivitis (radang gusi) adalah penyakit akibat infeksi bakteri yang menyebabkan gusi meradang hingga merah dan membengkak.', 'Penyebab utama dari gingivitis adalah penumpukkan plak. Plak sendiri merupakan lapisan lengket bakteri yang terbentuk dari endapan sisa-sisa makanan di permukaan gigi. \r\nPlak yang dibiarkan terus-terusan menumpuk dalam jangka waktu lama akan mengeras membentuk karang gigi di bawah garis gusi. Nah, karang gigi inilah yang memicu peradangan pada gusi.\r\n', '1. Menjaga kebersihan mulut\r\nGosoklah gigi Anda setidaknya dua kali sehari (pagi dan malam) dengan teknik yang tepat. Sikat gigi secara perlahan dengan gerakan melingkar dari atas ke bawah. Lakukan dengan cara yang sama untuk setiap bagian selama 20 detik.\r\n2. Obat pereda nyeri. Apabila rasa sakitnya amat intens sampai membuat Anda kesulitan mengunyah dan menggigit makanan, dokter dapat meresepkan obat pereda nyeri seperti ibuprofen dan paracetamol.\r\n3. Obat kumur. Obat kumur antiseptik yang mengandung klorheksidin dapat digunakan untuk membantu melawan bakteri penyebab infeksi di dalam mulut.\r\n4. Obat antibiotik. Dokter juga mungkin akan meresepkan obat antibiotik untuk mencegah infeksi semakin parah.\r\n'),
(18, 'Karies Gigi (Gigi Berlubang)', 'Karies gigi adalah sebuah penyakit infeksi yang merusak struktur jaringan keras gigi.[1] Penyakit ini ditandai dengan gigi berlubang. Jika tidak ditangani, penyakit ini dapat menyebabkan nyeri, kematian saraf gigi (nekrose) dan infeksi periapikal dan infeksi sistemik yang bisa membahayakan penderita.', 'Penyebab utama karies gigi adalah plak yang menumpuk di permukaan gigi. Plak terbentuk dari sisa-sisa makanan, kotoran, dan bakteri di dalam mulut. Jarang sikat gigi dan sering mengonsumsi makanan yang manis dapat mempercepat pertumbuhan plak.', '1. Sikat Gigi Secara Teratur\r\n2. Berkumur dengan Mouthwash\r\n3. Gunakan Dental Floss\r\n4. Konsumsi Makanan dan Minuman yang Aman')
ON DUPLICATE KEY UPDATE `nama_penyakit` = VALUES(`nama_penyakit`), `defenisi` = VALUES(`defenisi`), `penyebab` = VALUES(`penyebab`), `penanganan` = VALUES(`penanganan`);

INSERT INTO `penyakit_gejala` (`id_penyakit_gejala`, `id_jenis_penyakit`, `id_gejala`, `mb`, `md`) VALUES
(6, 1, 6, 0.80, 0.20),
(7, 1, 8, 0.80, 0.20),
(8, 3, 1, 0.80, 0.20),
(9, 3, 2, 0.80, 0.20),
(10, 3, 4, 0.80, 0.20),
(11, 3, 5, 0.80, 0.20),
(12, 4, 6, 0.80, 0.20),
(13, 4, 7, 0.80, 0.20),
(14, 5, 1, 0.80, 0.20),
(15, 5, 3, 0.80, 0.20),
(16, 5, 5, 0.80, 0.20),
(17, 5, 7, 0.80, 0.20),
(18, 6, 2, 0.80, 0.20),
(19, 6, 8, 0.80, 0.20),
(20, 3, 32, 0.80, 0.20),
(21, 1, 11, 0.80, 0.20),
(22, 4, 11, 0.80, 0.20),
(23, 4, 22, 0.80, 0.20),
(24, 5, 26, 0.80, 0.20),
(25, 5, 6, 0.80, 0.20),
(26, 5, 11, 0.80, 0.20),
(27, 5, 14, 0.80, 0.20),
(28, 5, 15, 0.80, 0.20),
(29, 6, 13, 0.80, 0.20),
(30, 6, 34, 0.80, 0.20),
(31, 6, 35, 0.80, 0.20),
(32, 6, 38, 0.80, 0.20),
(33, 9, 11, 0.80, 0.20),
(34, 9, 6, 0.80, 0.20),
(35, 9, 8, 0.80, 0.20),
(36, 10, 10, 0.80, 0.20),
(37, 11, 16, 0.80, 0.20),
(38, 11, 17, 0.80, 0.20),
(39, 11, 21, 0.80, 0.20),
(40, 11, 20, 0.80, 0.20),
(41, 12, 11, 0.80, 0.20),
(42, 12, 7, 0.80, 0.20),
(43, 12, 10, 0.80, 0.20),
(44, 12, 8, 0.80, 0.20),
(45, 13, 18, 0.80, 0.20),
(46, 13, 24, 0.80, 0.20),
(47, 13, 25, 0.80, 0.20),
(48, 13, 26, 0.80, 0.20),
(49, 13, 27, 0.80, 0.20),
(50, 13, 28, 0.80, 0.20),
(51, 13, 29, 0.80, 0.20),
(52, 13, 31, 0.80, 0.20),
(53, 13, 36, 0.80, 0.20),
(54, 14, 6, 0.80, 0.20),
(55, 14, 8, 0.80, 0.20),
(56, 14, 11, 0.80, 0.20),
(57, 15, 1, 0.80, 0.20),
(59, 15, 2, 0.80, 0.20),
(60, 15, 32, 0.80, 0.20),
(61, 15, 4, 0.80, 0.20),
(62, 15, 5, 0.80, 0.20),
(63, 16, 11, 0.80, 0.20),
(64, 16, 6, 0.80, 0.20),
(65, 16, 7, 0.80, 0.20),
(66, 16, 22, 0.80, 0.20),
(67, 17, 1, 0.80, 0.20),
(74, 17, 2, 0.80, 0.20),
(75, 17, 4, 0.80, 0.20),
(76, 17, 5, 0.80, 0.20),
(77, 17, 32, 0.80, 0.20),
(78, 18, 11, 0.80, 0.20),
(79, 18, 6, 0.80, 0.20),
(80, 18, 8, 0.80, 0.20),
(81, 18, 7, 0.80, 0.20);
