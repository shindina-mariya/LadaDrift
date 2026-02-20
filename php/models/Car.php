<?php
/**
 * Модель Car - Автомобиль
 * Работа с данными автопарка LADA Drift
 * 
 * @package LadaDrift
 */

require_once __DIR__ . '/../config/database.php';

class Car {
    // Свойства автомобиля
    private ?int $id = null;
    private string $model = '';
    private int $year = 0;
    private int $power = 0;
    private string $engine = '';
    private string $transmission = '';
    private string $drive = 'задний';
    private ?array $modifications = null;
    private ?string $description = null;
    private ?string $imageUrl = null;
    private ?array $gallery = null;
    private float $pricePerHour = 0;
    private ?float $pricePerSession = null;
    private bool $isAvailable = true;
    private int $sortOrder = 0;
    
    /**
     * Конструктор - заполняет свойства из массива данных
     * 
     * @param array $data Данные автомобиля
     */
    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->fill($data);
        }
    }
    
    /**
     * Заполнение свойств из массива
     * 
     * @param array $data Данные для заполнения
     * @return self
     */
    public function fill(array $data): self {
        $this->id = $data['id'] ?? null;
        $this->model = $data['model'] ?? '';
        $this->year = (int)($data['year'] ?? 0);
        $this->power = (int)($data['power'] ?? 0);
        $this->engine = $data['engine'] ?? '';
        $this->transmission = $data['transmission'] ?? '';
        $this->drive = $data['drive'] ?? 'задний';
        $this->description = $data['description'] ?? null;
        $this->imageUrl = $data['image_url'] ?? null;
        $this->pricePerHour = (float)($data['price_per_hour'] ?? 0);
        $this->pricePerSession = isset($data['price_per_session']) ? (float)$data['price_per_session'] : null;
        $this->isAvailable = (bool)($data['is_available'] ?? true);
        $this->sortOrder = (int)($data['sort_order'] ?? 0);
        
        // JSON поля
        $this->modifications = isset($data['modifications']) 
            ? (is_string($data['modifications']) ? json_decode($data['modifications'], true) : $data['modifications'])
            : null;
            
        $this->gallery = isset($data['gallery']) 
            ? (is_string($data['gallery']) ? json_decode($data['gallery'], true) : $data['gallery'])
            : null;
            
        return $this;
    }
    
    /**
     * Получение всех доступных автомобилей
     * 
     * @param bool $onlyAvailable Только доступные для бронирования
     * @return array Массив объектов Car
     */
    public static function getAll(bool $onlyAvailable = false): array {
        $sql = "SELECT * FROM cars";
        
        if ($onlyAvailable) {
            $sql .= " WHERE is_available = 1";
        }
        
        $sql .= " ORDER BY sort_order ASC, id ASC";
        
        $stmt = Database::query($sql);
        $cars = [];
        
        while ($row = $stmt->fetch()) {
            $cars[] = new self($row);
        }
        
        return $cars;
    }
    
    /**
     * Получение автомобиля по ID
     * 
     * @param int $id ID автомобиля
     * @return Car|null Объект автомобиля или null
     */
    public static function getById(int $id): ?self {
        $stmt = Database::execute(
            "SELECT * FROM cars WHERE id = :id LIMIT 1",
            ['id' => $id]
        );
        
        $data = $stmt->fetch();
        
        return $data ? new self($data) : null;
    }
    
    /**
     * Получение автомобилей по модели
     * 
     * @param string $model Название модели
     * @return array Массив объектов Car
     */
    public static function getByModel(string $model): array {
        $stmt = Database::execute(
            "SELECT * FROM cars WHERE model LIKE :model ORDER BY sort_order ASC",
            ['model' => "%{$model}%"]
        );
        
        $cars = [];
        while ($row = $stmt->fetch()) {
            $cars[] = new self($row);
        }
        
        return $cars;
    }
    
    /**
     * Сохранение автомобиля (создание или обновление)
     * 
     * @return bool Успешность операции
     */
    public function save(): bool {
        $data = [
            'model' => $this->model,
            'year' => $this->year,
            'power' => $this->power,
            'engine' => $this->engine,
            'transmission' => $this->transmission,
            'drive' => $this->drive,
            'modifications' => $this->modifications ? json_encode($this->modifications, JSON_UNESCAPED_UNICODE) : null,
            'description' => $this->description,
            'image_url' => $this->imageUrl,
            'gallery' => $this->gallery ? json_encode($this->gallery, JSON_UNESCAPED_UNICODE) : null,
            'price_per_hour' => $this->pricePerHour,
            'price_per_session' => $this->pricePerSession,
            'is_available' => $this->isAvailable ? 1 : 0,
            'sort_order' => $this->sortOrder
        ];
        
        if ($this->id) {
            // Обновление существующего
            $sets = [];
            foreach (array_keys($data) as $key) {
                $sets[] = "{$key} = :{$key}";
            }
            $data['id'] = $this->id;
            
            $sql = "UPDATE cars SET " . implode(', ', $sets) . " WHERE id = :id";
            Database::execute($sql, $data);
            
        } else {
            // Создание нового
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            
            $sql = "INSERT INTO cars ({$columns}) VALUES ({$placeholders})";
            Database::execute($sql, $data);
            
            $this->id = (int)Database::lastInsertId();
        }
        
        return true;
    }
    
    /**
     * Удаление автомобиля
     * 
     * @return bool Успешность операции
     */
    public function delete(): bool {
        if (!$this->id) {
            return false;
        }
        
        Database::execute("DELETE FROM cars WHERE id = :id", ['id' => $this->id]);
        return true;
    }
    
    /**
     * Проверка доступности автомобиля на определённую дату
     * 
     * @param string $date Дата в формате Y-m-d
     * @param string $timeSlot Временной слот
     * @return bool Доступен ли автомобиль
     */
    public function isAvailableAt(string $date, string $timeSlot): bool {
        if (!$this->isAvailable) {
            return false;
        }
        
        // Проверяем, есть ли бронирования на это время
        $stmt = Database::execute(
            "SELECT COUNT(*) as count FROM bookings 
             WHERE car_id = :car_id 
             AND booking_date = :date 
             AND time_slot = :time_slot 
             AND status NOT IN ('cancelled')",
            [
                'car_id' => $this->id,
                'date' => $date,
                'time_slot' => $timeSlot
            ]
        );
        
        $result = $stmt->fetch();
        return $result['count'] == 0;
    }
    
    /**
     * Преобразование в массив для JSON
     * 
     * @return array
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'model' => $this->model,
            'year' => $this->year,
            'power' => $this->power,
            'engine' => $this->engine,
            'transmission' => $this->transmission,
            'drive' => $this->drive,
            'modifications' => $this->modifications,
            'description' => $this->description,
            'image_url' => $this->imageUrl,
            'gallery' => $this->gallery,
            'price_per_hour' => $this->pricePerHour,
            'price_per_session' => $this->pricePerSession,
            'is_available' => $this->isAvailable,
            'sort_order' => $this->sortOrder
        ];
    }
    
    /**
     * Преобразование в JSON
     * 
     * @return string
     */
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
    
    // ============================================
    // Геттеры
    // ============================================
    
    public function getId(): ?int { return $this->id; }
    public function getModel(): string { return $this->model; }
    public function getYear(): int { return $this->year; }
    public function getPower(): int { return $this->power; }
    public function getEngine(): string { return $this->engine; }
    public function getTransmission(): string { return $this->transmission; }
    public function getDrive(): string { return $this->drive; }
    public function getModifications(): ?array { return $this->modifications; }
    public function getDescription(): ?string { return $this->description; }
    public function getImageUrl(): ?string { return $this->imageUrl; }
    public function getGallery(): ?array { return $this->gallery; }
    public function getPricePerHour(): float { return $this->pricePerHour; }
    public function getPricePerSession(): ?float { return $this->pricePerSession; }
    public function getIsAvailable(): bool { return $this->isAvailable; }
    public function getSortOrder(): int { return $this->sortOrder; }
    
    /**
     * Форматированная цена за час
     */
    public function getFormattedPricePerHour(): string {
        return number_format($this->pricePerHour, 0, ',', ' ') . ' ₽';
    }
    
    /**
     * Форматированная цена за сессию
     */
    public function getFormattedPricePerSession(): ?string {
        if ($this->pricePerSession === null) {
            return null;
        }
        return number_format($this->pricePerSession, 0, ',', ' ') . ' ₽';
    }
    
    // ============================================
    // Сеттеры
    // ============================================
    
    public function setModel(string $model): self { $this->model = $model; return $this; }
    public function setYear(int $year): self { $this->year = $year; return $this; }
    public function setPower(int $power): self { $this->power = $power; return $this; }
    public function setEngine(string $engine): self { $this->engine = $engine; return $this; }
    public function setTransmission(string $transmission): self { $this->transmission = $transmission; return $this; }
    public function setDrive(string $drive): self { $this->drive = $drive; return $this; }
    public function setModifications(?array $modifications): self { $this->modifications = $modifications; return $this; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function setImageUrl(?string $imageUrl): self { $this->imageUrl = $imageUrl; return $this; }
    public function setGallery(?array $gallery): self { $this->gallery = $gallery; return $this; }
    public function setPricePerHour(float $price): self { $this->pricePerHour = $price; return $this; }
    public function setPricePerSession(?float $price): self { $this->pricePerSession = $price; return $this; }
    public function setIsAvailable(bool $available): self { $this->isAvailable = $available; return $this; }
    public function setSortOrder(int $order): self { $this->sortOrder = $order; return $this; }
}
