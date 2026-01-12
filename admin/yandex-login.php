<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/UserManager.php';

// Проверяем наличие vendor/autoload.php
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    header('Location: login.php?error=yandex_not_configured');
    exit;
}

require_once $autoloadPath;

use Aego\OAuth2\Client\Provider\Yandex;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Проверяем, что переменные окружения установлены
if (empty($_ENV['YANDEX_CLIENT_ID']) || empty($_ENV['YANDEX_CLIENT_SECRET']) || empty($_ENV['YANDEX_REDIRECT_URI'])) {
    header('Location: login.php?error=yandex_not_configured');
    exit;
}

$provider = new Yandex([
    'clientId'     => $_ENV['YANDEX_CLIENT_ID'],
    'clientSecret' => $_ENV['YANDEX_CLIENT_SECRET'],
    'redirectUri'  => $_ENV['YANDEX_REDIRECT_URI'],
]);

// Если нет кода, перенаправляем на авторизацию Яндекс
if (!isset($_GET['code'])) {
    // Принудительно показываем выбор аккаунта при каждом входе
    // Яндекс использует параметр force_confirm для показа выбора аккаунта
    $options = [
        'force_confirm' => 'yes' // Всегда показывать выбор аккаунта и подтверждение
    ];
    
    $authUrl = $provider->getAuthorizationUrl($options);
    // Добавляем параметр force_confirm вручную, если библиотека не поддерживает
    if (strpos($authUrl, 'force_confirm') === false) {
        $authUrl .= (strpos($authUrl, '?') !== false ? '&' : '?') . 'force_confirm=yes';
    }
    
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);
    exit;

// Если есть код, обрабатываем callback
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    header('Location: login.php?error=yandex_failed');
    exit;
    
} else {
    try {
        // Получаем access token
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // Получаем информацию о пользователе
        $resourceOwner = $provider->getResourceOwner($token);
        $userInfo = $resourceOwner->toArray();

        // Проверяем пользователя в базе данных
        $userManager = new UserManager();
        $user = $userManager->authenticateByYandex(
            $userInfo['id'],
            $userInfo['default_email'] ?? $userInfo['email'] ?? '',
            $userInfo['real_name'] ?? $userInfo['display_name'] ?? $userInfo['login'] ?? ''
        );

        if ($user) {
            // Если пользователь уже авторизован, но это другой аккаунт - разлогиниваем
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user['id']) {
                // Очищаем старую сессию
                session_unset();
                session_destroy();
                session_start();
            }
            
            // Создаем новую сессию для текущего пользователя
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['yandex_id'] = $userInfo['id']; // Сохраняем Яндекс ID для проверки
            
            header('Location: index.php');
            exit;
        } else {
            header('Location: login.php?error=yandex_no_access');
            exit;
        }

    } catch (Exception $e) {
        error_log('Yandex OAuth error: ' . $e->getMessage());
        header('Location: login.php?error=yandex_failed');
        exit;
    }
}
?>
