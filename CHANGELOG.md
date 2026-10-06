# Changelog Analisis Aplikasi E-VoteSiswa

**Framework:** CodeIgniter 3
**Branch:** main

---

## [1.4.1] - 7 Oktober 2026

### Fixed
- **Pemetaan Status Voting Tertukar** — Pada halaman voting, status "sudah memilih" untuk OSIS/OSIM dan MPK sebelumnya tertukar (`opsi_mpkosis` 0/1 dipakai kebalik). Akibatnya siswa baru memilih salah satu kategori tetapi kategori lain yang terkunci, sehingga voting tidak bisa diselesaikan. Kini dipetakan sesuai penandaan database (`0 = MPK`, `1 = OSIS/OSIM`), termasuk di `viewlogout`.
- **Isolasi Sesi Admin vs Siswa** — Halaman admin sebelumnya dijaga oleh kunci sesi `username` yang sama dengan sesi siswa, sehingga siswa yang login dapat membuka seluruh halaman admin (identitas, kandidat, DPT, hasil vote, reset). Admin kini memakai kunci sesi terpisah (`admin`) dan siswa memakai `nisn`.
- **Ganti Password Admin** — `updatepassword` tidak lagi memanggil `updateuser()` (efek samping salah ke `tb_siswa.hadir`); username diambil dari sesi admin dan password hanya diubah pada akun admin yang login.
- **Duplikat DPT** — Form tambah DPT kini menolak NISN yang sudah terdaftar (pesan jelas) alih-alih memicu kegagalan insert.
- **Validasi Upload Massal** — Validasi MIME file import memakai `finfo` dari isi file di server (bukan `$_FILES['type']` dari klien yang dapat dipalsukan).

### Removed
- **`application/controllers/datavote.php`** — Berkas berisi view HTML yang tidak terpakai dan merujuk rute `admin/autorefresh` yang tidak ada; dihapus.

### Changed
- Log debug per-vote pada controller `User` dihapus agar tidak menulis NISN ke log aplikasi.

---

## [1.4.0] - 7 Oktober 2026

### Added
- **Pilihan Jenis Satuan (Sekolah/Madrasah)** — Admin dapat memilih jenis satuan pendidikan pada halaman Identitas. Seluruh label terkait menyesuaikan otomatis: `OSIS ↔ OSIM`, `Sekolah ↔ Madrasah`, dan `Kepala Sekolah ↔ Kepala Madrasah` pada halaman admin, halaman voting siswa, serta laporan PDF.
- **Helper `labels_helper.php`** — Helper baru (`org_mode()`, `org_label()`) dengan cache per-request untuk membaca jenis satuan dan menyediakan label dinamis; di-autoload melalui `config/autoload.php`.
- **Kolom DB `jenis`** — Ditambahkan pada `tb_identitassekolah` (`varchar(10)` default `sekolah`).

### Changed
- **Form Tambah Kandidat** — Layout dirapikan menjadi grid dua kolom; field pendek (NISN, Nomor Urut, Kandidat, Foto) berdampingan, field nama calon memakai lebar penuh.
- **Form Identitas** — Jarak antar label diseragamkan (`mt-4`).

### Fixed
- **Update Identitas** — `UPDATE tb_identitassekolah` sebelumnya tanpa klausa `WHERE` sehingga memperbarui seluruh baris dan memicu error duplikat saat lebih dari satu baris; kini di-scope ke baris identitas aktif.

