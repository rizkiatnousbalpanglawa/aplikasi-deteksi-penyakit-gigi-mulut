<?php
require_once __DIR__ . '/../config/bootstrap.php';
session_destroy();
$_SESSION = [];

redirect('../login/index.php');
