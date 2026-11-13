<?php
require_once __DIR__ . '/../config/helpers.php';
start_secure_session();
session_destroy();
$_SESSION = [];

header("Location: ../login/index.php");
exit;