### Migration
- Database lama perlu menambahkan kolom `jenis` pada `tb_identitassekolah` (bagian #6 `db_migrate_from_md5.sql`).

---

## [1.3.2] - 6 Oktober 2026

### Changed
- **OSIS → OSIM** — Seluruh label, kartu voting, hasil vote, laporan PDF, dan pesan aplikasi kini menggunakan istilah OSIM (MPK tidak berubah).
- **Form Kandidat** — Kolom input paslon kini: NISN, Kandidat (OSIM/MPK), Nama Calon Ketua, Nama Calon Wakil Ketua, Nomor Urut Paslon, Foto Paslon.
- **Kolom DB `nama_wakil`** — Ditambahkan pada `tb_pilihan` untuk menyimpan nama calon wakil ketua; ditampilkan di data calon, kartu voting, hasil vote, dan laporan PDF.

### Fixed
- **Laporan PDF** — Label kategori hasil pemilihan sebelumnya tertukar (kandidat `0 = MPK` diberi label "OSIS"); kini sesuai penandaan database.

---

## [1.3.1] - 6 Oktober 2026

### Changed
- **Integrasi Docker** — Konfigurasi Docker (`Dockerfile`, `docker-compose.yml`, `.dockerignore`, `docker/`) dipindah permanen ke branch `main`; branch terpisah `docker` tidak lagi diperlukan.

### Fixed
- **Permission bind mount** — `docker/entrypoint.sh` menyetel ulang ownership `application/cache`, `application/logs`, `uploads`, dan `asset/img` ke `www-data` saat container start, karena bind mount `.:/var/www/html` menimpa permission hasil build (menyebabkan upload foto/DPT dan penulisan log/cache gagal).
- **Port MySQL bentrok** — Port host database diubah ke `3308` agar tidak bertabrakan dengan MySQL lokal.
- **`.dockerignore`** — Pengecualian `docker/` dipersempit ke `docker/php/` supaya `entrypoint.sh` ikut ter-copy ke image.
- **`docker-compose.yml`** — Menghapus atribut `version` yang sudah obsolete.

### Removed
- `merge-docker-to-main.sh`, `sync-docker.sh` — skrip workflow 2-branch yang tidak lagi relevan.

---

## [1.2.1] - 7 Juni 2026

### Fixed
- **Flashdata Conflict** — Pisahkan flashdata key `'failed'` antara admin dan user (siswa) di `User.php`, `Admin.php`, dan `user/login.php` agar pesan error login tidak terbawa antar halaman.
- **Dockerfile** — Perbaiki permission FPDF di `Dockerfile` agar rebuild tidak error.

### Files yang Diperbaiki
| File | Perbaikan |
|------|-----------|
| `application/controllers/Admin.php` | Pembersihan flashdata `'failed'` saat login sukses |
| `application/controllers/User.php` | Ubah flashdata key `'failed'` menjadi `'user_failed'` |
| `application/views/user/login.php` | Sesuaikan flashdata key menjadi `'user_failed'` |
| `Dockerfile` | Perbaiki permission FPDF untuk rebuild |

---

## [1.2] - 21 Mei 2026

### UI/UX Modernization
- **CSS Global** — `asset/css/main.css`: rewrite dengan CSS custom properties, modern card shadows, smooth transitions, improved typography, responsive utilities, better form/button/table styling.
- **CSS Variables** — Warna tema dijadikan CSS variable (`--primary`, `--navbar-bg`, dll) untuk kemudahan kostumisasi tema.
- **Navbar** — Gradient background, smooth hover, dropdown modern dengan shadow dan rounded.
- **Box/Card Component** — Border dihapus, diganti shadow lembut; hover efek translateY; padding lebih lega.
- **Stat Cards (Dashboard)** — Hover efek angkat, icon lebih besar, typography lebih modern.
- **Tables** — Header uppercase + letter-spacing, row hover highlight.
- **Buttons** — Border-radius 6px, hover translateY + shadow.
- **Form Controls** — Border 1.5px, focus ring teal, height auto.

### Halaman Voting User
- **Card Kandidat** — Redesain total: card putih dengan border-radius 12px, shadow, hover translateY(-4px) + shadow-lg.
- **Image** — `object-fit: cover; height: 280px` untuk rasio konsisten.
- **Typography** — Nomor urut teal uppercase, nama bold 18px.
- **Tombol Vote** — Custom styling (merah untuk OSIM, teal untuk MPK), hover efek angkat.
- **Section Title** — Underline gradient dekoratif.

### Halaman Login (Admin & User)
- **Card** — Padding lebih lega (40px/32px), border-radius 16px, shadow lebih dalam (0 20px 60px).
- **Animasi** — `fadeUp` keyframe saat load.
- **Input** — Tinggi 48px, icon teal, border-radius 8px.
- **Button** — Full-width 48px, teal solid, hover shadow.
- **Overlay** — Gradient (dark → transparan) bukan solid.
- **Aksesibilitas** — Ditambahkan `id`, `label for`, `sr-only`, `aria-label`, `autocomplete` di user login.

### Files yang Diperbarui
| File | Perbaikan |
|------|-----------|
| `asset/css/main.css` | Rewrite CSS modern (variables, card, stat, table, form, button) |
| `asset/css/charisma-app.css` | Form container margin auto |
| `application/views/user/index.php` | Redesain card kandidat modern |
| `application/views/admin/login.php` | Redesain card login modern |
| `application/views/user/login.php` | Redesain card login + aksesibilitas |

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


