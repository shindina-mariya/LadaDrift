<?php
/**
 * Админ-панель LADA Drift - Управление бронированиями
 */

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../php/config/database.php';
require_once __DIR__ . '/../php/models/Booking.php';

// Обработка действий
$message = '';
$messageType = '';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $booking = Booking::getById($id);
    
    if ($booking) {
        switch ($_GET['action']) {
            case 'confirm':
                $booking->confirm();
                $message = "Бронирование #{$id} подтверждено";
                $messageType = 'success';
                break;
                
            case 'cancel':
                $booking->cancel();
                $message = "Бронирование #{$id} отменено";
                $messageType = 'warning';
                break;
                
            case 'complete':
                $booking->complete();
                $message = "Бронирование #{$id} завершено";
                $messageType = 'success';
                break;
        }
    }
}

// Экспорт в Excel (CSV)
if (isset($_GET['action']) && $_GET['action'] === 'export') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=bookings_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    
    // BOM для Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Заголовки
    fputcsv($output, ['ID', 'Клиент', 'Телефон', 'Email', 'Услуга', 'Дата', 'Время', 'Сумма', 'Статус', 'Создано'], ';');
    
    // Данные
    $bookings = Booking::getFiltered([], 1000, 0);
    foreach ($bookings as $b) {
        fputcsv($output, [
            $b->getId(),
            $b->getClientName(),
            $b->getPhone(),
            $b->getEmail() ?? '-',
            $b->getServiceTypeLabel(),
            $b->getBookingDate(),
            $b->getTimeSlot(),
            $b->getTotalPrice(),
            $b->getStatusLabel(),
            $b->toArray()['created_at'] ?? ''
        ], ';');
    }
    
    fclose($output);
    exit;
}

// Фильтры
$filters = [];
if (!empty($_GET['status'])) $filters['status'] = $_GET['status'];
if (!empty($_GET['date_from'])) $filters['date_from'] = $_GET['date_from'];
if (!empty($_GET['date_to'])) $filters['date_to'] = $_GET['date_to'];
if (!empty($_GET['service_type'])) $filters['service_type'] = $_GET['service_type'];

