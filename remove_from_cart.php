<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Необходимо авторизоваться']);
    exit;
}

if (!isset($_POST['cart_id'])) {
    echo json_encode(['success' => false, 'message' => 'Не указан ID товара в корзине']);
    exit;
}

$cart_id = (int)$_POST['cart_id'];
$user_id = $_SESSION['user_id'];

try {
    // Проверяем, принадлежит ли товар пользователю
    $check_sql = "SELECT id FROM cart WHERE id = ? AND user_id = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$cart_id, $user_id]);
    
    if (!$check_stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Товар не найден в корзине']);
        exit;
    }

    // Удаляем товар из корзины
    $delete_sql = "DELETE FROM cart WHERE id = ?";
    $delete_stmt = $pdo->prepare($delete_sql);
    $delete_stmt->execute([$cart_id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка при удалении товара из корзины']);
} 