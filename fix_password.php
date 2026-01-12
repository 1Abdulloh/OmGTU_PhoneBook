<?php
// Bu fayl password hash ni to'g'rilash uchun
// Bir marta ishga tushiring va keyin o'chiring

require_once __DIR__ . '/config/database.php';

$db = Database::getInstance();

// Yangi password hash yaratish
$password = 'EgorOmGTU';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Yangi hash: " . $hash . "\n";

// Database da yangilash
$sql = "UPDATE users SET password_hash = :hash WHERE username = 'EgorVikulov'";
$stmt = $db->prepare($sql);
$stmt->execute([':hash' => $hash]);

echo "Password yangilandi!\n";
echo "Username: EgorVikulov\n";
echo "Password: EgorOmGTU\n";
