<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/DepartmentManager.php';

$page_title = "Подразделения - Справочник телефонов ОмГТУ";
$departmentManager = new DepartmentManager();
$departments = $departmentManager->getAllDepartments();

include __DIR__ . '/includes/header.php';
?>
<main class="main-content">
    <div class="container">
        <div class="page-header">
            <div class="breadcrumb">
                <a href="index.php">Главная</a>
                <span>/</span>
                <span>Подразделения</span>
            </div>
            <h1 class="page-title">Подразделения</h1>
            <p class="page-subtitle">Все отделы и факультеты университета</p>
        </div>

        <?php if (count($departments) > 0): ?>
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
                    <h3 class="department-name"><?php echo escape($dept['name']); ?></h3>
                    <?php if (!empty($dept['description'])): ?>
                        <p class="department-info"><?php echo escape($dept['description']); ?></p>
                    <?php endif; ?>
                    <span class="department-count"><?php echo $dept['contact_count'] ?? 0; ?> контактов</span>
                </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                    <path d="M2 17l10 5 10-5"></path>
                    <path d="M2 12l10 5 10-5"></path>
                </svg>
                <h3>Подразделений нет</h3>
                <p>Пока нет добавленных подразделений</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
