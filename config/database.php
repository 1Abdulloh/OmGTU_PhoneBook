<?php
// config/database.php

if (!class_exists('Database')) {
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // Настройки для XAMPP с MySQL
        $host = 'localhost';
        $dbname = 'oxirgi';  // ваше название базы данных
        $username = 'root';   // стандартный пользователь XAMPP
        $password = '';       // пароль по умолчанию пустой
        
        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}
} // end if !class_exists
?>