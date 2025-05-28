<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require 'db.php';

// Получаем данные пользователя
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - Арман</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="container_main">
            <div class="profile-container">
                <h1>Личный кабинет</h1>
                <div class="profile-info">
                    <h2>Добро пожаловать, <?php echo htmlspecialchars($user['username']); ?>!</h2>
                    <div class="profile-details">
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <?php if (isset($user['created_at'])): ?>
                            <p><strong>Дата регистрации:</strong> <?php echo date('d.m.Y', strtotime($user['created_at'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="profile-menu">
                    <a href="my_orders_view.php" class="profile-menu-item">Мои заказы</a>
                    <a href="my_adress_view.php" class="profile-menu-item">Мои адреса</a>
                    <a href="setting_profile_view.php" class="profile-menu-item">Настройки профиля</a>
                    <a href="logout.php" class="profile-menu-item">Выйти</a>
                </div>
            </div>
        </div>
    </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html> 