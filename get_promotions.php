<?php
require_once 'db.php';

$category = $_GET['category'] ?? 'Все';

$params = [];
$sqlCategories = "SELECT p.category, COUNT(*) AS discount_count
                  FROM products p
                  WHERE p.discount_percentage IS NOT NULL OR p.status = 'распродажа'
                  GROUP BY p.category
                  ORDER BY discount_count DESC
                  LIMIT 5";
$categoriesQuery = $pdo->query($sqlCategories);
$dbCategories = $categoriesQuery->fetchAll(PDO::FETCH_COLUMN);

$categories = array_merge(['Все'], $dbCategories);

$sql = "SELECT * FROM products 
        WHERE (discount_percentage IS NOT NULL OR status = 'распродажа')";

if ($category !== 'Все') {
    $sql .= " AND category = :category";
    $params[':category'] = $category;
}

$sql .= " ORDER BY 
          CASE 
              WHEN status = 'распродажа' THEN 1
              ELSE 2
          END, 
          discount_percentage DESC,
          rating DESC
          LIMIT 5";

$query = $pdo->prepare($sql);
$query->execute($params);
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
