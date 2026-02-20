<?php
/**
 * Админ-панель LADA Drift - Главная страница (Dashboard)
 */

session_start();

// Проверка авторизации
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../php/config/database.php';
require_once __DIR__ . '/../php/models/Booking.php';

// Получаем статистику
try {
    // Всего бронирований
    $totalBookings = Database::query("SELECT COUNT(*) as count FROM bookings")->fetch()['count'];
    
    // Бронирований сегодня
    $todayBookings = Database::execute(
        "SELECT COUNT(*) as count FROM bookings WHERE DATE(created_at) = CURDATE()",
        []
    )->fetch()['count'];
    
    // Ожидающих подтверждения
    $pendingBookings = Database::execute(
        "SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'",
        []
    )->fetch()['count'];
    
    // Доход за месяц
    $monthlyRevenue = Database::execute(
        "SELECT COALESCE(SUM(total_price), 0) as revenue FROM bookings 
         WHERE status IN ('confirmed', 'completed') 
         AND MONTH(created_at) = MONTH(CURDATE()) 
         AND YEAR(created_at) = YEAR(CURDATE())",
        []
    )->fetch()['revenue'];
    
    // Последние 10 бронирований
    $recentBookings = Booking::getFiltered([], 10, 0);
    
} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
    $totalBookings = $todayBookings = $pendingBookings = $monthlyRevenue = 0;
    $recentBookings = [];
}

