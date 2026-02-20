-- =====================================================
-- LADA Drift - Схема базы данных
-- Дрифт на автомобилях LADA
-- =====================================================

CREATE DATABASE IF NOT EXISTS lada_drift
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE lada_drift;

-- =====================================================
-- Таблица автомобилей (cars)
-- =====================================================
CREATE TABLE IF NOT EXISTS cars (
    id INT PRIMARY KEY AUTO_INCREMENT,
    model VARCHAR(100) NOT NULL COMMENT 'Модель автомобиля',
    year INT NOT NULL COMMENT 'Год выпуска',
    power INT NOT NULL COMMENT 'Мощность двигателя в л.с.',
    engine VARCHAR(50) COMMENT 'Объем и тип двигателя',
    transmission VARCHAR(50) NOT NULL COMMENT 'Тип трансмиссии',
    drive VARCHAR(20) DEFAULT 'задний' COMMENT 'Тип привода',
    modifications TEXT COMMENT 'Список доработок (JSON)',
    description TEXT COMMENT 'Описание автомобиля',
    image_url VARCHAR(255) COMMENT 'Путь к изображению',
    gallery TEXT COMMENT 'Галерея изображений (JSON)',
    price_per_hour DECIMAL(10,2) NOT NULL COMMENT 'Цена за час',
    price_per_session DECIMAL(10,2) COMMENT 'Цена за сессию',
    is_available BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_available (is_available),
    INDEX idx_model (model)
) ENGINE=InnoDB COMMENT='Автопарк LADA Drift';

-- =====================================================
-- Таблица пользователей (users)
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL COMMENT 'Email для входа',
    password_hash VARCHAR(255) NOT NULL COMMENT 'Хеш пароля (bcrypt)',
    phone VARCHAR(20) NOT NULL COMMENT 'Телефон',
    full_name VARCHAR(100) NOT NULL COMMENT 'ФИО',
    driver_license VARCHAR(50) COMMENT 'Номер ВУ',
    role ENUM('user', 'admin') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_phone (phone)
) ENGINE=InnoDB COMMENT='Пользователи (клиенты)';

-- =====================================================
-- Таблица услуг (services)
-- =====================================================
CREATE TABLE IF NOT EXISTS services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    slug VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    short_description VARCHAR(255),
    full_description TEXT,
    price_from DECIMAL(10,2),
    price_to DECIMAL(10,2),
    duration VARCHAR(50),
    icon VARCHAR(50),
    image_url VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB COMMENT='Услуги компании';

-- =====================================================
-- Таблица бронирований (bookings)
-- =====================================================
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    driver_license VARCHAR(50),
    user_id INT NULL COMMENT 'ID пользователя (если авторизован)',
    service_type ENUM('car_rental', 'track_rental', 'training', 'certificate', 'corporate') NOT NULL,
    car_id INT,
    booking_date DATE NOT NULL,
    time_slot VARCHAR(50) NOT NULL,
    duration INT DEFAULT 60,
    participants INT DEFAULT 1,
    base_price DECIMAL(10,2),
    discount DECIMAL(10,2) DEFAULT 0,
    total_price DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    payment_id VARCHAR(100),
    status ENUM('pending', 'confirmed', 'cancelled', 'completed', 'no_show') DEFAULT 'pending',
    client_comment TEXT,
    admin_comment TEXT,
    source VARCHAR(50) DEFAULT 'website',
    utm_source VARCHAR(100),
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    confirmed_at TIMESTAMP NULL,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_date (booking_date),
    INDEX idx_phone (phone),
    INDEX idx_created (created_at),
    INDEX idx_bookings_user_id (user_id)
) ENGINE=InnoDB COMMENT='Бронирования услуг';

-- =====================================================
-- Таблица администраторов (admins)
-- =====================================================
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100),
    role ENUM('admin', 'manager', 'viewer') DEFAULT 'manager',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB COMMENT='Администраторы';

-- =====================================================
-- Таблица отзывов (reviews)
-- =====================================================
CREATE TABLE IF NOT EXISTS reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_name VARCHAR(100) NOT NULL,
    avatar_url VARCHAR(255),
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    text TEXT NOT NULL,
    service_type VARCHAR(50),
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_published (is_published),
    INDEX idx_rating (rating)
) ENGINE=InnoDB COMMENT='Отзывы клиентов';

