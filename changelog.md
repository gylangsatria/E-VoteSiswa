# Changelog Analisis Aplikasi E-VoteSiswa

**Tanggal:** 20 Mei 2026  
**Framework:** CodeIgniter 3  
**Skor Kesehatan:** 3/10

---

## BUG KRITIS

### 1. Operator Assignment (=) Bukannya Comparison (== / ===) di Seluruh Controller Admin

Pada file `application/controllers/Admin.php`, semua blok kondisi menggunakan single `=` (assignment) bukan `==` atau `===` (comparison). Contoh:

```php
if($reg = true) { ... }       // baris simpansekolah
if($update = true) { ... }    // baris updatepassword, updatedatapilketos, resetuser
if($save = true) { ... }      // baris simpankelas, simpandpt, updateidsekolah
if($hapus = true) { ... }     // baris hapuskelas, hapuscalon, hapusdpt
```

**Dampak:** Semua operasi selalu menampilkan pesan "Berhasil" meskipun operasi gagal di database. Branch `else` (pesan gagal) tidak pernah dieksekusi.

### 2. SQL Injection di Seluruh Model

Semua query di `application/models/Admin_Model.php` menggunakan string interpolation langsung tanpa parameter binding:

```php
$this->db->query("DELETE FROM tb_siswa WHERE username='$username'");
$this->db->query("UPDATE tb_kelas SET ... WHERE kd_kelas='$kd_kelas'");
$this->db->query("UPDATE tb_admin SET password='$password_hash' WHERE username='$username'");
```

**Dampak:** Attacker bisa menginjeksi SQL melalui form input.

### 3. MD5 untuk Password

```php
$password_hash = md5($password);
```

MD5 adalah algoritma hashing yang sudah rusak secara kriptografi dan dapat di-reverse dalam hitungan detik.

### 4. Password Siswa Sama dengan NISN (Username)

Di `Admin.php` method `simpandpt()`:

```php
$username = $this->input->post('nisn');
$password = $this->input->post('nisn');  // Password = Username = NISN
```

Setiap siswa memiliki username dan password yang sama, yaitu NISN mereka.

### 5. Foto Opsional Tapi Query Update Tetap Menimpa dengan NULL

Di `Admin_Model::updatecalon()`, ketika parameter `$photo` bernilai `null` (user tidak upload foto baru), query tetap menulis `photo = ''` ke database, sehingga foto lama terhapus.

### 6. Route Bentrok

Di `application/config/routes.php`:

```php
$route['(:any)'] = 'User/$1';   // Ditimpa oleh baris berikutnya
$route['(:any)'] = 'Admin/$1';  // Route User jadi tidak berfungsi
```

Semua request `/(:any)` akan diarahkan ke controller `Admin` saja.

---

## BUG SEDANG

### 7. Double DELETE tb_siswa di resetdata()

Di `Admin_Model::resetdata()`, tabel `tb_siswa` dihapus dua kali:

```php
$reset1 = $this->db->query("DELETE FROM tb_siswa");
$reset3 = $this->db->query("DELETE FROM tb_siswa"); // DUPLIKAT
```

### 8. Logika Database di View

Di `application/views/user/index.php`, query database dijalankan langsung di file view:

```php
$cek_osis = $this->db->get_where('tb_pilih', ['username' => $username, 'opsi_mpkosis' => 0])->num_rows();
```

Ini melanggar prinsip MVC (Model-View-Controller).

### 9. regvalid() Return Boolean Tidak Konsisten

Method `Admin_Model::regvalid()` mengembalikan `true`/`false` (boolean), tetapi di controller method `regvalid()` diakses sebagai array:

```php
$data = $this->Admin_Model->regvalid();
$valid = $data[0];  // Error: $data adalah boolean, bukan array
```

### 10. Tipe File Upload Tidak Konsisten

Di `simpancalon()`, allowed types adalah `gif|jpg|jpeg|png`, sedangkan di `updatecalon()` hanya `jpg|jpeg|png` (tanpa gif).

### 11. Logging Dimatikan Total

```php
$config['log_threshold'] = 0;  // Semua logging disabled
```

Error tidak tercatat, menyulitkan proses debugging.

---

## CELAH KEAMANAN

### Kritis

- **CSRF Protection = FALSE** - Semua form rentan terhadap Cross-Site Request Forgery attack
- **Global XSS Filtering = FALSE** - Input pengguna tidak difilter XSS secara global
- **Empty Encryption Key** - Session dan cookie tidak terenkripsi

### Sedang

- **Cookie HttpOnly = FALSE** - Cookie bisa diakses oleh JavaScript (memudahkan session theft via XSS)
- **Session IP Matching = FALSE** - Memudahkan session hijacking
- **No Rate Limiting Login** - Brute force attack tanpa hambatan
- **Tidak ada .htaccess di root** - Direktori asset tidak terlindungi
- **Upload file tanpa validasi konten** - Hanya ekstensi yang dicek, bukan MIME type sebenarnya

---

## POTENSI PENINGKATAN

### Prioritas Tinggi (Segera)

| # | Peningkatan | Manfaat |
|---|-------------|---------|
| 1 | Ganti `=` jadi `==`/`===` di semua kondisi Admin.php | Memperbaiki bug feedback error |
| 2 | Ganti MD5 menjadi `password_hash()` + `password_verify()` | Password aman secara kriptografi |
| 3 | Ganti query raw menjadi Query Builder dengan binding parameter | Cegah SQL injection |
| 4 | Enable CSRF Protection (`csrf_protection = TRUE`) | Lindungi dari CSRF attack |
| 5 | Pisahkan password siswa dari NISN (generate random password) | Tingkatkan keamanan login siswa |
| 6 | Set encryption key yang valid | Amankan session dan cookie |
| 7 | Enable HttpOnly cookies (`cookie_httponly = TRUE`) | Cegah session hijacking via XSS |

### Prioritas Sedang

| # | Peningkatan | Manfaat |
|---|-------------|---------|
| 8 | Perbaiki updatecalon - hanya update foto jika diupload | Cegah foto kandidat terhapus |
| 9 | Perbaiki route di routes.php | Buat routing konsisten |
| 10 | Pindahkan query dari view ke model | Ikuti MVC dengan benar |
| 11 | Enable logging (`log_threshold = 1`) | Permudah debugging |
| 12 | Hapus duplicate DELETE di resetdata() | Bersihkan kode |
| 13 | Enable session IP matching | Kurangi risiko session hijacking |

### Prioritas Rendah (Jangka Panjang)

| # | Peningkatan | Manfaat |
|---|-------------|---------|
| 14 | Upgrade ke CodeIgniter 4 | Framework modern dengan security built-in |
| 15 | Tambahkan rate limiter login | Cegah brute force attack |
| 16 | Implementasi reCAPTCHA di form login | Cegah serangan bot |
| 17 | Validasi input lebih ketat (no urut, format NISN) | Data lebih bersih dan konsisten |
| 18 | Gunakan migrations untuk versioning database | Manajemen skema database lebih baik |
| 19 | Tambahkan unit test | Jamin kualitas kode |
| 20 | Migrasi tampilan ke versi Bootstrap terbaru | Tampilan lebih modern dan responsif |

---

## Ringkasan

Aplikasi E-VoteSiswa secara fungsional dapat berjalan, tetapi mengandung bug kritis (terutama operator `=` vs `==`) yang membuat feedback error tidak pernah muncul, dan celah keamanan serius (SQL injection, MD5, CSRF disabled) yang membuatnya sangat berbahaya jika digunakan di lingkungan production. Sebelum digunakan secara luas, wajib memperbaiki 7 item prioritas tinggi di atas.
