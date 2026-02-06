<?php
require_once __DIR__ . '/../app/bootstrap.php';
$auth = new Auth();
$auth->logout();
header('Location: /ksp_mono/login.php');
exit;
