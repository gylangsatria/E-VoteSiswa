# Changelog Analisis Aplikasi E-VoteSiswa

**Tanggal:** 20 Mei 2026
**Framework:** CodeIgniter 3
**Branch:** fix/bugs

---

## Ringkasan Perbaikan (Commit d0042b8)

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

## Bug Yang Belum Diperbaiki

Berikut issue yang tercatat di analisis awal namun belum diperbaiki di batch ini:

| # | Issue | Prioritas | Keterangan |
|---|-------|-----------|------------|
| 1 | Upgrade ke CodeIgniter 4 | Rendah | Perubahan besar, perlu migrasi menyeluruh |
| 2 | Rate limiter login | Sedang | Mencegah brute force |
| 3 | reCAPTCHA di form login | Rendah | Proteksi tambahan |
| 4 | Validasi input lebih ketat | Sedang | Format NISN, no urut, dll |
| 5 | Migrations untuk versioning DB | Rendah | Manajemen skema database |
| 6 | Unit test | Rendah | Jamin kualitas kode |
| 7 | Upload file tanpa validasi MIME | Sedang | Keamanan upload foto |
| 8 | Tidak ada .htaccess di root | Sedang | Proteksi direktori |

---

## Skor Kesehatan

**Sebelum perbaikan:** 3/10
**Sesudah perbaikan:** 7/10
