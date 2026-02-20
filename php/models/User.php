<?php
/**
 * Модель User - Пользователь (клиент)
 * Регистрация, авторизация, профиль
 *
 * @package LadaDrift
 */

require_once __DIR__ . '/../config/database.php';

class User
{
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    private ?int $id = null;
    private string $email = '';
    private string $passwordHash = '';
    private string $phone = '';
    private string $fullName = '';
    private ?string $driverLicense = null;
    private string $role = self::ROLE_USER;
    private bool $isActive = true;
    private ?string $createdAt = null;
    private ?string $updatedAt = null;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data): self
    {
        $this->id = isset($data['id']) ? (int) $data['id'] : null;
        $this->email = $data['email'] ?? '';
        $this->passwordHash = $data['password_hash'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->fullName = $data['full_name'] ?? '';
        $this->driverLicense = $data['driver_license'] ?? null;
        $this->role = $data['role'] ?? self::ROLE_USER;
        $this->isActive = (bool) ($data['is_active'] ?? true);
        $this->createdAt = $data['created_at'] ?? null;
        $this->updatedAt = $data['updated_at'] ?? null;

        return $this;
    }

    public static function getById(int $id): ?self
    {
        $stmt = Database::execute(
            'SELECT * FROM users WHERE id = :id AND is_active = 1 LIMIT 1',
            ['id' => $id]
        );
        $row = $stmt->fetch();

        return $row ? new self($row) : null;
    }

    public static function getByEmail(string $email): ?self
    {
        $stmt = Database::execute(
            'SELECT * FROM users WHERE email = :email LIMIT 1',
            ['email' => strtolower(trim($email))]
        );
        $row = $stmt->fetch();

        return $row ? new self($row) : null;
    }

    public static function register(array $data): array
    {
        $errors = self::validateRegistration($data);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $email = strtolower(trim($data['email']));
        $existing = self::getByEmail($email);

        if ($existing) {
            return ['success' => false, 'errors' => ['email' => 'Пользователь с таким email уже зарегистрирован']];
        }

        $phone = self::sanitizePhone($data['phone'] ?? '');

        if (!preg_match('/^\+7\d{10}$/', $phone)) {
            return ['success' => false, 'errors' => ['phone' => 'Введите телефон в формате +7XXXXXXXXXX']];
        }

        $user = new self();
        $user->email = $email;
        $user->passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        $user->phone = $phone;
        $user->fullName = self::sanitize($data['full_name'] ?? '');
        $user->driverLicense = !empty($data['driver_license']) ? self::sanitize($data['driver_license']) : null;
        $user->role = self::ROLE_USER;

        $user->save();

        return ['success' => true, 'user' => $user];
    }

    public static function validateRegistration(array $data): array
    {
        $errors = [];

        if (empty(trim($data['email'] ?? ''))) {
            $errors['email'] = 'Введите email';
        } elseif (!filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный email';
        }

        if (empty($data['password'] ?? '')) {
            $errors['password'] = 'Введите пароль';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'Пароль должен быть не менее 6 символов';
        }

        if (empty(trim($data['full_name'] ?? '')) || mb_strlen(trim($data['full_name'])) < 2) {
            $errors['full_name'] = 'Введите имя (минимум 2 символа)';
        }

        if (empty(trim($data['phone'] ?? ''))) {
            $errors['phone'] = 'Введите телефон';
        }

        return $errors;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

    public function save(): bool
    {
        $data = [
            'email' => $this->email,
            'password_hash' => $this->passwordHash,
            'phone' => $this->phone,
            'full_name' => $this->fullName,
            'driver_license' => $this->driverLicense,
            'role' => $this->role,
            'is_active' => $this->isActive ? 1 : 0,
        ];

        if ($this->id) {
            unset($data['password_hash']);
            $sets = [];
            foreach (array_keys($data) as $key) {
                $sets[] = "{$key} = :{$key}";
            }
            $data['id'] = $this->id;
            $sql = 'UPDATE users SET ' . implode(', ', $sets) . ', updated_at = NOW() WHERE id = :id';
            Database::execute($sql, $data);
        } else {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $sql = "INSERT INTO users ({$columns}) VALUES ({$placeholders})";
            Database::execute($sql, $data);
            $this->id = (int) Database::lastInsertId();
        }

        return true;
    }

    public function toArray(bool $safe = true): array
    {
        $arr = [
            'id' => $this->id,
            'email' => $this->email,
            'phone' => $this->phone,
            'full_name' => $this->fullName,
            'driver_license' => $this->driverLicense,
            'role' => $this->role,
        ];
        if (!$safe) {
            $arr['created_at'] = $this->createdAt;
        }

        return $arr;
    }

    private static function sanitize(?string $v): ?string
    {
        return $v === null ? null : htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
    }

    private static function sanitizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);
        if (strlen($digits) === 11 && $digits[0] === '8') {
            $digits = '7' . substr($digits, 1);
        }

        return '+' . $digits;
    }

    public function setFullName(string $v): self { $this->fullName = $v; return $this; }
    public function setPhone(string $v): self { $this->phone = $v; return $this; }
    public function setDriverLicense(?string $v): self { $this->driverLicense = $v; return $this; }

    public function getId(): ?int { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): string { return $this->phone; }
    public function getFullName(): string { return $this->fullName; }
    public function getDriverLicense(): ?string { return $this->driverLicense; }
    public function getRole(): string { return $this->role; }
    public function isAdmin(): bool { return $this->role === self::ROLE_ADMIN; }
}
