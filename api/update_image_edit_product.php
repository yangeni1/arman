<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Метод не поддерживается']);
    exit;
}

$imageId = $_POST['imageId'] ?? null;

if (!$imageId) {
    echo json_encode(['success' => false, 'error' => 'Не указан ID изображения']);
    exit;
}

$uploadDir = __DIR__ . '/../uploads/products/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode(['success' => false, 'error' => 'Не удалось создать папку uploads']);
        exit;
    }
}

try {
    // Получаем текущее изображение
    $stmt = $pdo->prepare("SELECT image_url FROM image WHERE id = ?");
    $stmt->execute([$imageId]);
    $currentImage = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($currentImage) {
        $oldFilePath = __DIR__ . '/../uploads/products/' . $currentImage['image_url'];
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath); // Удаляем старое изображение
        }
    }

    // Проверяем новый файл
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Ошибка при загрузке файла");
    }

    $tmpName = $_FILES['image']['tmp_name'];
    $name = basename($_FILES['image']['name']);
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $newName = uniqid('img_', true) . '.' . $ext;
    $newFilePath = $uploadDir . $newName;

    if (!move_uploaded_file($tmpName, $newFilePath)) {
        throw new Exception("Не удалось сохранить файл");
    }

    // Обновляем только имя файла в БД
    $pdo->prepare("UPDATE image SET image_url = ? WHERE id = ?")
       ->execute([$newName, $imageId]);

    echo json_encode(['success' => true, 'message' => 'Изображение успешно обновлено']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}