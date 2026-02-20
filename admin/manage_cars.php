<?php
/**
 * Админ-панель LADA Drift - Управление автомобилями
 */

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../php/config/database.php';
require_once __DIR__ . '/../php/models/Car.php';

$message = '';
$messageType = '';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $car = Car::getById($id);

    if ($car) {
        if ($_GET['action'] === 'toggle') {
            $car->setIsAvailable(!$car->getIsAvailable());
            $car->save();
            $message = 'Статус автомобиля обновлён';
            $messageType = 'success';
        } elseif ($_GET['action'] === 'delete') {
            $car->delete();
            $message = 'Автомобиль удалён';
            $messageType = 'warning';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_car'])) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        $car = Car::getById($id);
    } else {
        $car = new Car();
    }

    if ($car) {
        $car->setModel($_POST['model'] ?? '');
        $car->setYear((int)($_POST['year'] ?? 0));
        $car->setPower((int)($_POST['power'] ?? 0));
        $car->setEngine($_POST['engine'] ?? '');
        $car->setTransmission($_POST['transmission'] ?? '');
        $car->setDrive($_POST['drive'] ?? 'задний');
        $car->setDescription($_POST['description'] ?? null);
        $car->setImageUrl($_POST['image_url'] ?? null);
        $car->setPricePerHour((float)($_POST['price_per_hour'] ?? 0));
        $car->setPricePerSession(!empty($_POST['price_per_session']) ? (float)$_POST['price_per_session'] : null);
        $car->setIsAvailable(!empty($_POST['is_available']));
        $car->setSortOrder((int)($_POST['sort_order'] ?? 0));
        $car->save();
        $message = $id ? 'Автомобиль обновлён' : 'Автомобиль добавлен';
        $messageType = 'success';
    }
}

$cars = Car::getAll(false);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление автомобилями | LADA Drift</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --color-bg: #0a0a0a; --color-sidebar: #111; --color-card: #1a1a1a; --color-accent: #E30613; --color-success: #28A745; --color-warning: #FFC107; --color-text: #fff; --color-text-muted: #888; --border-radius: 8px; --sidebar-width: 260px; }
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
        .main__header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .main__title { font-family: 'Montserrat', sans-serif; font-size: 1.5rem; font-weight: 700; }
        .message { padding: 1rem; border-radius: var(--border-radius); margin-bottom: 1.5rem; }
        .message--success { background: rgba(40,167,69,0.2); border: 1px solid var(--color-success); color: var(--color-success); }
        .message--warning { background: rgba(255,193,7,0.2); border: 1px solid var(--color-warning); color: var(--color-warning); }
        .table-card { background: var(--color-card); border-radius: var(--border-radius); overflow: hidden; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 1rem; text-align: left; }
        .table th { background: rgba(255,255,255,0.05); color: var(--color-text-muted); font-weight: 500; font-size: 0.75rem; text-transform: uppercase; }
        .table tr { border-bottom: 1px solid #222; }
        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; }
        .badge--1 { background: rgba(40,167,69,0.2); color: var(--color-success); }
        .badge--0 { background: rgba(220,53,69,0.2); color: #DC3545; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: var(--border-radius); font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
        .btn--primary { background: var(--color-accent); color: white; }
        .btn--primary:hover { background: #c00510; }
        .btn--outline { background: transparent; border: 1px solid #444; color: var(--color-text); }
        .btn--outline:hover { border-color: var(--color-accent); color: var(--color-accent); }
        .btn--sm { padding: 0.375rem 0.75rem; font-size: 0.75rem; }
        .action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.05); border: none; border-radius: var(--border-radius); color: var(--color-text-muted); cursor: pointer; text-decoration: none; margin-right: 0.25rem; }
        .action-btn:hover { background: var(--color-accent); color: white; }
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
            <a href="manage_cars.php" class="sidebar__nav-item active"><i class="fas fa-car"></i> Автомобили</a>
            <a href="manage_users.php" class="sidebar__nav-item"><i class="fas fa-users"></i> Пользователи</a>
            <a href="logout.php" class="sidebar__nav-item"><i class="fas fa-sign-out-alt"></i> Выход</a>
        </nav>
    </aside>
    <main class="main">
        <div class="main__header">
            <h1 class="main__title">Управление автомобилями</h1>
        </div>
        <?php if ($message): ?>
        <div class="message message--<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <div class="table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Модель</th>
                        <th>Год</th>
                        <th>Мощность</th>
                        <th>Цена/час</th>
                        <th>Доступен</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $c): ?>
                    <tr>
                        <td>#<?= $c->getId() ?></td>
                        <td><?= htmlspecialchars($c->getModel()) ?></td>
                        <td><?= $c->getYear() ?></td>
                        <td><?= $c->getPower() ?> л.с.</td>
                        <td><?= number_format($c->getPricePerHour(), 0, ',', ' ') ?> ₽</td>
                        <td><span class="badge badge--<?= $c->getIsAvailable() ? 1 : 0 ?>"><?= $c->getIsAvailable() ? 'Да' : 'Нет' ?></span></td>
                        <td>
                            <a href="?action=toggle&id=<?= $c->getId() ?>" class="action-btn" title="Переключить доступность"><i class="fas fa-<?= $c->getIsAvailable() ? 'eye-slash' : 'eye' ?>"></i></a>
                            <a href="?action=delete&id=<?= $c->getId() ?>" class="action-btn" title="Удалить" onclick="return confirm('Удалить автомобиль?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
