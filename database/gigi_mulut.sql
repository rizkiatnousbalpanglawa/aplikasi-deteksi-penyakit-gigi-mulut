-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 19, 2020 at 01:51 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gigi_mulut`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(100) NOT NULL,
  `kata_sandi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `kata_sandi`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `gejala`
--

CREATE TABLE `gejala` (
  `id_gejala` int(11) NOT NULL,
  `gejala` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gejala`
--

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
(40, 'Adanya lapisan pada lidah');

-- --------------------------------------------------------

--
-- Table structure for table `hasil_kemungkinan`
--

CREATE TABLE `hasil_kemungkinan` (
  `id_hasil_kemungkinan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_jenis_penyakit` int(11) NOT NULL,
  `jumlah_gejala` int(11) NOT NULL,
  `total_gejala` int(11) NOT NULL,
  `presentase` decimal(4,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hasil_kemungkinan`
--

INSERT INTO `hasil_kemungkinan` (`id_hasil_kemungkinan`, `id_user`, `id_jenis_penyakit`, `jumlah_gejala`, `total_gejala`, `presentase`) VALUES
(439, 1, 17, 5, 5, '100.0'),
(440, 1, 3, 5, 5, '100.0'),
(441, 1, 15, 5, 5, '100.0'),
(442, 1, 5, 2, 9, '22.2'),
(443, 1, 6, 1, 6, '16.7');

-- --------------------------------------------------------

--
-- Table structure for table `hasil_pemeriksaan`
--

CREATE TABLE `hasil_pemeriksaan` (
  `id_hasil_pemeriksaan` int(11) NOT NULL,
  `id_jenis_penyakit` int(11) NOT NULL,
  `tanggal_pemeriksaan` date NOT NULL,
  `presentase_hasil` varchar(100) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hasil_pemeriksaan`
--

INSERT INTO `hasil_pemeriksaan` (`id_hasil_pemeriksaan`, `id_jenis_penyakit`, `tanggal_pemeriksaan`, `presentase_hasil`, `id_user`) VALUES
(21, 1, '2020-02-23', '100.0', 2),
(28, 5, '2020-02-24', '100.0', 5),
(29, 5, '2020-02-24', '100.0', 3),
(31, 5, '2020-02-25', '40.0', 6),
(32, 5, '2020-02-25', '44.4', 8),
(33, 4, '2020-02-25', '100.0', 6),
(34, 4, '2020-02-25', '100.0', 6),
(39, 1, '2020-02-27', '100.0', 3),
(40, 3, '2020-02-27', '60.0', 3),
(41, 9, '2020-02-27', '100.0', 3),
(42, 4, '2020-02-27', '100.0', 3),
(43, 4, '2020-02-27', '50.0', 3),
(44, 4, '2020-02-27', '50.0', 3),
(45, 1, '2020-02-27', '100.0', 3),
(46, 5, '2020-02-28', '100.0', 10),
(47, 3, '2020-03-03', '80.0', 10),
(48, 3, '2020-03-03', '80.0', 10),
(49, 3, '2020-03-03', '40.0', 10),
(50, 3, '2020-03-03', '80.0', 10),
(51, 4, '2020-03-03', '100.0', 10),
(52, 4, '2020-03-03', '100.0', 10),
(53, 10, '2020-03-03', '100.0', 10),
(54, 1, '2020-03-03', '100.0', 10),
(55, 11, '2020-03-03', '100.0', 10),
(56, 5, '2020-03-03', '66.7', 10),
(57, 3, '2020-03-03', '80.0', 10),
(59, 5, '2020-03-03', '40.0', 10),
(60, 3, '2020-03-03', '40.0', 10),
(61, 3, '2020-03-03', '40.0', 10),
(62, 9, '2020-03-03', '100.0', 10),
(67, 14, '2020-03-03', '100.0', 10),
(70, 4, '2020-03-03', '75.0', 10),
(71, 18, '2020-03-03', '100.0', 10),
(72, 3, '2020-03-03', '40.0', 10),
(73, 17, '2020-03-03', '60.0', 10),
(74, 3, '2020-03-03', '40.0', 10),
(75, 3, '2020-03-03', '100.0', 10),
(76, 3, '2020-03-03', '100.0', 10),
(77, 5, '2020-03-03', '100.0', 10),
(78, 5, '2020-03-03', '40.0', 3),
(79, 15, '2020-03-03', '100.0', 3),
(80, 3, '2020-03-04', '40.0', 3),
(81, 17, '2020-03-04', '100.0', 3),
(82, 15, '2020-03-04', '20.0', 3),
(83, 3, '2020-03-04', '20.0', 3),
(84, 3, '2020-03-04', '40.0', 3),
(85, 3, '2020-03-04', '40.0', 3),
(86, 3, '2020-03-04', '80.0', 3),
(87, 3, '2020-03-04', '80.0', 3),
(88, 3, '2020-03-04', '80.0', 3),
(89, 3, '2020-03-04', '80.0', 3),
(90, 17, '2020-03-04', '80.0', 3),
(91, 17, '2020-03-04', '100.0', 1),
(92, 17, '2020-03-04', '100.0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hasil_sementara`
--

CREATE TABLE `hasil_sementara` (
  `id_hasil_sementara` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_jenis_penyakit` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hasil_sementara`
--

INSERT INTO `hasil_sementara` (`id_hasil_sementara`, `id_user`, `id_jenis_penyakit`, `id_gejala`) VALUES
(989, 1, 3, 1),
(990, 1, 5, 1),
(991, 1, 15, 1),
(992, 1, 17, 1),
(993, 1, 3, 2),
(994, 1, 6, 2),
(995, 1, 15, 2),
(996, 1, 17, 2),
(997, 1, 3, 4),
(998, 1, 15, 4),
(999, 1, 17, 4),
(1000, 1, 3, 5),
(1001, 1, 5, 5),
(1002, 1, 15, 5),
(1003, 1, 17, 5),
(1004, 1, 3, 32),
(1005, 1, 15, 32),
(1006, 1, 17, 32);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_penyakit`
--

CREATE TABLE `jenis_penyakit` (
  `id_jenis_penyakit` int(11) NOT NULL,
  `nama_penyakit` varchar(100) NOT NULL,
  `defenisi` text NOT NULL,
  `penyebab` text NOT NULL,
  `penanganan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenis_penyakit`
--

INSERT INTO `jenis_penyakit` (`id_jenis_penyakit`, `nama_penyakit`, `defenisi`, `penyebab`, `penanganan`) VALUES
(10, 'Halitosis (Bau Nafas tidak Sedap)', 'Halitosis adalah bau nafas yang tidak sedap. Halitosis terutama adalah hasil dari fermentasi anaerobik partikel makanan oleh bakteri gram negatif di dalam mulut yang menghasilkan senyawa belerang atsiri seperti hidrogen sulfida dan merkaptan metil.', '1. Mulut kering\r\n2. Jenis makanan tertentu\r\n3. Merokok (Jika merokok)\r\n4. Kebersihan gigi yang buruk\r\n5. Infeksi di mulut\r\n', 'Bau mulut dapat hilang sendirinya jika Anda mengubah kebiasaan membersihkan mulut Anda seperti lebih sering menggosok gigi, terutama pada gusi dan lidah, gunakan dental floss dan minum lebih banyak air.'),
(14, 'Erosi Gigi (Enamel Gigi yang Terkikis)', 'Erosi gigi atau dental erosion adalah terkikisnya enamel gigi yang disebabkan oleh asam. Enamel adalah lapisan keras pelindung gigi, yang melindungi dentin yang sensitif. Apabila enamel terkikis, dentin di bawahnya akan terekspos, yang dapat menyebabkan rasa sakit dan sensitivitas.', '1. Konsumsi minuman ringan berlebih (kadar fosfor dan asam sitrat yang tinggi)\r\n2. Minuman dari buah (beberapa asam pada minuman dari buah lebih erosif daripada asam baterai)\r\n3. Mulut kering atau air liur yang sedikit (xerostomia)\r\n4. Makanan (tinggi akan gula dan pati)\r\n5. Asam lambung\r\n6. Gangguan pencernaan\r\n7. Obat-obatan (aspirin, antihistamin)\r\n8. Genetik (kondisi turunan)\r\n9. Faktor lingkungan (gesekan, keausan, stres, dan korosi)', 'Jika Anda mengalami rasa sakit atau ngilu saat makan, Anda dapat melakukan beberapa hal berikut untuk meringankan rasa sakit:\r\n1. Berkumur air hangat untuk mengeluarkan sisa makanan yang tersangkut pada lubang. Gunakan benang gigi untuk mengangkat makanan yang tersangkut pada lubang atau pada sela-sela gigi.\r\n2. Jangan meletakkan aspirin pada gigi yang sakit atau jaringan gusi. '),
(16, 'Pulpitis (Peradangan Pulpa)', 'Pulpitis adalah penyebab utama dari sakit gigi dan tanggalnya gigi pada orang-orang yang lebih muda. Pulpitis merupakan peradangan pada pulpa gigi (bagian gigi terdalam yang berisi saraf dan pembuluh darah) dan jaringan periradikular yang mengelilingi akar gigi.', 'Beberapa penyebab pulpitis adalah sebagai berikut:\r\n1. Infeksi bakteri\r\n2. Cedera saat operasi gigi dan mulut\r\n3. Trauma, misalnya akar atau crown (gigi tiruan) yang retak serta abrasi gigi. Bisa juga disebabkan oleh bruxism, yaitu kebiasaan menggemeretakkan atau menggesekkan gigi saat tidur.\r\n4. Kecacatan bentuk gigi', 'Perawatan: mengangkat karies yang ada, meletakkan pelindung pulpa yang cocok, dan restorasi permanen dilakukan.\r\nPerawatan untuk radang pulpa gigi serius: melibatkan antara perawatan saluran akar atau operasi cabut gigi.'),
(17, 'Gingivitis (Radang Gusi)', 'Gingivitis (radang gusi) adalah penyakit akibat infeksi bakteri yang menyebabkan gusi meradang hingga merah dan membengkak.', 'Penyebab utama dari gingivitis adalah penumpukkan plak. Plak sendiri merupakan lapisan lengket bakteri yang terbentuk dari endapan sisa-sisa makanan di permukaan gigi. \r\nPlak yang dibiarkan terus-terusan menumpuk dalam jangka waktu lama akan mengeras membentuk karang gigi di bawah garis gusi. Nah, karang gigi inilah yang memicu peradangan pada gusi.\r\n', '1. Menjaga kebersihan mulut\r\nGosoklah gigi Anda setidaknya dua kali sehari (pagi dan malam) dengan teknik yang tepat. Sikat gigi secara perlahan dengan gerakan melingkar dari atas ke bawah. Lakukan dengan cara yang sama untuk setiap bagian selama 20 detik.\r\n2. Obat pereda nyeri. Apabila rasa sakitnya amat intens sampai membuat Anda kesulitan mengunyah dan menggigit makanan, dokter dapat meresepkan obat pereda nyeri seperti ibuprofen dan paracetamol.\r\n3. Obat kumur. Obat kumur antiseptik yang mengandung klorheksidin dapat digunakan untuk membantu melawan bakteri penyebab infeksi di dalam mulut.\r\n4. Obat antibiotik. Dokter juga mungkin akan meresepkan obat antibiotik untuk mencegah infeksi semakin parah.\r\n'),
(18, 'Karies Gigi (Gigi Berlubang)', 'Karies gigi adalah sebuah penyakit infeksi yang merusak struktur jaringan keras gigi.[1] Penyakit ini ditandai dengan gigi berlubang. Jika tidak ditangani, penyakit ini dapat menyebabkan nyeri, kematian saraf gigi (nekrose) dan infeksi periapikal dan infeksi sistemik yang bisa membahayakan penderita.', 'Penyebab utama karies gigi adalah plak yang menumpuk di permukaan gigi. Plak terbentuk dari sisa-sisa makanan, kotoran, dan bakteri di dalam mulut. Jarang sikat gigi dan sering mengonsumsi makanan yang manis dapat mempercepat pertumbuhan plak.', '1. Sikat Gigi Secara Teratur\r\n2. Berkumur dengan Mouthwash\r\n3. Gunakan Dental Floss\r\n4. Konsumsi Makanan dan Minuman yang Aman');

-- --------------------------------------------------------

--
-- Table structure for table `penyakit_gejala`
--

CREATE TABLE `penyakit_gejala` (
  `id_penyakit_gejala` int(11) NOT NULL,
  `id_jenis_penyakit` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penyakit_gejala`
--

INSERT INTO `penyakit_gejala` (`id_penyakit_gejala`, `id_jenis_penyakit`, `id_gejala`) VALUES
(6, 1, 6),
(7, 1, 8),
(8, 3, 1),
(9, 3, 2),
(10, 3, 4),
(11, 3, 5),
(12, 4, 6),
(13, 4, 7),
(14, 5, 1),
(15, 5, 3),
(16, 5, 5),
(17, 5, 7),
(18, 6, 2),
(19, 6, 8),
(20, 3, 32),
(21, 1, 11),
(22, 4, 11),
(23, 4, 22),
(24, 5, 26),
(25, 5, 6),
(26, 5, 11),
(27, 5, 14),
(28, 5, 15),
(29, 6, 13),
(30, 6, 34),
(31, 6, 35),
(32, 6, 38),
(33, 9, 11),
(34, 9, 6),
(35, 9, 8),
(36, 10, 10),
(37, 11, 16),
(38, 11, 17),
(39, 11, 21),
(40, 11, 20),
(41, 12, 11),
(42, 12, 7),
(43, 12, 10),
(44, 12, 8),
(45, 13, 18),
(46, 13, 24),
(47, 13, 25),
(48, 13, 26),
(49, 13, 27),
(50, 13, 28),
(51, 13, 29),
(52, 13, 31),
(53, 13, 36),
(54, 14, 6),
(55, 14, 8),
(56, 14, 11),
(57, 15, 1),
(59, 15, 2),
(60, 15, 32),
(61, 15, 4),
(62, 15, 5),
(63, 16, 11),
(64, 16, 6),
(65, 16, 7),
(66, 16, 22),
(67, 17, 1),
(74, 17, 2),
(75, 17, 4),
(76, 17, 5),
(77, 17, 32),
(78, 18, 11),
(79, 18, 6),
(80, 18, 8),
(81, 18, 7);

-- --------------------------------------------------------

--
-- Table structure for table `sementara`
--

CREATE TABLE `sementara` (
  `id_sementara` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `gejala` text NOT NULL,
  `jawaban` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sementara`
--

INSERT INTO `sementara` (`id_sementara`, `id_user`, `gejala`, `jawaban`) VALUES
(3301, 1, 'Gusi Bengkak', '1'),
(3302, 1, 'Gusi Berdarah', '2'),
(3303, 1, 'Gusi Sakit Jika Disentuh', '0'),
(3304, 1, 'Radang Gusi', '4'),
(3305, 1, 'Gusi Mengkilat', '5'),
(3306, 1, 'Gigi Berlubang', '0'),
(3307, 1, 'Gigi Nyeri', '0'),
(3308, 1, 'Gigi Ngilu', '0'),
(3309, 1, 'Gigi Patah', '0'),
(3310, 1, 'Bau Napas Tidak Sedap', '0'),
(3311, 1, 'Gigi sakit jika makan/minum panas atau dingin', '0'),
(3312, 1, 'Mulut terasa pahit', '0'),
(3313, 1, 'Gigi terasa goyang', '0'),
(3314, 1, 'Nyeri saat melepaskan tekanan gigitan pada objek', '0'),
(3315, 1, 'Benjolan pada gusi', '0'),
(3316, 1, 'Sakit disekitar sendi rahang', '0'),
(3317, 1, 'Sakit disekitar telinga', '0'),
(3318, 1, 'Kesulitan menelan makanan', '0'),
(3319, 1, 'Sakit disekitar wajah', '0'),
(3320, 1, 'Ada suara clicking ketika mengunyah makanan atau membuka mulut  ', '0'),
(3321, 1, 'Rahang bagian mulut sulit dibuka atau ditutup', '0'),
(3322, 1, 'Sakit disekitar kepala', '0'),
(3323, 1, 'Gigitan yang terasa tidak pas', '0'),
(3324, 1, 'Sakit dan bengkak pada leher', '0'),
(3325, 1, 'Leher menjadi merah', '0'),
(3326, 1, 'Badan menjadi demam', '0'),
(3327, 1, 'Badan terasa lemah', '0'),
(3328, 1, 'Badan terasa lesu', '0'),
(3329, 1, 'Badan menjadi mudah capek', '0'),
(3330, 1, 'Pikiran terasa bingung atau linglung ', '0'),
(3331, 1, 'Perubahan mental dan kesulitan bernapas', '0'),
(3332, 1, 'Gusi merah terang atau keunguan ', '32'),
(3333, 1, 'Gusi terasa tebal ketika disentuh', '0'),
(3334, 1, 'Gusi yang terdorong maju membuat gigi terlihat lebih Panjang', '0'),
(3335, 1, 'Jarak yang timbul diantara gigi', '0'),
(3336, 1, 'Rasa tidak enak pada mulut', '0'),
(3337, 1, 'Gigi tanggal', '0'),
(3338, 1, 'Perubahan pada bentuk barisan gigi', '0'),
(3339, 1, 'Mulut menjadi kering', '0'),
(3340, 1, 'Adanya lapisan pada lidah', '0');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(100) NOT NULL,
  `kata_sandi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `tgl_lahir`, `alamat`, `no_hp`, `kata_sandi`) VALUES
(1, 'etha', '2020-02-03', 'Jl. Perintis Kemerdekaan 1', '082292888306', '123'),
(2, 'Usbal', '2020-02-01', 'Jl. Perintis', '082292888396', '123'),
(3, 'Pratiwi', '1997-02-23', 'Pk7', '082346180283', '082346'),
(4, 'Andi paramita', '2019-10-15', 'Makassar', '082', ''),
(5, 'Andi paramita', '2017-06-12', 'Makassar', '082151106013', '121212itha'),
(6, 'wirda', '2020-02-21', 'Hhh', '085222', 'laskarbiru'),
(7, 'Itha', '2019-11-11', 'Makassar', '082151106013', '121212itha'),
(8, 'Itha', '2019-12-16', 'Makassar', '08151106013', 'itha1212'),
(9, 'maria', '2020-01-20', 'BTP', '082164583656', '123'),
(10, 'Ayu', '2000-08-12', 'Nusa Tamalanrea Indah', '0895415080503', 'ayuimut12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`id_gejala`);

--
-- Indexes for table `hasil_kemungkinan`
--
ALTER TABLE `hasil_kemungkinan`
  ADD PRIMARY KEY (`id_hasil_kemungkinan`);

--
-- Indexes for table `hasil_pemeriksaan`
--
ALTER TABLE `hasil_pemeriksaan`
  ADD PRIMARY KEY (`id_hasil_pemeriksaan`);

--
-- Indexes for table `hasil_sementara`
--
ALTER TABLE `hasil_sementara`
  ADD PRIMARY KEY (`id_hasil_sementara`);

--
-- Indexes for table `jenis_penyakit`
--
ALTER TABLE `jenis_penyakit`
  ADD PRIMARY KEY (`id_jenis_penyakit`);

--
-- Indexes for table `penyakit_gejala`
--
ALTER TABLE `penyakit_gejala`
  ADD PRIMARY KEY (`id_penyakit_gejala`);

--
-- Indexes for table `sementara`
--
ALTER TABLE `sementara`
  ADD PRIMARY KEY (`id_sementara`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gejala`
--
ALTER TABLE `gejala`
  MODIFY `id_gejala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `hasil_kemungkinan`
--
ALTER TABLE `hasil_kemungkinan`
  MODIFY `id_hasil_kemungkinan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=444;

--
-- AUTO_INCREMENT for table `hasil_pemeriksaan`
--
ALTER TABLE `hasil_pemeriksaan`
  MODIFY `id_hasil_pemeriksaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `hasil_sementara`
--
ALTER TABLE `hasil_sementara`
  MODIFY `id_hasil_sementara` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1007;

--
-- AUTO_INCREMENT for table `jenis_penyakit`
--
ALTER TABLE `jenis_penyakit`
  MODIFY `id_jenis_penyakit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `penyakit_gejala`
--
ALTER TABLE `penyakit_gejala`
  MODIFY `id_penyakit_gejala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `sementara`
--
ALTER TABLE `sementara`
  MODIFY `id_sementara` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3341;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
