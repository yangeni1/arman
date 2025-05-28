<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = 'Пожалуйста, заполните все поля.';
    } elseif ($new_password !== $confirm_password) {
        $message = 'Новый пароль и подтверждение не совпадают.';
    } else {
        // Получаем текущий хэш пароля из базы
        $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($current_password, $user['password'])) {
            // Хэшируем новый пароль
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

            // Обновляем пароль в базе
            $update_stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            if ($update_stmt->execute([$new_password_hash, $user_id])) {
                $message = 'Пароль успешно изменён.';
            } else {
                $message = 'Ошибка при обновлении пароля. Попробуйте позже.';
            }
        } else {
            $message = 'Текущий пароль неверен.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки профиля - Арман</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
<style>
    .change-password-form{
        display: table;
        margin: 0 auto;
        width: 310px;
        font-family: "Quicksand", sans-serif;
    }
    .message{
        font-family: "Quicksand", sans-serif;
        display: table;
        margin: 20px auto;
        color: #66B158;
        font-size: 16px;
        font-weight: 600;
    }
</style>
    <div class="container">
        <div class="container_main">
            <div class="profile-container">
                <h1>Личный кабинет</h1>
            </div>
            <div class="profile-menu">
                <a href="profile.php" class="profile-menu-item">Вернуться назад</a>
                <a href="my_orders_view.php" class="profile-menu-item">Мои заказы</a>
                <a href="my_adress_view.php" class="profile-menu-item">Мои адреса</a>
                <a href="setting_profile_view.php" class="profile-menu-item">Настройки профиля</a>
                <a href="logout.php" class="profile-menu-item">Выйти</a>
            </div>
            <div class="profile-content">
                <h2 class="center">Смена пароля</h2>
                <?php if ($message): ?>
                    <p class="message"><?= htmlspecialchars($message) ?></p>
                <?php endif; ?>
                <form method="POST" action="setting_profile_view.php" class="change-password-form">
                    <input type="hidden" name="change_password" value="1" />
                    <label for="current_password">Текущий пароль:</label>
                    <div class="password-container">
                            <input type="password"  class="password" id="current_password" name="current_password" required />
                                <button id="togglePasswordcurrent" type="button"><img src="./media/popup/1.png" alt=""></button>
                    </div>
                    <label for="new_password">Новый пароль:</label>
                    <div class="password-container">  
                            <input type="password" id="new_password" name="new_password" required />
                                <button id="togglePasswordnew" type="button"><img src="./media/popup/1.png" alt=""></button>
                    </div>
                    <label for="confirm_password">Подтвердите новый пароль:</label>
                    <div class="password-container">
                            <input type="password" id="confirm_password" name="confirm_password" required />
                                <button id="togglePasswordconfirm" type="button"><img src="./media/popup/1.png" alt=""></button>
                    </div>
                    <button class='login-button'type="submit">Изменить пароль</button>
                </form>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
        <script>
            const togglePasswordLogin = document.getElementById('togglePasswordcurrent');
            const passwordFieldLogin = document.getElementById('current_password');

            const togglePasswordReg = document.getElementById('togglePasswordnew');
            const passwordFieldReg = document.getElementById('new_password');

            const togglePasswordConfirmation = document.getElementById('togglePasswordconfirm');
            const passwordFieldConfirmation = document.getElementById('confirm_password');

            function togglePassword1(field, button) {
                if (field.type === 'password') {
                    field.type = 'text';
                    button.querySelector('img').src = './media/popup/2.png';
                } else {
                    field.type = 'password';
                    button.querySelector('img').src = './media/popup/1.png';
                }
            }

            if (togglePasswordLogin && passwordFieldLogin) {
                togglePasswordLogin.addEventListener('click', (e) => {
                    e.preventDefault();
                    togglePassword1(passwordFieldLogin, togglePasswordLogin);
                });
            }

            if (togglePasswordReg && passwordFieldReg) {
                togglePasswordReg.addEventListener('click', (e) => {
                    e.preventDefault();
                    togglePassword1(passwordFieldReg, togglePasswordReg);
                });
            }

            if (togglePasswordConfirmation && passwordFieldConfirmation) {
                togglePasswordConfirmation.addEventListener('click', (e) => {
                    e.preventDefault();
                    togglePassword1(passwordFieldConfirmation, togglePasswordConfirmation);
                });
            }

    </script>
</body>
</html>
