<?php
include 'db.php';

// Получаем все уникальные категории из products.category
$stmt = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все категории</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper @11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="container_main">
        <h1>Категории товаров</h1>

        <?php if (!empty($categories)): ?>
            <div class="catalog_main_menu_list_item">
                <?php foreach ($categories as $category): ?>
                    <a href="catalog_products.php?category=<?= urlencode($category) ?>&subcategory_id=0">
                        <?= htmlspecialchars($category) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Нет доступных категорий.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>