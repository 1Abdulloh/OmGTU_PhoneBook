<?php
require_once '../includes/admin_auth.php';
require_once '../../includes/ContactManager.php';
require_once '../../includes/DepartmentManager.php';

$page_title = "Редактировать контакт";
include '../includes/admin_header.php';

$contactManager = new ContactManager();
$departmentManager = new DepartmentManager();

$contactId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$contact = $contactManager->getContactById($contactId);

if (!$contact) {
    header('Location: index.php');
    exit;
}

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
            if ($contactManager->updateContact($contactId, $data)) {
                $success = 'Контакт успешно обновлен!';
                // Обновляем данные контакта для отображения
                $contact = $contactManager->getContactById($contactId);
            } else {
                $error = 'Ошибка при обновлении контакта';
            }
        } catch (Exception $e) {
            $error = 'Ошибка при обновлении контакта: ' . $e->getMessage();
        }
    }
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4">Редактировать контакт</h1>
    
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
                            <label for="name" class="form-label">Имя *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo escape($contact['name']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="position" class="form-label">Должность</label>
                            <input type="text" class="form-control" id="position" name="position"
                                   value="<?php echo escape($contact['position']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="department_id" class="form-label">Подразделение</label>
                            <select class="form-control" id="department_id" name="department_id">
                                <option value="">— Не выбрано —</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>" 
                                    <?php echo ($contact['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
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
                                   value="<?php echo escape($contact['phone']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php echo escape($contact['email']); ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room" class="form-label">Кабинет</label>
                                    <input type="text" class="form-control" id="room" name="room"
                                           value="<?php echo escape($contact['room']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="internal_phone" class="form-label">Внутренний телефон</label>
                                    <input type="text" class="form-control" id="internal_phone" name="internal_phone"
                                           value="<?php echo escape($contact['internal_phone']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    <a href="index.php" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>