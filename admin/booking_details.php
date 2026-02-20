<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: manage_bookings.php');
    exit;
}

require_once __DIR__ . '/../php/config/database.php';
require_once __DIR__ . '/../php/models/Booking.php';

$booking = Booking::getById($id);
if (!$booking) {
    header('Location: manage_bookings.php');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'confirm') {
            $booking->confirm();
            $message = 'Бронирование подтверждено';
        } elseif ($_POST['action'] === 'cancel') {
            $booking->cancel();
            $message = 'Бронирование отменено';
        } elseif ($_POST['action'] === 'complete') {
            $booking->complete();
            $message = 'Бронирование завершено';
        }
        $booking = Booking::getById($id);
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирование #<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?> | LADA Drift</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --color-bg:#0a0a0a; --color-card:#1a1a1a; --color-accent:#E30613; --color-text:#fff; --color-muted:#888; --radius:8px; }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:Roboto,sans-serif; background:var(--color-bg); color:var(--color-text); min-height:100vh; display:flex; }
        .sidebar { width:260px; background:#111; border-right:1px solid #222; padding:1.5rem 0; }
        .sidebar__logo { padding:0 1.5rem 1rem; border-bottom:1px solid #222; margin-bottom:1rem; }
        .sidebar__logo img { width:40px; }
        .sidebar__nav-item { display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1.5rem; color:var(--color-muted); text-decoration:none; }
        .sidebar__nav-item:hover { color:var(--color-text); }
        .main { flex:1; padding:2rem; }
        .card { background:var(--color-card); border-radius:var(--radius); padding:1.5rem; margin-bottom:1.5rem; }
        .card h2 { font-size:1.25rem; margin-bottom:1rem; }
        .detail-row { display:flex; padding:0.5rem 0; border-bottom:1px solid #222; }
        .detail-row:last-child { border-bottom:none; }
        .detail-row strong { width:180px; color:var(--color-muted); }
        .btn { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; border-radius:var(--radius); text-decoration:none; border:none; cursor:pointer; font-size:0.875rem; }
        .btn--outline { background:transparent; border:1px solid #444; color:var(--color-text); }
        .btn--primary { background:var(--color-accent); color:#fff; }
        .btn--danger { background:#DC3545; color:#fff; }
        .message { padding:1rem; border-radius:var(--radius); margin-bottom:1rem; background:rgba(40,167,69,0.2); color:#28A745; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar__logo">
            <a href="dashboard.php"><img src="../assets/images/logo/logo.svg" alt="LADA Drift"></a>
        </div>
        <a href="dashboard.php" class="sidebar__nav-item"><i class="fas fa-home"></i> Главная</a>
        <a href="manage_bookings.php" class="sidebar__nav-item"><i class="fas fa-calendar-alt"></i> Бронирования</a>
        <a href="manage_cars.php" class="sidebar__nav-item"><i class="fas fa-car"></i> Автомобили</a>
        <a href="manage_users.php" class="sidebar__nav-item"><i class="fas fa-users"></i> Пользователи</a>
        <a href="manage_reviews.php" class="sidebar__nav-item"><i class="fas fa-star"></i> Отзывы</a>
        <a href="settings.php" class="sidebar__nav-item"><i class="fas fa-cog"></i> Настройки</a>
    </aside>
    <main class="main">
        <a href="manage_bookings.php" class="btn btn--outline" style="margin-bottom:1.5rem;"><i class="fas fa-arrow-left"></i> К списку</a>
        <?php if ($message): ?><div class="message"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <div class="card">
            <h2>Бронирование #<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?></h2>
            <div class="detail-row"><strong>Клиент</strong><?= htmlspecialchars($booking->getClientName()) ?></div>
            <div class="detail-row"><strong>Телефон</strong><?= htmlspecialchars($booking->getPhone()) ?></div>
            <div class="detail-row"><strong>Email</strong><?= htmlspecialchars($booking->getEmail() ?? '—') ?></div>
            <div class="detail-row"><strong>Услуга</strong><?= htmlspecialchars($booking->getServiceTypeLabel()) ?></div>
            <div class="detail-row"><strong>Дата</strong><?= htmlspecialchars($booking->getFormattedDate()) ?></div>
            <div class="detail-row"><strong>Время</strong><?= htmlspecialchars($booking->getTimeSlot()) ?></div>
            <div class="detail-row"><strong>Сумма</strong><?= htmlspecialchars($booking->getFormattedPrice()) ?></div>
            <div class="detail-row"><strong>Статус</strong><?= htmlspecialchars($booking->getStatusLabel()) ?></div>
            <?php if ($booking->getClientComment()): ?>
            <div class="detail-row"><strong>Комментарий</strong><?= htmlspecialchars($booking->getClientComment()) ?></div>
            <?php endif; ?>
        </div>
        <?php if (in_array($booking->getStatus(), ['pending', 'confirmed'])): ?>
        <div style="display:flex; gap:0.5rem;">
            <?php if ($booking->getStatus() === 'pending'): ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="confirm">
                <button type="submit" class="btn btn--primary"><i class="fas fa-check"></i> Подтвердить</button>
            </form>
            <form method="POST" style="display:inline;" onsubmit="return confirm('Отменить бронирование?')">
                <input type="hidden" name="action" value="cancel">
                <button type="submit" class="btn btn--danger"><i class="fas fa-times"></i> Отменить</button>
            </form>
            <?php elseif ($booking->getStatus() === 'confirmed'): ?>
            <form method="POST" style="display:inline;" onsubmit="return confirm('Отметить как завершённое?')">
                <input type="hidden" name="action" value="complete">
                <button type="submit" class="btn btn--primary"><i class="fas fa-flag-checkered"></i> Завершить</button>
            </form>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>
