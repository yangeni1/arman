<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/db.php';

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$files = $_FILES;

// Валидация данных
$requiredFields = ['status_id', 'category_id', 'brand_id', 'name', 'price', 'quantity'];
$errors = [];

foreach ($requiredFields as $field) {
    if (empty($input[$field])) {
        $errors[] = "Поле $field обязательно для заполнения";
    }
}

// Валидация изображений
if (empty($files['images']['tmp_name'][0])) {
    $errors[] = "Необходимо загрузить хотя бы одно изображение товара";
} else {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    
    foreach ($files['images']['tmp_name'] as $index => $tmpName) {
        if (empty($tmpName)) continue;
        
        $mimeType = finfo_file($fileInfo, $tmpName);
        if (!in_array($mimeType, $allowedTypes)) {
            $errors[] = "Допустимы только изображения JPG, PNG или GIF";
            break;
        }
        
        if ($files['images']['size'][$index] > 2 * 1024 * 1024) {
            $errors[] = "Размер изображения не должен превышать 2MB";
            break;
        }
    }
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode("\n", $errors)]);
    exit;
}

// Загрузка изображений
$uploadDir = __DIR__ . '/uploads/products/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$uploadedImages = [];
$mainImageIndex = isset($input['main_image']) ? (int)$input['main_image'] : 0;

foreach ($files['images']['tmp_name'] as $index => $tmpName) {
    if (empty($tmpName)) continue;
    
    $filename = uniqid() . '_' . basename($files['images']['name'][$index]);
    $targetPath = $uploadDir . $filename;
    
    if (move_uploaded_file($tmpName, $targetPath)) {
        $isMain = ($index === $mainImageIndex) ? 1 : 0;
        $uploadedImages[] = ['filename' => $filename, 'is_main' => $isMain];
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Ошибка при сохранении изображения']);
        exit;
    }
}

// Если ни одно изображение не помечено как главное, делаем первое главным
if (!empty($uploadedImages) && !in_array(1, array_column($uploadedImages, 'is_main'))) {
    $uploadedImages[0]['is_main'] = 1;
}

// Сохранение в БД
try {
    $pdo->beginTransaction();

    // Добавление товара
    $stmtProduct = $pdo->prepare("INSERT INTO product 
        (name, price, category_id, status_id, brand_id, stock_initial, stock_remaining, sale, description) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmtProduct->execute([
        $input['name'],
        (float)$input['price'],
        (int)$input['category_id'],
        (int)$input['status_id'],
        (int)$input['brand_id'],
        (int)$input['quantity'],
        (int)$input['quantity'],
        (float)($input['discount'] ?? 0),
        $input['description'] ?? ''
    ]);
    
    $productId = $pdo->lastInsertId();

    // Добавление изображений
    $imageIds = [];
    foreach ($uploadedImages as $image) {
        $stmtImage = $pdo->prepare("INSERT INTO image (image_url, main_image) VALUES (?, ?)");
        $stmtImage->execute([$image['filename'], $image['is_main']]);
        $imageId = $pdo->lastInsertId();
        $imageIds[] = $imageId;

        // Связь товара с изображением
        $stmtLink = $pdo->prepare("INSERT INTO product_images (product_id, image_id) VALUES (?, ?)");
        $stmtLink->execute([$productId, $imageId]);
    }

    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Товар успешно добавлен',
        'product_id' => $productId,
        'image_ids' => $imageIds
    ]);
    
} catch (PDOException $e) {
    $pdo->rollBack();
    
    // Удаление загруженных файлов при ошибке
    foreach ($uploadedImages as $image) {
        $filePath = $uploadDir . $image['filename'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Ошибка базы данных',
        'error_details' => $e->getMessage()
    ]);
    
    error_log("DB Error: " . $e->getMessage());
}
?>