// Получаем бронирования
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$bookings = Booking::getFiltered($filters, $perPage, $offset);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление бронированиями | LADA Drift</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --color-bg: #0a0a0a;
            --color-sidebar: #111;
            --color-card: #1a1a1a;
            --color-accent: #E30613;
            --color-success: #28A745;
            --color-warning: #FFC107;
            --color-text: #ffffff;
            --color-text-muted: #888;
            --border-radius: 8px;
            --sidebar-width: 260px;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--color-sidebar);
            border-right: 1px solid #222;
            position: fixed;
            height: 100vh;
        }
        
        .sidebar__logo {
            padding: 1.5rem;
            border-bottom: 1px solid #222;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .sidebar__logo svg { width: 40px; height: 40px; }
        
        /* .sidebar__logo-text {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }
        
        .sidebar__logo-text span {
            color: var(--color-accent);
            display: block;
            font-size: 0.75rem;
        } */
        
        .sidebar__nav { padding: 1rem 0; }
        
        .sidebar__nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            color: var(--color-text-muted);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .sidebar__nav-item:hover, .sidebar__nav-item.active {
            background-color: rgba(227, 6, 19, 0.1);
            color: var(--color-text);
            border-left-color: var(--color-accent);
        }
        
        .main {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }
        
        .main__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .main__title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        /* Filters */
        .filters {
            background-color: var(--color-card);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .filter-group label {
            font-size: 0.75rem;
            color: var(--color-text-muted);
            text-transform: uppercase;
        }
        
        .filter-group select,
        .filter-group input {
            padding: 0.5rem 0.75rem;
            background-color: var(--color-bg);
            border: 1px solid #333;
            border-radius: var(--border-radius);
            color: var(--color-text);
            font-size: 0.875rem;
        }
        
        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--color-accent);
        }
        
        /* Message */
        .message {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
        }
        
        .message--success {
            background-color: rgba(40, 167, 69, 0.2);
            border: 1px solid var(--color-success);
            color: var(--color-success);
        }
        
        .message--warning {
            background-color: rgba(255, 193, 7, 0.2);
            border: 1px solid var(--color-warning);
            color: var(--color-warning);
        }
        
        /* Table */
        .table-card {
            background-color: var(--color-card);
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            padding: 1rem;
            text-align: left;
        }
        
        .table th {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--color-text-muted);
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
        }
        
        .table tr {
            border-bottom: 1px solid #222;
        }
        
        .table tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge--pending { background-color: rgba(255, 193, 7, 0.2); color: var(--color-warning); }
        .badge--confirmed { background-color: rgba(40, 167, 69, 0.2); color: var(--color-success); }
        .badge--cancelled { background-color: rgba(220, 53, 69, 0.2); color: #DC3545; }
        .badge--completed { background-color: rgba(23, 162, 184, 0.2); color: #17A2B8; }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .btn--primary { background-color: var(--color-accent); color: white; }
        .btn--primary:hover { background-color: #c00510; }
        
        .btn--outline {
            background: transparent;
            border: 1px solid #444;
            color: var(--color-text);
        }
        
        .btn--outline:hover { border-color: var(--color-accent); color: var(--color-accent); }
        
        .btn--sm { padding: 0.375rem 0.75rem; font-size: 0.75rem; }
        
        .actions { display: flex; gap: 0.5rem; }
        
        .action-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.05);
            border: none;
            border-radius: var(--border-radius);
            color: var(--color-text-muted);
            cursor: pointer;
            text-decoration: none;
        }
        
        .action-btn:hover { background-color: var(--color-accent); color: white; }
        .action-btn--success:hover { background-color: var(--color-success); }
        .action-btn--danger:hover { background-color: #DC3545; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar__logo">
            <a href="dashboard.php"><img src="../assets/images/logo/logo.svg" alt="LADA Drift"></a>
        </div>
            
        
        <nav class="sidebar__nav">
            <a href="dashboard.php" class="sidebar__nav-item">
                <i class="fas fa-home"></i> Главная
            </a>
            <a href="manage_bookings.php" class="sidebar__nav-item active">
                <i class="fas fa-calendar-alt"></i> Бронирования
            </a>
            <a href="manage_cars.php" class="sidebar__nav-item">
                <i class="fas fa-car"></i> Автомобили
            </a>
            <a href="manage_users.php" class="sidebar__nav-item">
                <i class="fas fa-users"></i> Пользователи
            </a>
            <a href="logout.php" class="sidebar__nav-item">
                <i class="fas fa-sign-out-alt"></i> Выход
            </a>
        </nav>
    </aside>
    
    <!-- Main -->
    <main class="main">
        <div class="main__header">
            <h1 class="main__title">Управление бронированиями</h1>
            <a href="?action=export" class="btn btn--primary">
                <i class="fas fa-download"></i> Экспорт в Excel
            </a>
        </div>
        
        <?php if ($message): ?>
        <div class="message message--<?= $messageType ?>">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
        </div>
        <?php endif; ?>
        
        <!-- Filters -->
        <form class="filters" method="GET">
            <div class="filter-group">
                <label>Статус</label>
                <select name="status">
                    <option value="">Все</option>
                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Ожидает</option>
                    <option value="confirmed" <?= ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Подтверждено</option>
                    <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Завершено</option>
                    <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Отменено</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Дата от</label>
                <input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>">
            </div>
            
            <div class="filter-group">
                <label>Дата до</label>
                <input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>">
            </div>
            
            <div class="filter-group">
                <label>Услуга</label>
                <select name="service_type">
                    <option value="">Все</option>
                    <option value="car_rental" <?= ($filters['service_type'] ?? '') === 'car_rental' ? 'selected' : '' ?>>Аренда авто</option>
                    <option value="track_rental" <?= ($filters['service_type'] ?? '') === 'track_rental' ? 'selected' : '' ?>>Аренда трека</option>
                    <option value="training" <?= ($filters['service_type'] ?? '') === 'training' ? 'selected' : '' ?>>Обучение</option>
                    <option value="certificate" <?= ($filters['service_type'] ?? '') === 'certificate' ? 'selected' : '' ?>>Сертификат</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn--outline">
                <i class="fas fa-filter"></i> Применить
            </button>
            
            <a href="manage_bookings.php" class="btn btn--outline">Сбросить</a>
        </form>
        
        <!-- Table -->
        <div class="table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Клиент</th>
                        <th>Услуга</th>
                        <th>Дата / Время</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--color-text-muted); padding: 2rem;">
                            Бронирования не найдены
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td>#<?= str_pad($booking->getId(), 4, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <div><?= htmlspecialchars($booking->getClientName()) ?></div>
                            <div style="font-size: 0.75rem; color: var(--color-text-muted);">
                                <?= htmlspecialchars($booking->getPhone()) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($booking->getServiceTypeLabel()) ?></td>
                        <td>
                            <div><?= htmlspecialchars($booking->getFormattedDate()) ?></div>
                            <div style="font-size: 0.75rem; color: var(--color-text-muted);">
                                <?= htmlspecialchars($booking->getTimeSlot()) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($booking->getFormattedPrice()) ?></td>
                        <td>
                            <span class="badge badge--<?= $booking->getStatus() ?>">
                                <?= htmlspecialchars($booking->getStatusLabel()) ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <?php if ($booking->getStatus() === 'pending'): ?>
                                <a href="?action=confirm&id=<?= $booking->getId() ?>" 
                                   class="action-btn action-btn--success" 
                                   title="Подтвердить"
                                   onclick="return confirm('Подтвердить бронирование?')">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="?action=cancel&id=<?= $booking->getId() ?>" 
                                   class="action-btn action-btn--danger" 
                                   title="Отменить"
                                   onclick="return confirm('Отменить бронирование?')">
                                    <i class="fas fa-times"></i>
                                </a>
                                <?php elseif ($booking->getStatus() === 'confirmed'): ?>
                                <a href="?action=complete&id=<?= $booking->getId() ?>" 
                                   class="action-btn action-btn--success" 
                                   title="Завершить"
                                   onclick="return confirm('Отметить как завершённое?')">
                                    <i class="fas fa-flag-checkered"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
