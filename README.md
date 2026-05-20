# E-VoteSiswa

Aplikasi e-voting untuk pemilihan Ketua OSIS dan MPK di sekolah. Dikembangkan sebagai penyesuaian dan pengembangan ulang dari [E-Pilketos](https://github.com/fpls-software/pilketos).

Aplikasi ini tersedia secara gratis untuk digunakan oleh sekolah-sekolah.

---

## Daftar Perubahan

| Tanggal | Versi | Keterangan |
|---------|-------|------------|
| 20 Mei 2026 | 1.1 | Perbaikan keamanan dan bug: SQL injection, MD5 ke bcrypt, CSRF/XSS enable, dan lainnya (lihat [CHANGELOG.md](CHANGELOG.md)) |
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

> **Catatan:** Secara default `base_url` di `config.php` sudah menggunakan deteksi dinamis, tidak perlu diubah.

### 4. Admin Default
Setelah import database, hash password admin perlu diupdate dengan bcrypt. Jalankan query berikut di phpMyAdmin:

```sql
UPDATE tb_admin SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'admin';
```

Password default: **admin**

> **PENTING:** Aplikasi sekarang menggunakan bcrypt (`password_hash()`), bukan MD5. Password yang sudah tersimpan dengan MD5 harus di-update dengan cara login sebagai admin, lalu ganti password melalui menu **Ganti Password**.

### 5. Akses

| Role | URL | Login |
|------|-----|-------|
| Admin | `http://localhost/evotesiswa/index.php/admin/` | Username: `admin`, Password: `admin` |
| Siswa | `http://localhost/evotesiswa/` | Username & Password = NISN |

---

## Perbaikan Keamanan (Branch: `fix/bugs`)

Branch `fix/bugs` berisi perbaikan untuk 16 issue yang ditemukan saat audit kode. Detail lengkap ada di [CHANGELOG.md](CHANGELOG.md).

Ringkasan perbaikan utama:
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
