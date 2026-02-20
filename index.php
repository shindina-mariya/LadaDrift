<?php
$base = '';
$current_page = 'index';
$page_title = 'LADA Drift - Дай боком!';
include __DIR__ . '/php/includes/header.php';
?>
        <!-- Hero -->
        <section class="hero" id="hero">
            <!-- Фоновое видео/изображение -->
            <div class="hero__bg">
                <video autoplay muted loop playsinline>
                    <source src="assets/images/grok-video-8fd6cdbf-6dde-46ff-8c28-c0947c26b3ae-2.mp4" type="video/mp4">
                </video>
            </div>
            
            <!-- Оверлей -->
            <div class="hero__overlay"></div>
            
            <!-- Контент hero секции -->
            <div class="hero__content">
                <!-- Бейдж -->
                <span class="hero__badge">
                    <i class="fas fa-fire"></i> Новый сезон 2024
                </span>
                
                <!-- Заголовок -->
                <h1 class="hero__title animate">
                    Дай боком!
                    <span>на LADA</span>
                </h1>
                
                <!-- Подзаголовок -->
                <p class="hero__subtitle animate animate--delay-100">
                    Профессиональный дрифт на легендарных отечественных автомобилях. 
                    Аренда, обучение и незабываемые эмоции!
                </p>
                
                <!-- Кнопки -->
                <div class="hero__buttons animate animate--delay-200">
                    <a href="pages/booking.php" class="btn btn--cta btn--lg">
                        <i class="fas fa-calendar-check"></i>
                        Забронировать заезд
                    </a>
                    <a href="pages/fleet.php" class="btn btn--secondary btn--lg">
                        <i class="fas fa-car"></i>
                        Смотреть автопарк
                    </a>
                </div>
                
                <!-- Статистика -->
                <div class="hero__stats animate animate--delay-300">
                    <div class="hero__stat">
                        <div class="hero__stat-number">5+</div>
                        <div class="hero__stat-label">Автомобилей</div>
                    </div>
                    <div class="hero__stat">
                        <div class="hero__stat-number">1000+</div>
                        <div class="hero__stat-label">Довольных клиентов</div>
                    </div>
                    <div class="hero__stat">
                        <div class="hero__stat-number">3</div>
                        <div class="hero__stat-label">Года опыта</div>
                    </div>
                    <div class="hero__stat">
                        <div class="hero__stat-number">100%</div>
                        <div class="hero__stat-label">Адреналина</div>
                    </div>
                </div>
            </div>
            
            <!-- Стрелка вниз -->
            <a href="#advantages" class="hero__scroll">
                <span>Листай вниз</span>
                <i class="fas fa-chevron-down"></i>
            </a>
        </section>

        <!-- ============================================
             СЕКЦИЯ ПРЕИМУЩЕСТВ
             ============================================ -->
        <section class="section advantages" id="advantages">
            <div class="container">
                <!-- Заголовок секции -->
                <div class="section-header">
                    <span class="section-header__label">Почему мы</span>
                    <h2 class="section-header__title">
                        Наши <span>преимущества</span>
                    </h2>
                    <p class="section-header__subtitle">
                        Мы создали идеальные условия для безопасного и захватывающего дрифта
                    </p>
                </div>
                
                <!-- Сетка преимуществ -->
                <div class="advantages__grid">
                    <!-- Карточка 1 -->
                    <div class="advantage-card animate">
                        <div class="advantage-card__icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="advantage-card__title">Безопасность</h3>
                        <p class="advantage-card__text">
                            Все автомобили оборудованы каркасом безопасности, 6-точечными ремнями и профессиональными шлемами
                        </p>
                    </div>
                    
                    <!-- Карточка 2 -->
                    <div class="advantage-card animate animate--delay-100">
                        <div class="advantage-card__icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3 class="advantage-card__title">Опытные инструкторы</h3>
                        <p class="advantage-card__text">
                            Наши инструкторы — профессиональные дрифтеры с многолетним опытом соревнований
                        </p>
                    </div>
                    
                    <!-- Карточка 3 -->
                    <div class="advantage-card animate animate--delay-200">
                        <div class="advantage-card__icon">
                            <i class="fas fa-ruble-sign"></i>
                        </div>
                        <h3 class="advantage-card__title">Доступные цены</h3>
                        <p class="advantage-card__text">
                            Дрифт на отечественных авто — это не только патриотично, но и выгодно. От 2000₽ за сессию
                        </p>
                    </div>
                    
                    <!-- Карточка 4 -->
                    <div class="advantage-card animate animate--delay-300">
                        <div class="advantage-card__icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3 class="advantage-card__title">Удобная локация</h3>
                        <p class="advantage-card__text">
                            Собственный трек в 30 минутах от МКАД. Бесплатная парковка и зона отдыха для гостей
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================
             СЕКЦИЯ УСЛУГ
             ============================================ -->
        <section class="section services" id="services">
            <div class="container">
                <!-- Заголовок секции -->
                <div class="section-header">
                    <span class="section-header__label">Что мы предлагаем</span>
                    <h2 class="section-header__title">
                        Наши <span>услуги</span>
                    </h2>
                    <p class="section-header__subtitle">
                        Выберите подходящий формат: от самостоятельного заезда до корпоративных мероприятий
                    </p>
                </div>
                
                <!-- Сетка услуг -->
                <div class="services__grid">
                    <!-- Услуга 1: Аренда авто -->
                    <div class="service-card animate">
                        <div class="service-card__image">
                            <img src="https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?w=600&h=400&fit=crop" alt="Аренда авто для дрифта">
                            <span class="service-card__icon">
                                <i class="fas fa-car"></i>
                            </span>
                        </div>
                        <div class="service-card__content">
                            <h3 class="service-card__title">Аренда авто для дрифта</h3>
                            <p class="service-card__description">
                                Выберите автомобиль из нашего автопарка и прокатитесь с ветерком! Идеально для тех, кто хочет почувствовать драйв.
                            </p>
                            <div class="service-card__price">
                                от 2 000 ₽ <span>/ 30 минут</span>
                            </div>
                            <a href="pages/services.php#car-rental" class="service-card__link">
                                Подробнее <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Услуга 2: Аренда трека -->
                    <div class="service-card animate animate--delay-100">
                        <div class="service-card__image">
                            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=400&fit=crop" alt="Аренда трека">
                            <span class="service-card__icon">
                                <i class="fas fa-road"></i>
                            </span>
                        </div>
                        <div class="service-card__content">
                            <h3 class="service-card__title">Аренда трека</h3>
                            <p class="service-card__description">
                                Закрытая площадка для вашей компании или мероприятия. Организуем дрифт-батлы и соревнования.
                            </p>
                            <div class="service-card__price">
                                от 15 000 ₽ <span>/ час</span>
                            </div>
                            <a href="pages/services.php#track-rental" class="service-card__link">
                                Подробнее <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Услуга 3: Обучение дрифту -->
                    <div class="service-card animate animate--delay-200">
                        <div class="service-card__image">
                            <img src="https://images.unsplash.com/photo-1449965408869-ebd3fee56fd5?w=600&h=400&fit=crop" alt="Обучение дрифту">
                            <span class="service-card__icon">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                        </div>
                        <div class="service-card__content">
                            <h3 class="service-card__title">Обучение дрифту</h3>
                            <p class="service-card__description">
                                Индивидуальные и групповые занятия с профессиональным инструктором. Научим с нуля!
                            </p>
                            <div class="service-card__price">
                                от 5 000 ₽ <span>/ час</span>
                            </div>
                            <a href="pages/services.php#training" class="service-card__link">
                                Подробнее <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Услуга 4: Подарочные сертификаты -->
                    <div class="service-card animate animate--delay-300">
                        <div class="service-card__image">
                            <img src="https://images.unsplash.com/photo-1513201099705-a9746e1e201f?w=600&h=400&fit=crop" alt="Подарочный сертификат">
                            <span class="service-card__icon">
                                <i class="fas fa-gift"></i>
                            </span>
                        </div>
                        <div class="service-card__content">
                            <h3 class="service-card__title">Подарочные сертификаты</h3>
                            <p class="service-card__description">
                                Идеальный подарок для любителя скорости и адреналина. Красивое оформление и гибкий номинал.
                            </p>
                            <div class="service-card__price">
                                от 3 000 ₽
                            </div>
                            <a href="pages/services.php#certificate" class="service-card__link">
                                Подробнее <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Услуга 5: Корпоративы -->
                    <div class="service-card animate animate--delay-400">
                        <div class="service-card__image">
                            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=400&fit=crop" alt="Корпоративные мероприятия">
                            <span class="service-card__icon">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                        <div class="service-card__content">
                            <h3 class="service-card__title">Корпоративы и тимбилдинг</h3>
                            <p class="service-card__description">
                                Незабываемые корпоративные мероприятия с элементами дрифта. Сплотите команду через драйв!
                            </p>
                            <div class="service-card__price">
                                от 50 000 ₽ <span>/ мероприятие</span>
                            </div>
                            <a href="pages/services.php#corporate" class="service-card__link">
                                Подробнее <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- CTA карточка -->
                    <div class="service-card animate animate--delay-500" style="background: var(--gradient-accent);">
                        <div class="service-card__content" style="height: 100%; display: flex; flex-direction: column; justify-content: center; text-align: center;">
                            <h3 class="service-card__title" style="font-size: 1.5rem;">Не знаете, что выбрать?</h3>
                            <p class="service-card__description" style="color: rgba(255,255,255,0.9);">
                                Позвоните нам, и мы поможем подобрать идеальный вариант для вас!
                            </p>
                            <a href="tel:+79001234567" class="btn btn--secondary" style="margin-top: 1rem;">
                                <i class="fas fa-phone"></i>
                                Позвонить
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================
             СЕКЦИЯ АВТОПАРКА
             ============================================ -->
        <section class="section fleet" id="fleet">
            <div class="container">
                <!-- Заголовок секции -->
                <div class="section-header">
                    <span class="section-header__label">Наш автопарк</span>
                    <h2 class="section-header__title">
                        Легендарные <span>LADA</span>
                    </h2>
                    <p class="section-header__subtitle">
                        Каждый автомобиль подготовлен для безопасного и эффектного дрифта
                    </p>
                </div>
                
                <!-- Сетка автомобилей -->
                <div class="fleet__grid">
                    <!-- Автомобиль 1 -->
                    <div class="car-card animate">
                        <span class="car-card__badge">Хит</span>
                        <div class="car-card__image">
                            <img src='/assets/images/Frame 2642.png' alt="LADA Granta Sport">
                        </div>
                        <div class="car-card__content">
                            <h3 class="car-card__title">LADA Granta Sport</h3>
                            <div class="car-card__specs">
                                <span><i class="fas fa-tachometer-alt"></i> 118 л.с.</span>
                                <span><i class="fas fa-cog"></i> МКПП</span>
                                <span><i class="fas fa-calendar"></i> 2023</span>
                            </div>
                            <div class="car-card__price">
                                <div>
                                    <span class="car-card__price-value">3 500 ₽</span>
                                    <span class="car-card__price-label">/ час</span>
                                </div>
                                <a href="pages/fleet.php#granta" class="btn btn--outline btn--sm">Выбрать</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Автомобиль 2 -->
                    <div class="car-card animate animate--delay-100">
                        <span class="car-card__badge" style="background: var(--color-cta);">Мощный</span>
                        <div class="car-card__image">
                            <img src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=400&h=300&fit=crop" alt="LADA Vesta Sport">
                        </div>
                        <div class="car-card__content">
                            <h3 class="car-card__title">LADA Vesta Sport</h3>
                            <div class="car-card__specs">
                                <span><i class="fas fa-tachometer-alt"></i> 145 л.с.</span>
                                <span><i class="fas fa-cog"></i> МКПП</span>
                                <span><i class="fas fa-calendar"></i> 2023</span>
                            </div>
                            <div class="car-card__price">
                                <div>
                                    <span class="car-card__price-value">4 500 ₽</span>
                                    <span class="car-card__price-label">/ час</span>
                                </div>
                                <a href="pages/fleet.php#vesta" class="btn btn--outline btn--sm">Выбрать</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Автомобиль 3 -->
                    <div class="car-card animate animate--delay-200">
                        <span class="car-card__badge" style="background: var(--color-gray);">Классика</span>
                        <div class="car-card__image">
                            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=400&h=300&fit=crop" alt="LADA 2107 Drift">
                        </div>
                        <div class="car-card__content">
                            <h3 class="car-card__title">LADA 2107 Drift</h3>
                            <div class="car-card__specs">
                                <span><i class="fas fa-tachometer-alt"></i> 160 л.с.</span>
                                <span><i class="fas fa-cog"></i> МКПП</span>
                                <span><i class="fas fa-calendar"></i> 2022</span>
                            </div>
                            <div class="car-card__price">
                                <div>
                                    <span class="car-card__price-value">4 000 ₽</span>
                                    <span class="car-card__price-label">/ час</span>
                                </div>
                                <a href="pages/fleet.php#2107" class="btn btn--outline btn--sm">Выбрать</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Автомобиль 4 -->
                    <div class="car-card animate animate--delay-300">
                        <span class="car-card__badge" style="background: var(--color-success);">Уникальный</span>
                        <div class="car-card__image">
                            <img src="https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=400&h=300&fit=crop" alt="LADA Niva Legend">
                        </div>
                        <div class="car-card__content">
                            <h3 class="car-card__title">LADA Niva Legend</h3>
                            <div class="car-card__specs">
                                <span><i class="fas fa-tachometer-alt"></i> 135 л.с.</span>
                                <span><i class="fas fa-cog"></i> МКПП</span>
                                <span><i class="fas fa-calendar"></i> 2023</span>
                            </div>
                            <div class="car-card__price">
                                <div>
                                    <span class="car-card__price-value">5 000 ₽</span>
                                    <span class="car-card__price-label">/ час</span>
                                </div>
                                <a href="pages/fleet.php#niva" class="btn btn--outline btn--sm">Выбрать</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Кнопка "Все автомобили" -->
                <div style="text-align: center; margin-top: var(--spacing-2xl);">
                    <a href="pages/fleet.php" class="btn btn--primary btn--lg">
                        Смотреть весь автопарк
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- ============================================
             СЕКЦИЯ "КАК ЭТО РАБОТАЕТ"
             ============================================ -->
        <section class="section how-it-works" id="how-it-works">
            <div class="container">
                <!-- Заголовок секции -->
                <div class="section-header">
                    <span class="section-header__label">Просто и быстро</span>
                    <h2 class="section-header__title">
                        Как <span>забронировать</span>
                    </h2>
                    <p class="section-header__subtitle">
                        Всего 5 простых шагов отделяют вас от незабываемого дрифта
                    </p>
                </div>
                
                <!-- Таймлайн -->
                <div class="how-it-works__timeline">
                    <div class="timeline-step animate">
                        <div class="timeline-step__number">1</div>
                        <h4 class="timeline-step__title">Выберите услугу</h4>
                        <p class="timeline-step__text">Аренда авто, трека или обучение</p>
                    </div>
                    
                    <div class="timeline-step animate animate--delay-100">
                        <div class="timeline-step__number">2</div>
                        <h4 class="timeline-step__title">Выберите авто</h4>
                        <p class="timeline-step__text">Подберите машину под свой уровень</p>
                    </div>
                    
                    <div class="timeline-step animate animate--delay-200">
                        <div class="timeline-step__number">3</div>
                        <h4 class="timeline-step__title">Укажите дату</h4>
                        <p class="timeline-step__text">Выберите удобное время в календаре</p>
                    </div>
                    
                    <div class="timeline-step animate animate--delay-300">
                        <div class="timeline-step__number">4</div>
                        <h4 class="timeline-step__title">Оплатите онлайн</h4>
                        <p class="timeline-step__text">Безопасная оплата картой или СБП</p>
                    </div>
                    
                    <div class="timeline-step animate animate--delay-400">
                        <div class="timeline-step__number">5</div>
                        <h4 class="timeline-step__title">Приезжайте!</h4>
                        <p class="timeline-step__text">Получите инструктаж и наслаждайтесь</p>
                    </div>
                </div>
                
                <!-- CTA -->
                <div style="text-align: center; margin-top: var(--spacing-2xl);">
                    <a href="pages/booking.php" class="btn btn--cta btn--lg">
                        <i class="fas fa-calendar-check"></i>
                        Забронировать сейчас
                    </a>
                </div>
            </div>
        </section>

        <!-- ============================================
             СЕКЦИЯ ОТЗЫВОВ
             ============================================ -->
        <section class="section reviews" id="reviews">
            <div class="container">
                <!-- Заголовок секции -->
                <div class="section-header">
                    <span class="section-header__label">Отзывы</span>
                    <h2 class="section-header__title">
                        Что говорят <span>клиенты</span>
                    </h2>
                    <p class="section-header__subtitle">
                        Более 1000 довольных клиентов уже испытали драйв с LADA Drift
                    </p>
                </div>
                
                <!-- Слайдер отзывов -->
                <div class="reviews__slider">
                    <div class="swiper reviewsSwiper">
                        <div class="swiper-wrapper">
                            <!-- Отзыв 1 -->
                            <div class="swiper-slide">
                                <div class="review-card">
                                    <div class="review-card__avatar">
                                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face" alt="Алексей М.">
                                    </div>
                                    <div class="review-card__rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="review-card__text">
                                        Невероятные эмоции! Первый раз в жизни дрифтовал и сразу на отечественном авто. Инструктор всё объяснил, машина послушная. Обязательно вернусь!
                                    </p>
                                    <p class="review-card__author">Алексей М.</p>
                                    <p class="review-card__service">Обучение дрифту</p>
                                </div>
                            </div>
                            
                            <!-- Отзыв 2 -->
                            <div class="swiper-slide">
                                <div class="review-card">
                                    <div class="review-card__avatar">
                                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=face" alt="Дмитрий К.">
                                    </div>
                                    <div class="review-card__rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="review-card__text">
                                        Арендовал Гранту на день рождения друга. Он был в восторге! Организация на высшем уровне, всё чётко и безопасно. Рекомендую всем!
                                    </p>
                                    <p class="review-card__author">Дмитрий К.</p>
                                    <p class="review-card__service">Аренда авто</p>
                                </div>
                            </div>
                            
                            <!-- Отзыв 3 -->
                            <div class="swiper-slide">
                                <div class="review-card">
                                    <div class="review-card__avatar">
                                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face" alt="Мария С.">
                                    </div>
                                    <div class="review-card__rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="review-card__text">
                                        Подарила мужу сертификат на дрифт. Лучший подарок за все годы! Теперь он фанат LADA Drift и планирует пройти обучение.
                                    </p>
                                    <p class="review-card__author">Мария С.</p>
                                    <p class="review-card__service">Подарочный сертификат</p>
                                </div>
                            </div>
                            
                            <!-- Отзыв 4 -->
                            <div class="swiper-slide">
                                <div class="review-card">
                                    <div class="review-card__avatar">
                                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face" alt="Игорь В.">
                                    </div>
                                    <div class="review-card__rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                    <p class="review-card__text">
                                        Отличный трек, хорошие машины. Единственное — хотелось бы больше временных слотов в выходные. А так всё супер!
                                    </p>
                                    <p class="review-card__author">Игорь В.</p>
                                    <p class="review-card__service">Аренда трека</p>
                                </div>
                            </div>
                            
                            <!-- Отзыв 5 -->
                            <div class="swiper-slide">
                                <div class="review-card">
                                    <div class="review-card__avatar">
                                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face" alt="Анна Л.">
                                    </div>
                                    <div class="review-card__rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="review-card__text">
                                        Корпоратив прошел на ура! Коллеги до сих пор обсуждают. Спасибо за организацию и индивидуальный подход!
                                    </p>
                                    <p class="review-card__author">Анна Л.</p>
                                    <p class="review-card__service">Корпоратив</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Навигация -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                        
                        <!-- Пагинация -->
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================
             СЕКЦИЯ FAQ
             ============================================ -->
        <section class="section faq" id="faq">
            <div class="container">
                <!-- Заголовок секции -->
                <div class="section-header">
                    <span class="section-header__label">FAQ</span>
                    <h2 class="section-header__title">
                        Частые <span>вопросы</span>
                    </h2>
                    <p class="section-header__subtitle">
                        Ответы на популярные вопросы о дрифте в LADA Drift
                    </p>
                </div>
                
                <!-- Аккордеон FAQ -->
                <div class="faq__accordion">
                    <!-- Вопрос 1 -->
                    <div class="faq-item active">
                        <div class="faq-item__header">
                            <h4 class="faq-item__question">Нужны ли водительские права для участия?</h4>
                            <span class="faq-item__icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-item__content">
                            <p class="faq-item__answer">
                                Да, для управления автомобилем необходимы действующие водительские права категории B. 
                                Для пассажирских заездов (когда за рулём инструктор) права не требуются — достаточно быть старше 14 лет.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Вопрос 2 -->
                    <div class="faq-item">
                        <div class="faq-item__header">
                            <h4 class="faq-item__question">Какой опыт вождения нужен?</h4>
                            <span class="faq-item__icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-item__content">
                            <p class="faq-item__answer">
                                Для обучения и аренды достаточно базовых навыков вождения — уверенно трогаться, переключать передачи 
                                (если МКПП) и тормозить. Наши инструкторы помогут освоить технику дрифта с нуля, начиная с простых упражнений.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Вопрос 3 -->
                    <div class="faq-item">
                        <div class="faq-item__header">
                            <h4 class="faq-item__question">Что включено в стоимость аренды?</h4>
                            <span class="faq-item__icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-item__content">
                            <p class="faq-item__answer">
                                В стоимость входит: автомобиль с полным баком топлива, шлем, инструктаж по безопасности, 
                                базовая страховка участника. Расходники (покрышки) оплачиваются отдельно только при сильном износе 
                                или повреждении по вине клиента.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Вопрос 4 -->
                    <div class="faq-item">
                        <div class="faq-item__header">
                            <h4 class="faq-item__question">Можно ли отменить или перенести бронирование?</h4>
                            <span class="faq-item__icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-item__content">
                            <p class="faq-item__answer">
                                Да, бесплатная отмена или перенос возможны за 24 часа до начала сессии. При отмене менее чем за 24 часа 
                                удерживается 50% стоимости. Перенос на другую дату бесплатный при уведомлении за сутки.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Вопрос 5 -->
                    <div class="faq-item">
                        <div class="faq-item__header">
                            <h4 class="faq-item__question">Есть ли ограничения по возрасту?</h4>
                            <span class="faq-item__icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-item__content">
                            <p class="faq-item__answer">
                                Минимальный возраст для водителя — 18 лет (при наличии прав). Пассажиром может быть любой человек 
                                старше 14 лет (с письменного согласия родителей для несовершеннолетних). Верхнего ограничения нет, 
                                главное — хорошее самочувствие!
                            </p>
                        </div>
                    </div>
                    
                    <!-- Вопрос 6 -->
                    <div class="faq-item">
                        <div class="faq-item__header">
                            <h4 class="faq-item__question">Как добраться до трека?</h4>
                            <span class="faq-item__icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-item__content">
                            <p class="faq-item__answer">
                                Наш трек находится по адресу: Московская область, г. Дмитров, ул. Промышленная, 15. 
                                От МКАД ~30 минут по Дмитровскому шоссе. Есть бесплатная парковка. Подробная схема проезда 
                                и координаты для навигатора — на странице <a href="pages/contacts.php">Контакты</a>.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Вопрос 7 -->
                    <div class="faq-item">
                        <div class="faq-item__header">
                            <h4 class="faq-item__question">Предоставляете ли вы страховку?</h4>
                            <span class="faq-item__icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-item__content">
                            <p class="faq-item__answer">
                                Да, все участники автоматически застрахованы от несчастных случаев (базовая страховка включена в стоимость). 
                                Дополнительно можно оформить расширенную страховку КАСКО на автомобиль за 1500₽, которая покрывает 
                                любые повреждения машины.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================
             CTA СЕКЦИЯ (Призыв к действию)
             ============================================ -->
        <section class="section section--accent" style="padding: var(--spacing-3xl) 0;">
            <div class="container">
                <div style="text-align: center; max-width: 700px; margin: 0 auto;">
                    <h2 style="font-size: clamp(1.75rem, 4vw, 2.5rem); font-weight: 800; margin-bottom: var(--spacing-md);">
                        Готовы испытать настоящий драйв?
                    </h2>
                    <p style="font-size: 1.125rem; margin-bottom: var(--spacing-xl); opacity: 0.9;">
                        Забронируйте свой первый заезд прямо сейчас и получите скидку 10% по промокоду <strong>FIRSTDRIFT</strong>
                    </p>
                    <div style="display: flex; justify-content: center; gap: var(--spacing-md); flex-wrap: wrap;">
                        <a href="pages/booking.php" class="btn btn--secondary btn--lg">
                            <i class="fas fa-calendar-check"></i>
                            Забронировать
                        </a>
                        <a href="tel:+79001234567" class="btn btn--lg" style="background: rgba(255,255,255,0.2); color: white;">
                            <i class="fas fa-phone"></i>
                            Позвонить
                        </a>
                    </div>
                </div>
            </div>
        </section>

<?php include __DIR__ . '/php/includes/footer.php'; ?>
