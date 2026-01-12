<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/UserManager.php';

$page_title = "Вход в админ-панель";

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$userManager = new UserManager();

// Yandex xatolarini ko'rsatish
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'yandex_not_configured':
            $error = 'Yandex авторизация пока не настроена';
            break;
        case 'yandex_no_access':
            $error = 'У вас нет доступа через Yandex. Обратитесь к администратору.';
            break;
        case 'yandex_failed':
            $error = 'Ошибка при авторизации через Yandex';
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        $user = $userManager->authenticate($username, $password);
        
        if ($user && $user['role'] === 'admin') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            
            header('Location: index.php');
            exit;
        } else {
            $error = 'Неверные учетные данные или недостаточно прав';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        .form-control {
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            width: 100%;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .yandex-login {
            background: #fc3f1d;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            width: 100%;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 600;
            cursor: pointer;
        }
        .yandex-login:hover {
            background: #e03615;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h1>Вход в админ-панель</h1>
            <p>Справочник телефонов ОмГТУ</p>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Имя пользователя" required autofocus>
            </div>
            
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Пароль" required>
            </div>
            
            <button type="submit" class="btn-login">Войти</button>
        </form>
        
        <div class="mt-3 text-center">
            <a href="yandex-login.php" class="yandex-login">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="white" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                Войти через Яндекс
            </a>
            <p style="font-size: 11px; color: #999; margin-top: 5px;">
                При входе будет показан выбор аккаунта Яндекс
            </p>
        </div>
        
        <div class="mt-3 text-center">
            <a href="../index.php" style="color: #666; text-decoration: none;">← Вернуться на сайт</a>
        </div>
        
        <div class="mt-4 text-center" style="font-size: 12px; color: #999;">
            Чтобы получить доступ к панели администратора, обратитесь :<br>
            Telegram: @Abdullah_Hanafiy<br>
            Почта: Tagneuver@gmail.com
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>