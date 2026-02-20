<?php
session_start();
require_once __DIR__ . '/php/includes/auth.php';
require_once __DIR__ . '/php/models/User.php';
require_once __DIR__ . '/php/models/Booking.php';

if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = getCurrentUserId();
$user = User::getById($userId);

if (!$user) {
    clearUserSession();
    header('Location: login.php');
    exit;
}

// Отмена бронирования
if (isset($_GET['cancel'])) {
    $bid = (int) $_GET['cancel'];
    $booking = Booking::getById($bid);
    if ($booking && $booking->getUserId() === $userId && in_array($booking->getStatus(), ['pending', 'confirmed'])) {
        $booking->cancel();
    }
    header('Location: cabinet.php');
    exit;
}

// Редактирование профиля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    if (!empty($_POST['full_name']) && mb_strlen(trim($_POST['full_name'])) >= 2) {
        $user->setFullName(htmlspecialchars(trim($_POST['full_name']), ENT_QUOTES, 'UTF-8'));
    }
    if (!empty($_POST['phone'])) {
        $d = preg_replace('/\D/', '', $_POST['phone']);
        if (strlen($d) === 11 && $d[0] === '8') $d = '7' . substr($d, 1);
        $phone = '+' . $d;
        if (preg_match('/^\+7\d{10}$/', $phone)) $user->setPhone($phone);
    }
    if (array_key_exists('driver_license', $_POST)) {
        $user->setDriverLicense($_POST['driver_license'] === '' ? null : htmlspecialchars(trim($_POST['driver_license']), ENT_QUOTES, 'UTF-8'));
    }
    $user->save();
    $_SESSION['user_name'] = $user->getFullName();
    header('Location: cabinet.php');
    exit;
}

