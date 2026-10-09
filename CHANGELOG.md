# Changelog Analisis Aplikasi E-VoteSiswa

**Framework:** CodeIgniter 3
**Branch:** main

---

## [1.6.3] - 9 Oktober 2026

### Security
- **Validasi Server-Side Aksi Admin** — input bertipe array (`username[]=`, `nm_kelas[]=`, `password[]=`) kini ditolak sebelum diproses pada `updatepassword`, `simpankelas`, `simpandpt`, `updatedpt`, `simpancalon`, dan `updatecalon`. Sebelumnya `password[]=` menyebabkan HTTP 500 (`password_hash(): Argument #1 must be of type string, array given`) dan `nm_kelas[]=` memicu `Unknown column 'Array' in 'field list'`; kini pesan validasi tampil tanpa error 500.
- **Validasi Nilai Aksi Admin** — `simpankelas` menolak nama kelas kosong/terlalu panjang (maks. 32 karakter, sesuai kolom `varchar(32)`); `simpancalon`/`updatecalon` menolak nomor urut non-angka, nama calon/wakil kosong, dan jenis kandidat di luar 0/1.
- **Pesan Sukses Palsu `updatepassword`** — password kosong atau 1 karakter sebelumnya diterima dengan pesan "Berhasil Memperbarui Password"; kini minimal 6 karakter (konsisten dengan atribut `minlength` pada form).
- **Stored XSS pada Pesan Flash** — seluruh pesan `flashdata` yang dirender ke HTML kini di-escape dengan `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` pada 12 view (admin & user). Nilai yang berasal dari input pengguna (mis. NISN pada pesan duplikat DPT) sebelumnya ditampilkan mentah; karena CSP memakai nonce dan jQuery mem-patch tag `<script>` saat runtime, payload `<script>` dapat dieksekusi. Ini menutup kanal tersebut.
- **NISN Non-Numerik pada Impor Massal** — `simpanmassaldpt` kini menolak baris dengan NISN non-numerik (`ctype_digit`).

### Fixed
- **Pesan Flash Selalu Muncul Lagi Setiap Reload** — `Session::_ci_init_vars()` pada CI 3.1.8 bergantung pada perbandingan longgar `'old' < time()`. Di PHP 7 string `'old'` dikonversi ke `0` sehingga selalu lebih kecil dari `time()` dan flashdata dibersihkan; di PHP 8 perbandingan string dengan integer memakai aturan baru dan hasilnya `false`, sehingga penanda `'old'` **tidak pernah** dibersihkan dan pesan (mis. "Berhasil Mereset User", "Berhasil Menambahkan Data") muncul terus di setiap reload dan menumpuk di sesi. Penanda `'old'` kini dicocokkan eksplisit (`$value === 'old' OR (int) $value < $current_time`); `tempdata` berbasis timestamp tidak terpengaruh.
- **`updatedpt` Menulis Data Tak Valid** — `updatedpt` kini melakukan validasi server-side yang sama dengan `simpandpt` (NISN numerik, nama wajib, jenis kelamin L/P, kelas harus terdaftar). Sebelumnya `jk=Z`, `kd_kelas=999` (kelas tidak ada), dan nama kosong tersimpan langsung ke database.
- **Pengecekan "Sudah Memilih" Tidak Pernah Aktif** — `view_vote` masih membandingkan `tb_pilihan.nisn` dengan `tb_pilih.nisn` (kini berisi NISN pemilih) sehingga selalu mengembalikan 0 baris. Join diperbaiki ke `tb_pilih.calon_nisn`. Akibat bug ini, siswa yang sudah memilih tetap dapat login kembali dan tombol voting tetap tampil. Diperbaiki pada `db_evotesiswa.sql`, migrasi `db_migrate_from_md5.sql`, dan database berjalan.
- **`simpandpt` Tidak Memvalidasi Input** — NISN kosong/non-numerik/terlalu pendek, nama kosong, jenis kelamin tidak valid, dan kelas yang tidak terdaftar kini ditolak dengan pesan jelas. Sebelumnya baris tidak masuk database namun pesan "Berhasil Menambahkan Data" tetap tampil.
- **Pesan Salah pada `hapusdpt` Gagal** — flashdata `failed` sebelumnya berisi "Berhasil Menghapus Data"; kini "Gagal Menghapus Data".

---

## [1.6.2] - 8 Oktober 2026

