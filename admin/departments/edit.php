<?php
require_once '../includes/admin_auth.php';
require_once '../../includes/DepartmentManager.php';

$page_title = "Редактировать подразделение";
include '../includes/admin_header.php';

$departmentManager = new DepartmentManager();

$deptId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$department = $departmentManager->getDepartmentById($deptId);

if (!$department) {
    header('Location: index.php');
    exit;
}

$allDepartments = $departmentManager->getAllDepartments();
// Исключаем текущее подразделение из списка родителей
$availableParents = array_filter($allDepartments, function($dept) use ($deptId) {
    return $dept['id'] != $deptId;
});

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'parent_id' => !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null
    ];
    
    if (empty($data['name'])) {
        $error = 'Название обязательно для заполнения';
    } else {
        try {
            if ($departmentManager->updateDepartment($deptId, $data)) {
                $success = 'Подразделение успешно обновлено!';
                $department = $departmentManager->getDepartmentById($deptId);
            } else {
                $error = 'Ошибка при обновлении подразделения';
            }
        } catch (Exception $e) {
            $error = 'Ошибка при обновлении подразделения: ' . $e->getMessage();
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
                <a class="nav-link active text-white" href="index.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 3a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3H6a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3V6a3 3 0 0 0-3-3 3 3 0 0 0-3 3 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 3 3 0 0 0-3-3z"></path>
                    </svg>
                    Подразделения
                </a>
            </li>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="../users/">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    Пользователи
                </a>
            </li>
            <?php endif; ?>
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
        <h1 class="h3 mb-0">Редактировать подразделение</h1>
        <a href="../index.php" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            На главную
        </a>
    </div>
    <h1 class="h3 mb-4">Редактировать подразделение</h1>
    
    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Название *</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?php echo escape($department['name']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo escape($department['description']); ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="parent_id" class="form-label">Родительское подразделение</label>
                    <select class="form-control" id="parent_id" name="parent_id">
                        <option value="">— Нет —</option>
                        <?php foreach ($availableParents as $dept): ?>
                        <option value="<?php echo $dept['id']; ?>" 
                            <?php echo ($department['parent_id'] == $dept['id']) ? 'selected' : ''; ?>>
                            <?php echo escape($dept['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Сохранить изменения
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>