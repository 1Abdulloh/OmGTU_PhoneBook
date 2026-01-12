<?php
require_once '../includes/admin_auth.php';
require_once '../../includes/ContactManager.php';
require_once '../../includes/DepartmentManager.php';

$page_title = "Добавить контакт";
include '../includes/admin_header.php';

$contactManager = new ContactManager();
$departmentManager = new DepartmentManager();

$departments = $departmentManager->getAllDepartments();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'position' => trim($_POST['position'] ?? ''),
        'department_id' => !empty($_POST['department_id']) ? intval($_POST['department_id']) : null,
        'phone' => trim($_POST['phone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'room' => trim($_POST['room'] ?? ''),
        'internal_phone' => trim($_POST['internal_phone'] ?? '')
    ];
    
    // Валидация
    if (empty($data['name']) || empty($data['phone'])) {
        $error = 'Заполните обязательные поля (Имя и Телефон)';
    } else {
        try {
            $contactId = $contactManager->createContact($data);
            $success = 'Контакт успешно создан! ID: ' . $contactId;
            $_POST = []; // Очищаем форму
        } catch (Exception $e) {
            $error = 'Ошибка при создании контакта: ' . $e->getMessage();
        }
    }
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Добавить контакт</h1>
        <a href="../index.php" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            На главную
        </a>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Имя *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo escape($_POST['name'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="position" class="form-label">Должность</label>
                            <input type="text" class="form-control" id="position" name="position"
                                   value="<?php echo escape($_POST['position'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="department_id" class="form-label">Подразделение</label>
                            <select class="form-control" id="department_id" name="department_id">
                                <option value="">— Не выбрано —</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>" 
                                    <?php echo (($_POST['department_id'] ?? '') == $dept['id']) ? 'selected' : ''; ?>>
                                    <?php echo escape($dept['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Телефон *</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="<?php echo escape($_POST['phone'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php echo escape($_POST['email'] ?? ''); ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room" class="form-label">Кабинет</label>
                                    <input type="text" class="form-control" id="room" name="room"
                                           value="<?php echo escape($_POST['room'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="internal_phone" class="form-label">Внутренний телефон</label>
                                    <input type="text" class="form-control" id="internal_phone" name="internal_phone"
                                           value="<?php echo escape($_POST['internal_phone'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="index.php" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>