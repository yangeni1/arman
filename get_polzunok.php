<?php
include 'db.php';

header('Content-Type: application/json');

$category = isset($_GET['category']) ? $_GET['category'] : '';
$minPrice = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$maxPrice = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 1000;
$selectedBrands = isset($_GET['brands']) ? explode(',', $_GET['brands']) : [];
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'popularity';

$sql = "SELECT * FROM products WHERE category = :category";
$params = [':category' => $category];

if ($minPrice > 0) {
    $sql .= " AND price >= :min_price";
    $params[':min_price'] = $minPrice;
}

if ($maxPrice > 0) {
    $sql .= " AND price <= :max_price";
    $params[':max_price'] = $maxPrice;
}

if (!empty($selectedBrands)) {
    $placeholders = [];
    foreach ($selectedBrands as $i => $brand) {
        $placeholders[] = ":brand" . $i;
        $params[":brand" . $i] = $brand;
    }
    $sql .= " AND brand IN (" . implode(',', $placeholders) . ")";
}

switch ($sort) {
    case 'price_asc':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY price DESC";
        break;
    default:
        $sql .= " ORDER BY rating DESC";
}

$query = $pdo->prepare($sql);
$query->execute($params);
$products = $query->fetchAll(PDO::FETCH_ASSOC);

$html = '';
foreach ($products as $product):
    $discountPrice = $product['discount_percentage'] 
        ? $product['price'] * (1 - $product['discount_percentage'] / 100)
        : null;
    $html .= '<div class="swiper-slide product-card-catalog-1" data-description="' . htmlspecialchars($product['description']) . '">';
    if ($product['status'] === 'хит') {
        $html .= '<div class="badge_xit"><p>Хит</p></div>';
    }
    $html .= '<img class="img_product-card" src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">';
    $html .= '<span>' . htmlspecialchars($product['category']) . '</span>';
    $html .= '<p>' . htmlspecialchars($product['name']) . '</p>';
    $html .= '<div class="grade">';
    $html .= '<img src="./media/popular-product/иконка звезда отзывы.svg" alt="">';
    $html .= '<span>(' . number_format($product['rating'], 1) . ')</span>';
    $html .= '</div>';
    $html .= '<div class="price">';
    $html .= '<div class="price-values">';
    if ($discountPrice) {
        $html .= '<p>₽' . number_format($discountPrice, 2) . '</p>';
        $html .= '<span>₽' . number_format($product['price'], 2) . '</span>';
    } else {
        $html .= '<p>₽' . number_format($product['price'], 2) . '</p>';
    }
    $html .= '</div>';
    $html .= '<button class="add-to-cart-btn" data-product-id="' . $product['id'] . '">';
    $html .= '<img src="./media/popular-product/иконка добавить в корзину.svg" alt="Добавить в корзину">';
    $html .= '</button>';
    $html .= '</div>';
    $html .= '</div>';
endforeach;

echo json_encode([
    'success' => true,
    'html' => $html
]);
