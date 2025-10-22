-- Adminer 5.4.1 MySQL 8.4.6 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `tb_admin`;
CREATE TABLE `tb_admin` (
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tb_admin` (`username`, `password`) VALUES
('admin',	'21232f297a57a5a743894a0e4a801fc3');

DROP TABLE IF EXISTS `tb_datapilketos`;
CREATE TABLE `tb_datapilketos` (
  `id` int NOT NULL DEFAULT '1',
  `tapel` varchar(10) NOT NULL,
  `tgl` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tb_datapilketos` (`id`, `tapel`, `tgl`) VALUES
(1,	'',	'0000-00-00');

DROP TABLE IF EXISTS `tb_identitassekolah`;
CREATE TABLE `tb_identitassekolah` (
  `npsn` varchar(15) NOT NULL,
  `nm_sekolah` varchar(32) NOT NULL,
  `jln` varchar(32) DEFAULT NULL,
  `desa` varchar(32) DEFAULT NULL,
  `kec` varchar(32) DEFAULT NULL,
  `kab` varchar(32) DEFAULT NULL,
  `kpl_sekolah` varchar(32) DEFAULT NULL,
  `nip` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`npsn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tb_identitassekolah` (`npsn`, `nm_sekolah`, `jln`, `desa`, `kec`, `kab`, `kpl_sekolah`, `nip`) VALUES
('20278807',	'MTs Negeri 11 Cirebon',	'Jln. Pangeran Panjunan 01',	'Desa Cisaat',	'Dukupuntang',	'Kabupaten Cirebon',	'Drs. H. Sarip, M.M.',	'1966090719940310');

DROP TABLE IF EXISTS `tb_kelas`;
CREATE TABLE `tb_kelas` (
  `kd_kelas` int NOT NULL AUTO_INCREMENT,
  `nm_kelas` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`kd_kelas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tb_kelas` (`kd_kelas`, `nm_kelas`) VALUES
(12,	'VII-A'),
(13,	'VII-B'),
(14,	'VII-C'),
(15,	'VII-D'),
(16,	'VII-E'),
(17,	'VII-F'),
(18,	'VII-G'),
(19,	'VII-H'),
(21,	'VII-I'),
(22,	'VIII-B'),
(23,	'VIII-A'),
(24,	'VIII-C'),
(25,	'VIII-D'),
(26,	'VIII-E'),
(27,	'VIII-F'),
(28,	'VIII-G'),
(29,	'VIII-H'),
(30,	'IX-A'),
(31,	'IX-B'),
(32,	'IX-C'),
(34,	'IX-D'),
(35,	'IX-E'),
(36,	'IX-F'),
(37,	'IX-G'),
(38,	'IX-H');

DROP TABLE IF EXISTS `tb_pilih`;
CREATE TABLE `tb_pilih` (
  `id_pilih` int NOT NULL AUTO_INCREMENT,
  `nisn` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `opsi_mpkosis` tinyint(1) NOT NULL COMMENT '0 = OSIS, 1 = MPK',
  `calon_nisn` varchar(32) NOT NULL,
  `waktu_vote` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pilih`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tb_pilihan`;
CREATE TABLE `tb_pilihan` (
  `nisn` varchar(32) NOT NULL,
  `nama` varchar(32) NOT NULL,
  `photo` varchar(32) NOT NULL,
  `no` int NOT NULL,
  `opsi_mpkosis` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nisn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tb_siswa`;
CREATE TABLE `tb_siswa` (
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `nm_siswa` varchar(32) DEFAULT NULL,
  `jk` char(1) NOT NULL,
  `kd_kelas` int DEFAULT NULL,
  `hadir` varchar(12) NOT NULL DEFAULT 'Tidak Hadir',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP VIEW IF EXISTS `view_daftarhadir`;
CREATE TABLE `view_daftarhadir` (`NISN` varchar(32), `nm_siswa` varchar(32), `nm_kelas` varchar(32));


DROP VIEW IF EXISTS `view_vote`;
CREATE TABLE `view_vote` (`nisn` varchar(32), `nama` varchar(32), `photo` varchar(32), `no` int, `username` varchar(32));


DROP TABLE IF EXISTS `view_daftarhadir`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_daftarhadir` AS select `tb_siswa`.`username` AS `NISN`,`tb_siswa`.`nm_siswa` AS `nm_siswa`,`tb_kelas`.`nm_kelas` AS `nm_kelas` from ((`tb_siswa` join `tb_kelas` on((`tb_kelas`.`kd_kelas` = `tb_siswa`.`kd_kelas`))) join `tb_pilih` on((`tb_siswa`.`username` = `tb_pilih`.`username`)));

DROP TABLE IF EXISTS `view_vote`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_vote` AS select `tb_pilihan`.`nisn` AS `nisn`,`tb_pilihan`.`nama` AS `nama`,`tb_pilihan`.`photo` AS `photo`,`tb_pilihan`.`no` AS `no`,`tb_siswa`.`username` AS `username` from ((`tb_pilih` join `tb_pilihan` on((`tb_pilihan`.`nisn` = `tb_pilih`.`nisn`))) join `tb_siswa` on((`tb_siswa`.`username` = `tb_pilih`.`username`)));

-- 2025-10-22 01:28:06 UTC
