<?php
/**
 * Модель Booking - Бронирование
 * Работа с заявками на бронирование LADA Drift
 * 
 * @package LadaDrift
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Car.php';

class Booking {
    // Типы услуг
    public const SERVICE_CAR_RENTAL = 'car_rental';
    public const SERVICE_TRACK_RENTAL = 'track_rental';
    public const SERVICE_TRAINING = 'training';
    public const SERVICE_CERTIFICATE = 'certificate';
    public const SERVICE_CORPORATE = 'corporate';
    
    // Статусы бронирования
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_NO_SHOW = 'no_show';
    
    // Статусы оплаты
    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_REFUNDED = 'refunded';
    
    // Свойства
    private ?int $id = null;
    private string $clientName = '';
    private string $phone = '';
    private ?string $email = null;
    private ?string $driverLicense = null;
    private ?int $userId = null;
    private string $serviceType = self::SERVICE_CAR_RENTAL;
    private ?int $carId = null;
    private string $bookingDate = '';
    private string $timeSlot = '';
    private int $duration = 60;
    private int $participants = 1;
    private ?float $basePrice = null;
    private float $discount = 0;
    private float $totalPrice = 0;
    private string $paymentStatus = self::PAYMENT_PENDING;
    private ?string $paymentId = null;
    private string $status = self::STATUS_PENDING;
    private ?string $clientComment = null;
    private ?string $adminComment = null;
    private string $source = 'website';
    private ?string $utmSource = null;
    private ?string $ipAddress = null;
    private ?string $userAgent = null;
    private ?string $createdAt = null;
    private ?string $confirmedAt = null;
    
    // Связанные объекты
    private ?Car $car = null;
    
    /**
     * Конструктор
     */
    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->fill($data);
        }
    }
    
    /**
     * Заполнение свойств из массива
     */
    public function fill(array $data): self {
        $this->id = $data['id'] ?? null;
        $this->clientName = $data['client_name'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->email = $data['email'] ?? null;
        $this->driverLicense = $data['driver_license'] ?? null;
        $this->userId = isset($data['user_id']) ? (int)$data['user_id'] : null;
        $this->serviceType = $data['service_type'] ?? self::SERVICE_CAR_RENTAL;
        $this->carId = isset($data['car_id']) ? (int)$data['car_id'] : null;
        $this->bookingDate = $data['booking_date'] ?? '';
        $this->timeSlot = $data['time_slot'] ?? '';
        $this->duration = (int)($data['duration'] ?? 60);
        $this->participants = (int)($data['participants'] ?? 1);
        $this->basePrice = isset($data['base_price']) ? (float)$data['base_price'] : null;
        $this->discount = (float)($data['discount'] ?? 0);
        $this->totalPrice = (float)($data['total_price'] ?? 0);
        $this->paymentStatus = $data['payment_status'] ?? self::PAYMENT_PENDING;
        $this->paymentId = $data['payment_id'] ?? null;
        $this->status = $data['status'] ?? self::STATUS_PENDING;
        $this->clientComment = $data['client_comment'] ?? null;
        $this->adminComment = $data['admin_comment'] ?? null;
        $this->source = $data['source'] ?? 'website';
        $this->utmSource = $data['utm_source'] ?? null;
        $this->ipAddress = $data['ip_address'] ?? null;
        $this->userAgent = $data['user_agent'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
        $this->confirmedAt = $data['confirmed_at'] ?? null;
        
        return $this;
    }
    
    /**
     * Создание нового бронирования из данных формы
     * 
     * @param array $formData Данные из формы
     * @return self
     */
    public static function createFromForm(array $formData, ?int $userId = null): self {
        $booking = new self();
        
        $booking->userId = $userId;

        // Заполняем данные клиента
        $booking->clientName = self::sanitize($formData['client_name'] ?? '');
        $booking->phone = self::sanitizePhone($formData['phone'] ?? '');
        $booking->email = isset($formData['email']) ? filter_var($formData['email'], FILTER_SANITIZE_EMAIL) : null;
        $booking->driverLicense = self::sanitize($formData['driver_license'] ?? '');
        
        // Данные бронирования
        $booking->serviceType = $formData['service_type'] ?? self::SERVICE_CAR_RENTAL;
        $booking->carId = isset($formData['car_id']) ? (int)$formData['car_id'] : null;
        $booking->bookingDate = $formData['booking_date'] ?? '';
        $booking->timeSlot = $formData['time_slot'] ?? '';
        $booking->duration = (int)($formData['duration'] ?? 60);
        $booking->participants = (int)($formData['participants'] ?? 1);
        $booking->clientComment = self::sanitize($formData['comment'] ?? '');
        
        // Метаданные
        $booking->ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $booking->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $booking->utmSource = self::sanitize($formData['utm_source'] ?? '');
        
        // Расчёт цены
        $booking->calculatePrice();
        
        return $booking;
    }
    
    /**
     * Расчёт стоимости бронирования
     */
    public function calculatePrice(): self {
        $basePrice = 0;
        
        switch ($this->serviceType) {
            case self::SERVICE_CAR_RENTAL:
                // Если выбран автомобиль, берём его цену
                if ($this->carId) {
                    $car = Car::getById($this->carId);
                    if ($car) {
                        $basePrice = ($this->duration / 60) * $car->getPricePerHour();
                    }
                }
                break;
                
            case self::SERVICE_TRACK_RENTAL:
                // Аренда трека - 15000₽/час
                $basePrice = ($this->duration / 60) * 15000;
                break;
                
            case self::SERVICE_TRAINING:
                // Обучение - 5000₽/час
                $basePrice = ($this->duration / 60) * 5000;
                break;
                
            case self::SERVICE_CERTIFICATE:
                // Сертификат - фиксированная цена
                $basePrice = 3000;
                break;
                
            case self::SERVICE_CORPORATE:
                // Корпоратив - от 50000₽
                $basePrice = 50000 * $this->participants;
                break;
        }
        
        $this->basePrice = $basePrice;
        $this->totalPrice = $basePrice - $this->discount;
        
        return $this;
    }
    
    /**
     * Валидация данных бронирования
     * 
     * @return array Массив ошибок (пустой, если валидация прошла)
     */
    public function validate(): array {
        $errors = [];
        
        // Проверка имени
        if (empty($this->clientName) || mb_strlen($this->clientName) < 2) {
            $errors['client_name'] = 'Введите корректное имя (минимум 2 символа)';
        }
        
        // Проверка телефона
        if (!preg_match('/^\+7\d{10}$/', $this->phone)) {
            $errors['phone'] = 'Введите телефон в формате +7XXXXXXXXXX';
        }
        
        // Проверка email (если указан)
        if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введите корректный email';
        }
        
        // Проверка типа услуги
        $validServices = [
            self::SERVICE_CAR_RENTAL,
            self::SERVICE_TRACK_RENTAL,
            self::SERVICE_TRAINING,
            self::SERVICE_CERTIFICATE,
            self::SERVICE_CORPORATE
        ];
        
        if (!in_array($this->serviceType, $validServices)) {
            $errors['service_type'] = 'Выберите корректный тип услуги';
        }
        
        // Проверка автомобиля для аренды
        if ($this->serviceType === self::SERVICE_CAR_RENTAL && !$this->carId) {
            $errors['car_id'] = 'Выберите автомобиль';
        }
        
        // Проверка даты
        if (empty($this->bookingDate)) {
            $errors['booking_date'] = 'Выберите дату';
        } else {
            $date = new DateTime($this->bookingDate);
            $today = new DateTime('today');
            
            if ($date < $today) {
                $errors['booking_date'] = 'Дата не может быть в прошлом';
            }
        }
        
        // Проверка временного слота
        if (empty($this->timeSlot)) {
            $errors['time_slot'] = 'Выберите время';
        }
        
        // Проверка водительских прав для аренды авто
        if ($this->serviceType === self::SERVICE_CAR_RENTAL && empty($this->driverLicense)) {
            $errors['driver_license'] = 'Укажите номер водительского удостоверения';
        }
        
        return $errors;
    }
    
    /**
     * Сохранение бронирования в БД
     */
    public function save(): bool {
        $data = [
            'client_name' => $this->clientName,
            'phone' => $this->phone,
            'email' => $this->email,
            'driver_license' => $this->driverLicense,
            'user_id' => $this->userId,
            'service_type' => $this->serviceType,
            'car_id' => $this->carId,
            'booking_date' => $this->bookingDate,
            'time_slot' => $this->timeSlot,
            'duration' => $this->duration,
            'participants' => $this->participants,
            'base_price' => $this->basePrice,
            'discount' => $this->discount,
            'total_price' => $this->totalPrice,
            'payment_status' => $this->paymentStatus,
            'payment_id' => $this->paymentId,
            'status' => $this->status,
            'client_comment' => $this->clientComment,
            'admin_comment' => $this->adminComment,
            'source' => $this->source,
            'utm_source' => $this->utmSource,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent
        ];
        
        if ($this->id) {
            // Обновление
            $sets = [];
            foreach (array_keys($data) as $key) {
                $sets[] = "{$key} = :{$key}";
            }
            $data['id'] = $this->id;
            
            $sql = "UPDATE bookings SET " . implode(', ', $sets) . ", updated_at = NOW() WHERE id = :id";
            Database::execute($sql, $data);
            
        } else {
            // Создание
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            
            $sql = "INSERT INTO bookings ({$columns}) VALUES ({$placeholders})";
            Database::execute($sql, $data);
            
            $this->id = (int)Database::lastInsertId();
        }
        
        return true;
    }
    
    /**
     * Подтверждение бронирования
     */
    public function confirm(): bool {
        $this->status = self::STATUS_CONFIRMED;
        $this->confirmedAt = date('Y-m-d H:i:s');
        return $this->save();
    }
    
    /**
     * Отмена бронирования
     */
    public function cancel(): bool {
        $this->status = self::STATUS_CANCELLED;
        return $this->save();
    }
    
    /**
     * Завершение бронирования
     */
    public function complete(): bool {
        $this->status = self::STATUS_COMPLETED;
        return $this->save();
    }
    
    /**
     * Получение бронирования по ID
     */
    public static function getById(int $id): ?self {
        $stmt = Database::execute(
            "SELECT * FROM bookings WHERE id = :id LIMIT 1",
            ['id' => $id]
        );
        
        $data = $stmt->fetch();
        return $data ? new self($data) : null;
    }
    
    /**
     * Получение бронирований с фильтрацией
     */
    public static function getFiltered(array $filters = [], int $limit = 50, int $offset = 0): array {
        $where = [];
        $params = [];
        
        // Фильтр по статусу
        if (!empty($filters['status'])) {
            $where[] = "status = :status";
            $params['status'] = $filters['status'];
        }
        
        // Фильтр по дате
        if (!empty($filters['date_from'])) {
            $where[] = "booking_date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where[] = "booking_date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        // Фильтр по типу услуги
        if (!empty($filters['service_type'])) {
            $where[] = "service_type = :service_type";
            $params['service_type'] = $filters['service_type'];
        }
        
        // Поиск по телефону
        if (!empty($filters['phone'])) {
            $where[] = "phone LIKE :phone";
            $params['phone'] = '%' . $filters['phone'] . '%';
        }
        
        $sql = "SELECT * FROM bookings";
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}";
        
        $stmt = Database::execute($sql, $params);
        
        $bookings = [];
        while ($row = $stmt->fetch()) {
            $bookings[] = new self($row);
        }
        
        return $bookings;
    }

    /**
     * Получение бронирований пользователя
     */
    public static function getByUserId(int $userId, int $limit = 50, int $offset = 0): array {
        $stmt = Database::execute(
            "SELECT * FROM bookings WHERE user_id = :user_id ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}",
            ['user_id' => $userId]
        );
        $bookings = [];
        while ($row = $stmt->fetch()) {
            $bookings[] = new self($row);
        }
        return $bookings;
    }
    
    /**
     * Получение доступных временных слотов на дату
     */
    public static function getAvailableSlots(string $date, ?int $carId = null): array {
        // Генерируем все возможные слоты (10:00 - 21:00, каждый час)
        $allSlots = [];
        for ($hour = 10; $hour <= 20; $hour++) {
            $start = sprintf('%02d:00', $hour);
            $end = sprintf('%02d:00', $hour + 1);
            $allSlots[] = "{$start}-{$end}";
        }
        
        // Получаем занятые слоты
        $sql = "SELECT time_slot FROM bookings 
                WHERE booking_date = :date 
                AND status NOT IN ('cancelled')";
        $params = ['date' => $date];
        
        if ($carId) {
            $sql .= " AND car_id = :car_id";
            $params['car_id'] = $carId;
        }
        
        $stmt = Database::execute($sql, $params);
        $bookedSlots = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Возвращаем только свободные
        return array_values(array_diff($allSlots, $bookedSlots));
    }
    
    /**
     * Преобразование в массив
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'client_name' => $this->clientName,
            'phone' => $this->phone,
            'email' => $this->email,
            'driver_license' => $this->driverLicense,
            'service_type' => $this->serviceType,
            'service_type_label' => $this->getServiceTypeLabel(),
            'car_id' => $this->carId,
            'car' => $this->getCar()?->toArray(),
            'booking_date' => $this->bookingDate,
            'booking_date_formatted' => $this->getFormattedDate(),
            'time_slot' => $this->timeSlot,
            'duration' => $this->duration,
            'participants' => $this->participants,
            'base_price' => $this->basePrice,
            'discount' => $this->discount,
            'total_price' => $this->totalPrice,
            'total_price_formatted' => $this->getFormattedPrice(),
            'payment_status' => $this->paymentStatus,
            'payment_id' => $this->paymentId,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'client_comment' => $this->clientComment,
            'admin_comment' => $this->adminComment,
            'created_at' => $this->createdAt
        ];
    }
    
    // ============================================
    // Геттеры
    // ============================================
    
    public function getId(): ?int { return $this->id; }
    public function getClientName(): string { return $this->clientName; }
    public function getPhone(): string { return $this->phone; }
    public function getEmail(): ?string { return $this->email; }
    public function getServiceType(): string { return $this->serviceType; }
    public function getCarId(): ?int { return $this->carId; }
    public function getUserId(): ?int { return $this->userId; }
    public function getBookingDate(): string { return $this->bookingDate; }
    public function getTimeSlot(): string { return $this->timeSlot; }
    public function getTotalPrice(): float { return $this->totalPrice; }
    public function getStatus(): string { return $this->status; }
    
    /**
     * Получение связанного автомобиля
     */
    public function getCar(): ?Car {
        if ($this->car === null && $this->carId) {
            $this->car = Car::getById($this->carId);
        }
        return $this->car;
    }
    
    /**
     * Человекочитаемое название типа услуги
     */
    public function getServiceTypeLabel(): string {
        $labels = [
            self::SERVICE_CAR_RENTAL => 'Аренда авто',
            self::SERVICE_TRACK_RENTAL => 'Аренда трека',
            self::SERVICE_TRAINING => 'Обучение',
            self::SERVICE_CERTIFICATE => 'Сертификат',
            self::SERVICE_CORPORATE => 'Корпоратив'
        ];
        
        return $labels[$this->serviceType] ?? $this->serviceType;
    }
    
    /**
     * Человекочитаемый статус
     */
    public function getStatusLabel(): string {
        $labels = [
            self::STATUS_PENDING => 'Ожидает подтверждения',
            self::STATUS_CONFIRMED => 'Подтверждено',
            self::STATUS_CANCELLED => 'Отменено',
            self::STATUS_COMPLETED => 'Завершено',
            self::STATUS_NO_SHOW => 'Неявка'
        ];
        
        return $labels[$this->status] ?? $this->status;
    }
    
    /**
     * Форматированная цена
     */
    public function getFormattedPrice(): string {
        return number_format($this->totalPrice, 0, ',', ' ') . ' ₽';
    }
    
    /**
     * Форматированная дата
     */
    public function getFormattedDate(): string {
        if (empty($this->bookingDate)) return '';
        
        $date = new DateTime($this->bookingDate);
        $formatter = new IntlDateFormatter(
            'ru_RU',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );
        
        return $formatter->format($date);
    }
    
    // ============================================
    // Сеттеры
    // ============================================
    
    public function setDiscount(float $discount): self {
        $this->discount = $discount;
        $this->totalPrice = $this->basePrice - $discount;
        return $this;
    }
    
    public function setAdminComment(?string $comment): self {
        $this->adminComment = $comment;
        return $this;
    }
    
    // ============================================
    // Утилиты
    // ============================================
    
    /**
     * Очистка строки от XSS
     */
    private static function sanitize(?string $value): ?string {
        if ($value === null) return null;
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Нормализация телефона
     */
    private static function sanitizePhone(string $phone): string {
        $digits = preg_replace('/\D/', '', $phone);
        
        if (strlen($digits) === 11 && $digits[0] === '8') {
            $digits = '7' . substr($digits, 1);
        }
        
        return '+' . $digits;
    }
}
