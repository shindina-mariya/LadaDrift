<?php
/**
 * Админ-панель LADA Drift - Страница входа
 */

session_start();

// Если уже авторизован - редирект на dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../php/config/database.php';

$error = '';

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        try {
            $stmt = Database::execute(
                "SELECT id, username, password_hash, full_name, role FROM admins WHERE username = :username AND is_active = 1",
                ['username' => $username]
            );
            
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Успешный вход
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_role'] = $admin['role'];
                
                // Обновляем время последнего входа
                Database::execute(
                    "UPDATE admins SET last_login = NOW() WHERE id = :id",
                    ['id' => $admin['id']]
                );
                
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Неверный логин или пароль';
            }
        } catch (Exception $e) {
            $error = 'Ошибка сервера. Попробуйте позже.';
            error_log("Admin login error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Вход в админ-панель | LADA Drift</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --color-bg: #0a0a0a;
            --color-card: #1a1a1a;
            --color-accent: #E30613;
            --color-text: #ffffff;
            --color-text-muted: #888;
            --color-error: #ff4444;
            --border-radius: 8px;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo svg {
            width: 60px;
            height: 60px;
            margin-bottom: 1rem;
        }
        
        .login-logo h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .login-logo h1 span {
            color: var(--color-accent);
        }
        
        .login-card {
            background-color: var(--color-card);
            border-radius: var(--border-radius);
            padding: 2rem;
        }
        
        .login-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--color-text-muted);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: var(--color-bg);
            border: 1px solid #333;
            border-radius: var(--border-radius);
            color: var(--color-text);
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--color-accent);
        }
        
        .form-control::placeholder {
            color: #555;
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-text-muted);
        }
        
        .input-icon .form-control {
            padding-left: 2.75rem;
        }
        
        .error-message {
            background-color: rgba(255, 68, 68, 0.1);
            border: 1px solid var(--color-error);
            color: var(--color-error);
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            text-align: center;
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 0.875rem;
            background-color: var(--color-accent);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
        }
        
        .btn:hover {
            background-color: #c00510;
        }
        
        .btn:active {
            transform: scale(0.98);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: var(--color-text-muted);
        }
        
        .login-footer a {
            color: var(--color-accent);
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <img src="../assets/images/logo/logo.svg" alt="LADA Drift" style="width: 212px; height: auto;">
        </div>
        
        <div class="login-card">
            <h2 class="login-title">Вход в панель управления</h2>
            
            <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Логин</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" class="form-control" 
                               placeholder="Введите логин" required autofocus
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Пароль</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" 
                               placeholder="Введите пароль" required>
                    </div>
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Войти
                </button>
            </form>
        </div>
        
        <div class="login-footer">
            <a href="../index.html"><i class="fas fa-arrow-left"></i> Вернуться на сайт</a>
        </div>
    </div>
</body>
</html>
