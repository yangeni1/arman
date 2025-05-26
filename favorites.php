<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Арман</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .favorites_products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
        }
        .product-card-catalog {
            position: relative;
            width: calc(25% - 20px);
            margin-bottom: 20px;
        }
        .remove-from-favorites {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            z-index: 2;
            padding: 5px;
            font-size:20px;
        }
        .remove-from-favorites img {
            width: 20px;
            height: 20px;
        }
        .remove-from-favorites:hover img {
            opacity: 0.8;
        }
        @media (max-width: 1200px) {
            .product-card-catalog {
                width: calc(33.33% - 20px);
            }
        }
        @media (max-width: 900px) {
            .product-card-catalog {
                width: calc(50% - 20px);
            }
        }
        @media (max-width: 600px) {
            .product-card-catalog {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php
        include 'db.php';
        include 'header.php';
    ?>
    <div class="container">
        <div class="container_main">
            <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="basket_main">
                <div class="basket_main_txt">
                    <a href="#" id="loginLink">Войдите в личный кабинет,</a>
                    <p>чтобы добавить товары в избранное</p>
                </div>
            </div>
            <?php else: ?>
            <div class="favorites_main">
                <h2>Избранные товары</h2>
                <div class="favorites_products">
                    <?php
                    $sql = "SELECT p.* FROM products p 
                           INNER JOIN favorites f ON p.id = f.product_id 
                           WHERE f.user_id = ?";
                    $query = $pdo->prepare($sql);
                    $query->execute([$_SESSION['user_id']]);
                    $favorites = $query->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($favorites)): ?>
                        <p class="empty-favorites">У вас пока нет избранных товаров</p>
                    <?php else:
                        foreach ($favorites as $product):
                            $discountPrice = $product['discount_percentage'] 
                                ? $product['price'] * (1 - $product['discount_percentage'] / 100)
                                : null;
                    ?>
                        <div class="product-card-catalog" data-description="<?= htmlspecialchars($product['description']) ?>">
                            <button class="remove-from-favorites" data-product-id="<?= $product['id'] ?>">×</button>
                            <?php if ($product['status'] === 'хит'): ?>
                            <div class="badge_xit">
                                <p>Хит</p>
                            </div>
                            <?php endif; ?>
                            <img class="img_product-card" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
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
                                <div class="product-buttons">
                                    <button class="add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                                        <img src="./media/popular-product/иконка добавить в корзину.svg" alt="Добавить в корзину">
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
        include 'footer.php';
    ?>
    <script src="js/modal.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/product-modal.js"></script>
    <script src="js/favorites.js"></script>
    <script>
        document.getElementById('loginLink')?.addEventListener('click', function(e) {
            e.preventDefault();
            const popup = document.querySelector('.popup');
            if (popup) {
                popup.style.display = 'flex';
                const popupLogButton = document.querySelector('.popup_log');
                const popupRegButton = document.querySelector('.popup_reg');
                const inputFieldsReg = document.querySelector('.input_fields_reg');
                const inputFieldsLog = document.querySelector('.input_fields_login');
                
                popupLogButton.style.color = "#5E9F67";
                popupRegButton.style.color = "#253D4E";
                inputFieldsReg.style.display = 'none';
                inputFieldsLog.style.display = 'block';
            }
        });

        // Обработчик для кнопок удаления из избранного
        document.querySelectorAll('.remove-from-favorites').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = this.getAttribute('data-product-id');
                if (!productId) return;

                try {
                    const formData = new FormData();
                    formData.append('product_id', productId);

                    const response = await fetch('add_to_favorites.php', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) throw new Error('Ошибка при удалении из избранного');
                    
                    const data = await response.json();
                    if (data.success) {
                        // Удаляем карточку товара из DOM
                        this.closest('.product-card-catalog').remove();
                        
                        // Если больше нет товаров, показываем сообщение
                        const remainingProducts = document.querySelectorAll('.product-card-catalog');
                        if (remainingProducts.length === 0) {
                            const container = document.querySelector('.favorites_products');
                            container.innerHTML = '<p class="empty-favorites">У вас пока нет избранных товаров</p>';
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>
</body>
</html>