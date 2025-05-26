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
    // Сначала устанавливаем все изображения как не главные
    $pdo->exec("UPDATE image SET main_image = 0 WHERE id IN (
        SELECT image_id FROM product_images WHERE product_id = $productId
    )");

    // Затем делаем выбранное изображение главным
    $pdo->prepare("UPDATE image SET main_image = 1 WHERE id = ?")->execute([$imageId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}