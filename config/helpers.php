<?php
// Helper keamanan bersama: session, escaping, CSRF, guard auth.

// Polyfill untuk PHP 7.x (str_starts_with baru ada di PHP 8.0).
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle)
    {
        return $needle === '' || strpos((string)$haystack, (string)$needle) === 0;
    }
}

if (!function_exists('start_secure_session')) {
    function start_secure_session(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax',
                'secure' => $secure,
            ]);
            session_start();
        }
    }
}

if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        start_secure_session();
        if (empty($_SESSION['_csrf']) || !is_string($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('csrf_check')) {
    function csrf_check(): void
    {
        start_secure_session();
        $sent = $_POST['_csrf'] ?? '';
        $expected = $_SESSION['_csrf'] ?? '';
        if (!is_string($sent) || !is_string($expected) || $sent === '' || !hash_equals($expected, $sent)) {
            http_response_code(403);
            die('Token keamanan tidak valid. Muat ulang halaman.');
        }
    }
}

if (!function_exists('require_admin')) {
    function require_admin(): void
    {
        start_secure_session();
        if (empty($_SESSION['admin']['user'])) {
            header('Location: ../login/');
            exit;
        }
    }
}

if (!function_exists('require_user')) {
    function require_user()
    {
        start_secure_session();
        if (empty($_SESSION['id'])) {
            header('Location: login.php');
            exit;
        }
    }
}

if (!function_exists('flash')) {
    function flash(string $message, string $type = 'success'): void
    {
        start_secure_session();
        $_SESSION['_flash'] = ['message' => (string) $message, 'type' => (string) $type];
    }
}

if (!function_exists('flash_html')) {
    function flash_html(): string
    {
        start_secure_session();
        $flash = isset($_SESSION['_flash']) ? $_SESSION['_flash'] : null;
        unset($_SESSION['_flash']);
        if (!is_array($flash) || empty($flash['message'])) {
            return '';
        }
        $allowed = ['success', 'danger', 'warning', 'info'];
        $type = in_array(isset($flash['type']) ? $flash['type'] : '', $allowed, true)
            ? $flash['type']
            : 'info';
        return '<div class="alert alert-' . e($type) . ' alert-dismissible fade show" role="alert">'
            . e($flash['message'])
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button></div>';
    }
}

if (!function_exists('rate_kunci')) {
    function rate_kunci(string $nama): string
    {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : 'cli';
        return 'login:' . mb_strtolower(trim($nama)) . '|' . $ip;
    }
}

if (!function_exists('rate_izinkan')) {
    // Rate-limit login: maksimal 5x gagal per 10 menit per kunci.
    // Fail-open (true) bila tabel login_upaya belum ada (DB lama).
    function rate_izinkan($koneksi, string $kunci): bool
    {
        $batas = date('Y-m-d H:i:s', time() - 600);
        $stmt = $koneksi->prepare(
            'SELECT COUNT(*) AS c FROM login_upaya WHERE kunci = ? AND berhasil = 0 AND waktu > ?'
        );
        if (!$stmt) {
            return true;
        }
        $stmt->bind_param('ss', $kunci, $batas);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return ((int)($row['c'] ?? 0)) < 5;
    }
}

if (!function_exists('rate_catat')) {
    function rate_catat($koneksi, string $kunci, bool $ok): void
    {
        if ($ok) {
            $del = $koneksi->prepare('DELETE FROM login_upaya WHERE kunci = ?');
            if ($del) {
                $del->bind_param('s', $kunci);
                $del->execute();
                $del->close();
            }
            return;
        }
        $ins = $koneksi->prepare('INSERT INTO login_upaya (kunci, berhasil) VALUES (?, 0)');
        if ($ins) {
            $ins->bind_param('s', $kunci);
            $ins->execute();
            $ins->close();
        }
        $koneksi->query("DELETE FROM login_upaya WHERE waktu < DATE_SUB(NOW(), INTERVAL 1 DAY)");
    }
}

if (!function_exists('audit')) {
    // Catat aktivitas ke audit_log. Aktor dibaca dari session (admin/user).
    // Diam bila tabel belum ada (DB lama tanpa migrasi 005).
    function audit($koneksi, string $aksi, string $detail = ''): void
    {
        $aktor = '-';
        $peran = '-';
        if (!empty($_SESSION['admin']['user'])) {
            $aktor = (string) $_SESSION['admin']['user'];
            $peran = 'admin';
        } elseif (!empty($_SESSION['nama'])) {
            $aktor = (string) $_SESSION['nama'];
            $peran = 'user';
        }
        $ip = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : null;
        $stmt = $koneksi->prepare(
            'INSERT INTO audit_log (aktor, peran, aksi, detail, ip) VALUES (?, ?, ?, ?, ?)'
        );
        if (!$stmt) {
            return;
        }
        $stmt->bind_param('sssss', $aktor, $peran, $aksi, $detail, $ip);
        $stmt->execute();
        $stmt->close();
    }
}

if (!function_exists('redirect')) {
    // Redirect via header. Hanya untuk handler yang berjalan SEBELUM output dimulai.
    function redirect(string $url, $message = null, string $type = 'success'): void
    {
        if ($message !== null) {
            flash($message, $type);
        }
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('redirect_js')) {
    // Redirect via JS untuk handler fragment yang berjalan SETELAH output dimulai
    // (mis. form di admin/gejala.php). header() sudah tidak bisa dipakai di sana.
    function redirect_js(string $url, $message = null, string $type = 'success'): void
    {
        if ($message !== null) {
            flash($message, $type);
        }
        if (!preg_match('~^[a-zA-Z0-9_./?=&%#-]*$~', (string) $url)) {
            $url = 'index.php';
        }
        echo '<script>location=' . json_encode((string) $url) . ';</script>';
        exit;
    }
}