-- =====================================================
-- Таблица FAQ (faq)
-- =====================================================
CREATE TABLE IF NOT EXISTS faq (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,
    category VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB COMMENT='Часто задаваемые вопросы';

-- =====================================================
-- Таблица заявок (contact_requests)
-- =====================================================
CREATE TABLE IF NOT EXISTS contact_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    message TEXT,
    is_processed BOOLEAN DEFAULT FALSE,
    processed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (processed_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Заявки с формы обратной связи';

-- =====================================================
-- Таблица подписчиков (subscribers)
-- =====================================================
CREATE TABLE IF NOT EXISTS subscribers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_active (is_active)
) ENGINE=InnoDB COMMENT='Подписчики на рассылку';

-- =====================================================
-- Таблица временных слотов (time_slots)
-- =====================================================
CREATE TABLE IF NOT EXISTS time_slots (
    id INT PRIMARY KEY AUTO_INCREMENT,
    slot_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    max_bookings INT DEFAULT 1,
    current_bookings INT DEFAULT 0,
    is_available BOOLEAN DEFAULT TRUE,
    UNIQUE KEY unique_slot (slot_date, start_time),
    INDEX idx_date (slot_date),
    INDEX idx_available (is_available)
) ENGINE=InnoDB COMMENT='Временные слоты';

-- =====================================================
-- Начальные данные
-- =====================================================

INSERT INTO cars (model, year, power, engine, transmission, drive, modifications, description, image_url, price_per_hour, price_per_session, sort_order) VALUES
('ВАЗ-2105 VFTS', 1990, 160, '1.6 л', 'МКПП-5', 'задний',
 '["Спортивная подвеска", "Каркас безопасности", "Блокировка дифференциала", "Гидравлический ручник", "Ковшеобразные сиденья", "6-точечные ремни"]',
 'Легендарная «Пятёрка-ветеран» — классика советского автопрома в дрифт-исполнении.',
 'assets/images/fleet-card__image.webp', 2800, 1600, 1),
('ВАЗ-2107 Семёрка-Сатана', 1991, 140, '1.7 л', 'МКПП-5', 'задний',
 '["Турбонаддув", "Спортивный выхлоп", "Регулируемая подвеска", "Гидроручник", "6-точечные ремни", "Быстрая рулевая рейка"]',
 '«Семёрка-Сатана» — культовая классика с турбонаддувом.',
 'assets/images/fleet-card__image.jpg.webp', 2500, 1400, 2),
('LADA Priora', 2010, 150, '1.6 л', 'МКПП-5', 'задний',
 '["Турбомотор", "Полный каркас", "Гидроручник", "Сварной дифференциал", "Спортивные амортизаторы", "Полиуретановые сайлентблоки"]',
 '«Приора-прорыв» — современная классика.',
 'assets/images/fleet-card__image.jpg', 3200, 1800, 3),
('LADA Granta Sport', 2023, 120, '1.6 л', 'МКПП-5', 'задний',
 '["Отключаемый передний мост", "Лифт подвески", "Грязевая резина", "Шноркель", "Защита днища", "Лебёдка"]',
 '«Грант-громовержец» — идеальный выбор для начинающих.',
 'assets/images/car-card__image.jpg', 3500, 2000, 4),
('LADA Niva Legend', 2023, 125, '1.7 л', 'МКПП-5', 'полный',
 '["Отключаемый передний мост", "Лифт подвески", "Грязевая резина", "Шноркель", "Защита днища", "Лебёдка"]',
 '«Нива-наездник» — уникальный внедорожный дрифт.',
 'assets/images/car-card__image4.jpg', 3800, 2100, 5),
('ВАЗ-2107 Семёрка-Шериф', 1992, 130, '1.6 л', 'МКПП-5', 'задний',
 '["Облегчённый кузов", "Жёсткая подвеска", "Гидроручник", "Быстрая рулевая рейка", "Спортивные сиденья"]',
 '«Семёрка-шериф» — облегчённая классика.',
 'assets/images/car-card__image3.jpg', 2700, 1500, 6);

INSERT INTO services (slug, name, short_description, price_from, duration, icon, sort_order) VALUES
('car-rental', 'Аренда авто для дрифта', 'Выберите автомобиль из нашего автопарка!', 2000, '30 мин', 'fa-car', 1),
('track-rental', 'Аренда трека', 'Закрытая площадка для вашей компании', 15000, '1 час', 'fa-road', 2),
('training', 'Обучение дрифту', 'Индивидуальные и групповые занятия', 5000, '1 час', 'fa-graduation-cap', 3),
('certificate', 'Подарочный сертификат', 'Идеальный подарок для любителя скорости', 3000, 'без ограничений', 'fa-gift', 4),
('corporate', 'Корпоративные мероприятия', 'Тимбилдинг с дрифтом', 50000, 'от 3 часов', 'fa-users', 5);

INSERT INTO admins (username, password_hash, email, full_name, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@ladadrift.ru', 'Администратор', 'admin');

INSERT INTO reviews (client_name, rating, text, service_type, is_published) VALUES
('Алексей М.', 5, 'Невероятные эмоции! Первый раз в жизни дрифтовал. Обязательно вернусь!', 'training', TRUE),
('Дмитрий К.', 5, 'Арендовал Гранту на день рождения друга. Он был в восторге!', 'car_rental', TRUE),
('Мария С.', 5, 'Подарила мужу сертификат на дрифт. Лучший подарок!', 'certificate', TRUE),
('Игорь В.', 4, 'Отличный трек, хорошие машины.', 'track_rental', TRUE),
('Анна Л.', 5, 'Корпоратив прошел на ура!', 'corporate', TRUE);

INSERT INTO faq (question, answer, category, sort_order) VALUES
('Нужны ли водительские права для участия?', 'Да, для управления автомобилем необходимы права категории B.', 'general', 1),
('Какой опыт вождения нужен?', 'Для обучения достаточно базовых навыков вождения.', 'general', 2),
('Есть ли ограничения по возрасту?', 'Минимальный возраст для водителя - 18 лет.', 'general', 3),
('Что включено в стоимость аренды?', 'Автомобиль, топливо, шлем, инструктаж, страховка.', 'price', 4),
('Как происходит бронирование?', 'Выберите услугу, дату и время на сайте, заполните форму.', 'booking', 5),
('Можно ли отменить бронирование?', 'Бесплатная отмена за 24 часа до начала.', 'booking', 6),
('Предоставляете ли вы страховку?', 'Да, все участники застрахованы.', 'safety', 7),
('Где находится трек?', 'Московская область, г. Дмитров, ул. Промышленная, 15.', 'location', 8);
