<?php
require_once '../db.php';

try {
    $stmt = $pdo->query("
  SELECT 
    id,
    name,
    description,
    price,
    discount_percentage,
    status, -- или замени на NULL, если не нужен
    category,
    category_id,
    brand, -- или замени на NULL, если не нужен
    image,
    created_at
FROM 
    products
ORDER BY 
    id DESC;
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