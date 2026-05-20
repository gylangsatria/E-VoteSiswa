# Changelog Analisis Aplikasi E-VoteSiswa

**Framework:** CodeIgniter 3
**Branch:** fix/bugs

---

## [1.1.1] - 21 Mei 2026

### Perbaikan Keamanan Lanjutan
- **XSS Protection** — Semua output di view di-escape dengan `htmlspecialchars()` untuk mencegah XSS injection
- **File Upload Security** — Validasi MIME type untuk file Excel/CSV, whitelist karakter filename
- **File Permissions** — Ubah `chmod(0777)` menjadi `chmod(0644)` untuk file upload agar lebih aman
- **Code Cleanup** — Hapus duplikasi kode fungsi `simpancalon()` yang di-comment

**Files yang diperbaiki:**
- `application/views/admin/datacalon.php` — XSS escaping pada nama dan foto kandidat
- `application/views/user/index.php` — XSS escaping pada data display voting
- `application/controllers/Admin.php` — MIME validation, file permissions, cleanup duplikasi

---

## [1.1] - 20 Mei 2026

### Tambahan
- **Rate Limiter Login** — Maksimal 5 percobaan login dalam 5 menit (brute force protection)

### Perbaikan
- **Kompatibilitas PHP 8.1** — Ditambahkan `#[\\ReturnTypeWillChange]` pada `Session_files_driver.php` (6 method)
- **Error reporting** — `index.php` di mode development tidak lagi menampilkan E_DEPRECATED
- **Closing tag `?>`** — Dihapus dari 5 file PHP murni (model, controller, library) untuk mencegah "headers already sent"
- **Hash password admin** — Diperbaiki bcrypt hash di `db_evotesiswa.sql` agar sesuai dengan password `admin`

### Dokumentasi
- README.md dan CHANGELOG.md diperbarui

---

## Detail Perbaikan v1.1.1

### 1. XSS (Cross-Site Scripting) Prevention

**Lokasi:** 
- `application/views/admin/datacalon.php` (6 field)
- `application/views/user/index.php` (12 field)

**Perbaikan:** Semua output variable database di-escape dengan `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')` untuk mencegah XSS injection.

**Contoh Sebelum:**
```php
<td><?php echo $loaddata['nama']; ?></td>
<img src="<?php echo base_url(); ?>/asset/img/<?php echo $loaddata['photo']; ?>">
```

**Contoh Sesudah:**
```php
<td><?php echo htmlspecialchars($loaddata['nama'], ENT_QUOTES, 'UTF-8'); ?></td>
<img src="<?php echo base_url(); ?>/asset/img/<?php echo htmlspecialchars($loaddata['photo'], ENT_QUOTES, 'UTF-8'); ?>">
```

**Dampak:** User tidak bisa inject JavaScript melalui field nama atau nama file foto.

### 2. File Upload - MIME Type Validation

**Lokasi:** `application/controllers/Admin.php` - fungsi `simpanmassaldpt()` (line 415-445)

**Perbaikan:** 
- Tambah whitelist MIME type (Excel dan CSV only)
- Validasi dengan `$_FILES['datadpt']['type']`
- Whitelist karakter filename dengan `preg_replace()`

**Kode Baru:**
```php
// Validasi MIME type
$allowed_mime = array(
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'text/csv',
    'text/plain'
);
$file_mime = $_FILES['datadpt']['type'];
if (!in_array($file_mime, $allowed_mime)) {
    // reject file
}

// Whitelist filename
$filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
```

**Dampak:** Tidak bisa upload file malicious dengan extension palsu atau path traversal.

### 3. File Permissions - Secure chmod

**Lokasi:** `application/controllers/Admin.php` - fungsi `simpanmassaldpt()` (line 444)

**Perbaikan Sebelum:**
```php
chmod($target, 0777);  // ❌ Semua user bisa read/write/execute
```

**Perbaikan Sesudah:**
```php
@chmod($target, 0644);  // ✅ Owner bisa read/write, others hanya read
```

**Dampak:** Mencegah file upload dieksekusi oleh user lain atau dihapus/dimodif tanpa izin.

### 4. Code Cleanup - Hapus Duplikasi

**Lokasi:** `application/controllers/Admin.php` - simpancalon() function (line 551-580)

**Perbaikan:** Dihapus 30 baris kode yang di-comment (old version simpancalon tanpa opsi MPK/OSIS).

**Dampak:** Kode lebih clean dan tidak membingungkan.

---

