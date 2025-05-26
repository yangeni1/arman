<?php
require_once '../db.php';

try {
    $stmt = $pdo->query("
        SELECT p.*, 
               c.name as category_name, 
               b.name as brand_name, 
               s.status as status_name,
               (SELECT CONCAT('/uploads/products/', i.image_url)
                FROM product_images pi
                JOIN image i ON pi.image_id = i.id
                WHERE pi.product_id = p.id AND i.main_image = 1
                LIMIT 1) AS image_url
        FROM product p
        LEFT JOIN category c ON p.category_id = c.id
        LEFT JOIN brand b ON p.brand_id = b.id
        LEFT JOIN status s ON p.status_id = s.id
        ORDER BY p.id DESC
    ");

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as &$product) {
        // Если изображение не найдено, ставим дефолтное
        if (empty($product['image_url'])) {
            $product['image_url'] = '/uploads/products/default-product.jpg';
        } elseif (!filter_var($product['image_url'], FILTER_VALIDATE_URL)) {
            // Делаем путь относительным
            $product['image_url'] = '/' . ltrim($product['image_url'], '/');
        }
    }

    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка базы данных: ' . $e->getMessage()
    ]);
}