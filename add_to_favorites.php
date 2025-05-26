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
    // Проверяем существование товара
    $check_product = $pdo->prepare("SELECT id FROM products WHERE id = ?");
    $check_product->execute([$product_id]);
    if (!$check_product->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Товар не найден']);
        exit;
    }

    // Проверяем, есть ли уже такой товар в избранном
    $check_sql = "SELECT id FROM favorites WHERE user_id = ? AND product_id = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$user_id, $product_id]);
    $existing_item = $check_stmt->fetch();

    if ($existing_item) {
        // Если товар уже есть, удаляем его из избранного
        $delete_sql = "DELETE FROM favorites WHERE id = ?";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->execute([$existing_item['id']]);
        echo json_encode(['success' => true, 'message' => 'Товар удален из избранного', 'action' => 'removed']);
    } else {
        // Если товара нет, добавляем в избранное
        $insert_sql = "INSERT INTO favorites (user_id, product_id) VALUES (?, ?)";
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->execute([$user_id, $product_id]);
        echo json_encode(['success' => true, 'message' => 'Товар добавлен в избранное', 'action' => 'added']);
    }
} catch (PDOException $e) {
    error_log("Error in add_to_favorites.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Ошибка при работе с избранным']);
} 