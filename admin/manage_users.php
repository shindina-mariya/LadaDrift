<?php
/**
 * Админ-панель LADA Drift - Список пользователей
 */

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../php/config/database.php';
require_once __DIR__ . '/../php/models/User.php';

$users = [];
try {
    $stmt = Database::query("SELECT id, email, phone, full_name, role, is_active, created_at FROM users ORDER BY created_at DESC");
    while ($row = $stmt->fetch()) {
        $users[] = $row;
    }
} catch (PDOException $e) {
    // Таблица users может не существовать
    error_log("manage_users: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пользователи | LADA Drift</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --color-bg: #0a0a0a; --color-sidebar: #111; --color-card: #1a1a1a; --color-accent: #E30613; --color-text: #fff; --color-text-muted: #888; --border-radius: 8px; --sidebar-width: 260px; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Roboto', sans-serif; background: var(--color-bg); color: var(--color-text); display: flex; min-height: 100vh; }
        .sidebar { width: var(--sidebar-width); background: var(--color-sidebar); border-right: 1px solid #222; position: fixed; height: 100vh; }
        .sidebar__logo { padding: 1.5rem; border-bottom: 1px solid #222; display: flex; align-items: center; gap: 0.75rem; }
        .sidebar__logo svg { width: 40px; height: 40px; }
        .sidebar__logo-text { font-family: 'Montserrat', sans-serif; font-weight: 700; }
        .sidebar__logo-text span { color: var(--color-accent); display: block; font-size: 0.75rem; }
        .sidebar__nav { padding: 1rem 0; }
        .sidebar__nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1.5rem; color: var(--color-text-muted); text-decoration: none; transition: all 0.2s; border-left: 3px solid transparent; }
        .sidebar__nav-item:hover, .sidebar__nav-item.active { background: rgba(227,6,19,0.1); color: var(--color-text); border-left-color: var(--color-accent); }
        .main { flex: 1; margin-left: var(--sidebar-width); padding: 2rem; }
        .main__title { font-family: 'Montserrat', sans-serif; font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem; }
        .table-card { background: var(--color-card); border-radius: var(--border-radius); overflow: hidden; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 1rem; text-align: left; }
        .table th { background: rgba(255,255,255,0.05); color: var(--color-text-muted); font-weight: 500; font-size: 0.75rem; text-transform: uppercase; }
        .table tr { border-bottom: 1px solid #222; }
        .empty { text-align: center; padding: 3rem; color: var(--color-text-muted); }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar__logo">
            <a href="dashboard.php"><img src="../assets/images/logo/logo.svg" alt="LADA Drift"></a>
        </div>
        <nav class="sidebar__nav">
            <a href="dashboard.php" class="sidebar__nav-item"><i class="fas fa-home"></i> Главная</a>
            <a href="manage_bookings.php" class="sidebar__nav-item"><i class="fas fa-calendar-alt"></i> Бронирования</a>
            <a href="manage_cars.php" class="sidebar__nav-item"><i class="fas fa-car"></i> Автомобили</a>
            <a href="manage_users.php" class="sidebar__nav-item active"><i class="fas fa-users"></i> Пользователи</a>
            <a href="logout.php" class="sidebar__nav-item"><i class="fas fa-sign-out-alt"></i> Выход</a>
        </nav>
    </aside>
    <main class="main">
        <h1 class="main__title">Пользователи</h1>
        <div class="table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>ФИО</th>
                        <th>Роль</th>
                        <th>Регистрация</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="empty">Пользователи не найдены. Выполните миграцию database/migrations/001_add_users.sql</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td>#<?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['phone']) ?></td>
                        <td><?= htmlspecialchars($u['full_name']) ?></td>
                        <td><?= htmlspecialchars($u['role']) ?></td>
                        <td><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
