<?php
include 'db.php';

header('Content-Type: application/json');

// Получаем параметры из URL
$category = isset($_GET['category']) ? $_GET['category'] : '';
$minPrice = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$maxPrice = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 1000;
$selectedBrands = isset($_GET['brands']) ? explode(',', $_GET['brands']) : [];
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'popularity';
$subcategory_id = isset($_GET['subcategory_id']) ? trim($_GET['subcategory_id']) : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    // Базовый SQL-запрос
    $sql = "SELECT p.* FROM products p WHERE 1=1";

    $params = [];

    // Фильтр по категории
    if (!empty($category) && $category !== 'Все товары') {
        $sql .= " AND p.category = ?";
        $params[] = $category;
    }

    // Фильтр по подкатегории (category_id)
    if ($subcategory_id !== null && $subcategory_id !== '0' && $subcategory_id !== 'Все') {
        $sql .= " AND p.category_id LIKE ?";
        $params[] = $subcategory_id;
    } else {
        // No filtering by category_id if subcategory_id is '0', 'Все' or null (all subcategories)
    }

    // Фильтр по минимальной цене
    if ($minPrice > 0) {
        $sql .= " AND p.price >= ?";
        $params[] = $minPrice;
    }

    // Фильтр по максимальной цене
    if ($maxPrice > 0) {
        $sql .= " AND p.price <= ?";
        $params[] = $maxPrice;
    }

    // Фильтр по брендам
    if (!empty($selectedBrands)) {
        $placeholders = implode(',', array_fill(0, count($selectedBrands), '?'));
        $sql .= " AND p.brand IN ($placeholders)";
        $params = array_merge($params, $selectedBrands);
    }

    // Фильтр по поисковому запросу
    if (!empty($search)) {
        $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
        $searchParam = '%' . $search . '%';
        $params[] = $searchParam;
        $params[] = $searchParam;
    }

    // Сортировка
    switch ($sort) {
        case 'price_asc':
            $sql .= " ORDER BY p.price ASC";
            break;
        case 'price_desc':
            $sql .= " ORDER BY p.price DESC";
            break;
        default:
            $sql .= " ORDER BY p.rating DESC"; // По популярности (рейтингу)
            break;
    }

    // Выполняем запрос
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Генерируем HTML для карточек товаров
    $html = '';

    foreach ($products as $product) {
        $discountPrice = null;
        if (!empty($product['discount_percentage'])) {
            $discountPrice = $product['price'] * (1 - $product['discount_percentage'] / 100);
        }

        // Отображение статуса (хит/новинка/распродажа)
        $statusBadge = '';
        if ($product['status'] === 'хит') {
            $statusBadge = '<div class="badge_xit"><p>Хит</p></div>';
        }
        if ($product['status'] === 'новинка') {
            $statusBadge = '<div class="badge_xit"><p>Новинка</p></div>';
        }
        if ($product['status'] === 'распродажа') {
            $statusBadge = '<div class="badge_rasp"><p>Распродажа</p></div>';
        }
      
        // Формируем HTML-карточку товара
        $html .= '
        <div class="swiper-slide product-card-catalog-1" data-description="' . htmlspecialchars($product['description']) . '">
            ' . $statusBadge . '
            <img class="img_product-card" src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">
            <span>' . htmlspecialchars($product['category_id'] ?? 'Без категории') . '</span>
            <p>' . htmlspecialchars($product['name']) . '</p>
            <div class="price">
                <div class="price-values">';
        if ($discountPrice) {
            $html .= '
                    <p>₽' . number_format($discountPrice, 2) . '</p>
                    <span>₽' . number_format($product['price'], 2) . '</span>';
        } else {
            $html .= '
                    <p>₽' . number_format($product['price'], 2) . '</p>';
        }
        $html .= '
                </div>
                <div class="product-buttons">
                    <button class="add-to-cart-btn" data-product-id="' . $product['id'] . '">
                        <img src="./media/popular-product/иконка добавить в корзину.svg" alt="Добавить в корзину">
                    </button>
                    <button class="add-to-favorites-btn" data-product-id="' . $product['id'] . '">
                        <img class="img_favorites" src="./media/modal/Vector (2).png" alt="Добавить в избранное">
                    </button>
                </div>
            </div>
        </div>';
    }

    header('X-Debug-SQL: ' . base64_encode($sql));
    echo json_encode(['success' => true, 'html' => $html, 'debug_params' => $params]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Ошибка базы данных: ' . $e->getMessage()]);
}