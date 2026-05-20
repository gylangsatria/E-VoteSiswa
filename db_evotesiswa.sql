-- Adminer 5.4.1 MySQL 8.4.6 dump
-- Modified 20 Mei 2026: Fix charset ke utf8, password column size untuk bcrypt

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `tb_admin`;
CREATE TABLE `tb_admin` (
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Default admin password: admin (bcrypt hash)
INSERT INTO `tb_admin` (`username`, `password`) VALUES
('admin', '$2y$10$YoJfJYqVHmMewUk43m3wPe3hRWZ8.0yXvKOHzSYUqFFVxRPuw58mu');

DROP TABLE IF EXISTS `tb_datapilketos`;
CREATE TABLE `tb_datapilketos` (
  `id` int NOT NULL DEFAULT '1',
  `tapel` varchar(30) NOT NULL,
  `tgl` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `tb_identitassekolah`;
CREATE TABLE `tb_identitassekolah` (
  `npsn` varchar(15) NOT NULL,
  `nm_sekolah` varchar(100) NOT NULL,
  `jln` varchar(100) DEFAULT NULL,
  `desa` varchar(100) DEFAULT NULL,
  `kec` varchar(100) DEFAULT NULL,
  `kab` varchar(100) DEFAULT NULL,
  `kpl_sekolah` varchar(100) DEFAULT NULL,
  `nip` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`npsn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `tb_kelas`;
CREATE TABLE `tb_kelas` (
  `kd_kelas` int NOT NULL AUTO_INCREMENT,
  `nm_kelas` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`kd_kelas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `tb_pilih`;
CREATE TABLE `tb_pilih` (
  `id_pilih` int NOT NULL AUTO_INCREMENT,
  `nisn` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `opsi_mpkosis` tinyint(1) DEFAULT NULL COMMENT '0 = MPK, 1 = OSIS',
  `calon_nisn` varchar(32) NOT NULL,
  `waktu_vote` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pilih`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `tb_pilihan`;
CREATE TABLE `tb_pilihan` (
  `nisn` varchar(32) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `no` int NOT NULL,
  `opsi_mpkosis` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nisn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `tb_siswa`;
CREATE TABLE `tb_siswa` (
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nm_siswa` varchar(100) DEFAULT NULL,
  `jk` char(1) NOT NULL,
  `kd_kelas` int DEFAULT NULL,
  `hadir` varchar(12) NOT NULL DEFAULT 'Tidak Hadir',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP VIEW IF EXISTS `view_daftarhadir`;
DROP VIEW IF EXISTS `view_vote`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_daftarhadir` AS
SELECT `tb_siswa`.`username` AS `NISN`,
       `tb_siswa`.`nm_siswa` AS `nm_siswa`,
       `tb_kelas`.`nm_kelas` AS `nm_kelas`
FROM (`tb_siswa`
      JOIN `tb_kelas` ON (`tb_kelas`.`kd_kelas` = `tb_siswa`.`kd_kelas`))
JOIN `tb_pilih` ON (`tb_siswa`.`username` = `tb_pilih`.`username`);

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_vote` AS
SELECT `tb_pilihan`.`nisn` AS `nisn`,
       `tb_pilihan`.`nama` AS `nama`,
       `tb_pilihan`.`photo` AS `photo`,
       `tb_pilihan`.`no` AS `no`,
       `tb_siswa`.`username` AS `username`
FROM (`tb_pilih`
      JOIN `tb_pilihan` ON (`tb_pilihan`.`nisn` = `tb_pilih`.`nisn`))
JOIN `tb_siswa` ON (`tb_siswa`.`username` = `tb_pilih`.`username`);
