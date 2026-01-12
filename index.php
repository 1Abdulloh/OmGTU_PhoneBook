<?php
session_start();

// Подключаем все необходимые файлы в правильном порядке
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/ContactManager.php';
require_once __DIR__ . '/includes/DepartmentManager.php';

$page_title = "Справочник телефонов ОмГТУ";

try {
    $contactManager = new ContactManager();
    $departmentManager = new DepartmentManager();
    
    $totalContacts = $contactManager->getTotalContacts();
    $departments = $departmentManager->getAllDepartments();
} catch (Exception $e) {
    // Если база данных не подключена, используем заглушки
    $totalContacts = 0;
    $departments = [];
    error_log("Database error: " . $e->getMessage());
}

include __DIR__ . '/includes/header.php';
?>

<main class="main-content">
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="university-name">ОмГТУ</span>
                    <span class="hero-subtitle">Справочник телефонов</span>
                </h1>
                <p class="hero-description">
                    Быстрый доступ к контактной информации всех подразделений и сотрудников университета
                </p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($departments); ?></div>
                        <div class="stat-label">Подразделений</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $totalContacts; ?></div>
                        <div class="stat-label">Сотрудников</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Доступ</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="search-section">
        <div class="container">
            <div class="search-box">
                <form action="search.php" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <svg class="search-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input 
                            type="text" 
                            name="query" 
                            class="search-input" 
                            placeholder="Поиск по имени, должности, отделу или телефону..."
                            autocomplete="off"
                        >
                        <button type="submit" class="search-btn">Найти</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="departments-section">
        <div class="container">
            <h2 class="section-title">Основные подразделения</h2>
            <div class="departments-grid">
                <?php foreach ($departments as $dept): ?>
                <a href="department.php?id=<?php echo $dept['id']; ?>" class="department-card">
                    <div class="department-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5"></path>
                            <path d="M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <h3 class="department-name"><?php echo htmlspecialchars($dept['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p class="department-info"><?php echo escape($dept['description']); ?></p>
                    <span class="department-count"><?php echo $dept['contact_count']; ?> контактов</span>
                </a>
                <?php endforeach; ?>
                
                <a href="contacts.php" class="department-card">
                    <div class="department-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h3 class="department-name">Все контакты</h3>
                    <p class="department-info">Полный список сотрудников</p>
                    <span class="department-count"><?php echo $totalContacts; ?> контактов</span>
                </a>
            </div>
        </div>
    </section>

    <section class="quick-access-section">
        <div class="container">
            <h2 class="section-title">Быстрый доступ</h2>
            <div class="quick-links">
                <a href="contacts.php" class="quick-link">
                    <span class="quick-link-icon">📋</span>
                    <span class="quick-link-text">Все контакты</span>
                </a>
                <a href="departments.php" class="quick-link">
                    <span class="quick-link-icon">🏢</span>
                    <span class="quick-link-text">Подразделения</span>
                </a>
                <a href="search.php" class="quick-link">
                    <span class="quick-link-icon">🔍</span>
                    <span class="quick-link-text">Поиск</span>
                </a>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>