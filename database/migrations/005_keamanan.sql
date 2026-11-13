-- Migrasi 005: rate-limit login + audit log.
-- Jalankan sekali pada database lama:
--   mysql -u USAMU DBMU < database/migrations/005_keamanan.sql

CREATE TABLE IF NOT EXISTS `login_upaya` (
  `id_upaya` int(11) NOT NULL AUTO_INCREMENT,
  `kunci` varchar(150) NOT NULL COMMENT 'username + ip yang dinormalisasi',
  `waktu` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `berhasil` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_upaya`),
  KEY `idx_upaya_kunci` (`kunci`, `waktu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `audit_log` (
  `id_audit` int(11) NOT NULL AUTO_INCREMENT,
  `waktu` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `aktor` varchar(100) NOT NULL DEFAULT '-',
  `peran` varchar(10) NOT NULL DEFAULT '-',
  `aksi` varchar(100) NOT NULL,
  `detail` text NULL,
  `ip` varchar(45) NULL,
  PRIMARY KEY (`id_audit`),
  KEY `idx_audit_waktu` (`waktu`),
  KEY `idx_audit_aktor` (`aktor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
