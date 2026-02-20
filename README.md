# LADA Drift - Дрифт на автомобилях LADA

Полноценный веб-сайт для компании по дрифту на отечественных автомобилях LADA.

## Структура проекта

```
/lada-drift/
├── index.html                 # Главная страница
├── README.md                  # Документация
│
├── css/
│   ├── fonts.css              # Подключение шрифтов
│   ├── style.css              # Основные стили
│   └── responsive.css         # Адаптивные стили
│
├── js/
│   ├── main.js                # Основной JavaScript
│   ├── booking.js             # Логика бронирования
│   └── gallery.js             # Галерея автопарка
│
├── php/
│   ├── config/
│   │   └── database.php       # Конфигурация БД (PDO Singleton)
│   ├── models/
│   │   ├── Car.php            # Модель автомобиля
│   │   └── Booking.php        # Модель бронирования
│   ├── process_booking.php    # Обработка формы бронирования (POST)
│   ├── login.php              # Вход
│   ├── register.php           # Регистрация
│   ├── logout.php             # Выход
│   ├── cabinet.php            # Личный кабинет
│   └── includes/
│       ├── header.php         # Шапка сайта
│       └── footer.php         # Подвал сайта
│
├── pages/
│   ├── services.html          # Страница услуг
│   ├── fleet.html             # Страница автопарка
│   ├── about.html             # О компании
│   ├── contacts.html          # Контакты
│   └── booking.php            # Форма бронирования
│
├── admin/
│   ├── login.php              # Вход в админку
│   ├── dashboard.php          # Главная админки
│   ├── manage_bookings.php    # Управление бронированиями
│   └── logout.php             # Выход
│
├── database/
│   └── schema.sql             # Полная схема БД (таблицы + данные)
│
└── assets/
    └── images/                # Изображения (logo/, и др.)
```

## Требования

- PHP 7.4+
- MySQL 5.7+ / MariaDB 10.3+
- Веб-сервер (Apache/Nginx)
- Поддержка PDO

## Установка

### 1. Клонирование репозитория

```bash
git clone https://github.com/Mariya1820205/LadaDrift.git
cd LadaDrift
```

### 2. Настройка базы данных

```sql
-- Создайте базу данных
CREATE DATABASE lada_drift CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Импортируйте схему
mysql -u username -p lada_drift < database/schema.sql
```

### 3. Конфигурация

Скопируйте шаблон и настройте подключение к БД:

```bash
cp php/config/database.php.example php/config/database.php
```

Отредактируйте `php/config/database.php` и укажите учётные данные MySQL.

### 4. Права доступа

```bash
# Права на запись для логов (если используются)
chmod 755 /path/to/lada-drift
```

### 5. Настройка веб-сервера

#### Apache (.htaccess)
```apache
RewriteEngine On
RewriteBase /

# Убираем index.php из URL
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [L]
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
}
```

## Админ-панель

- URL: `/admin/login.php`
- Логин по умолчанию: `admin`
- Пароль по умолчанию: `admin123` (СМЕНИТЬ!)

### Смена пароля администратора

```php
// Сгенерируйте новый хеш
$newPassword = 'your_new_password';
$hash = password_hash($newPassword, PASSWORD_DEFAULT);
// Обновите в БД
```

## Функционал

### Клиентская часть
- Адаптивный дизайн (mobile-first)
- Многошаговая форма бронирования
- Галерея автопарка с фильтрами
- Слайдер отзывов (Swiper.js)
- FAQ аккордеон
- Анимации при скролле
- Валидация форм
- Маска телефона

### Админ-панель
- Авторизация
- Статистика (дашборд)
- Управление бронированиями
- Экспорт в Excel (CSV)

## Цветовая палитра

```css
--color-black: #000000;       /* Основной фон */
--color-accent: #E30613;      /* Красный LADA */
--color-cta: #FF9500;         /* Оранжевый CTA */
--color-white: #FFFFFF;       /* Текст */
--color-gray-dark: #1A1A1A;   /* Секции */
--color-gray: #333333;        /* Карточки */
```

## TODO (Дополнительно)

- [ ] Интеграция с ЮКасса/Тинькофф для оплаты
- [ ] Telegram-бот для уведомлений
- [ ] Email-уведомления (PHPMailer)
- [ ] Генерация PDF-сертификатов
- [ ] Личный кабинет клиента
- [ ] Расширенная аналитика

## Технологии

- HTML5, CSS3 (Custom Properties, Flexbox, Grid)
- JavaScript ES6+ (Vanilla JS)
- PHP 7.4+ (ООП, PDO)
- MySQL 5.7+
- Swiper.js (слайдеры)
- Font Awesome (иконки)
- Google Fonts (Montserrat, Roboto)

## Публикация на GitHub

- **README.md** — описание проекта, инструкция по установке
- **.gitignore** — исключает `node_modules/`, `vendor/`, `.env`, `php/config/database.php`, логи, системные файлы

> ⚠️ Файл `php/config/database.php` не попадает в репозиторий (учётные данные БД). При клонировании используйте `database.php.example` как шаблон.

## Лицензия

Проект создан для демонстрационных целей.

---

**LADA Drift** - Дай боком! 🚗💨
