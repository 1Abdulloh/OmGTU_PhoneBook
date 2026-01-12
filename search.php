<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/ContactManager.php';
require_once __DIR__ . '/includes/DepartmentManager.php';

$page_title = "Поиск - Справочник телефонов ОмГТУ";
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

include __DIR__ . '/includes/header.php';

$contactManager = new ContactManager();
$departmentManager = new DepartmentManager();

$results = [];
if ($query) {
    $results = $contactManager->searchContacts($query);
}
?>

<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Поиск контактов</h1>
            <p class="page-subtitle">Найдите нужного сотрудника или подразделение</p>
        </div>

        <div class="search-box search-box-page">
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
                        value="<?php echo escape($query); ?>"
                        autocomplete="off"
                        autofocus
                    >
                    <button type="submit" class="search-btn">Найти</button>
                </div>
            </form>
        </div>

        <?php if ($query): ?>
            <div class="search-results">
                <div class="results-header">
                    <h2 class="results-title">
                        Результаты поиска
                        <span class="results-count">(найдено: <?php echo count($results); ?>)</span>
                    </h2>
                </div>

                <?php if (count($results) > 0): ?>
                    <div class="contacts-grid contacts-list">
                        <?php foreach ($results as $contact): ?>
                        <div class="contact-card">
                            <div class="contact-card-header">
                                <div class="contact-avatar">
                                    <?php echo mb_substr($contact['name'], 0, 1); ?>
                                </div>
                                <div class="contact-info">
                                    <h3 class="contact-name"><?php echo escape($contact['name']); ?></h3>
                                    <p class="contact-position"><?php echo escape($contact['position']); ?></p>
                                </div>
                            </div>
                            <div class="contact-card-body">
                                <?php if ($contact['department_name']): ?>
                                <div class="contact-detail">
                                    <span class="contact-label">Подразделение:</span>
                                    <span class="contact-value"><?php echo escape($contact['department_name']); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="contact-detail">
                                    <span class="contact-label">Телефон:</span>
                                    <a href="tel:<?php echo format_phone($contact['phone']); ?>" class="contact-value contact-phone">
                                        <?php echo escape($contact['phone']); ?>
                                    </a>
                                </div>
                                
                                <?php if ($contact['email']): ?>
                                <div class="contact-detail">
                                    <span class="contact-label">Email:</span>
                                    <a href="mailto:<?php echo escape($contact['email']); ?>" class="contact-value contact-email">
                                        <?php echo escape($contact['email']); ?>
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="contact-card-footer">
                                <a href="tel:<?php echo format_phone($contact['phone']); ?>" class="btn btn-primary btn-sm">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                    Позвонить
                                </a>
                                <?php if ($contact['email']): ?>
                                <a href="mailto:<?php echo escape($contact['email']); ?>" class="btn btn-secondary btn-sm">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    Email
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <h3>Ничего не найдено</h3>
                        <p>Попробуйте изменить поисковый запрос</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="search-placeholder">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <h3>Введите поисковый запрос</h3>
                <p>Начните вводить имя, должность, отдел или номер телефона</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>