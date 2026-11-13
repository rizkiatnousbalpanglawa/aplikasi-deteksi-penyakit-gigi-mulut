<?php
require_once __DIR__ . '/../config/bootstrap.php';
audit($koneksi, 'logout_user');
$_SESSION = [];
session_destroy();

redirect('login.php');
