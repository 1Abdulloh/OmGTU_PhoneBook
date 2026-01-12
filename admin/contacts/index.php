<?php
require_once '../includes/admin_auth.php';
require_once '../../includes/ContactManager.php';
require_once '../../includes/DepartmentManager.php';

$page_title = "Управление контактами";
include '../includes/admin_header.php';

$contactManager = new ContactManager();
$departmentManager = new DepartmentManager();

// Пагинация
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$contacts = $contactManager->getAllContacts($limit, $offset);
$totalContacts = $contactManager->getTotalContacts();
$totalPages = ceil($totalContacts / $limit);

// Удаление контакта
if (isset($_GET['delete']) && $_SESSION['user_role'] === 'admin') {
    if ($contactManager->deleteContact($_GET['delete'])) {
        $_SESSION['flash']['success'] = 'Контакт успешно удален';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['flash']['error'] = 'Ошибка при удалении контакта';
    }
}

$flashMessage = get_flash_message('success') ?? get_flash_message('error');
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Управление контактами</h1>
        <a href="../index.php" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            На главную
        </a>
    </div>
    
    <?php if ($flashMessage): ?>
    <div class="alert alert-<?php echo strpos($flashMessage, 'Ошибка') !== false ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
        <?php echo $flashMessage; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Список контактов</h5>
            <a href="create.php" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Добавить контакт
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Должность</th>
                            <th>Подразделение</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?php echo $contact['id']; ?></td>
                            <td><?php echo escape($contact['name']); ?></td>
                            <td><?php echo escape($contact['position']); ?></td>
                            <td><?php echo escape($contact['department_name'] ?? '—'); ?></td>
                            <td><?php echo escape($contact['phone']); ?></td>
                            <td><?php echo escape($contact['email'] ?? '—'); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="edit.php?id=<?php echo $contact['id']; ?>" class="btn btn-primary">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                    <a href="?delete=<?php echo $contact['id']; ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('Удалить контакт?')">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Пагинация -->
            <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">Назад</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">Вперед</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>