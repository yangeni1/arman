<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        // Удаляем связанные изображения и сам товар
        $pdo->beginTransaction();

        // Получаем ID изображений для удаления файлов с диска
        $stmt = $pdo->prepare("SELECT image_id FROM product_images WHERE product_id = ?");
        $stmt->execute([$id]);
        $imageIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Удаляем связи из product_images
        $stmt = $pdo->prepare("DELETE FROM product_images WHERE product_id = ?");
        $stmt->execute([$id]);

        // Удаляем изображения из таблицы image
        $stmt = $pdo->prepare("DELETE FROM image WHERE id IN (" . implode(',', array_map('intval', $imageIds)) . ")");
        $stmt->execute();

        // Удаляем сам товар
        $stmt = $pdo->prepare("DELETE FROM product WHERE id = ?");
        $stmt->execute([$id]);

        $pdo->commit();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка удаления: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Неверный запрос'
    ]);
}