// Форматирование денег
function formatMoney($amount) {
    return number_format($amount, 0, ',', ' ') . ' ₽';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Панель управления | LADA Drift</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --color-bg: #0a0a0a;
            --color-sidebar: #111;
            --color-card: #1a1a1a;
            --color-accent: #E30613;
            --color-cta: #FF9500;
            --color-success: #28A745;
            --color-warning: #FFC107;
            --color-text: #ffffff;
            --color-text-muted: #888;
            --border-radius: 8px;
            --sidebar-width: 260px;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--color-sidebar);
            border-right: 1px solid #222;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }
        
        .sidebar__logo {
            padding: 1.5rem;
            border-bottom: 1px solid #222;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .sidebar__logo svg {
            width: 40px;
            height: 40px;
        }
        
        /* .sidebar__logo-text {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            line-height: 1.2;
        }
        
        .sidebar__logo-text span {
            color: var(--color-accent);
            display: block;
            font-size: 0.75rem;
        } */
        
        .sidebar__nav {
            flex: 1;
            padding: 1rem 0;
        }
        
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
        
        .sidebar__nav-item:hover,
        .sidebar__nav-item.active {
            background-color: rgba(227, 6, 19, 0.1);
            color: var(--color-text);
            border-left-color: var(--color-accent);
        }
        
        .sidebar__nav-item i {
            width: 20px;
            text-align: center;
        }
        
        .sidebar__footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #222;
        }
        
        .sidebar__user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }
        
        .sidebar__avatar {
            width: 40px;
            height: 40px;
            background-color: var(--color-accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .sidebar__user-info {
            font-size: 0.875rem;
        }
        
        .sidebar__user-name {
            font-weight: 500;
        }
        
        .sidebar__user-role {
            color: var(--color-text-muted);
            font-size: 0.75rem;
        }
        
        .sidebar__logout {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }
        
        .sidebar__logout:hover {
            color: var(--color-accent);
        }
        
        /* Main Content */
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
        
        .main__title span {
            color: var(--color-accent);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background-color: var(--color-card);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-card__icon {
            width: 50px;
            height: 50px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        
        .stat-card__icon--primary { background-color: rgba(227, 6, 19, 0.2); color: var(--color-accent); }
        .stat-card__icon--success { background-color: rgba(40, 167, 69, 0.2); color: var(--color-success); }
        .stat-card__icon--warning { background-color: rgba(255, 193, 7, 0.2); color: var(--color-warning); }
        .stat-card__icon--cta { background-color: rgba(255, 149, 0, 0.2); color: var(--color-cta); }
        
        .stat-card__value {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
        }
        
        .stat-card__label {
            color: var(--color-text-muted);
            font-size: 0.875rem;
        }
        
        /* Table */
        .table-card {
            background-color: var(--color-card);
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .table-card__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #333;
        }
        
        .table-card__title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 1rem 1.5rem;
            text-align: left;
        }
        
        .table th {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--color-text-muted);
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .table tr {
            border-bottom: 1px solid #222;
        }
        
        .table tr:last-child {
            border-bottom: none;
        }
        
        .table tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }
        
        /* Status Badge */
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
        
        /* Button */
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
        
        .btn--primary {
            background-color: var(--color-accent);
            color: white;
        }
        
        .btn--primary:hover {
            background-color: #c00510;
        }
        
        .btn--outline {
            background-color: transparent;
            border: 1px solid #444;
            color: var(--color-text);
        }
        
        .btn--outline:hover {
            border-color: var(--color-accent);
            color: var(--color-accent);
        }
        
        .btn--sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        /* Action buttons */
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
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
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            background-color: var(--color-accent);
            color: white;
        }
        
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main {
                margin-left: 0;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .table-card {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar__logo">
            <a href="dashboard.php"><img src="../assets/images/logo/logo.svg" alt="LADA Drift"></a>
        </div>
        
        <nav class="sidebar__nav">
            <a href="dashboard.php" class="sidebar__nav-item active">
                <i class="fas fa-home"></i> Главная
            </a>
            <a href="manage_bookings.php" class="sidebar__nav-item">
                <i class="fas fa-calendar-alt"></i> Бронирования
            </a>
            <a href="manage_cars.php" class="sidebar__nav-item">
                <i class="fas fa-car"></i> Автомобили
            </a>
            <a href="manage_users.php" class="sidebar__nav-item">
                <i class="fas fa-users"></i> Пользователи
            </a>
            <a href="manage_reviews.php" class="sidebar__nav-item">
                <i class="fas fa-star"></i> Отзывы
            </a>
            <a href="settings.php" class="sidebar__nav-item">
                <i class="fas fa-cog"></i> Настройки
            </a>
        </nav>
        
        <div class="sidebar__footer">
            <div class="sidebar__user">
                <div class="sidebar__avatar">
                    <?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="sidebar__user-info">
                    <div class="sidebar__user-name"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Администратор') ?></div>
                    <div class="sidebar__user-role"><?= htmlspecialchars($_SESSION['admin_role'] ?? 'admin') ?></div>
                </div>
            </div>
            <a href="logout.php" class="sidebar__logout">
                <i class="fas fa-sign-out-alt"></i> Выйти
            </a>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main">
        <div class="main__header">
            <h1 class="main__title">
                Добро пожаловать, <span><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Администратор') ?></span>
            </h1>
            <a href="manage_bookings.php?action=export" class="btn btn--outline">
                <i class="fas fa-download"></i> Экспорт в Excel
            </a>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card__icon stat-card__icon--primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <div class="stat-card__value"><?= $totalBookings ?></div>
                    <div class="stat-card__label">Всего бронирований</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card__icon stat-card__icon--success">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="stat-card__value"><?= $todayBookings ?></div>
                    <div class="stat-card__label">Сегодня</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card__icon stat-card__icon--warning">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div>
                    <div class="stat-card__value"><?= $pendingBookings ?></div>
                    <div class="stat-card__label">Ожидают подтверждения</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card__icon stat-card__icon--cta">
                    <i class="fas fa-ruble-sign"></i>
                </div>
                <div>
                    <div class="stat-card__value"><?= formatMoney($monthlyRevenue) ?></div>
                    <div class="stat-card__label">Доход за месяц</div>
                </div>
            </div>
        </div>
        
        <!-- Recent Bookings -->
        <div class="table-card">
            <div class="table-card__header">
                <h2 class="table-card__title">Последние бронирования</h2>
                <a href="manage_bookings.php" class="btn btn--sm btn--outline">
                    Все бронирования <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Клиент</th>
                        <th>Услуга</th>
                        <th>Дата</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentBookings)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--color-text-muted);">
                            Бронирований пока нет
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($recentBookings as $booking): ?>
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
                                <a href="booking_details.php?id=<?= $booking->getId() ?>" class="action-btn" title="Просмотр">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($booking->getStatus() === 'pending'): ?>
                                <button class="action-btn" title="Подтвердить" onclick="confirmBooking(<?= $booking->getId() ?>)">
                                    <i class="fas fa-check"></i>
                                </button>
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
    
    <script>
        function confirmBooking(id) {
            if (confirm('Подтвердить бронирование #' + String(id).padStart(4, '0') + '?')) {
                window.location.href = 'manage_bookings.php?action=confirm&id=' + id;
            }
        }
    </script>
</body>
</html>
