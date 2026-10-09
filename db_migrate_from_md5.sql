-- Migration: Fix database untuk mendukung bcrypt dan charset utf8
-- Jalankan setelah mengupdate kode ke branch fix/bugs
-- Menyesuaikan database dengan perubahan dari MD5 ke password_hash()

-- 1. Perbesar kolom password untuk bcrypt (60 karakter)
ALTER TABLE tb_admin MODIFY password VARCHAR(255) NOT NULL;
ALTER TABLE tb_siswa MODIFY password VARCHAR(255) NOT NULL;

-- 2. Ubah charset tabel ke utf8 (konsisten dengan konfigurasi aplikasi)
ALTER TABLE tb_admin CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE tb_datapilketos CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE tb_identitassekolah CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE tb_kelas CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE tb_pilih CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE tb_pilihan CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE tb_siswa CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;

-- 3. Hapus admin dengan password MD5 lama dan insert ulang dengan bcrypt
-- Password default: admin (bcrypt hash)
DELETE FROM tb_admin WHERE username = 'admin';
INSERT INTO tb_admin (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- 4. Catatan: Password siswa yang tersimpan sebagai plain text (NISN)
-- sudah tidak berfungsi karena kode baru menggunakan password_hash().
-- Semua siswa harus di-reset passwordnya melalui menu Admin > Reset User,
-- atau hapus dan input ulang DPT.
-- Password siswa baru yang ditambahkan akan otomatis di-hash dengan bcrypt.

-- 5. Tambah kolom nama calon wakil pada tabel kandidat (OSIM/MPK)
ALTER TABLE tb_pilihan ADD COLUMN nama_wakil VARCHAR(100) NOT NULL DEFAULT '' AFTER nama;

-- 6. Tambah kolom jenis satuan pendidikan (sekolah/madrasah) untuk label dinamis
ALTER TABLE tb_identitassekolah ADD COLUMN jenis VARCHAR(10) NOT NULL DEFAULT 'sekolah' AFTER nip;

-- 7. Pastikan baris data pilketos (id=1) ada dan tanggal boleh kosong
ALTER TABLE tb_datapilketos MODIFY tgl DATE DEFAULT NULL;
INSERT INTO tb_datapilketos (id, tapel, tgl) VALUES (1, '', NULL)
  ON DUPLICATE KEY UPDATE id = id;

-- 7b. Jadwal batas waktu voting (jam mulai/selesai + aktif)
ALTER TABLE tb_datapilketos ADD COLUMN jam_mulai TIME DEFAULT NULL AFTER tgl;
ALTER TABLE tb_datapilketos ADD COLUMN jam_selesai TIME DEFAULT NULL AFTER jam_mulai;
ALTER TABLE tb_datapilketos ADD COLUMN aktif TINYINT(1) NOT NULL DEFAULT 0 AFTER jam_selesai;

-- 8. Tabel rate-limit login (persisten, tahan lintas session)
CREATE TABLE IF NOT EXISTS tb_login_attempts (
  username VARCHAR(32) NOT NULL,
  attempts INT NOT NULL DEFAULT 0,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


-- 9. Perbaiki view_vote: join ke kolom calon_nisn (bukan nisn)
-- Sebelumnya view membandingkan tb_pilihan.nisn dengan tb_pilih.nisn (NISN pemilih)
-- sehingga selalu 0 baris dan pengecekan "sudah pernah voting" tidak pernah aktif.
CREATE OR REPLACE VIEW view_vote AS
SELECT `tb_pilihan`.`nisn` AS `nisn`,
       `tb_pilihan`.`nama` AS `nama`,
       `tb_pilihan`.`photo` AS `photo`,
       `tb_pilihan`.`no` AS `no`,
       `tb_siswa`.`username` AS `username`
FROM (`tb_pilih`
      JOIN `tb_pilihan` ON (`tb_pilihan`.`nisn` = `tb_pilih`.`calon_nisn`))
JOIN `tb_siswa` ON (`tb_siswa`.`username` = `tb_pilih`.`username`);

