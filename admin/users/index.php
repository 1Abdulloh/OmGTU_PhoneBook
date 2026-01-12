<?php
require_once '../includes/admin_auth.php';

// Только админы могут управлять пользователями
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../../includes/UserManager.php';

$page_title = "Управление пользователями";
include '../includes/admin_header.php';

$userManager = new UserManager();
$users = $userManager->getAllUsers();

// Удаление пользователя
if (isset($_GET['delete'])) {
    $currentUserId = $_SESSION['user_id'] ?? null;
    if ($userManager->deleteUser($_GET['delete'], $currentUserId)) {
        $_SESSION['flash']['success'] = 'Пользователь удален';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['flash']['error'] = 'Не удалось удалить пользователя. Только главный администратор может удалять других администраторов.';
    }
}

$flashMessage = get_flash_message('success') ?? get_flash_message('error');
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Управление пользователями</h1>
        <a href="../index.php" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            На главную
        </a>
    </div>
    
    <?php if ($flashMessage): ?>
    <div class="alert alert-<?php echo strpos($flashMessage, 'Не удалось') !== false ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
        <?php echo $flashMessage; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Список пользователей</h5>
            <a href="create.php" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Добавить пользователя
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Логин</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Активен</th>
                            <th>Яндекс ID</th>
                            <th>Последний вход</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo escape($user['username']); ?></td>
                            <td><?php echo escape($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td><?php echo escape($user['email'] ?? '—'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'info'; ?>">
                                    <?php 
                                    if ($user['role'] === 'admin') {
                                        echo $user['id'] == 1 ? 'Главный админ' : 'Администратор';
                                    } else {
                                        echo ucfirst($user['role']);
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
                                    <?php echo $user['is_active'] ? 'Да' : 'Нет'; ?>
                                </span>
                            </td>
                            <td><?php echo $user['yandex_id'] ? 'Да' : 'Нет'; ?></td>
                            <td><?php echo $user['last_login'] ? date('d.m.Y H:i', strtotime($user['last_login'])) : '—'; ?></td>
                            <td>
                                <?php 
                                // Только главный админ (id=1) может удалять других админов
                                // Остальные админы могут удалять только не-админов
                                $canDelete = false;
                                if ($_SESSION['user_id'] == 1) {
                                    // Главный админ может удалять всех, кроме себя
                                    $canDelete = ($user['id'] != 1);
                                } else {
                                    // Обычные админы могут удалять только не-админов
                                    $canDelete = ($user['role'] !== 'admin');
                                }
                                ?>
                                <?php if ($canDelete): ?>
                                <a href="?delete=<?php echo $user['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Удалить пользователя <?php echo escape($user['username']); ?>?')">
                                    Удалить
                                </a>
                                <?php elseif ($user['role'] === 'admin' && $_SESSION['user_id'] != 1): ?>
                                <span class="text-muted">Только главный админ</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>