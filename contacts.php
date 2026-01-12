<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/ContactManager.php';
require_once __DIR__ . '/includes/DepartmentManager.php';

$page_title = "Все контакты - Справочник телефонов ОмГТУ";
$contactManager = new ContactManager();
$departmentManager = new DepartmentManager();

// Фильтрация по подразделению
$departmentFilter = isset($_GET['department']) ? (int)$_GET['department'] : null;

// Получаем все контакты
if ($departmentFilter) {
    $contacts = $contactManager->getContactsByDepartment($departmentFilter);
} else {
    $contacts = $contactManager->getAllContacts(500, 0);
}

$departments = $departmentManager->getAllDepartments();

include __DIR__ . '/includes/header.php';
?>
<main class="main-content">
    <div class="container">
        <div class="page-header">
            <div class="breadcrumb">
                <a href="index.php">Главная</a>
                <span>/</span>
                <span>Все контакты</span>
            </div>
            <h1 class="page-title">Все контакты</h1>
            <p class="page-subtitle">Список всех сотрудников университета</p>
        </div>

        <div class="contacts-toolbar">
            <div class="filter-group">
                <label for="department-filter">Подразделение:</label>
                <select id="department-filter" class="filter-select" onchange="window.location.href='contacts.php?department='+this.value">
                    <option value="">Все подразделения</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo $dept['id']; ?>" <?php echo ($departmentFilter == $dept['id']) ? 'selected' : ''; ?>>
                            <?php echo escape($dept['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php if (count($contacts) > 0): ?>
            <div class="contacts-grid contacts-list">
                <?php foreach ($contacts as $contact): ?>
                <div class="contact-card">
                    <div class="contact-card-header">
                        <div class="contact-avatar"><?php echo mb_substr($contact['name'], 0, 1, 'UTF-8'); ?></div>
                        <div class="contact-info">
                            <h3 class="contact-name"><?php echo escape($contact['name']); ?></h3>
                            <?php if (!empty($contact['position'])): ?>
                                <p class="contact-position"><?php echo escape($contact['position']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="contact-card-body">
                        <?php if (!empty($contact['department_name'])): ?>
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

                        <?php if (!empty($contact['email'])): ?>
                        <div class="contact-detail">
                            <span class="contact-label">Email:</span>
                            <a href="mailto:<?php echo escape($contact['email']); ?>" class="contact-value contact-email">
                                <?php echo escape($contact['email']); ?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($contact['room'])): ?>
                        <div class="contact-detail">
                            <span class="contact-label">Кабинет:</span>
                            <span class="contact-value"><?php echo escape($contact['room']); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($contact['internal_phone'])): ?>
                        <div class="contact-detail">
                            <span class="contact-label">Внутренний:</span>
                            <span class="contact-value"><?php echo escape($contact['internal_phone']); ?></span>
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
                        <?php if (!empty($contact['email'])): ?>
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
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <h3>Контактов нет</h3>
                <p>Пока нет добавленных контактов</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