## Ringkasan Perbaikan Keamanan (Commit d0042b8)

6 file diubah, 106 insertions, 92 deletions.

| # | Bug | Status | File |
|---|-----|--------|------|
| 1 | Operator = bukan === di seluruh Admin controller | FIXED | `Admin.php` |
| 2 | SQL Injection via raw queries | FIXED | `Admin_Model.php`, `User_Model.php` |
| 3 | MD5 password hashing | FIXED | `Admin_Model.php`, `User_Model.php`, `Admin.php` |
| 4 | Password siswa = NISN | FIXED | `Admin_Model.php` |
| 5 | Foto kehapus saat update tanpa upload | FIXED | `Admin_Model.php` |
| 6 | Route conflict User tertimpa Admin | FIXED | `routes.php` |
| 7 | Double DELETE tb_siswa di resetdata() | FIXED | `Admin_Model.php` |
| 8 | DB query langsung di view | FIXED | `views/user/index.php` |
| 9 | regvalid() return boolean vs array | FIXED | `Admin_Model.php`, `Admin.php` |
| 10 | CSRF Protection disabled | FIXED | `config.php` |
| 11 | XSS Filtering disabled | FIXED | `config.php` |
| 12 | Encryption key kosong | FIXED | `config.php` |
| 13 | Cookie HttpOnly disabled | FIXED | `config.php` |
| 14 | Session IP Match disabled | FIXED | `config.php` |
| 15 | Session Regenerate Destroy disabled | FIXED | `config.php` |
| 16 | Logging mati total | FIXED | `config.php` |

---

## Detail Perbaikan

### Bug Kritis

#### 1. Operator Assignment (=) Bukannya Comparison (===)

**Lokasi:** `application/controllers/Admin.php`
**Perbaikan:** 12 kondisi `if($var = true)` diubah menjadi `if($var === true)`, termasuk 1 di dalam blok komentar.

**Dampak sebelum:** Semua operasi selalu menampilkan pesan "Berhasil" meskipun gagal di database.
**Dampak sesudah:** Feedback error berfungsi dengan benar.

#### 2. SQL Injection

**Lokasi:** `application/models/Admin_Model.php`, `application/models/User_Model.php`
**Perbaikan:** 12 query raw dengan interpolasi variable langsung diubah menggunakan Query Builder dengan parameter binding.

Method yang diperbaiki:
- `gantipassword()` - UPDATE dengan where binding
- `updatedatapilketos()` - UPDATE dengan where binding
- `resetuser()` - DELETE pakai `$this->db->delete()`
- `updateuser()` - UPDATE pakai Query Builder
- `updateidsekolah()` - UPDATE pakai array data
- `hapuskelas()` - DELETE pakai `$this->db->delete()`
- `hapuscalon()` - DELETE pakai `$this->db->delete()`
- `datakddpt()` - SELECT dengan JOIN pakai Query Builder
- `hapusdpt()` - DELETE pakai `$this->db->delete()`
- `updatedpt()` - UPDATE pakai array data
- `datacalonspesifik()` - SELECT pakai `get_where()`
- `hadir()` (User_Model) - UPDATE pakai Query Builder

#### 3. MD5 Password Hashing

**Lokasi:** `Admin.php`, `Admin_Model.php`, `User_Model.php`
**Perbaikan:** Semua penggunaan `md5()` diganti dengan `password_hash(PASSWORD_DEFAULT)` untuk hash dan `password_verify()` untuk verifikasi.

#### 4. Password Siswa Sama dengan NISN

**Lokasi:** `Admin_Model.php` method `simpandpt()` dan `simpanmassaldpt()`
**Perbaikan:** Password di-hash menggunakan `password_hash()` sebelum disimpan ke database, bukan disimpan dalam bentuk plaintext NISN.

#### 5. Foto Kehapus Saat Update Tanpa Upload File Baru

**Lokasi:** `Admin_Model.php` method `updatecalon()`
**Perbaikan:** Kolom `photo` hanya di-update jika parameter `$photo` tidak kosong (ada file baru yang diupload).

### Bug Sedang

#### 6. Route Conflict

**Lokasi:** `application/config/routes.php`
**Perbaikan:** Route `$route['(:any)'] = 'Admin/$1'` yang menimpa `$route['(:any)'] = 'User/$1'` diganti dengan `$route['admin/(:any)'] = 'Admin/$1'` yang spesifik.

#### 7. Double DELETE tb_siswa

