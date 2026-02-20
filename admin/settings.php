<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки | LADA Drift</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --color-bg:#0a0a0a; --color-card:#1a1a1a; --color-accent:#E30613; --color-text:#fff; --color-muted:#888; }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:Roboto,sans-serif; background:var(--color-bg); color:var(--color-text); min-height:100vh; display:flex; }
        .sidebar { width:260px; background:#111; border-right:1px solid #222; padding:1.5rem 0; }
        .sidebar__logo { padding:0 1.5rem 1rem; border-bottom:1px solid #222; margin-bottom:1rem; }
        .sidebar__logo img { width:120px; }
        .sidebar__nav-item { display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1.5rem; color:var(--color-muted); text-decoration:none; }
        .sidebar__nav-item:hover { color:var(--color-text); }
        .sidebar__nav-item.active { color:var(--color-accent); }
        .main { flex:1; padding:2rem; }
        .card { background:var(--color-card); border-radius:8px; padding:2rem; text-align:center; color:var(--color-muted); }
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
        <a href="settings.php" class="sidebar__nav-item active"><i class="fas fa-cog"></i> Настройки</a>
    </aside>
    <main class="main">
        <h1 style="margin-bottom:1.5rem;">Настройки</h1>
        <div class="card">Раздел в разработке.</div>
    </main>
</body>
</html>
