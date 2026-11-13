<?php
require_once __DIR__ . '/../config/bootstrap.php';
audit($koneksi, 'logout_admin');
session_destroy();
$_SESSION = [];

redirect('../login/index.php');
