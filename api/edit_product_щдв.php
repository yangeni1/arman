<?php
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
		$category = $_POST['category'];
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
            discount_percentage = ?, status = ?, category = ?, category_id = ?, 
            brand = ?, image = ? 
        WHERE id = ?
    ");
    $stmt->execute([
        $name, $description, $price, $discount_percentage,
        $status, $category, $category_id, $brand, $image, $product_id
    ]);

    echo "<p>Товар успешно обновлен!</p>";
    header("Location: ../product_list.php"); // перенаправление обратно
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
        body { font-family: Arial; padding: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], textarea, input[type="number"] { width: 100%; padding: 5px; }
        img { max-width: 200px; margin-top: 10px; }
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

    <label>Статус :
        <input type="text" name="status" value="<?= $product['status'] ?>">
    </label>

    <label>Категория ID:
        <input type="text" name="category_id" value="<?= $product['category_id'] ?>">
    </label>
		<label>Подкатегория
			<input type="text" name="category" value="<?=$product['category']?>">
		</label>

    <label>Бренд ID:
        <input type="text" name="brand" value="<?= $product['brand'] ?>">
    </label>

    <label>Изображение:
        <input type="file" name="image">
        <?php if ($product['image']): ?>
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="Текущее изображение">
        <?php endif; ?>
    </label>

    <button type="submit">Сохранить изменения</button>
</form>
<script src="../js/product_management.js"></script>

</body>
</html>