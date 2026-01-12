# Инструкция по установке Composer зависимостей

## Проблема
Ошибка: `Failed to open stream: No such file or directory vendor/autoload.php`

Это означает, что зависимости Composer не установлены.

## Решение

### Шаг 1: Установка Composer (если не установлен)

1. **Скачайте Composer:**
   - Перейдите на: https://getcomposer.org/download/
   - Скачайте `Composer-Setup.exe` (Windows Installer)

2. **Установите Composer:**
   - Запустите `Composer-Setup.exe`
   - Следуйте инструкциям установщика
   - Установщик автоматически найдет PHP (XAMPP обычно в `C:\xampp\php\php.exe`)

3. **Проверьте установку:**
   Откройте командную строку (CMD) или PowerShell и выполните:
   ```bash
   composer --version
   ```
   
   Если выводится версия (например, `Composer version 2.x.x`), значит установка прошла успешно.

### Шаг 2: Установка зависимостей проекта

1. **Откройте командную строку** (CMD) или PowerShell

2. **Перейдите в папку проекта:**
   ```bash
   cd C:\xampp\htdocs\oxirgi
   ```
   
   **Альтернативный способ:**
   - Откройте папку `C:\xampp\htdocs\oxirgi` в Проводнике
   - Зажмите `Shift` и кликните правой кнопкой мыши в пустой области
   - Выберите "Открыть окно PowerShell здесь" или "Открыть окно команд здесь"

3. **Выполните команду установки:**
   ```bash
   composer install
   ```

4. **Дождитесь завершения:**
   Команда установит все необходимые библиотеки:
   - `vlucas/phpdotenv` (для работы с .env файлами)
   - `league/oauth2-client` (библиотека OAuth2)
   - `aego/oauth2-yandex` (провайдер Яндекс OAuth)

5. **Проверьте результат:**
   После выполнения команды должна появиться папка `vendor` в корне проекта:
   ```
   C:\xampp\htdocs\oxirgi\vendor\
   ```

### Шаг 3: Настройка .env файла

После установки зависимостей настройте переменные окружения в файле `.env`:

```env
YANDEX_CLIENT_ID=ваш_client_id
YANDEX_CLIENT_SECRET=ваш_client_secret
YANDEX_REDIRECT_URI=http://localhost/oxirgi/admin/yandex-login.php
```

## Если возникли проблемы

### Проблема: "composer не является внутренней или внешней командой"
**Решение:** Composer не установлен или не добавлен в PATH. Установите через `Composer-Setup.exe` (см. Шаг 1).

### Проблема: "PHP не найден"
**Решение:** 
1. Убедитесь, что XAMPP установлен
2. При установке Composer укажите путь к PHP вручную: `C:\xampp\php\php.exe`

### Проблема: "SSL certificate problem"
**Решение:** Временно отключите проверку SSL (только для разработки):
```bash
composer config -g secure-http false
composer install
```

### Проблема: "Memory limit exceeded"
**Решение:** Увеличьте лимит памяти PHP:
```bash
php -d memory_limit=512M composer install
```

## После установки

После успешной установки зависимостей авторизация через Яндекс должна работать (при условии правильной настройки .env файла).
