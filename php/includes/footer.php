<?php
if (!isset($base)) $base = '';
?>
    </main>
    <footer class="footer">
        <div class="footer__top">
            <div class="container">
                <div class="footer__grid">
                    <div class="footer__col footer__col--about">
                        <a href="<?php echo $base; ?>index.php" class="footer__logo">
                            <svg width="50" height="50" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="18" stroke="#E30613" stroke-width="3"/>
                                <path d="M12 28L20 12L28 28H12Z" fill="#E30613"/>
                            </svg>
                            <span>LADA<br>DRIFT</span>
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
                            <li><a href="<?php echo $base; ?>index.php">Главная</a></li>
                            <li><a href="<?php echo $base; ?>pages/services.php">Услуги</a></li>
                            <li><a href="<?php echo $base; ?>pages/fleet.php">Автопарк</a></li>
                            <li><a href="<?php echo $base; ?>pages/about.php">О компании</a></li>
                            <li><a href="<?php echo $base; ?>pages/contacts.php">Контакты</a></li>
                            <li><a href="<?php echo $base; ?>pages/booking.php">Бронирование</a></li>
                        </ul>
                    </div>

                    <div class="footer__col">
                        <h3 class="footer__title">Услуги</h3>
                        <ul class="footer__menu">
                            <li><a href="<?php echo $base; ?>pages/services.php#car-rental">Аренда авто</a></li>
                            <li><a href="<?php echo $base; ?>pages/services.php#track-rental">Аренда трека</a></li>
                            <li><a href="<?php echo $base; ?>pages/services.php#training">Обучение дрифту</a></li>
                            <li><a href="<?php echo $base; ?>pages/services.php#certificate">Подарочные сертификаты</a></li>
                            <li><a href="<?php echo $base; ?>pages/services.php#corporate">Корпоративы</a></li>
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
                                <a href="<?php echo $base; ?>pages/privacy.html">политикой конфиденциальности</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer__map">
            <div class="footer__map-container" id="footerMap">
                <iframe
                    src="https://yandex.ru/map-widget/v1/?um=constructor%3A2f8e9d3b7c4a5f1e0d9c8b7a6f5e4d3c&amp;source=constructor"
                    width="100%"
                    height="300"
                    frameborder="0"
                    loading="lazy"
                    title="Карта проезда к LADA Drift"
                ></iframe>
            </div>
        </div>

        <div class="footer__bottom">
            <div class="container">
                <div class="footer__bottom-inner">
                    <p class="footer__copyright">
                        &copy; <?php echo date('Y'); ?> LADA Drift. Все права защищены.
                    </p>
                    <div class="footer__links">
                        <a href="<?php echo $base; ?>pages/privacy.html">Политика конфиденциальности</a>
                        <a href="<?php echo $base; ?>pages/terms.html">Пользовательское соглашение</a>
                        <a href="<?php echo $base; ?>pages/oferta.html">Публичная оферта</a>
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

    <button class="scroll-top" id="scrollTopBtn" aria-label="Наверх">
        <i class="fas fa-chevron-up"></i>
    </button>

    <div class="callback-widget" id="callbackWidget">
        <button class="callback-widget__btn" id="callbackBtn" aria-label="Заказать звонок">
            <i class="fas fa-phone-alt"></i>
            <span class="callback-widget__pulse"></span>
        </button>
        <div class="callback-widget__form" id="callbackForm">
            <div class="callback-widget__header">
                <h4>Заказать звонок</h4>
                <button class="callback-widget__close" id="closeCallbackBtn" aria-label="Закрыть">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="callbackRequestForm">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Ваше имя" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" class="form-control" placeholder="+7 (___) ___-__-__" required>
                </div>
                <button type="submit" class="btn btn--primary btn--block">Перезвоните мне</button>
            </form>
        </div>
    </div>

    <div class="modal" id="modal">
        <div class="modal__overlay" id="modalOverlay"></div>
        <div class="modal__content" id="modalContent">
            <button class="modal__close" id="modalClose" aria-label="Закрыть">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal__body" id="modalBody"></div>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="<?php echo $base; ?>js/main.js"></script>
    <?php if (isset($current_page) && $current_page === 'booking'): ?>
    <script src="<?php echo $base; ?>js/booking.js"></script>
    <?php endif; ?>
    <?php if (isset($current_page) && $current_page === 'fleet'): ?>
    <script src="<?php echo $base; ?>js/gallery.js"></script>
    <?php endif; ?>
</body>
</html>
