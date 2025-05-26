<?php
require_once 'db.php';

// Получаем категорию из GET-параметра
$selectedCategory = $_GET['category'] ?? 'Все';

// Формируем SQL-запрос в зависимости от выбранной категории
$sql = "SELECT * FROM products WHERE rating > 4.5";
if ($selectedCategory !== 'Все') {
    $sql .= " AND category = :category";
}
$sql .= " ORDER BY 
          CASE 
              WHEN status = 'хит' THEN 1
              WHEN status = 'новинка' THEN 2
              WHEN status = 'распродажа' THEN 3
              WHEN discount_percentage IS NOT NULL THEN 4
              ELSE 5
          END, rating DESC
          LIMIT 14";

// Подготавливаем и выполняем запрос
$query = $pdo->prepare($sql);
if ($selectedCategory !== 'Все') {
    $query->execute([':category' => $selectedCategory]);
} else {
    $query->execute();
}
$products = $query->fetchAll(PDO::FETCH_ASSOC);

// Возвращаем HTML-разметку для товаров
foreach ($products as $product): 
    $discountPrice = $product['discount_percentage'] 
        ? $product['price'] * (1 - $product['discount_percentage'] / 100)
        : null;
?>


    <div class="swiper-slide product-card" data-description="<?= htmlspecialchars($product['description']) ?>">
        <?php if ($product['status'] == 'хит'): ?>
            <div class="badge_xit">
                <p>Хит</p>
            </div>
        <?php elseif ($product['status'] == 'распродажа'): ?>
            <div class="badge_rasp">
                <p>Распродажа</p>
            </div>
        <?php elseif ($product['status'] == 'новинка'): ?>
            <div class="badge_new">
                <p>Новинка</p>
            </div>
        <?php elseif ($product['discount_percentage']): ?>
            <div class="badge_sale">
                <p>-<?= round($product['discount_percentage']) ?>%</p>
            </div>
        <?php endif; ?>
        
        <img class="img_product-card" src="<?= htmlspecialchars($product['image'] ?? './media/popular-product/default-product.png') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        <span><?= htmlspecialchars($product['category']) ?></span>
        <p><?= htmlspecialchars($product['name']) ?></p>
        <div class="grade">
            <img src="./media/popular-product/иконка звезда отзывы.svg" alt="Рейтинг">
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