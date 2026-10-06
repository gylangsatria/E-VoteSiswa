-- Index performa untuk skala besar (ribuan pemilih).
-- Jalankan pada database yang sudah ada: mysql -u root -p db_pilketos < db_indexes.sql
-- Tidak perlu dijalankan lagi jika sudah mengimpor db_evotesiswa.sql versi terbaru.

ALTER TABLE `tb_pilih`
  ADD INDEX `idx_pilih_username_opsi` (`username`, `opsi_mpkosis`),
  ADD INDEX `idx_pilih_calon` (`calon_nisn`);

ALTER TABLE `tb_siswa`
  ADD INDEX `idx_siswa_kelas` (`kd_kelas`);
