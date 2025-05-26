<?php
require_once 'db.php';

// Получаем категорию из GET-параметра
$category = $_GET['category'] ?? 'Все';

// Формируем SQL-запрос для товаров со скидками
$sql = "SELECT * FROM products 
        WHERE (discount_percentage IS NOT NULL OR status = 'распродажа')";

// Добавляем фильтр по категории если нужно
if ($category !== 'Все') {
    $sql .= " AND category = :category";
}

// Сортировка для акционных товаров
$sql .= " ORDER BY 
          CASE 
              WHEN status = 'распродажа' THEN 1
              ELSE 2
          END, 
          discount_percentage DESC,
          rating DESC
          LIMIT 12";

// Подготавливаем и выполняем запрос
$query = $pdo->prepare($sql);
if ($category !== 'Все') {
    $query->execute([':category' => $category]);
} else {
    $query->execute();
}
$products = $query->fetchAll(PDO::FETCH_ASSOC);

// Выводим HTML для каждого товара
foreach ($products as $product): 
    $discountPercent = $product['discount_percentage'] ?? ($product['status'] == 'распродажа' ? 10 : null);
    $discountPrice = $discountPercent ? $product['price'] * (1 - $discountPercent / 100) : null;
?>
    <div class="swiper-slide product-card" data-description="<?= htmlspecialchars($product['description']) ?>">
        <?php if ($product['status'] == 'распродажа'): ?>
            <div class="badge_rasp">
                <p>Распродажа</p>
            </div>
        <?php elseif ($discountPercent): ?>
            <div class="badge_sale">
                <p>-<?= round($discountPercent) ?>%</p>
            </div>
        <?php endif; ?>
        
        <img class="img_product-card" src="<?= htmlspecialchars($product['image'] ?? './media/sale/default.png') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        <span><?= htmlspecialchars($product['category']) ?></span>
        <p><?= htmlspecialchars($product['name']) ?></p>
        <div class="grade">
            <img src="./media/popular-product/иконка звезда отзывы.svg" alt="">
            <span>(<?= number_format($product['rating'], 1) ?>)</span>
        </div>
        <div class="price">
            <div class="price-values">
                <?php if ($discountPrice): ?>
                    <p>₽<?= number_format($discountPrice, 2) ?></p>
                    <span>₽<?= number_format($product['price'], 2) ?></span>
                <?php else: ?>
                    <p>₽<?= number_format($product['price'], 2) ?></p>
                <?php endif; ?>
            </div>
            <button class="add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                <img src="./media/popular-product/иконка добавить в корзину.svg" alt="Добавить в корзину">
            </button>
        </div>
    </div>
<?php endforeach; ?>