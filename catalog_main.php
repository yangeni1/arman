<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Арман</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php
        include 'db.php';
        include 'header.php';
    ?>
    <style>
        .img_favorites {
            width: 25px;
            height: 25px;
            cursor: pointer;
            background: #fff;
            padding: 5px;
            transition: all 0.3s ease;
            background: #5E9F67;
        }
        .img_favorites:hover {
            background: #5E9F67;
        }
    </style>
    <div class="container">
        <div class="container_main">
            <div class="catalog_main">
                <div class="catalog_main_menu">
                    <p>Конфеты</p>
                    <div class="catalog_main_menu_list">
                        <p>Категории товаров</p>
                        <div class="catalog_main_menu_list_item">
                            <a href="catalog_products.php?category=шоколадные конфеты">Шоколадные конфеты</a>
                            <a href="catalog_products.php?category=драже">Драже</a>
                            <a href="catalog_products.php?category=карамель">Карамель</a>
                            <a href="catalog_products.php?category=конфеты желейные">Конфеты желейные</a>
                            <a href="catalog_products.php?category=батончики">Батончики</a>
                            <a href="catalog_products.php?category=ирис, ирисовые конфеты">Ирис</a>
                            <a href="catalog_products.php?category=леденцы">Леденцы</a>
                        </div>
                    </div>
                </div>
                <div class="chavo">
                    <div class="slider_catalog_menu">
                        <div class="swiper-button-prev custom-prev4">
                            <img src="./media/popular-product/Tabpanel-left.png" alt="Previous">
                        </div>
                        <div class="swiper4">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide card_categori">
                                    <a href="catalog_products.php?category=шоколадные конфеты">
                                        <div class="card_categories">
                                            <div class="card_categories_pink">
                                                <img src="./media/recom-card-category/конфеты.png" alt="Шоколадные конфеты">
                                            </div>
                                            <p>Шоколадные конфеты</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="swiper-slide card_categori">
                                    <a href="catalog_products.php?category=драже    ">
                                        <div class="card_categories">
                                            <div class="card_categories_linght_green">
                                                <img src="./media/catalog_main/драже.png" alt="драже">
                                            </div>
                                            <p>Драже</p>
                                        </div>
                                    </a>        
                                </div>
                                <div class="swiper-slide card_categori">
                                    <a href="catalog_products.php?category=карамель">
                                        <div class="card_categories">
                                            <div class="card_categories_violet">
                                                <img src="./media/catalog_main/карамель.png" alt="карамель">
                                            </div>
                                            <p>Карамель</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="swiper-slide card_categori">
                                    <a href="catalog_products.php?category=конфеты желейные">
                                        <div class="card_categories">
                                            <div class="card_categories_milk">
                                                <img src="./media/catalog_main/конфеты_желейные.png" alt="Конфеты желейные">
                                            </div>
                                            <p>Конфеты желейные</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="swiper-slide card_categori">
                                    <a href="catalog_products.php?category=батончики">
                                        <div class="card_categories">
                                            <div class="card_categories_pink">
                                                <img src="./media/catalog_main/батончики.png" alt="Батончики">
                                            </div>
                                            <p>Батончики</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="swiper-slide card_categori">
                                    <a href="catalog_products.php?category=ирис, ирисовые конфеты">
                                        <div class="card_categories">
                                            <div class="card_categories_linght_green">
                                                <img src="./media/catalog_main/ирис.png" alt="Ирис, ирисовые конфеты">
                                            </div>
                                            <p>Ирис</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="swiper-slide card_categori">
                                    <a href="catalog_products.php?category=леденцы">
                                        <div class="card_categories">
                                            <div class="card_categories_violet">
                                                <img src="./media/catalog_main/леденец.png" alt="леденцы">
                                            </div>
                                            <p>Леденцы</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-button-next custom-next4">
                                <img src="./media/popular-product/Tabpanel.png" alt="Next">
                        </div>
                    </div>
                    <div class="container_products_catalog">
                        <div class="container_products_catalog_title">
                            <a href="catalog_products.php?category=шоколадные конфеты">Шоколадные конфеты</a>
                            <a href="catalog_products.php?category=шоколадные конфеты">></a>
                        </div>
                        <div class="blina">
                            <?php
                            // Получаем товары категории "шоколадные конфеты"
                            $sql = "SELECT * FROM products WHERE category = 'шоколадные конфеты' ORDER BY rating DESC LIMIT 4";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $products = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($products as $product):
                                $discountPrice = $product['discount_percentage'] 
                                    ? $product['price'] * (1 - $product['discount_percentage'] / 100)
                                    : null;
                            ?>
                            <div class="swiper-slide product-card-catalog">
                                <?php if ($product['status'] === 'хит'): ?>
                                    <div class="badge_xit">
                                        <p>Хит</p>
                                    </div>
                                <?php endif; ?>
                                    <img class="img_product-card" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <span><?= htmlspecialchars($product['category']) ?></span>
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
                        </div>
                    </div>

                    <div class="container_products_catalog">
                        <div class="container_products_catalog_title">
                            <a href="catalog_products.php?category=драже">Драже</a>
                            <a href="catalog_products.php?category=драже">></a>
                        </div>
                        <div class="blina">
                            <?php
                            $sql = "SELECT * FROM products WHERE category = 'драже' ORDER BY rating DESC LIMIT 4";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $products = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($products as $product):
                                $discountPrice = $product['discount_percentage'] 
                                    ? $product['price'] * (1 - $product['discount_percentage'] / 100)
                                    : null;
                            ?>
                            <div class="swiper-slide product-card-catalog">
                                <?php if ($product['status'] === 'хит'): ?>
                                <div class="badge_xit">
                                    <p>Хит</p>
                                </div>
                                <?php endif; ?>
                                <img class="img_product-card" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <span><?= htmlspecialchars($product['category']) ?></span>
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
                        </div>
                    </div>

                    <div class="container_products_catalog">
                        <div class="container_products_catalog_title">
                            <a href="catalog_products.php?category=карамель">Карамель</a>
                            <a href="catalog_products.php?category=карамель">></a>
                        </div>
                        <div class="blina">
                            <?php
                            $sql = "SELECT * FROM products WHERE category = 'карамель' ORDER BY rating DESC LIMIT 4";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $products = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($products as $product):
                                $discountPrice = $product['discount_percentage'] 
                                    ? $product['price'] * (1 - $product['discount_percentage'] / 100)
                                    : null;
                            ?>
                            <div class="swiper-slide product-card-catalog">
                                <?php if ($product['status'] === 'хит'): ?>
                                <div class="badge_xit">
                                    <p>Хит</p>
                                </div>
                                <?php endif; ?>
                                <img class="img_product-card" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <span><?= htmlspecialchars($product['category']) ?></span>
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
                        </div>
                    </div>

                    <div class="container_products_catalog">
                        <div class="container_products_catalog_title">
                            <a href="catalog_products.php?category=конфеты желейные">Конфеты желейные</a>
                            <a href="catalog_products.php?category=конфеты желейные">></a>
                        </div>
                        <div class="blina">
                            <?php
                            $sql = "SELECT * FROM products WHERE category = 'конфеты желейные' ORDER BY rating DESC LIMIT 4";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $products = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($products as $product):
                                $discountPrice = $product['discount_percentage'] 
                                    ? $product['price'] * (1 - $product['discount_percentage'] / 100)
                                    : null;
                            ?>
                            <div class="swiper-slide product-card-catalog">
                                <?php if ($product['status'] === 'хит'): ?>
                                <div class="badge_xit">
                                    <p>Хит</p>
                                </div>
                                <?php endif; ?>
                                <img class="img_product-card" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <span><?= htmlspecialchars($product['category']) ?></span>
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
                        </div>
                    </div>

                    <div class="container_products_catalog">
                        <div class="container_products_catalog_title">
                            <a href="catalog_products.php?category=батончики">Батончики</a>
                            <a href="catalog_products.php?category=батончики">></a>
                        </div>
                        <div class="blina">
                            <?php
                            $sql = "SELECT * FROM products WHERE category = 'батончики' ORDER BY rating DESC LIMIT 4";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $products = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($products as $product):
                                $discountPrice = $product['discount_percentage'] 
                                    ? $product['price'] * (1 - $product['discount_percentage'] / 100)
                                    : null;
                            ?>
                            <div class="swiper-slide product-card-catalog">
                                <?php if ($product['status'] === 'хит'): ?>
                                <div class="badge_xit">
                                    <p>Хит</p>
                                </div>
                                <?php endif; ?>
                                <img class="img_product-card" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <span><?= htmlspecialchars($product['category']) ?></span>
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
                        </div>
                    </div>

                    <div class="container_products_catalog">
                        <div class="container_products_catalog_title">
                            <a href="catalog_products.php?category=ирис, ирисовые конфеты">Ирис</a>
                            <a href="catalog_products.php?category=ирис, ирисовые конфеты">></a>
                        </div>
                        <div class="blina">
                            <?php
                            $sql = "SELECT * FROM products WHERE category = 'ирис, ирисовые конфеты' ORDER BY rating DESC LIMIT 4";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $products = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($products as $product):
                                $discountPrice = $product['discount_percentage'] 
                                    ? $product['price'] * (1 - $product['discount_percentage'] / 100)
                                    : null;
                            ?>
                            <div class="swiper-slide product-card-catalog">
                                <?php if ($product['status'] === 'хит'): ?>
                                <div class="badge_xit">
                                    <p>Хит</p>
                                </div>
                                <?php endif; ?>
                                <img class="img_product-card" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <span><?= htmlspecialchars($product['category']) ?></span>
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
                                            
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="container_products_catalog">
                        <div class="container_products_catalog_title">
                            <a href="catalog_products.php?category=леденцы">Леденцы</a>
                            <a href="catalog_products.php?category=леденцы">></a>
                        </div>
                        <div class="blina">
                            <?php
                            $sql = "SELECT * FROM products WHERE category = 'леденцы' ORDER BY rating DESC LIMIT 4";
                            $query = $pdo->prepare($sql);
                            $query->execute();
                            $products = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($products as $product):
                                $discountPrice = $product['discount_percentage'] 
                                    ? $product['price'] * (1 - $product['discount_percentage'] / 100)
                                    : null;
                            ?>
                            <div class="swiper-slide product-card-catalog">
                                <?php if ($product['status'] === 'хит'): ?>
                                <div class="badge_xit">
                                    <p>Хит</p>
                                </div>
                                <?php endif; ?>
                                <img class="img_product-card" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <span><?= htmlspecialchars($product['category']) ?></span>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        include 'footer.php';
    ?>
    <!-- begin scroll -->
    <button class="sroll" id="scrollToTopBtn"><img src="./media/scroll/scroll.png" alt=""></button>
    <!-- end scroll -->
    <script src="js/product-modal.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/favorites.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper4 = new Swiper('.swiper4', {
            slidesPerView: 7,
            spaceBetween: 16,
            navigation: {
                nextEl: '.custom-next4',
                prevEl: '.custom-prev4',
            },
        });

        // кнопка вверх
        // Получаем кнопку и изображение внутри кнопки
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        const buttonImg = scrollToTopBtn.querySelector('img');

        // Изначально скрываем кнопку
        scrollToTopBtn.style.display = 'none';

        // Флаг для отслеживания направления скролла
        let lastScrollTop = 0;

        // Обработчик прокрутки
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // Если прокрутка вниз
            if (scrollTop > lastScrollTop && scrollTop > 100) { // 100 - это порог, при котором кнопка появляется
                scrollToTopBtn.style.display = 'block';
            } else if (scrollTop < lastScrollTop || scrollTop <= 100) { // Кнопка скрывается при скролле вверх
                scrollToTopBtn.style.display = 'none';
            }

            // Обновляем значение lastScrollTop
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });

        // Обработчик клика по кнопке
        scrollToTopBtn.addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });

        // Обработчик наведения на изображение
        buttonImg.addEventListener('mouseover', function() {
            buttonImg.src = './media/scroll/scroll-hover.png';
        });

        // Обработчик ухода мыши с изображения
        buttonImg.addEventListener('mouseout', function() {
            buttonImg.src = './media/scroll/scroll.png';
        });
    </script>
</body>
</html>