<?php
// config/yandex_oauth.php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

return [
    'clientId' => $_ENV['YANDEX_CLIENT_ID'],
    'clientSecret' => $_ENV['YANDEX_CLIENT_SECRET'],
    'redirectUri' => $_ENV['YANDEX_REDIRECT_URI'],
];