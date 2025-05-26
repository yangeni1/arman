<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['images'])) {
    echo json_encode(['success' => false, 'error' => 'Нет изображений для загрузки']);
    exit;
}

$productId = $_POST['productId'] ?? null;

if (!$productId) {
    echo json_encode(['success' => false, 'error' => 'Не указан ID товара']);
    exit;
}

// Проверяем существование папки
$uploadDir = __DIR__ . '/../uploads/products/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode(['success' => false, 'error' => 'Не удалось создать папку uploads']);
        exit;
    }
}

$uploadedImages = [];

foreach ($_FILES['images']['name'] as $i => $name) {
    $tmpName = $_FILES['images']['tmp_name'][$i];

    // Проверяем, был ли загружен файл
    if (!isset($tmpName) || !is_uploaded_file($tmpName)) {
        $uploadedImages[] = ['error' => "Файл {$name} не был загружен корректно"];
        continue;
    }

    // Генерируем новое имя
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $newName = uniqid('img_', true) . '.' . $ext;
    $filePath = $uploadDir . $newName;

    // Перемещаем файл
    if (move_uploaded_file($tmpName, $filePath)) {
        // Сохраняем только имя файла в БД
        $stmt = $pdo->prepare("INSERT INTO image (image_url, main_image) VALUES (?, 0)");
        $stmt->execute([$newName]);
        $imageId = $pdo->lastInsertId();

        // Связываем с товаром
        $pdo->prepare("INSERT INTO product_images (product_id, image_id) VALUES (?, ?)")
           ->execute([$productId, $imageId]);

        $uploadedImages[] = $newName;
    } else {
        $uploadedImages[] = ['error' => "Не удалось сохранить файл {$name}"];
    }
}

echo json_encode(['success' => true, 'images' => $uploadedImages]);