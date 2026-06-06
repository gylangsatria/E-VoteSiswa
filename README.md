# E-VoteSiswa

Aplikasi e-voting untuk pemilihan Ketua OSIS dan MPK di sekolah. Dikembangkan sebagai penyesuaian dan pengembangan ulang dari [E-Pilketos](https://github.com/fpls-software/pilketos).

Aplikasi ini tersedia secara gratis untuk digunakan oleh sekolah-sekolah.

---

## Daftar Perubahan

| Tanggal | Versi | Keterangan |
|---------|-------|------------|
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

- **Reset Data** — Menghapus seluruh data pemilihan untuk periode berikutnya
- **Kunci Akun** — Mengunci akun DPT setelah memilih, mencegah pemilihan ganda
- **Reset User** — Membuka kembali akun DPT yang terkunci jika ada komplain
- **Data Sekolah** — Memperbarui informasi profil sekolah
- **Data Kelas** — Menambahkan atau menghapus kelas untuk DPT
- **Data Kandidat** — Menambahkan kandidat Ketua OSIS dan MPK
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

## Docker Branch Workflow

Proyek menggunakan 2 branch untuk memisahkan konfigurasi Docker dari kode aplikasi:

| Branch | Berisi Docker? | Kegunaan |
|--------|:---:|----------|
| `main` | ❌ | Kode aplikasi murni (production) |
| `docker` | ✅ | Kode aplikasi + konfigurasi Docker |

### Script Bantuan

| Script | Arah | Fungsi |
|--------|:----:|--------|
| `./sync-docker.sh` | **main → docker** | Ambil perubahan terbaru dari `main` ke `docker` |
| `./merge-docker-to-main.sh` | **docker → main** | Gabungkan hasil develop di `docker` ke `main` (file Docker otomatis dikeluarkan) |

### Workflow Develop

```bash
# 1. Mulai develop di branch docker
git checkout docker
git push origin docker

# 2. Saat selesai fitur → kirim ke main
./merge-docker-to-main.sh

# 3. Sync balik main → docker (agar kedua branch tetap sejajar)
./sync-docker.sh
```

---

## Perbaikan & Perubahan (Branch: `fix/bugs`)

Branch `fix/bugs` berisi perbaikan keamanan, kompatibilitas, dan tambahan fitur. Detail lengkap ada di [CHANGELOG.md](CHANGELOG.md).

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
