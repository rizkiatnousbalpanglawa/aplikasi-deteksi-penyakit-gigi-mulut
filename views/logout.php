<?php
require_once __DIR__ . '/../config/helpers.php';
start_secure_session();
$_SESSION = [];
session_destroy();

echo "<script>location='login.php'</script>";
exit;
