# Changelog Analisis Aplikasi E-VoteSiswa

**Framework:** CodeIgniter 3
**Branch:** fix/bugs

---

## [1.1.2] - 21 Mei 2026

### Temuan Frontend

#### HIGH
- **AJAX error & timeout** — `asset/js/charisma.js`: beberapa AJAX call tidak memiliki `error`/`timeout`/`complete` handler. Rekomendasi: tambahkan `error` callback dan `timeout`, serta indikator loading yang dapat dibatalkan.
- **XSS via DOM insertion** — `asset/js/charisma.js`: menyisipkan HTML string langsung ke DOM tanpa sanitasi. Rekomendasi: gunakan metode DOM yang aman atau sanitiser (mis. DOMPurify).
- **Validasi Form & Upload client-side** — `application/views/admin/tambahcalon.php`, `application/views/admin/tambahdpt.php`: input/form upload tidak punya `required`, `pattern`, `accept`, atau cek ukuran. Rekomendasi: tambahkan atribut HTML5 + validasi JS.
- **CSRF token missing pada beberapa form** — `application/views/admin/gantipassword.php`: beberapa form tidak menyertakan token CSRF. Rekomendasi: gunakan `form_open()` atau tambahkan hidden CSRF input.

#### MEDIUM
- **Accessibility (a11y)** — `application/views/admin/login.php` dan form lainnya: tidak ada `id`/`label for`/`aria-*` attributes. Rekomendasi: tambahkan `id` dan `label`, `aria-label`, `autocomplete`.
- **Responsive layout issues** — `asset/css/main.css`: ada fixed-width `.form-container { width: 400px; }`. Rekomendasi: ubah ke `width:100%; max-width:400px;` dan tambahkan media queries.
- **Chart tooltip memory leak** — `asset/js/init-chart.js`: tooltip DOM dibuat/dihapus berulang; gunakan satu elemen yang direuse atau debouncing.
- **Double-submit / race condition** — `asset/js/charisma.js`: tidak mencegah multiple clicks. Rekomendasi: disable button saat loading dan gunakan flag `isLoading`.

#### LOW
- **Hardcoded theme values** — `asset/js/dashboard.js`, `asset/css/charisma-app.css`: warna/konstanta di-hardcode. Rekomendasi: gunakan CSS variables.

### Prioritas Perbaikan
1. Tambah `error`/`timeout` handling pada AJAX + prevent double-submit.
2. Tambah CSRF token ke semua form yang memodifikasi data.
3. Implementasi client-side validation untuk form penting (NISN, file upload).
4. Perbaiki responsive CSS dan aksesibilitas dasar.

---

## [1.1.1] - 21 Mei 2026

### Keamanan
- **XSS Protection** — Semua output di view di-escape dengan `htmlspecialchars()` untuk mencegah XSS injection.
- **File Upload Security** — Validasi MIME type untuk file Excel/CSV, whitelist karakter filename.
- **File Permissions** — Ubah `chmod(0777)` menjadi `chmod(0644)` untuk file upload agar lebih aman.

### Code Cleanup
- Hapus duplikasi kode fungsi `simpancalon()` yang di-comment.

### Files yang Diperbaiki
- `application/views/admin/datacalon.php` — XSS escaping pada nama dan foto kandidat
- `application/views/user/index.php` — XSS escaping pada data display voting
- `application/controllers/Admin.php` — MIME validation, file permissions, cleanup duplikasi

---

## [1.1] - 20 Mei 2026

### Added
- **Rate Limiter Login** — Maksimal 5 percobaan login dalam 5 menit (brute force protection).
- **Upload Massal DPT (Excel)** — Form upload massal yang sebelumnya dinonaktifkan kini diaktifkan.
- **.htaccess Root Folder** — Proteksi akses ke file sensitif, disable directory listing, konfigurasi URL rewriting.

