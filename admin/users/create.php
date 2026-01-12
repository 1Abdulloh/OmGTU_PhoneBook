<?php
require_once '../includes/admin_auth.php';

// Только админы могут создавать пользователей
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../../includes/UserManager.php';

$page_title = "Добавить пользователя";
include '../includes/admin_header.php';

$userManager = new UserManager();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'username' => trim($_POST['username'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'email' => trim($_POST['email'] ?? ''),
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'role' => $_POST['role'] ?? 'admin'
    ];
    
    // Валидация
    if (empty($data['username']) || empty($data['password'])) {
        $error = 'Логин и пароль обязательны';
    } elseif (strlen($data['password']) < 6) {
        $error = 'Пароль должен быть не менее 6 символов';
    } else {
        try {
            $userId = $userManager->createUser($data);
            $success = 'Пользователь успешно создан!';
            $_POST = [];
        } catch (Exception $e) {
            $error = 'Ошибка при создании пользователя: ' . $e->getMessage();
        }
    }
}
?>

<div class="col-md-2 bg-dark text-white min-vh-100">
    <div class="sidebar-sticky pt-3">
        <div class="sidebar-header mb-4">
            <h4 class="text-center">Админ-панель</h4>
            <p class="text-center text-muted">ОмГТУ</p>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="../index.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Главная
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../contacts/">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Контакты
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../departments/">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 3a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3H6a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3V6a3 3 0 0 0-3-3 3 3 0 0 0-3 3 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 3 3 0 0 0-3-3z"></path>
                    </svg>
                    Подразделения
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active text-white" href="index.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    Пользователи
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="nav-link text-danger" href="../logout.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Выйти
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="col-md-10 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Добавить пользователя</h1>
        <a href="../index.php" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            На главную
        </a>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Логин *</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo escape($_POST['username'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <small class="text-muted">Не менее 6 символов</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php echo escape($_POST['email'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">Имя</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                   value="<?php echo escape($_POST['first_name'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Фамилия</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                   value="<?php echo escape($_POST['last_name'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Роль</label>
                            <select class="form-control" id="role" name="role">
                                <option value="admin" <?php echo (($_POST['role'] ?? 'admin') == 'admin') ? 'selected' : ''; ?>>Администратор</option>
                            </select>
                            <small class="text-muted">В админ-панель могут входить только администраторы</small>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        Создать пользователя
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>