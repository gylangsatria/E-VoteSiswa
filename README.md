# E-VoteSiswa

Aplikasi e-voting untuk pemilihan Ketua OSIS/OSIM dan MPK di sekolah maupun madrasah. Label organisasi (`OSIS`/`OSIM`) dan satuan (`Sekolah`/`Madrasah`) menyesuaikan pilihan jenis satuan pada Identitas. Dikembangkan sebagai penyesuaian dan pengembangan ulang dari [E-Pilketos](https://github.com/fpls-software/pilketos).

Aplikasi ini tersedia secara gratis untuk digunakan oleh sekolah dan madrasah.

---

## Daftar Perubahan

| Tanggal | Versi | Keterangan |
|---------|-------|------------|
| 7 Oktober 2026 | 1.5.1 | Keamanan lanjutan (uji brutal): atasi Host Header Injection, Reflected XSS keyword, ballot manipulation, forced-action GET (wajib POST), session fixation (rotasi ID), SameSite cookie, array-injection DoS, validasi ekstensi upload, tutup kebocoran docker-compose/ini/entrypoint |
| 7 Oktober 2026 | 1.5.0 | Perbaikan keamanan: guard semua endpoint admin, CSRF + POST untuk aksi destruktif, rate-limit login persisten (DB), escape output XSS, hardening Docker (DB/PMA tidak terekspos) |
| 7 Oktober 2026 | 1.4.1 | Perbaikan bug: status voting OSIS/MPK tertukar, isolasi sesi admin vs siswa, ganti password admin, cegah duplikat DPT, validasi MIME import, hapus dead code |
| 7 Oktober 2026 | 1.4.0 | Pilihan jenis satuan (Sekolah/Madrasah) dengan label dinamis OSIS/OSIM, perbaikan update identitas, dan perapian form kandidat |
| 6 Oktober 2026 | 1.3.2 | Ganti istilah OSIS → OSIM, tambah kolom calon wakil ketua pada form & data kandidat |
| 6 Oktober 2026 | 1.3.1 | Integrasi Docker permanen di `main` + perbaikan permission bind mount, port MySQL, dan `.dockerignore` |
| 7 Juni 2026 | 1.3 | Modernisasi halaman voting siswa: progress tracker OSIS/MPK, kartu kandidat dengan animasi, modal konfirmasi pilihan, navbar gradient, halaman sukses voting |
| 7 Juni 2026 | 1.2.1 | Perbaikan flashdata conflict, upload massal DPT (CSV/XLS/XLSX), & Dockerfile |
| 21 Mei 2026 | 1.2 | Modernisasi UI/UX tampilan aplikasi |
| 20 Mei 2026 | 1.1 | Kompatibilitas PHP 8.1, perbaikan keamanan & bug (lihat [CHANGELOG.md](CHANGELOG.md)) |
| - | 1.0 | Uji coba masal dan penyempurnaan fitur |
| - | 0.9.6 | Penambahan Logo di Login screen |
| - | 0.9.5 | Perbaikan laporan E-VoteSiswa |
| - | 0.9.4 | Perbaikan opsi Hapus semua data dan penambahan tombol Reset Vote |
| - | 0.9.3 | Perbaikan opsi OSIS dan MPK yang terbalik |
| - | 0.9.2 | Penambahan fitur grafik di hasil vote |
| - | 0.9.1 | Penambahan fitur pencarian DPT dan perbaikan login |
| - | 0.9 BETA | Rilis awal E-VoteSiswa |
| 19 Okt 2025 | - | Fork dari [pilketos](https://github.com/fpls-software/pilketos) |

---

## Fitur

- **Tampilan Voting Modern** — Progress tracker OSIS/OSIM/MPK, kartu kandidat dengan efek hover & animasi, modal konfirmasi pilihan
- **Navbar Gradient** — Navigasi modern dengan gradien warna dan ikon SVG
- **Halaman Sukses** — Tampilan setelah voting selesai dengan desain yang informatif
- **Reset Data** — Menghapus seluruh data pemilihan untuk periode berikutnya
- **Kunci Akun** — Mengunci akun DPT setelah memilih, mencegah pemilihan ganda
- **Reset User** — Membuka kembali akun DPT yang terkunci jika ada komplain
- **Jenis Satuan** — Memilih Sekolah atau Madrasah; label organisasi (OSIS/OSIM), satuan, dan kepala ikut menyesuaikan
- **Data Sekolah** — Memperbarui informasi profil sekolah/madrasah
- **Data Kelas** — Menambahkan atau menghapus kelas untuk DPT
- **Data Kandidat** — Menambahkan kandidat Ketua/Wakil Ketua OSIS/OSIM dan MPK
- **Data DPT** — Mengelola Daftar Pemilih Tetap dengan pencarian
- **Hasil Pemilihan** — Melihat hasil voting real-time dengan grafik
- **Daftar Hadir** — Mengunduh daftar kehadiran pemilih (PDF)
- **Laporan** — Mengunduh laporan hasil pemilihan (PDF)

---

## Instalasi Lokal

### 1. Persiapan
- Download dan install [XAMPP](https://www.apachefriends.org/download.html)
- Jalankan XAMPP Control Panel, start **Apache** dan **MySQL**
- Clone atau copy project ke folder `C:/xampp/htdocs/evotesiswa/` atau `/var/www/html/evotesiswa/`

### 2. Database
1. Buka `http://localhost/phpmyadmin`
2. Buat database dengan nama **db_pilketos**
3. Import file `db_evotesiswa.sql`

### 3. Konfigurasi Database
Edit `application/config/database.php`:
- `hostname` => `localhost`
- `username` => `root`
- `password` => `""` (kosong)
- `database` => `db_pilketos`

> **Catatan 1:** Secara default `base_url` di `config.php` sudah menggunakan deteksi dinamis, tidak perlu diubah.
> **Catatan 2:** CSRF Protection sudah diaktifkan. Semua form POST otomatis menyertakan token CSRF via `form_open()`. Jika menambahkan form baru, gunakan `form_open()` atau sertakan manual token CSRF.

### 4. Admin Default
Setelah import database, admin sudah langsung bisa login dengan:

- **Username:** `admin`
- **Password:** `admin`

> **Catatan:** Database baru (`db_evotesiswa.sql`) sudah menyertakan admin dengan bcrypt hash. Jika Anda migrasi dari database lama yang menggunakan MD5, jalankan `db_migrate_from_md5.sql` untuk update struktur dan password.

### 5. Akses

| Role | URL | Login |
|------|-----|-------|
| Admin | `http://localhost/evotesiswa/index.php/admin/` | Username: `admin`, Password: `admin` |
| Siswa | `http://localhost/evotesiswa/` | Username & Password = NISN |

---

## Menjalankan dengan Docker

Konfigurasi Docker sudah terintegrasi langsung di branch `main`.

```bash
cp .env.example .env        # lalu ubah DB_PASSWORD dan ENCRYPTION_KEY
docker compose up -d
```

| Layanan | URL | Kredensial |
|---------|-----|------------|
| Aplikasi | `http://localhost:8080` | Admin: `admin` / `admin` — Siswa: NISN |

MySQL dan phpMyAdmin **tidak** dipublikasikan ke host. Database hanya dapat diakses dari dalam jaringan Docker (`db:3306`). Untuk membuka phpMyAdmin secara sementara (hanya bind ke localhost):

```bash
docker compose --profile tools up -d phpmyadmin
```

Database `db_pilketos` otomatis dibuat dan di-import dari `db_evotesiswa.sql` saat container pertama kali dijalankan.

Perintah umum:

```bash
docker compose logs -f web   # lihat log
docker compose down          # hentikan
docker compose down -v       # hentikan + hapus data database
```

Konfigurasi aplikasi membaca variabel environment (`DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_NAME`, `SESS_SAVE_PATH`, `ENCRYPTION_KEY`, `CI_ENV`) sehingga lokal tanpa Docker tetap jalan dengan nilai default XAMPP.


---

## Perbaikan & Perubahan

Perbaikan keamanan, kompatibilitas, dan tambahan fitur didokumentasikan lengkap di [CHANGELOG.md](CHANGELOG.md).

Ringkasan perbaikan utama:
- **Kompatibilitas PHP 8.1** — Session driver dan error reporting disesuaikan
- **Operator `=` diganti `===`** — Feedback error sekarang berfungsi dengan benar
- **SQL Injection dihapus** — Semua query menggunakan Query Builder dengan parameter binding
- **MD5 diganti bcrypt** — Password di-hash dengan `password_hash()`
- **Password siswa dipisah dari NISN** — Password di-hash sebelum disimpan
- **CSRF Protection diaktifkan**
- **XSS Filtering diaktifkan**
- **Encryption key diset**
- **HttpOnly cookie, IP session matching, session regenerate destroy diaktifkan**

---

## Bug Diketahui

- Validasi MIME type upload foto masih bisa ditingkatkan (hanya ekstensi file yang dicek)
- Belum ada reCAPTCHA di halaman login

---

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
