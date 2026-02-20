<?php
/**
 * Админ-панель LADA Drift - Выход из системы
 */

session_start();

// Уничтожаем сессию
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Редирект на страницу входа
header('Location: login.php');
exit;
