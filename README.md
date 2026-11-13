# Aplikasi Deteksi Penyakit Gigi & Mulut

Aplikasi diagnosa sederhana berbasis gejala (PHP native + MySQL/MariaDB + Bootstrap 4).
User menjawab Ya/Tidak untuk tiap gejala, sistem menghitung kecocokan per penyakit
(`jumlah_gejala_cocok / total_gejala_penyakit × 100`) dan menyimpan hasil pemeriksaan.

## Fitur

- **User**: register/login, konsultasi diagnosa, riwayat hasil, edit akun.
- **Admin**: login terpisah, kelola jenis penyakit, relasi penyakit–gejala, kelola gejala,
  lihat daftar user + riwayat diagnosanya.

## Struktur

```
admin/    Panel admin (guard session admin + CSRF + prepared statements)
assets/   CSS/JS lokal (SB Admin, Font Awesome)
config/   koneksi.php (env-based), helpers.php (session/CSRF/escaping/guard)
database/ gigi_mulut.sql (skema bersih + seed master, tanpa PII)
login/    Login admin
views/    Aplikasi user (diagnosa, riwayat, akun)
index.php Redirect ke admin/
```

## Instalasi

1. Buat database:
   ```sql
   CREATE DATABASE gigi_mulut CHARACTER SET utf8mb4;
   ```
2. Import skema + data master:
   ```bash
   mysql -u usbal gigi_mulut < database/gigi_mulut.sql
   ```
   (Tanpa data pasien/hasil — hanya master gejala, penyakit, relasi, dan 1 akun admin.)
3. Atur kredensial via environment (jangan hardcode):
   ```bash
   export DB_HOST=localhost DB_USER=usbal DB_PASS= DB_NAME=gigi_mulut
   ```
   Lihat `.env.example`. Default kode mengikuti kredensial dev lama (`usbal`/kosong).
4. Jalankan via web server (Apache/Nginx + PHP 8 + mysqli), misal docroot ke repo ini.
   - User: `http://localhost/views/login.php`
   - Admin: `http://localhost/login/`

## Akun awal

- Admin: `admin` / `admin123` (hash bcrypt di seed).
  **Segera ganti** setelah login pertama. Jangan pakai `admin/admin` lagi.

## Keamanan (hasil audit + perbaikan)

Perbaikan yang sudah diterapkan:

- **SQL injection**: seluruh query user/admin memakai prepared statements
  (`login/login.php`, `views/login-act.php`, `views/register-act.php`,
  `views/diagnosa-pertanyaan-hasil.php`, `admin/*`, `views/*`). Verifikasi:
  input `' OR '1'='1` diperlakukan sebagai literal (0 baris).
- **Password**: `password_hash`/`password_verify` (bcrypt). Akun lama plaintext
  otomatis dimigrasi saat login pertama. Kolom diperlebar ke `varchar(255)`.
- **Auth**: `admin/index.php` + semua aksi admin memakai `require_admin()` dengan
  `exit`; tidak lagi hanya `alert` JS. Aksi destruktif tanpa guard dihapus/dikunci.
- **IDOR**: `diagnosa-hasil.php`, `riwayat-lihat.php`, `riwayat-hapus.php` memfilter
  `WHERE id = ? AND id_user = ?`.
- **XSS**: semua output DB melalui `e()` (`htmlspecialchars`).
- **CSRF**: token per-session di semua form POST (`csrf_field()`/`csrf_check()`).
- **Session**: cookie `HttpOnly` + `SameSite=Lax` (+ `Secure` bila HTTPS),
  `session_regenerate_id()` saat login, logout menghancurkan session.
- **Kredensial & PII**: `config/koneksi.php` baca env; file backup kredensial
  (`koneksi.php.bak-root`) dan `.DS_Store` dihapus; dump DB dibersihkan dari
  data pasien asli; seed hanya master + 1 admin.