### Fixed
- **Impor DPT Massal Gagal pada `.xlsx`** — ekstensi `zip` belum aktif di image PHP sehingga pembaca `SpreadsheetReader_XLSX` fatal. `ext-zip` ditambahkan ke Dockerfile; impor `.xls`, `.xlsx`, dan `.csv` kini terbaca di dalam container.
- **Impor DPT Berhenti di Tengah (HTTP 500)** — galat database saat impor (mis. NISN duplikat) kini ditangkap sebagai `Throwable` dan dicatat sebagai baris gagal, bukan mematikan proses. CI 3.1.8 di PHP 8.1 melempar `mysqli_sql_exception`, sehingga `catch (Exception)` sebelumnya tidak menangkapnya.
- **Nama Kelas dari Excel Menjadi Kosong di DPT** — kolom ke-4 file impor sering berisi **nama kelas** (mis. `VII B`) sedangkan `tb_siswa.kd_kelas` bertipe `int`; MySQL mengubahnya menjadi `0` tanpa error sehingga kolom Kelas tampak kosong. Impor kini memetakan kelas lewat `Admin_Model::kelas_id_dari()`: dicari berdasarkan `kd_kelas`, lalu `nm_kelas`; bila belum ada, kelas dibuat otomatis. `jk` dinormalkan (`l` → `L`), kelas wajib diisi, dan kelas baru dilaporkan pada catatan impor.
- **Kolom Kelas Kosong Terbaca Menyesatkan** — baris DPT tanpa kelas valid kini ditampilkan sebagai `—` dengan peringatan, dan opsi kelas kosong pada form ditandai `-- Pilih kelas --` (bukan baris kosong tanpa label).
- **Edit/Hapus DPT Gagal (400 / 404 / salah data)** — tautan aksi memakai parameter query/POST (bukan segmen URI), sehingga NISN berawalan `'` tidak lagi ditolak `permitted_uri_chars`; `editdpt`, `updatedpt`, dan `hapusdpt` menerima nilai `NULL` dan melakukan fallback. `datakddpt` memakai `LEFT JOIN` agar siswa tanpa kelas tetap tampil, dan halaman DPT menampilkan pesan hasil (flashdata) setelah simpan.
- **Mode Production Tidak Terpakai** — `ENVIRONMENT` dibaca langsung dari `getenv('CI_ENV')`; sebelumnya `variables_order = "EGPCS"` membuat `$_SERVER` tidak memuat variabel environment sehingga aplikasi tetap berjalan sebagai `development` meski `CI_ENV=production`.
- **Catatan Impor Kurang Informasi di Production** — pesan penyebab kegagalan kini tetap tampil di mode production, sedangkan baris debug (`🔍`) disembunyikan; galat pembacaan file tidak lagi membocorkan path server.
- **Background Halaman Login Kadang Hilang** — background login tidak lagi bergantung pada utility Tailwind CDN yang digenerate saat runtime; dipindah ke inline `<style> body.login-bg` pada view login siswa dan admin.

---

## [1.6.1] - 8 Oktober 2026

### Fixed
- **Akun Terkunci Permanen** — lock rate-limit login tidak lagi permanen. Setelah 5 kali percobaan gagal, akun terkunci 5 menit lalu terbuka otomatis (counter di-reset ke 1 memakai kolom `updated_at`, tanpa kolom baru). Sebelumnya tidak ada reset sama sekali sehingga akun admin harus dibuka manual lewat database.
- **Lockout DoS pada Akun Admin** — percobaan login dengan username kosong/spasi tidak lagi dicatat sebagai attempt, sehingga akun admin tidak dapat dikunci oleh request asal-asalan.
- **Feedback Lock** — pesan "akun terkunci 5 menit" kini tampil tepat pada percobaan ke-5 (sebelumnya baru muncul pada percobaan ke-6), dan username yang tidak terdaftar dibedakan pesannya.
- **Pertumbuhan Tabel** — baris `tb_login_attempts` yang lebih tua dari 1 hari dibersihkan otomatis saat ada percobaan login gagal.
- **Pesan Error Login Admin Tidak Muncul** — `Admin::login()` menghapus flashdata `failed` sebelum view dirender, sehingga pesan "Username atau Password Salah" (dan pesan lock) tidak pernah tampil. Penghapusan dipindah ke saat login berhasil.

---

## [1.6.0] - 7 Oktober 2026

### Added
- **Batas Waktu Voting** — Admin dapat menetapkan tanggal, jam mulai, dan jam selesai pelaksanaan pada halaman utama (`Data Pilketos`) serta mengaktifkannya. Bila diaktifkan, siswa hanya dapat memilih pada rentang waktu tersebut; tombol voting dinonaktifkan dan `User::vote` menolak di luar jadwal (guard server-side). Bila tidak diaktifkan, voting selalu terbuka.
- Kolom DB `jam_mulai`, `jam_selesai`, `aktif` pada `tb_datapilketos` (tersedia di `db_evotesiswa.sql` dan `db_migrate_from_md5.sql`).
- Helper `tgl_jadwal()` untuk menampilkan jadwal dalam format Indonesia.

### Fixed
- **Timezone Jadwal Voting** — timezone PHP kini di-set eksplisit (`Asia/Jakarta`, dapat dioverride via `APP_TIMEZONE`) pada `index.php`. Sebelumnya aplikasi mengandalkan timezone server; di container Docker (default UTC) jadwal voting meleset 7 jam sehingga rentang waktu yang seharusnya terbuka terbaca tertutup (dan sebaliknya).

