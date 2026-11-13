<?php
// Koneksi database terpusat.
// Kredensial dibaca dari environment agar tidak hardcode di repo.
// Contoh: DB_HOST=localhost DB_USER=usbal DB_PASS=secret DB_NAME=gigi_mulut

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_USER = getenv('DB_USER') ?: 'usbal'; // default mengikuti kredensial dev lama; override via env di produksi
$DB_PASS = getenv('DB_PASS') ?: '';
$DB_NAME = getenv('DB_NAME') ?: 'gigi_mulut';

mysqli_report(MYSQLI_REPORT_OFF);

try {
    $koneksi = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
} catch (mysqli_sql_exception $e) {
    error_log('DB connect failed: ' . $e->getMessage());
    http_response_code(500);
    die('Koneksi database gagal. Coba lagi nanti.');
}

if ($koneksi->connect_error) {
    error_log('DB connect error: ' . $koneksi->connect_error);
    http_response_code(500);
    die('Koneksi database gagal. Coba lagi nanti.');
}

$koneksi->set_charset('utf8mb4');
