<?php
session_start();
require_once __DIR__ . '/php/includes/auth.php';
clearUserSession();
header('Location: index.html');
exit;
