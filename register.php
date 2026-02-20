<?php
session_start();
require_once __DIR__ . '/php/includes/auth.php';
require_once __DIR__ . '/php/models/User.php';

if (isUserLoggedIn()) {
    header('Location: cabinet.php');
    exit;
}

$error = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = User::register($_POST);
    if ($result['success']) {
        setUserSession($result['user']->getId(), $result['user']->getEmail(), $result['user']->getFullName());
        header('Location: cabinet.php');
        exit;
    }
    $errors = $result['errors'] ?? [];
    $error = implode('. ', $errors);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="assets/images/logo/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pages.css">
    <link rel="stylesheet" href="css/responsive.css">
    <title>Регистрация | LADA Drift</title>
</head>
<body>
    <header class="header" id="header">
        <div class="container">
            <div class="header__inner">
                <a href="index.html" class="header__logo">
                    <img src="assets/images/logo/logo.svg" alt="LADA Drift" class="header__logo-img">
                </a>
                <nav class="header__nav">
                    <ul class="header__menu">
                        <li class="header__menu-item"><a href="index.html" class="header__menu-link">Главная</a></li>
                        <li class="header__menu-item"><a href="services.html" class="header__menu-link">Услуги</a></li>
                        <li class="header__menu-item"><a href="fleet.html" class="header__menu-link">Автопарк</a></li>
                        <li class="header__menu-item"><a href="about.html" class="header__menu-link">О нас</a></li>
                        <li class="header__menu-item"><a href="contacts.html" class="header__menu-link">Контакты</a></li>
                    </ul>
                </nav>
                <div class="header__actions">
                    <a href="tel:+79001234567" class="header__phone"><i class="fas fa-phone"></i><span>+7 (900) 123-45-67</span></a>
                    <a href="booking.html" class="btn btn--primary header__btn">Забронировать</a>
                </div>
            </div>
        </div>
    </header>

    <main class="page-hero" style="padding-top: calc(var(--header-height) + 3rem); min-height: 70vh; display: flex; align-items: center;">
        <div class="container">
            <div class="auth-card" style="max-width: 400px; margin: 0 auto; background: #1A1A1A; border-radius: 16px; padding: 2rem;">
                <h1 style="font-size: 1.5rem; margin-bottom: 1.5rem; text-align: center;">Регистрация</h1>
                <?php if ($error): ?>
                <div class="form-error" style="color: #DC3545; margin-bottom: 1rem; font-size: 0.875rem;"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" placeholder="example@mail.ru" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Пароль (мин. 6 символов) *</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Телефон *</label>
                        <input type="tel" name="phone" class="form-control" placeholder="+7 (900) 123-45-67" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ФИО *</label>
                        <input type="text" name="full_name" class="form-control" placeholder="Иванов Иван Иванович" required value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Номер ВУ (опционально)</label>
                        <input type="text" name="driver_license" class="form-control" placeholder="1234 567890" value="<?= htmlspecialchars($_POST['driver_license'] ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn--primary btn--block" style="margin-top: 1rem;">
                        <i class="fas fa-user-plus"></i> Зарегистрироваться
                    </button>
                </form>
                <p style="text-align: center; margin-top: 1.5rem; color: #AAAAAA; font-size: 0.875rem;">
                    Уже есть аккаунт? <a href="login.php" style="color: #E30613;">Войти</a>
                </p>
            </div>
        </div>
    </main>
</body>
</html>
