<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require 'db.php';

// Получаем данные пользователя
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заказы - Арман</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="container_main">
            <div class="profile-container">
                <h1>Личный кабинет</h1>
            </div>
            <div class="profile-menu">
                <a href="javascript:history.back()" class="profile-menu-item">Вернуться назад</a>
                <a href="my_orders_view.php" class="profile-menu-item">Мои заказы</a>
                <a href="my_adress_view.php" class="profile-menu-item">Мои адреса</a>
                <a href="setting_profile_view.php" class="profile-menu-item">Настройки профиля</a>
                <a href="logout.php" class="profile-menu-item">Выйти</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="container_main">
            <h2 class="center">Мои заказы</h2>
            <div class="container_card_catalog">
                <?php
                // Получаем заказанные товары пользователя из новой таблицы orders
                $stmt = $pdo->prepare('
                    SELECT o.id as order_id, o.quantity, o.datetime, o.status as order_status, o.address, o.phone,
                           p.id, p.name, p.price, p.image, p.category, p.discount_percentage, p.status as product_status
                    FROM orders o
                    JOIN products p ON o.product_id = p.id
                    WHERE o.user_id = ?
                    ORDER BY o.datetime DESC
                ');
                $stmt->execute([$_SESSION['user_id']]);
                $ordered_products = $stmt->fetchAll();

                if ($ordered_products) {
                    foreach ($ordered_products as $product) {
                        $discountPrice = null;
                        if (!empty($product['discount_percentage'])) {
                            $discountPrice = $product['price'] * (1 - $product['discount_percentage'] / 100);
                        }
                        $total_sum = 0;
                        $price_to_use = $discountPrice ?? $product['price'];
                        $line_total = $price_to_use * $product['quantity'];
                        $total_sum += $line_total;

                        $statusBadge = '';
                        if ($product['product_status'] === 'хит') {
                            $statusBadge = '<div class="badge_xit"><p>Хит</p></div>';
                        }

                        echo '<div class="swiper-slide product-card-catalog-2">';
                        echo $statusBadge;
                        echo '<img class="img_product-card" src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">';
                        echo '<span>' . htmlspecialchars($product['category']) . '</span>';
                        echo '<p>' . htmlspecialchars($product['name']) . '</p>';

                        echo '<div class="price">';
                        echo '<div class="price-values">';
                        if ($discountPrice) {
                            echo '<p>₽' . number_format($discountPrice, 2) . '</p>';
                            echo '<span>₽' . number_format($product['price'], 2) . '</span>';
                        } else {
                            echo '<p>₽' . number_format($product['price'], 2) . '</p>';
                        }
                        echo '</div>';
                        echo '<p class="order-total">Количество: ' . htmlspecialchars($product['quantity']) . '</p>';
                        echo '<div class="order-total"><p>Итоговая сумма заказа: ₽' . number_format($total_sum, 2) . '</p></div>';
                        echo '<div class="order-total"><p>Дата заказа: ' . htmlspecialchars($product['datetime']) . '</p></div>';
                        echo '<div class="order-total"><p>Адрес доставки: ' . htmlspecialchars($product['address']) . '</p></div>';
                        echo '<div class="order-total"><p>Телефон: ' . htmlspecialchars($product['phone']) . '</p></div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>У вас пока нет заказов.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
