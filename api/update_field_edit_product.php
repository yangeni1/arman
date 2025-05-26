<?php

require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Метод не поддерживается']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$productId = $data['productId'] ?? null;
$field = $data['field'] ?? null;
$value = $data['value'] ?? null;

if (!$productId || !$field || $value === null) {
    echo json_encode(['success' => false, 'error' => 'Недостаточно данных']);
    exit;
}

// Проверяем, чтобы поле было безопасным
$allowedFields = ['name', 'price', 'sale', 'description', 'category_id', 'brand_id', 'status_id'];
if (!in_array($field, $allowedFields)) {
    echo json_encode(['success' => false, 'error' => 'Запрещено обновлять это поле']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE product SET `$field` = ? WHERE id = ?");
    $stmt->execute([$value, $productId]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}