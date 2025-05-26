<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Необходимо авторизоваться']);
    exit;
}

if (!isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Не указан ID товара']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];

try {
    // Проверяем, есть ли уже такой товар в корзине
    $check_sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$user_id, $product_id]);
    $existing_item = $check_stmt->fetch();

    if ($existing_item) {
        // Если товар уже есть, увеличиваем количество
        $update_sql = "UPDATE cart SET quantity = quantity + 1 WHERE id = ?";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([$existing_item['id']]);
    } else {
        // Если товара нет, добавляем новую запись
        $insert_sql = "INSERT INTO cart (user_id, product_id) VALUES (?, ?)";
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->execute([$user_id, $product_id]);
    }

    echo json_encode(['success' => true, 'message' => 'Товар добавлен в корзину']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка при добавлении товара в корзину']);
} 