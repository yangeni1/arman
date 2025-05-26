<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email_login']);
    $password = $_POST['password_login'];

    // Валидация данных
    if (empty($email) || empty($password)) {
        die('Все поля обязательны для заполнения.');
    }

    // Поиск пользователя в базе данных
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Успешная авторизация
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Сохраняем роль пользователя в сессии

        // Перенаправляем администратора на админ-панель
        if ($user['role'] === 'admin') {
            header('Location: admin_panel.php');
            exit;
        }

        // Перенаправляем обычного пользователя на главную страницу
        header('Location: index.php');
        exit;
    } else {
        die('Неверный email или пароль.');
    }
}
?>