<?php
session_start();
require 'db.php'; // Подключаем config.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['first_name']);
    $email = trim($_POST['email_reg']);
    $password = $_POST['password_reg'];
    $password_confirmation = $_POST['password_confirmation'];

    // Валидация данных
    if (empty($username) || empty($email) || empty($password) || empty($password_confirmation)) {
        die('Все поля обязательны для заполнения.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Некорректный email.');
    }

    if ($password !== $password_confirmation) {
        die('Пароли не совпадают.');
    }

    // Проверка на существование пользователя
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        die('Пользователь с таким email уже существует.');
    }

    // Хэширование пароля
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Вставка нового пользователя в базу данных
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, "user")');
        if ($stmt->execute([$username, $email, $password_hash])) {
            // Устанавливаем сообщение об успешной регистрации
            $_SESSION['registration_success'] = 'Регистрация прошла успешно! Пожалуйста, войдите в систему.';
            
            // Перенаправляем на главную страницу
            header('Location: index.php');
            exit;
        } else {
            die('Ошибка при регистрации.');
        }
    } catch (PDOException $e) {
        die('Ошибка базы данных: ' . $e->getMessage());
    }
}
?>