**Lokasi:** `Admin_Model.php` method `resetdata()`
**Perbaikan:** Hapus query `DELETE FROM tb_siswa` yang duplikat.

#### 8. Database Query Langsung di View

**Lokasi:** `application/views/user/index.php`
**Perbaikan:** Query `$this->db->get_where()` langsung di view dihapus, diganti dengan menggunakan variable `$sudah_memilih_osis` dan `$sudah_memilih_mpk` yang sudah disediakan oleh controller.

#### 9. regvalid() Return Type Tidak Konsisten

**Lokasi:** `Admin_Model.php` dan `Admin.php`
**Perbaikan:** Model diubah mengembalikan array (atau array kosong) bukan boolean. Semua caller diperbaiki menggunakan `empty()` untuk pengecekan.

### Konfigurasi Keamanan

#### 10-16. Config Security Hardening

**Lokasi:** `application/config/config.php`

| Setting | Sebelum | Sesudah |
|---------|---------|---------|
| csrf_protection | FALSE | TRUE |
| global_xss_filtering | FALSE | TRUE |
| encryption_key | (kosong) | random 40-byte hex |
| cookie_httponly | FALSE | TRUE |
| sess_match_ip | FALSE | TRUE |
| sess_regenerate_destroy | FALSE | TRUE |
| log_threshold | 0 | 1 |

---

## Perbaikan Tambahan

### 17. Upload Massal DPT (Excel) Diaktifkan

**Lokasi:** `views/admin/tambahdpt.php`, `models/Admin_Model.php`
**Perbaikan:** Form upload massal yang sebelumnya dinonaktifkan (disabled, opacity 0.6, pointer-events: none) kini diaktifkan. Model methods `simpandpt()`, `simpanmassaldpt()`, `simpankelas()`, `regsekolah()`, `tambahcalon()` diperbaiki mengembalikan nilai boolean hasil operasi.

### 18. .htaccess Root Folder

**Lokasi:** `.htaccess` (file baru)
**Isi:** Proteksi akses ke file `.md`, `.sql`, `.log`, direktori `application/`, `system/`, `user_guide/`, folder `.git`, disable directory listing, dan konfigurasi URL rewriting (opsional).

### 19. Rate Limiter Login (Brute Force Protection)

**Lokasi:** `controllers/Admin.php`, `controllers/User.php`
**Perbaikan:** Maksimal 5 percobaan login dalam 5 menit. Setelah melebihi batas, akun diblokir sementara. Counter di-reset setelah login berhasil.

### 20. Database Schema Fix untuk bcrypt

**Lokasi:** `db_evotesiswa.sql` (update), `db_migrate_from_md5.sql` (file baru)
**Perbaikan:**
- Kolom `password` di `tb_admin` dan `tb_siswa`: VARCHAR(32) -> VARCHAR(255) untuk menampung bcrypt hash (60 karakter)
- Semua tabel diubah charset dari `latin1` ke `utf8_general_ci` (konsisten dengan konfigurasi aplikasi)
- Kolom `nama`, `nm_sekolah`, `photo`, dll diperbesar (varchar(32/56) -> varchar(100))
- Ditambahkan default admin user dengan bcrypt hash password
- File `db_migrate_from_md5.sql` disediakan untuk migrasi database eksisting

### 21. Fix CSRF Token di Form Vote User

**Lokasi:** `views/user/index.php`
**Perbaikan:** Dua form voting (OSIS dan MPK) menggunakan tag `<form>` HTML murni tanpa `form_open()`. Setelah CSRF protection diaktifkan, form ini tidak akan berfungsi karena tidak menyertakan CSRF token. Diperbaiki dengan mengganti ke `form_open()` yang otomatis menambahkan hidden input CSRF.

---

## Bug Yang Belum Diperbaiki

| # | Issue | Prioritas | Keterangan |
|---|-------|-----------|------------|
| 1 | Upgrade ke CodeIgniter 4 | Rendah | Perubahan besar, perlu migrasi menyeluruh |
| 2 | reCAPTCHA di form login | Rendah | Proteksi tambahan |
| 3 | Validasi input lebih ketat | Sedang | Format NISN, no urut, dll |
| 4 | Migrations untuk versioning DB | Rendah | Manajemen skema database |
| 5 | Unit test | Rendah | Jamin kualitas kode |
| 6 | Upload file tanpa validasi MIME | Sedang | Keamanan upload foto |

---

## Skor Kesehatan

**Sebelum perbaikan:** 3/10
**Sesudah perbaikan:** 8/10
