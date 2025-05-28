<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Обработка загруженного файла
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = $targetPath;
            }
        }
       
        // Подготовка данных товара
        $product = [
            'status' => htmlspecialchars(strip_tags($_POST['status_id'])),
            'discount_percentage' => htmlspecialchars(strip_tags($_POST['discount'])),
            'category' => htmlspecialchars(strip_tags($_POST['category_id'])),
            'category_id' => htmlspecialchars(strip_tags($_POST['categories'])),
            'name' => htmlspecialchars(strip_tags($_POST['name'])),
            'brand' => htmlspecialchars(strip_tags($_POST['brand_id'])),
            'price' => round(max((float)$_POST['price'], 0), 2),
            'description' => htmlspecialchars(strip_tags($_POST['description'])),
            'image' => $imagePath
        ];

        // SQL-запрос для новой структуры таблицы
        $sql = "INSERT INTO products (
                    status, discount_percentage, category, name, category_id, 
                     price, brand, description, image
                ) VALUES (
                    :status, :discount_percentage, :category, :name, :category_id,
                     :price,  :brand,  :description, :image
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($product);

        // Успешный ответ
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true, 
            'id' => $pdo->lastInsertId(),
            'image' => $imagePath,
            'message' => 'Товар успешно добавлен'
        ]);
        exit;

    } catch (PDOException $e) {
        // Обработка ошибок базы данных
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'error' => 'Database error',
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
              'query' => $sql ?? null, // Добавляем запрос для отладки
            'params' => $product ?? null // Добавляем параметры для отладки
        ]);
        exit;
    } catch (Exception $e) {
        // Обработка других ошибок
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode([
            'error' => 'Validation error',
            'message' => $e->getMessage()
        ]);
        exit;
    }
}

// Ошибка, если запрос не POST
http_response_code(405);
echo json_encode([
    'error' => 'Method not allowed',
    'message' => 'Используйте метод POST для добавления товара'
]);