- **DB**: `UNIQUE` di `user.nama` dan `penyakit_gejala(id_jenis_penyakit,id_gejala)`,
  FK `ON DELETE CASCADE` untuk data milik user, index untuk kolom join.
  Pengecualian: FK `penyakit_gejala → master` **sengaja tidak dipasang** karena
  data bawaan lama mengandung id yang tidak ada di master (lihat catatan di SQL).
  Rapikan master via panel admin sebelum mengaktifkan FK tersebut.

## Bug logika yang diperbaiki

- **Mapping jawaban geser** (`diagnosa-pertanyaan-hasil.php` lama pakai counter
  `1..N` padahal `id_gejala` mulai dari 3 dan berlubang) → sekarang loop berdasarkan
  `id_gejala` valid dari DB.
- **Tabel kerja menumpuk** (`sementara`/`hasil_sementara`/`hasil_kemungkinan` tidak
  dibersihkan, `MAX()` mengambil riwayat lama) → sekarang dibersihkan sebelum dan
  sesudah diagnosa dalam satu transaksi.
- **0 gejala** → sekarang ditolak dengan pesan, tidak insert `NULL`.
- **Duplikat relasi** penyakit–gejala dicegah di aplikasi + `UNIQUE`.
- **Form mati**: `akun.php` sekarang simpan via `akun-update.php` (ganti sandi opsional);
  tombol Hapus user yang mati di admin dihapus; typo `case 'user-riwayat';` diperbaiki;
  input `datetime-local` untuk kolom `date` diganti `date`.
- **Duplikasi view**: `diagnosa-hasil.php` dan `riwayat-lihat.php` memakai partial
  bersama `views/_hasil.php`.

## Verifikasi

- `php -l` lolos untuk 33 file PHP.
- Import skema bersih ke `gigi_mulut_test`: 38 gejala, 5 penyakit, 69 relasi, admin hash `$2y$12$…`.
- Simulasi diagnosa gejala `[6, 11]`: distribusi kecocokan terhitung benar
  (mis. penyakit 1: 2/3 = 66,7%).
- Register → password tersimpan sebagai bcrypt (60 char), login `password_verify` OK.

## Gaya kode (PER Coding Style)

Kode PHP mengikuti [PER Coding Style](https://www.php-fig.org/per/coding-style/):

- Tag hanya `<?php` / `<?=`, keyword lowercase, `elseif` satu kata.
- Spasi setelah keyword kontrol (`if (`, `while (`, `catch (`), brace `{` sejajar,
  `} else {` / `} elseif {` / `} catch {` sebaris.
- Fungsi: brace di baris sendiri, return type dideklarasikan (`: void`, `: string`).
- File PHP murni tanpa closing tag `?>`; tanpa trailing whitespace; 1 newline di EOF.
- Statement SQL panjang dipecah via konkatenasi multi-baris; batas lunak 120 kolom
  diterapkan pada kode PHP (baris template HTML/CDN dikecualikan — batas ini
  memang lunak/warning menurut PER).
- Sintaks kompatibel PHP 7.x (polyfill `str_starts_with()` di `config/helpers.php`).

Pengecualian yang disengaja:

- Tanpa `declare(strict_types=1)` — kode legacy mysqli mencampur string/int
  (mis. `bind_param`), strict mode berisiko `TypeError`. Ditinjau ulang bila
  lapisan DB dimigrasi ke PDO dengan casting eksplisit.
- File template campuran HTML+PHP memakai sintaks alternatif (`while: … endwhile;`)
  yang diizinkan PER untuk template.

## Batasan / TODO

- Rumus masih proporsi sederhana, belum bobot/CF; hasil seri (mis. tiga penyakit 66,7%)
  diambil salah satu — pertimbangkan skor lanjutan atau tampilkan kandidat teratas.
- Master data bawaan belum konsisten (id penyakit/gejala yatim) — rapikan via admin,
  lalu aktifkan FK `penyakit_gejala`.
- Belum ada rate-limit login, reset password, audit log, dan pagination server-side.
- Dependensi CDN (Bootstrap 4.4.1, jQuery, DataTables 1.10.20) sudah tua —
  upgrade terjadwal; CKEditor 4 yang EOL sudah dibuang dari form.
