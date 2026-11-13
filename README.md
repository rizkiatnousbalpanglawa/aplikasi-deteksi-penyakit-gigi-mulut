# Aplikasi Deteksi Penyakit Gigi & Mulut

Aplikasi diagnosa berbasis gejala (PHP native + MySQL/MariaDB + Bootstrap 4)
dengan metode **Certainty Factor (CF)**. User menjawab Ya/Tidak untuk tiap gejala,
sistem menggabung bobot pakar (MB/MD) tiap relasi penyakit–gejala lalu menyimpan
hasil pemeriksaan.

## Fitur

- **User**: register/login, konsultasi diagnosa, riwayat hasil, edit akun.
- **Admin**: login terpisah, kelola jenis penyakit, relasi penyakit–gejala, kelola gejala,
  lihat daftar user + riwayat diagnosanya.

## Struktur

```
admin/    Panel admin (guard session admin + CSRF + prepared statements)
assets/   CSS/JS lokal (SB Admin, Font Awesome)
config/   bootstrap.php (entry-point tunggal), koneksi.php (env-based),
          helpers.php (session/CSRF/escaping/guard/flash/redirect)
database/ gigi_mulut.sql (skema bersih + seed master, tanpa PII)
login/    Login admin
views/    Aplikasi user (diagnosa, riwayat, akun) + views/layout/ (header/footer)
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

## Metode Certainty Factor

Tiap relasi penyakit–gejala punya bobot pakar **MB** (measure of belief) dan
**MD** (measure of disbelief), rentang 0–1, dikelola di admin
(Jenis Penyakit > Detail). Nilai CF per relasi = `MB − MD`.

Jawaban Ya digabung sekuensial per penyakit (`lib/cf.php`):

- Keduanya positif: `CF = CF1 + CF2 × (1 − CF1)`
- Keduanya negatif: `CF = CF1 + CF2 × (1 + CF1)`
- Beda tanda: `CF = (CF1 + CF2) / (1 − min(|CF1|, |CF2|))`

Skor akhir = `CF × 100%`, pemenang = nilai tertinggi (seri → jumlah gejala
terbanyak → id terkecil). Hanya penyakit terdaftar di master yang dihitung.

Contoh hitungan tangan (bobot contoh MB 0,8 / MD 0,2 → CF 0,6 per gejala):
gejala [Gigi Berlubang, Gigi Ngilu, nyeri panas/dingin] pada Erosi Gigi:
`0,6 → 0,6+0,6×0,4=0,84 → 0,84+0,6×0,16=0,936` = **93,6%**.

> Bobot bawaan (`0,80/0,20`) adalah **contoh** agar aplikasi langsung jalan.
> Ganti dengan nilai hasil konsultasi pakar sebelum dipakai produksi.
> Riwayat lama (era metode proporsi) tetap terbaca; skornya tidak sebanding
> langsung dengan skor CF.

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
- **Kandidat & pemenang deterministik**: kecocokan hanya menghitung penyakit yang
  terdaftar di master (`JOIN jenis_penyakit`), pemenang diambil
  `ORDER BY presentase DESC, jumlah DESC, id ASC LIMIT 1` (dulu `MAX()` tanpa
  grup yang hasilnya tak-menentu), dan kegagalan `INSERT` hasil kini melempar
  error eksplisit + rollback, bukan redirect diam-diam ke `id=0`.
- **Migrasi ke CF**: `database/migrations/002_cf_bobot.sql` menambah `mb`/`md`
  (jalankan sekali di DB lama; skema baru di `gigi_mulut.sql` sudah memuatnya).
  Perhitungan di `views/diagnosa-pertanyaan-hasil.php` memakai fungsi murni
  `lib/cf.php`; admin kelola bobot di Jenis Penyakit > Detail (kolom MB/MD/CF,
  tambah relasi wajib isi bobot).

## Verifikasi

- `php -l` lolos untuk 33 file PHP.
- Import skema bersih ke `gigi_mulut_test`: 38 gejala, 5 penyakit, 69 relasi, admin hash `$2y$12$…`.
- Simulasi diagnosa gejala `[6, 11]`: distribusi kecocokan terhitung benar
  (mis. penyakit 1: 2/3 = 66,7%).
- Register → password tersimpan sebagai bcrypt (60 char), login `password_verify` OK.

## Arsitektur (Fase 1 maintainability)

- **Satu bootstrap**: setiap entry point cukup `require config/bootstrap.php`
  (session aman + helper + koneksi + error handler). Jangan require file config
  satu per satu lagi.
- **Layout user**: `views/layout/header.php` + `footer.php` (`$title`, `$bodyAttrs`
  opsional). Halaman hanya isi konten + `include` layout. Halaman admin memakai
  `admin/index.php` sebagai layoutnya; form login admin (`login/`) temanya sendiri.
- **Flash + redirect**: handler POST memakai `redirect($url, $pesan, $tipe)` bila
  berjalan sebelum output, atau `redirect_js(...)` bila di dalam fragment yang
  sudah terlanjur output (form admin). Pesan tampil sekali via `flash_html()`
  yang sudah dipasang di layout. Jangan tambah `alert()` JS baru.

## Gaya kode (PSR-12 + PER Coding Style)

Kode PHP mengikuti [PSR-12](https://www.php-fig.org/psr/psr-12/) dan
[PER Coding Style](https://www.php-fig.org/per/coding-style/) (PER memperluas PSR-12):

- Tag hanya `<?php` / `<?=`, keyword lowercase, `elseif` satu kata.
- Spasi setelah keyword kontrol (`if (`, `while (`, `catch (`), brace `{` sejajar,
  `} else {` / `} elseif {` / `} catch {` sebaris.
- Fungsi: brace di baris sendiri, return type dideklarasikan (`: void`, `: string`).
- File PHP murni tanpa closing tag `?>`; tanpa trailing whitespace; 1 newline di EOF;
  tanpa tab (indentasi 4 spasi); tanpa blank line beruntun; satu statement per baris.
- Cast rapat tanpa spasi (`(int)$x`, bukan `(int) $x`); `!` tanpa spasi (`!$x`).
- `switch`: `case` indent sekali, body + `break` sejajar; template boleh memakai
  sintaks alternatif (`while: … endwhile;`).
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

- Seri skor CF diselesaikan deterministik (id terkecil) — pertimbangkan tampilkan
  kandidat teratas bila butuh transparansi diagnosis banding.
- Bobot bawaan MB 0,8/MD 0,2 adalah contoh — wajib divalidasi pakar
  (dokter gigi/dosen pembimbing) sebelum produksi.
- Master data bawaan belum konsisten (id penyakit/gejala yatim) — rapikan via admin,
  lalu aktifkan FK `penyakit_gejala`.
- Belum ada rate-limit login, reset password, audit log, dan pagination server-side.
- Dependensi CDN (Bootstrap 4.4.1, jQuery, DataTables 1.10.20) sudah tua —
  upgrade terjadwal; CKEditor 4 yang EOL sudah dibuang dari form.