$bookings = Booking::getByUserId($userId);
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
    <title>Личный кабинет | LADA Drift</title>
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

    <main class="cabinet-page" style="margin-top: var(--header-height); padding: 5%;">
        <div class="container">
            <div class="cabinet-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                <h1 class="section__title" style="margin-bottom: 0;">Личный кабинет</h1>
                <div style="display: flex; gap: 0.75rem;">
                    <span style="color: #AAAAAA; display: flex; align-items: center;"><i class="fas fa-user" style="margin-right: 0.5rem;"></i><?= htmlspecialchars($user->getFullName()) ?></span>
                    <a href="logout.php" class="btn btn--outline btn--sm"><i class="fas fa-sign-out-alt"></i> Выход</a>
                </div>
            </div>
            <div class="cabinet-grid" style="display: grid; grid-template-columns: 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div class="cabinet-profile" style="background: #1A1A1A; border-radius: 16px; padding: 1.5rem;">
                    <h3 style="margin-bottom: 1rem; font-size: 1.125rem;">Профиль</h3>
                    <form method="POST">
                        <input type="hidden" name="save_profile" value="1">
                        <div class="form-group">
                            <label class="form-label">ФИО</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user->getFullName()) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Телефон</label>
                            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user->getPhone()) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Номер ВУ</label>
                            <input type="text" name="driver_license" class="form-control" value="<?= htmlspecialchars($user->getDriverLicense() ?? '') ?>">
                        </div>
                        <p style="color: #AAAAAA; font-size: 0.875rem;"><?= htmlspecialchars($user->getEmail()) ?></p>
                        <button type="submit" class="btn btn--primary btn--sm" style="margin-top: 1rem;">Сохранить</button>
                    </form>
                </div>
                <div class="cabinet-bookings" style="background: #1A1A1A; border-radius: 16px; padding: 1.5rem;">
                    <h3 style="margin-bottom: 1rem; font-size: 1.125rem;">Мои бронирования</h3>
                    <?php if (empty($bookings)): ?>
                    <p style="color: #AAAAAA;">Пока нет бронирований. <a href="booking.html" style="color: #E30613;">Забронировать заезд</a></p>
                    <?php else: ?>
                    <?php foreach ($bookings as $b): ?>
                    <div style="border: 1px solid #333; border-radius: 8px; padding: 1rem; margin-bottom: 0.75rem;">
                        <strong><?= htmlspecialchars($b->getServiceTypeLabel()) ?></strong>
                        <?php if ($b->getCar()): ?> — <?= htmlspecialchars($b->getCar()->getModel()) ?><?php endif; ?>
                        <p style="font-size: 0.875rem; color: #AAAAAA; margin-top: 0.25rem;">
                            <?= htmlspecialchars($b->getFormattedDate()) ?> <?= htmlspecialchars($b->getTimeSlot()) ?> · <?= htmlspecialchars($b->getFormattedPrice()) ?>
                        </p>
                        <span style="display: inline-block; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-top: 0.5rem; background: #333;"><?= htmlspecialchars($b->getStatusLabel()) ?></span>
                        <?php if (in_array($b->getStatus(), ['pending', 'confirmed'])): ?>
                        <a href="?cancel=<?= $b->getId() ?>" class="btn btn--outline btn--sm" style="margin-left: 0.5rem;" onclick="return confirm('Отменить бронирование?')">Отменить</a>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer__top">
            <div class="container">
                <div class="footer__grid">
                    <div class="footer__col footer__col--about">
                        <a href="index.html" class="footer__logo">
                            <img src="assets/images/logo/logo.svg" alt="LADA Drift" width="50" height="50">
                        </a>
                        <p class="footer__description">
                            Профессиональный дрифт на легендарных автомобилях LADA.
                            Аренда авто, обучение, корпоративные мероприятия и подарочные сертификаты.
                        </p>
                        <div class="footer__social">
                            <a href="https://t.me/ladadrift" target="_blank" rel="noopener" class="footer__social-link" aria-label="Telegram">
                                <i class="fab fa-telegram"></i>
                            </a>
                            <a href="https://vk.com/ladadrift" target="_blank" rel="noopener" class="footer__social-link" aria-label="VK">
                                <i class="fab fa-vk"></i>
                            </a>
                            <a href="https://youtube.com/@ladadrift" target="_blank" rel="noopener" class="footer__social-link" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="https://instagram.com/ladadrift" target="_blank" rel="noopener" class="footer__social-link" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>

                    <div class="footer__col">
                        <h3 class="footer__title">Навигация</h3>
                        <ul class="footer__menu">
                            <li><a href="index.html">Главная</a></li>
                            <li><a href="services.html">Услуги</a></li>
                            <li><a href="fleet.html">Автопарк</a></li>
                            <li><a href="about.html">О компании</a></li>
                            <li><a href="contacts.html">Контакты</a></li>
                            <li><a href="booking.html">Бронирование</a></li>
                        </ul>
                    </div>

                    <div class="footer__col">
                        <h3 class="footer__title">Услуги</h3>
                        <ul class="footer__menu">
                            <li><a href="services.html#car-rental">Аренда авто</a></li>
                            <li><a href="services.html#track-rental">Аренда трека</a></li>
                            <li><a href="services.html#training">Обучение дрифту</a></li>
                            <li><a href="services.html#certificate">Подарочные сертификаты</a></li>
                            <li><a href="services.html#corporate">Корпоративы</a></li>
                        </ul>
                    </div>

                    <div class="footer__col">
                        <h3 class="footer__title">Контакты</h3>
                        <ul class="footer__contacts">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Московская обл., г. Дмитров,<br>ул. Промышленная, 15</span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <a href="tel:+79001234567">+7 (900) 123-45-67</a>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:info@ladadrift.ru">info@ladadrift.ru</a>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Пн-Вс: 10:00 - 21:00</span>
                            </li>
                        </ul>
                    </div>

                    <div class="footer__col footer__col--subscribe">
                        <h3 class="footer__title">Подпишитесь на новости</h3>
                        <p class="footer__subscribe-text">
                            Получайте информацию об акциях и специальных предложениях
                        </p>
                        <form class="footer__subscribe-form" id="subscribeForm">
                            <div class="footer__subscribe-input">
                                <input type="email" name="email" placeholder="Ваш email" required autocomplete="email">
                                <button type="submit" aria-label="Подписаться">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                            <p class="footer__subscribe-note">
                                Нажимая кнопку, вы соглашаетесь с
                                <a href="pages/privacy.html">политикой конфиденциальности</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="footer__bottom">
            <div class="container">
                <div class="footer__bottom-inner">
                    <p class="footer__copyright">
                        &copy;  LADA Drift. Все права защищены.
                    </p>
                    <div class="footer__links">
                        <a href="pages/privacy.html">Политика конфиденциальности</a>
                        <a href="pages/terms.html">Пользовательское соглашение</a>
                        <a href="pages/oferta.html">Публичная оферта</a>
                    </div>
                    <div class="footer__payment">
                        <span>Принимаем к оплате:</span>
                        <i class="fab fa-cc-visa" style="font-size: 1.5rem;"></i>
                        <i class="fab fa-cc-mastercard" style="font-size: 1.5rem;"></i>
                        <span style="font-size: 0.75rem; background: #4CAF50; padding: 2px 6px; border-radius: 4px;">МИР</span>
                        <span style="font-size: 0.75rem; background: #1976D2; padding: 2px 6px; border-radius: 4px;">СБП</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <button class="scroll-top" id="scrollTopBtn" aria-label="Наверх"><i class="fas fa-chevron-up"></i></button>

    <script src="js/main.js"></script>
</body>
</html>
