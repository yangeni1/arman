
<?php
// Подключение к базе данных (замените на свои данные)
require '../db.php';


// Проверка наличия ID товара
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Неверный ID товара");
}
$product_id = $_GET['id'];

// Если форма отправлена — обновляем товар
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount_percentage = $_POST['discount_percentage'] ?? 0;
    $status = $_POST['status'] ?? 1;
    $category_id = $_POST['category_id'] ?? null;
    $brand = $_POST['brand'] ?? null;

    // Обработка загрузки нового изображения
    $image = $_POST['current_image']; // сохраняем текущее значение по умолчанию
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = 'uploads/products/';
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = uniqid('img_', true) . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Удаляем старое изображение, если оно не стандартное
                if ($image && file_exists($image)) {
                    unlink($image);
                }
                $image = $upload_path;
            } else {
                echo "<p class='error'>Ошибка при загрузке файла</p>";
            }
        } else {
            echo "<p class='error'>Недопустимый формат файла</p>";
        }
    }

    // Обновление записи в БД
    $stmt = $pdo->prepare("
        UPDATE products SET 
            name = ?, description = ?, price = ?,  
            discount_percentage = ?, status = ?, category_id = ?, 
            brand = ?, image = ? 
        WHERE id = ?
    ");
    $stmt->execute([
        $name, $description, $price, $discount_percentage,
        $status, $category_id, $brand, $image, $product_id
    ]);

    echo "<p>Товар успешно обновлен!</p>";
    header("Location: /product_list.php"); // перенаправление обратно
    exit();
}

// Получаем данные товара из БД
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Товар не найден");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать товар</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        padding: 40px;
    }

    .container {
        max-width: 800px;
        margin: auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
        font-size: 14px;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    img {
        max-width: 200px;
        margin-top: 10px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    button {
        margin-top: 25px;
        padding: 12px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        text-align: center;
        margin-bottom: 20px;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border: 1px solid #f5c6cb;
        border-radius: 6px;
        text-align: center;
        margin-bottom: 20px;
    }
</style>
</head>
<body>

<h2>Редактировать товар</h2>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="current_image" value="<?= htmlspecialchars($product['image']) ?>">

    <label>Название:
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
    </label>

    <label>Описание:
        <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
    </label>

    <label>Цена:
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
    </label>
    <label>Скидка (%):
        <input type="number" step="1" name="discount_percentage" value="<?= $product['discount_percentage'] ?>">
    </label>

    <label>Статус ID:
        <input type="number" name="status" value="<?= $product['status'] ?>">
    </label>

    <label>Категория ID:
        <input type="number" name="category_id" value="<?= $product['category_id'] ?>">
    </label>

    <label>Бренд ID:
        <input type="number" name="brand" value="<?= $product['brand'] ?>">
    </label>

    <label>Изображение:
        <input type="file" name="image">
        <?php if ($product['image']): ?>
            <img src="/<?= htmlspecialchars($product['image']) ?>" alt="Текущее изображение">
        <?php endif; ?>
    </label>

    <button type="submit">Сохранить изменения</button>
</form>

</body>
</html>