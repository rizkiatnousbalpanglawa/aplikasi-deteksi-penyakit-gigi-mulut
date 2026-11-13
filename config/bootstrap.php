<?php
// Bootstrap aplikasi: dimuat pertama oleh setiap entry point.
// Menyiapkan session aman, helper, koneksi DB, dan error handling terpusat.

require_once __DIR__ . '/helpers.php';

start_secure_session();

require_once __DIR__ . '/koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

set_exception_handler(function ($e) {
    error_log('Uncaught: ' . $e->getMessage());
    http_response_code(500);
    die('Terjadi kesalahan. Coba lagi nanti.');
});
