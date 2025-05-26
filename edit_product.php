<?php
require_once 'db.php';
if (!isset($_GET['id'])) {
    header("Location: product_list.php");
    exit();
}

$productId = $_GET['id'];

// Получаем данные товара
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name, b.name as brand_name, s.status as status_name
    FROM product p
    LEFT JOIN category c ON p.category_id = c.id
    LEFT JOIN brand b ON p.brand_id = b.id
    LEFT JOIN status s ON p.status_id = s.id
    WHERE p.id = ?
");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: product_list.php");
    exit();
}

// Получаем изображения товара
$stmt = $pdo->prepare("
    SELECT i.id, i.image_url, i.main_image
    FROM product_images pi
    JOIN image i ON pi.image_id = i.id
    WHERE pi.product_id = ?
    ORDER BY i.main_image DESC
");
$stmt->execute([$productId]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем категории, бренды и статусы
$categories = $pdo->query("SELECT id, name FROM category")->fetchAll();
$brands = $pdo->query("SELECT id, name FROM brand")->fetchAll();
$statuses = $pdo->query("SELECT id, status FROM status")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование товара</title>
    <link rel="stylesheet" href="css/edit_product.css">
</head>
<body>
    <div class="container_main_admin">
        <h1>Редактирование товара: <?= htmlspecialchars($product['name']) ?></h1>

        <form id="product-form" data-id="<?= $product['id'] ?>">
            <!-- Название -->
            <div class="form-group">
                <label>Название:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>">
                <button type="button" class="apply-field" data-field="name">Применить</button>
            </div>

            <!-- Цена -->
            <div class="form-group">
                <label>Цена:</label>
                <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>">
                <button type="button" class="apply-field" data-field="price">Применить</button>
            </div>

            <!-- Скидка -->
            <div class="form-group">
                <label>Скидка (%):</label>
                <input type="number" step="1" min="0" max="100" name="sale" value="<?= $product['sale'] ?? 0 ?>">
                <button type="button" class="apply-field" data-field="sale">Применить</button>
            </div>

            <!-- Цены со скидкой -->
            <div class="form-group">
                <label>Цена со скидкой:</label>
                <output id="discounted-price">
                    <?= number_format($product['price'] * (1 - ($product['sale'] / 100)), 2) ?> ₽
                </output>
            </div>

            <!-- Категория -->
            <div class="form-group">
                <label>Категория:</label>
                <select name="category_id">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="apply-field" data-field="category_id">Применить</button>
            </div>

            <!-- Бренд -->
            <div class="form-group">
                <label>Бренд:</label>
                <select name="brand_id">
                    <?php foreach ($brands as $brand): ?>
                        <option value="<?= $brand['id'] ?>" <?= $brand['id'] == $product['brand_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($brand['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="apply-field" data-field="brand_id">Применить</button>
            </div>

            <!-- Статус -->
            <div class="form-group">
                <label>Статус:</label>
                <select name="status_id">
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['id'] ?>" <?= $status['id'] == $product['status_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status['status']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="apply-field" data-field="status_id">Применить</button>
            </div>

            <!-- Описание -->
            <div class="form-group">
                <label>Описание:</label>
                <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
                <button type="button" class="apply-field" data-field="description">Применить</button>
            </div>

            <!-- Кнопка сохранения всех изменений -->
            <button type="button" class="save-all">Сохранить все изменения</button>
        </form>

        <h2>Изображения товара</h2>
        <div class="product-images">
            <?php foreach ($images as $image): ?>
                <div class="image-container<?= $image['main_image'] ? ' main-image' : '' ?>" data-image-id="<?= $image['id'] ?>">
                    <img src="/uploads/products/<?= basename($image['image_url']) ?>" alt="Товарное изображение" class="image-preview">
                    <div class="image-actions">
                        <input type="file" class="image-file" accept="image/*">
                        <button class="update-image">Обновить</button>
                        <button class="set-main-image"<?= $image['main_image'] ? ' disabled' : '' ?>>Сделать главной</button>
                        <button class="delete-image">Удалить</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Форма добавления новых изображений -->
        <div class="add-image">
            <h3>Добавить изображения</h3>
            <input type="file" id="new-image-file" name="images[]" multiple accept="image/*">
            <button id="add-image">Загрузить и добавить</button>
            <div id="image-preview-container" style="margin-top:10px;"></div>
        </div>
    </div>
    <script src="js/product_management.js"></script>
</body>
</html>