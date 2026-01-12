<?php
require_once 'includes/admin_auth.php';
require_once '../includes/ContactManager.php';
require_once '../includes/DepartmentManager.php';

$page_title = "Админ-панель";
include 'includes/admin_header.php';

$contactManager = new ContactManager();
$departmentManager = new DepartmentManager();

$totalContacts = $contactManager->getTotalContacts();
$totalDepartments = count($departmentManager->getAllDepartments());
$recentContacts = $contactManager->getAllContacts(5);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 bg-dark text-white min-vh-100">
            <div class="sidebar-sticky pt-3">
                <div class="sidebar-header mb-4">
                    <h4 class="text-center">Админ-панель</h4>
                    <p class="text-center text-muted">ОмГТУ</p>
                </div>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            Главная
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacts/">
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
                        <a class="nav-link" href="departments/">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 3a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3H6a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3V6a3 3 0 0 0-3-3 3 3 0 0 0-3 3 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 3 3 0 0 0-3-3z"></path>
                            </svg>
                            Подразделения
                        </a>
                    </li>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="users/">
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
                        <a class="nav-link text-danger" href="logout.php">
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
            <h1 class="mb-4">Добро пожаловать, <?php echo $_SESSION['user_name']; ?>!</h1>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Контактов</h5>
                            <h2 class="card-text"><?php echo $totalContacts; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Подразделений</h5>
                            <h2 class="card-text"><?php echo $totalDepartments; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Роль</h5>
                            <h2 class="card-text"><?php echo $_SESSION['user_role']; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5>Последние контакты</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Имя</th>
                                    <th>Должность</th>
                                    <th>Телефон</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentContacts as $contact): ?>
                                <tr>
                                    <td><?php echo $contact['id']; ?></td>
                                    <td><?php echo escape($contact['name']); ?></td>
                                    <td><?php echo escape($contact['position']); ?></td>
                                    <td><?php echo escape($contact['phone']); ?></td>
                                    <td>
                                        <a href="contacts/edit.php?id=<?php echo $contact['id']; ?>" class="btn btn-sm btn-primary">Редактировать</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="contacts/" class="btn btn-primary">Все контакты</a>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Быстрые действия</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="contacts/create.php" class="btn btn-success w-100 mb-2">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Добавить контакт
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="departments/create.php" class="btn btn-success w-100 mb-2">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Добавить подразделение
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>