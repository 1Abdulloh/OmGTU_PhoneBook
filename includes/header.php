<?php
if (!isset($page_title)) {
    $page_title = 'Справочник телефонов ОмГТУ';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">
                <div class="logo-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>
                <div class="logo-text">
                    <div class="logo-title">ОмГТУ</div>
                    <div class="logo-subtitle">Справочник телефонов</div>
                </div>
            </a>
            
            <nav class="nav">
                <a href="index.php" class="nav-link">Главная</a>
                <a href="contacts.php" class="nav-link">Все контакты</a>
                <a href="departments.php" class="nav-link">Подразделения</a>
                <a href="search.php" class="nav-link">Поиск</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="admin/" class="nav-link">Админ-панель</a>
                    <a href="admin/logout.php" class="nav-link">Выйти</a>
                <?php else: ?>
                    <a href="admin/login.php" class="nav-link" style="color: var(--primary-color); font-weight: 600;">Вход</a>
            <?php endif; ?>
        </nav>
        </div>
    </header>
