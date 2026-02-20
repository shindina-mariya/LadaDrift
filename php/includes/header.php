<?php
if (!isset($base)) $base = '';
$current_page = isset($current_page) ? $current_page : basename($_SERVER['PHP_SELF'], '.php');
function isActive($page) {
    global $current_page;
    return ($current_page === $page) ? 'active' : '';
}
$main_class = isset($main_class) ? $main_class : 'main';
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'LADA Drift - Дрифт на автомобилях LADA. Аренда авто для дрифта, обучение, подарочные сертификаты.'; ?>">
    <meta name="keywords" content="дрифт, LADA, дрифт обучение, аренда авто дрифт, подарочный сертификат дрифт, автодром">
    <link rel="icon" type="image/png" href="<?php echo $base; ?>assets/images/logo/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <link rel="stylesheet" href="<?php echo $base; ?>css/fonts.css">
    <link rel="stylesheet" href="<?php echo $base; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo $base; ?>css/pages.css">
    <link rel="stylesheet" href="<?php echo $base; ?>css/responsive.css">
    <title><?php echo isset($page_title) ? $page_title . ' | LADA Drift' : 'LADA Drift - Дай боком!'; ?></title>
</head>
<body>
    <div class="preloader" id="preloader">
        <div class="preloader__content">
            <div class="preloader__logo">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                    <circle cx="40" cy="40" r="38" stroke="#E30613" stroke-width="4"/>
                    <path d="M25 50L40 25L55 50H25Z" fill="#E30613"/>
                    <text x="40" y="65" text-anchor="middle" fill="#fff" font-family="Montserrat" font-weight="900" font-size="10">DRIFT</text>
                </svg>
            </div>
            <div class="preloader__spinner"></div>
            <p class="preloader__text">Загружаем адреналин...</p>
        </div>
    </div>

    <header class="header" id="header">
        <div class="container">
            <div class="header__inner">
                <a href="<?php echo $base; ?>index.php" class="header__logo">
                    <svg class="header__logo-img" width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <circle cx="20" cy="20" r="18" stroke="#E30613" stroke-width="3"/>
                        <path d="M12 28L20 12L28 28H12Z" fill="#E30613"/>
                    </svg>
                    <span class="header__logo-text">
                        <span class="header__logo-main">LADA</span>
                        <span class="header__logo-sub">DRIFT</span>
                    </span>
                </a>
                <nav class="header__nav" id="mainNav">
                    <ul class="header__menu">
                        <li class="header__menu-item"><a href="<?php echo $base; ?>index.php" class="header__menu-link <?php echo isActive('index'); ?>">Главная</a></li>
                        <li class="header__menu-item"><a href="<?php echo $base; ?>pages/services.php" class="header__menu-link <?php echo isActive('services'); ?>">Услуги</a></li>
                        <li class="header__menu-item"><a href="<?php echo $base; ?>pages/fleet.php" class="header__menu-link <?php echo isActive('fleet'); ?>">Автопарк</a></li>
                        <li class="header__menu-item"><a href="<?php echo $base; ?>pages/about.php" class="header__menu-link <?php echo isActive('about'); ?>">О нас</a></li>
                        <li class="header__menu-item"><a href="<?php echo $base; ?>pages/contacts.php" class="header__menu-link <?php echo isActive('contacts'); ?>">Контакты</a></li>
                    </ul>
                </nav>
                <div class="header__actions">
                    <a href="tel:+79001234567" class="header__phone"><i class="fas fa-phone"></i><span>+7 (900) 123-45-67</span></a>
                    <a href="<?php echo $base; ?>pages/booking.php" class="btn btn--primary header__btn">Забронировать</a>
                    <button class="header__burger" id="burgerBtn" aria-label="Открыть меню">
                        <span class="header__burger-line"></span><span class="header__burger-line"></span><span class="header__burger-line"></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu__overlay" id="mobileOverlay"></div>
            <div class="mobile-menu__content">
                <div class="mobile-menu__header">
                    <a href="<?php echo $base; ?>index.php" class="mobile-menu__logo">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none"><circle cx="20" cy="20" r="18" stroke="#E30613" stroke-width="3"/><path d="M12 28L20 12L28 28H12Z" fill="#E30613"/></svg>
                    </a>
                    <button class="mobile-menu__close" id="closeMenuBtn" aria-label="Закрыть меню"><i class="fas fa-times"></i></button>
                </div>
                <nav class="mobile-menu__nav">
                    <ul class="mobile-menu__list">
                        <li><a href="<?php echo $base; ?>index.php" class="<?php echo isActive('index'); ?>">Главная</a></li>
                        <li><a href="<?php echo $base; ?>pages/services.php" class="<?php echo isActive('services'); ?>">Услуги</a></li>
                        <li><a href="<?php echo $base; ?>pages/fleet.php" class="<?php echo isActive('fleet'); ?>">Автопарк</a></li>
                        <li><a href="<?php echo $base; ?>pages/about.php" class="<?php echo isActive('about'); ?>">О нас</a></li>
                        <li><a href="<?php echo $base; ?>pages/contacts.php" class="<?php echo isActive('contacts'); ?>">Контакты</a></li>
                    </ul>
                </nav>
                <div class="mobile-menu__footer">
                    <a href="tel:+79001234567" class="mobile-menu__phone"><i class="fas fa-phone"></i>+7 (900) 123-45-67</a>
                    <a href="<?php echo $base; ?>pages/booking.php" class="btn btn--primary btn--block">Забронировать</a>
                    <div class="mobile-menu__social">
                        <a href="https://t.me/ladadrift" target="_blank" rel="noopener" aria-label="Telegram"><i class="fab fa-telegram"></i></a>
                        <a href="https://vk.com/ladadrift" target="_blank" rel="noopener" aria-label="VK"><i class="fab fa-vk"></i></a>
                        <a href="https://youtube.com/@ladadrift" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="<?php echo htmlspecialchars($main_class); ?>">
