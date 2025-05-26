<?php




require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Метод не поддерживается']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$imageId = $data['imageId'] ?? null;
$productId = $data['productId'] ?? null;

if (!$imageId || !$productId) {
    echo json_encode(['success' => false, 'error' => 'Неверные данные']);
    exit;
}

try {
    // Получаем URL изображения
    $stmt = $pdo->prepare("SELECT image_url FROM image WHERE id = ?");
    $stmt->execute([$imageId]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($image) {
        // Удаляем связь с товаром
        $pdo->prepare("DELETE FROM product_images WHERE product_id = ? AND image_id = ?")
            ->execute([$productId, $imageId]);

        // Удаляем само изображение из файловой системы
        $filePath = __DIR__ . '/../uploads/products/' . $image['image_url'];
        if (file_exists($filePath)) unlink($filePath);

        // Удаляем запись из базы
        $pdo->prepare("DELETE FROM image WHERE id = ?")->execute([$imageId]);
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}