---

## [1.5.1] - 7 Oktober 2026

### Security
- **Host Header Injection** — `base_url` tidak lagi memakai `HTTP_HOST` mentah. Produksi memakai `APP_BASE_URL`; jika kosong pada `ENVIRONMENT=production`, fallback ke nilai tetap (tidak mengikuti header Host). Header Host sekarang hanya dipakai di mode development.
- **Reflected XSS** — nilai `?keyword` pada halaman DPT kini di-escape (`htmlspecialchars`) saat di-render ke `value` input.
- **Ballot Manipulation** — `User::vote` tidak lagi memercayai `nisn`/`opsi_mpkosis` dari klien. Varian `tb_pilihan` divalidasi server-side; `opsi_mpkosis` diambil dari baris kandidat, bukan dari POST. Vote untuk kandidat tak dikenal ditolak.
- **Forced-Action / CSRF GET** — seluruh endpoint tulis admin (`simpankelas`, `simpandpt`, `simpancalon`, `updatecalon`, `updatedpt`, `updateidsekolah`, `simpansekolah`, `updatedatapilketos`, `updatepassword`, `resetuser`, `reset_vote`, `hapussemuakelas`, `hapussemuadpt`, `simpanmassaldpt`, dll.) dan `User::vote` kini menolak metode non-POST (405).
- **Session Fixation** — ID sesi dirotasi (`sess_regenerate`) saat login admin dan siswa berhasil.
- **Cookie Hardening** — cookie sesi dan CSRF kini menyetel `SameSite=Lax` (via `sess_samesite`).
- **Array Injection (DoS)** — input `username`/`password` divalidasi bertipe string sebelum diproses; mencegah error 500/response kosong akibat `username[]=`.
- **Upload Berbahaya** — impor DPT massal kini membatasi ekstensi (`.xls/.xlsx/.csv`) selain validasi MIME.
- **Exposure Konfigurasi** — berkas `docker-compose.yml`, `docker/entrypoint.sh`, `docker/php/php.ini`, `.env.example`, dll. tidak lagi dapat diunduh (deny list `.htaccess` diperluas + direktori `docker/` diblokir).

### Changed
- `application/config/config.php`: `base_url` env-aware (`APP_BASE_URL`), `sess_samesite`.
- `system/libraries/Session/Session.php` & `system/core/Security.php`: dukungan `SameSite`.
- `.env.example` & `docker-compose.yml`: variabel `APP_BASE_URL`.

---

## [1.5.0] - 7 Oktober 2026

### Security
- **Guard Mutasi Admin** — `Admin::__construct()` kini menolak seluruh aksi kecuali `login`/`loginvalidation` bila sesi admin tidak ada. Sebelumnya endpoint mutasi (`simpankelas`, `simpancalon`, `updateidsekolah`, `reset_vote`, `hapussemuadpt`, dll.) dapat dipanggil tanpa login karena guard hanya ada di fungsi view.
- **CSRF untuk Aksi Destruktif** — `hapuscalon`, `hapusdpt`, `hapuskelas`, `hapussemuakelas`, `hapussemuadpt`, `resetdata`, `reset_vote`, dan `cetakdaftarhadir` kini wajib `POST` (GET ditolak 405) dan memakai `form_open` sehingga token CSRF wajib. Menutup CSRF berbasis `<img src>` yang memanfaatkan `Security::csrf_verify()` yang tidak memeriksa GET.
- **Rate-Limit Login Persisten** — counter percobaan login admin dan siswa dipindah dari session ke tabel `tb_login_attempts` (per akun), sehingga tidak dapat dilewati dengan menghapus cookie/lintas session.
- **XSS Output** — `global_xss_filtering` dimatikan (cegah double-encode) dan seluruh output data dinamis kini di-escape eksplisit (`datadpt`, `daftarhadir`, `datakelas`, `hasilvote`, `footer`, `editdpt`, `tambahdpt`). Data tetap tersimpan utuh tanpa mutasi.
- **Cek Registrasi Valid** — `cetakdaftarhadir` (PDF berisi PII) kini memerlukan sesi admin dan metode POST.

### Changed
- **Infra Docker** — `db` tidak lagi mempublikasikan port MySQL (hanya jaringan internal); `phpmyadmin` masuk profile `tools` (tidak jalan default) dan tidak lagi dipublikasikan ke host. Kredensial DB dan `encryption_key` dibaca dari environment (`.env`, lihat `.env.example`); `.env` di-`gitignore`.
- **Cookie & Environment** — `cookie_secure` otomatis `TRUE` saat HTTPS aktif; default `CI_ENV` compose menjadi `production`.
- **DB** — tabel baru `tb_login_attempts` (tersedia di `db_evotesiswa.sql` dan `db_migrate_from_md5.sql`).

---

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


