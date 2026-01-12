<?php
if (!isset($page_title)) {
    $page_title = 'Админ-панель';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - ОмГТУ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: #333;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        .content {
            padding: 20px;
        }
        .card {
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            border: none;
        }
        .table th {
            border-top: none;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-building"></i>
                Админ-панель ОмГТУ
            </a>
            <div class="navbar-text text-light">
                Вы вошли как: <strong><?php echo $_SESSION['user_name']; ?></strong> 
                (<?php echo $_SESSION['user_role']; ?>)
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">