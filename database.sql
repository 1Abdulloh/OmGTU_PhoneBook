-- MySQL database setup for oxirgi project
-- Run this script in phpMyAdmin or MySQL command line

-- Create database
CREATE DATABASE IF NOT EXISTS `oxirgi` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `oxirgi`;

-- Create departments table
CREATE TABLE IF NOT EXISTS `departments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `parent_id` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`parent_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create contacts table
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `position` VARCHAR(255),
    `department_id` INT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255),
    `room` VARCHAR(50),
    `internal_phone` VARCHAR(20),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) UNIQUE,
    `first_name` VARCHAR(100),
    `last_name` VARCHAR(100),
    `role` ENUM('admin', 'editor', 'viewer') DEFAULT 'viewer',
    `yandex_id` VARCHAR(100) UNIQUE,
    `yandex_email` VARCHAR(255),
    `yandex_name` VARCHAR(255),
    `is_active` TINYINT(1) DEFAULT 1,
    `last_login` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert initial departments data
INSERT INTO `departments` (`name`, `description`) VALUES
('Ректорат', 'Администрация университета'),
('Факультет информационных технологий', 'Деканат и кафедры ФИТ'),
('Кафедра программирования', 'Преподаватели и сотрудники кафедры'),
('Административные службы', 'Бухгалтерия, отдел кадров, юридический отдел'),
('Технические службы', 'IT-отдел, обслуживание, охрана'),
('Библиотека', 'Информационные ресурсы и сотрудники библиотеки')
ON DUPLICATE KEY UPDATE `name`=`name`;

-- Insert initial user (password: EgorOmGTU)
-- Hash yaratish uchun: php -r "echo password_hash('EgorOmGTU', PASSWORD_DEFAULT);"
-- Agar hash ishlamasa, fix_password.php faylini ishga tushiring
INSERT INTO `users` (`username`, `password_hash`, `email`, `first_name`, `last_name`, `role`) VALUES
('EgorVikulov', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'egor@omgtu.ru', 'Егор', 'Викулов', 'admin')
ON DUPLICATE KEY UPDATE `password_hash`=VALUES(`password_hash`);

-- Insert initial contacts data
INSERT INTO `contacts` (`name`, `position`, `department_id`, `phone`, `email`) VALUES
('Иванов Иван Иванович', 'Ректор', 1, '+7 (3812) 65-12-34', 'rector@omgtu.ru'),
('Петрова Мария Сергеевна', 'Проректор по учебной работе', 1, '+7 (3812) 65-12-35', 'prorector@omgtu.ru'),
('Сидоров Алексей Петрович', 'Декан ФИТ', 2, '+7 (3812) 65-12-50', 'dean.fit@omgtu.ru'),
('Козлова Елена Викторовна', 'Заведующий кафедрой', 3, '+7 (3812) 65-12-60', 'kafedra@omgtu.ru'),
('Морозов Дмитрий Александрович', 'Начальник IT-отдела', 5, '+7 (3812) 65-12-80', 'it@omgtu.ru')
ON DUPLICATE KEY UPDATE `name`=`name`;

-- Create indexes
CREATE INDEX IF NOT EXISTS `idx_contacts_department` ON `contacts`(`department_id`);
CREATE INDEX IF NOT EXISTS `idx_contacts_name` ON `contacts`(`name`);
CREATE INDEX IF NOT EXISTS `idx_users_username` ON `users`(`username`);
CREATE INDEX IF NOT EXISTS `idx_users_yandex` ON `users`(`yandex_id`);
CREATE INDEX IF NOT EXISTS `idx_users_role` ON `users`(`role`);