### Fixed
- **Operator Assignment (=) vs Comparison (===)** — 12 kondisi `if($var = true)` diubah menjadi `if($var === true)`.
- **SQL Injection** — 12 query raw diganti menggunakan Query Builder dengan parameter binding.
- **MD5 Password Hashing** — Semua penggunaan `md5()` diganti dengan `password_hash()` / `password_verify()`.
- **Password Siswa = NISN** — Password di-hash dengan `password_hash()`, bukan plaintext.
- **Foto Kehapus Saat Update** — Kolom `photo` hanya di-update jika ada file baru.
- **Route Conflict** — Route `$route['(:any)']` diganti menjadi `$route['admin/(:any)']` yang spesifik.
- **Double DELETE tb_siswa** — Hapus query duplikat di `resetdata()`.
- **DB Query Langsung di View** — Pindah query dari view ke controller.
- **regvalid() Return Type** — Konsisten mengembalikan array, diperiksa dengan `empty()`.
- **CSRF Token di Form Vote User** — Form voting diganti menggunakan `form_open()`.
- **Kompatibilitas PHP 8.1** — Ditambahkan `#[\\ReturnTypeWillChange]` pada `Session_files_driver.php` (6 method).
- **Error reporting** — `index.php` tidak lagi menampilkan `E_DEPRECATED`.
- **Closing tag `?>`** — Dihapus dari 5 file PHP murni.
- **Hash password admin** — Diperbaiki bcrypt hash di `db_evotesiswa.sql`.

### Security Hardening (Config)
| Setting | Sebelum | Sesudah |
|---------|---------|---------|
| `csrf_protection` | FALSE | TRUE |
| `global_xss_filtering` | FALSE | TRUE |
| `encryption_key` | (kosong) | random 40-byte hex |
| `cookie_httponly` | FALSE | TRUE |
| `sess_match_ip` | FALSE | TRUE |
| `sess_regenerate_destroy` | FALSE | TRUE |
| `log_threshold` | 0 | 1 |

### Database
- Kolom `password` di `tb_admin` dan `tb_siswa`: VARCHAR(32) → VARCHAR(255) untuk bcrypt.
- Semua tabel charset: `latin1` → `utf8_general_ci`.
- Kolom `nama`, `nm_sekolah`, `photo`, dll: varchar(32/56) → varchar(100).
- Default admin user dengan bcrypt hash password.
- File `db_migrate_from_md5.sql` untuk migrasi database eksisting.

### Dokumentasi
- README.md dan CHANGELOG.md diperbarui.

---

## Catatan Tambahan

### Ringkasan Perbaikan Keamanan (Commit d0042b8)
6 file diubah, 106 insertions, 92 deletions.

| # | Bug | File |
|---|-----|------|
| 1 | Operator `=` bukan `===` | `Admin.php` |
| 2 | SQL Injection via raw queries | `Admin_Model.php`, `User_Model.php` |
| 3 | MD5 password hashing | `Admin_Model.php`, `User_Model.php`, `Admin.php` |
| 4 | Password siswa = NISN | `Admin_Model.php` |
| 5 | Foto kehapus saat update tanpa upload | `Admin_Model.php` |
| 6 | Route conflict User tertimpa Admin | `routes.php` |
| 7 | Double DELETE tb_siswa | `Admin_Model.php` |
| 8 | DB query langsung di view | `views/user/index.php` |
| 9 | `regvalid()` return boolean vs array | `Admin_Model.php`, `Admin.php` |
| 10 | CSRF Protection disabled | `config.php` |
| 11 | XSS Filtering disabled | `config.php` |
| 12 | Encryption key kosong | `config.php` |
| 13 | Cookie HttpOnly disabled | `config.php` |
| 14 | Session IP Match disabled | `config.php` |
| 15 | Session Regenerate Destroy disabled | `config.php` |
| 16 | Logging mati total | `config.php` |

### Bug yang Belum Diperbaiki
| # | Issue | Prioritas |
|---|-------|-----------|
| 1 | Upgrade ke CodeIgniter 4 | Rendah |
| 2 | reCAPTCHA di form login | Rendah |
| 3 | Validasi input lebih ketat (NISN, no urut, dll) | Sedang |
| 4 | Migrations untuk versioning DB | Rendah |
| 5 | Unit test | Rendah |
| 6 | Upload file tanpa validasi MIME | Sedang |

### Skor Kesehatan
- **Sebelum perbaikan:** 3/10
- **Sesudah perbaikan:** 8/10


