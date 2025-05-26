<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Метод не поддерживается']);
    exit;
}

// Получаем productId из $_POST
$productId = $_POST['productId'] ?? null;

// Поля, которые можно обновить
$fields = [
    'name',
    'price',
    'sale',
    'description',
    'category_id',
    'brand_id',
    'status_id'
];

// Собираем только те поля, которые были переданы
$updateData = [];
foreach ($fields as $field) {
    if (isset($_POST[$field])) {
        $updateData[$field] = $_POST[$field];
    }
}

// Проверяем, есть ли ID товара и данные для обновления
if (!$productId || empty($updateData)) {
    echo json_encode(['success' => false, 'error' => 'Недостаточно данных']);
    exit;
}

try {
    $setClause = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($updateData)));
    $values = array_values($updateData);
    $values[] = $productId;

    $stmt = $pdo->prepare("UPDATE product SET $setClause WHERE id = ?");
    $stmt->execute($values);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}