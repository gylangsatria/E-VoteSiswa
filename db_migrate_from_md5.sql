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
