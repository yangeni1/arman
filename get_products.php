<?php
require_once 'db.php';

// Получаем категорию из GET-параметра
$selectedCategory = $_GET['category'] ?? 'Все';

$params = [];
$sql = "SELECT p.*, COALESCE(SUM(o.quantity), 0) AS total_ordered
        FROM products p
        LEFT JOIN orders o ON p.id = o.product_id
        WHERE 1=1";

if ($selectedCategory !== 'Все') {
    $sql .= " AND p.category = :category";
    $params[':category'] = $selectedCategory;
}

$sql .= " GROUP BY p.id
          ORDER BY total_ordered DESC
          LIMIT 14";

$query = $pdo->prepare($sql);
$query->execute($params);
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
        <span><?= htmlspecialchars($product['category_id'] ?? 'Без категории') ?></span>
        <p><?= htmlspecialchars($product['name']) ?></p>
        <div class="price">
            <div class="price-values">
                <?php if ($discountPrice): ?>
                    <p>₽<?= number_format($discountPrice, 2) ?></p>
                    <span>₽<?= number_format($product['price'], 2) ?></span>
                <?php else: ?>
                    <p>₽<?= number_format($product['price'], 2) ?></p>
                <?php endif; ?>
            </div>
            <div class="product-buttons">
                    <button class="add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                        <img src="./media/popular-product/иконка добавить в корзину.svg" alt="Добавить в корзину">
                    </button>
                        <button class="add-to-favorites-btn" data-product-id="<?= $product['id'] ?>">
                            <img class="img_favorites" src="./media/modal/Vector (2).png" alt="Добавить в избранное">
                        </button>
                </div>
        </div>
    </div>
<?php endforeach; ?>
