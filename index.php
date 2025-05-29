<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Арман</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <div class="container">
        <div class="container_main">
            <div class="slider">
                <div class="swiper-button-prev custom-prev">
                    <img src="./media/banner-slider/стрелка слайдера левая.svg" alt="Previous">
                </div>
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img style="height: 100%;" src="./media/banner-slider/Баннер-слайдер_1.png" alt="">
                            <a href="./catalog.php" class="btn_slider">Перейти в каталог</a>
                        </div>
                        <div class="swiper-slide">
                            <img style="height: 100%;" src="./media/banner-slider/Баннер-слайдер_2.png" alt="">
                            <a href="./catalog.php" class="btn_slider sin">Перейти в каталог</a>
                        </div>
                        <div class="swiper-slide">
                            <img style="height: 100%;" src="./media/banner-slider/Баннер-слайдер_3.png" alt="">
                            <a href="./catalog.php" class="btn_slider roz">Перейти в каталог</a>
                        </div>
                        <div class="swiper-slide">
                            <img style="height: 100%;" src="./media/banner-slider/Баннер-слайдер_4.png" alt="">
                            <a href="index.php#anchor_promotions" class="btn_slider fiol">Все акции и спецпредложения</a>
                        </div>
                    </div>
                    <div class="swiper-pagination custom-pagination"></div>
                </div>
                <div class="swiper-button-next custom-next">
                    <img src="./media/banner-slider/стрелка слайдера правая.svg" alt="Next">
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="container_main">
            <div class="container_card">
                <div class="card">
                    <img src="./media/privilege icons/Star.svg" alt="">
                    <div class="card_txt">
                        <p>свежая и качественная продукция</p>
                        <span>Мы уверены в безопасности наших продуктов и отличных потребительских свойствах.</span>
                    </div>
                </div>
                <div class="card">
                    <img src="./media/privilege icons/Buy.svg" alt="">
                    <div class="card_txt">
                        <p>Широкий ассортимент</p>
                        <span>Широкий ассортимент с постоянным обновлением.</span>
                    </div>
                </div>
                <div class="card">
                    <img src="./media/privilege icons/Wallet.png " alt="">
                    <div class="card_txt">
                        <p>Доступная цена</p>
                        <span>Доступная цена среди продукции с аналогичным исполнением, составом и качеством.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="container_main">
            <div class="container_categories">
                <p>Рекомендуемые категории</p>
                <div class="categories">
                    <a href="catalog_products.php?category=Конфеты&subcategory_id=0">
                        <div class="card_categories_green">
                            <img src="./media/recom-card-category/конфеты.png" alt="">
                            <p>Конфеты</p>
                        </div>
                    </a>
                    <a href="catalog_products.php?category=Конфеты&subcategory_id=Карамель">
                        <div class="card_categories_milk">
                            <img src="./media/recom-card-category/карамель.png" alt="">
                            <p>Карамель</p>
                        </div>
                    </a>
                    <a href="catalog_products.php?category=Шоколад, шоколадные пасты&subcategory_id=0">
                        <div class="card_categories_pink">
                            <img src="./media/recom-card-category/шоколад.png" alt="">
                            <p>Шоколад</p>
                        </div>
                    </a>
                    <a href="catalog_products.php?category=Чай, кофе&subcategory_id=0">
                        <div class="card_categories_linght_green">
                            <img src="./media/recom-card-category/чай.png" alt="">
                            <p>Чай</p>
                        </div>
                    </a>
                    <a href="catalog_products.php?category=Пастила, мармелад&subcategory_id=0">
                        <div class="card_categories_pink">
                            <img src="./media/recom-card-category/восточные сладости.png" alt="">
                            <p>Восточный сладости</p>
                        </div>
                    </a>
                    <a href="catalog_products.php?category=Специи, приправы&subcategory_id=0">
                        <div class="card_categories_violet">
                            <img src="./media/recom-card-category/специи.png" alt="">
                            <p>Специи</p>
                        </div>
                    </a>
                    <a href="catalog_products.php?category=Вафли&subcategory_id=0">
                        <div class="card_categories_green">
                            <img src="./media/recom-card-category/вафли.png" alt="">
                            <p>Вафли</p>
                        </div>
                    </a>
                    <a href="catalog_products.php?category=Печенье&subcategory_id=0">
                        <div class="card_categories_pink">
                            <img src="./media/recom-card-category/печенье.png" alt="">
                            <p>Печенье</p>
                        </div>
                    </a>
                    <a href="catalog_products.php?category=Пряники&subcategory_id=0">
                        <div class="card_categories_milk">
                            <img src="./media/recom-card-category/пряники.png" alt="">
                            <p>Пряники</p>
                        </div>
                    </a>
                    <a href="catalog_products.php?category=Консервация&subcategory_id=0">
                        <div class="card_categories_linght_green">
                            <img src="./media/recom-card-category/консервы.png" alt="">
                            <p>Консервы</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="container_main">
            <div class="container_banner">
                <div class="banner_green">
                    <div class="banner_txt">
                        <p>Профессиональное обслуживание</p>
                        <span>нашей целью является удовлетворение потребностей покупателей в качественной продукции</span>
                    </div>
                    <div class="btn_banner_green">
                        <a href="about.php">Подробнее <img src="./media/banner/иконка стрелочка.svg" alt=""></a>
                    </div>
                </div>
                <div class="banner_blue">
                    <div class="banner_txt">
                        <p>Бесплатная доставка</p>
                        <span>по Тюмени и Кургану при заказе от <br> 1000 рублей</span>
                    </div>
                    <div class="btn_banner_blue">
                        <a href="delivery.php">Подробнее <img src="./media/banner/иконка стрелочка.svg" alt=""></a>
                    </div>
                </div>
                <div class="banner_orange">
                    <div class="banner_txt">
                        <p>Поступление свежей продукции</p>
                        <span>в двух розничных магазинах в <br> Кургане и складе в Тюмени</span>
                    </div>
                    <div class="btn_banner_orange">
                        <a href="contant.php">Подробнее <img src="./media/banner/иконка стрелочка.svg" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once 'db.php';

$params = [];
$sqlCategories = "SELECT p.category, COALESCE(SUM(o.quantity), 0) AS total_sold
                  FROM products p
                  LEFT JOIN orders o ON p.id = o.product_id
                  GROUP BY p.category
                  ORDER BY total_sold DESC
                  LIMIT 5";
$categoriesQuery = $pdo->query($sqlCategories);
$dbCategories = $categoriesQuery->fetchAll(PDO::FETCH_COLUMN);

// Добавляем "Все" в начало списка категорий
$categories = array_merge(['Все'], $dbCategories);

    // Определяем выбранную категорию (из GET-параметра)
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
          LIMIT 8";

$query = $pdo->prepare($sql);
$query->execute($params);
$products = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="container">
        <div class="container_main">
            <div class="container_popular_products">
                <div class="preview">
                    <div class="txt_preview">
                        <p>Популярные товары</p>
                        <div class="btn_preview">
                            <?php foreach ($categories as $category): ?>
                                <a href="?category=<?= urlencode($category) ?>"
                                    class="<?= $category === $selectedCategory ? 'active' : '' ?>">
                                    <?= htmlspecialchars($category ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="container_slider_2">
                        <div class="swiper-button-prev custom-prev2">
                            <img src="./media/popular-product/Tabpanel-left.png" alt="Previous">
                        </div>
                        <div class="swiper2">
                            <div class="swiper-wrapper">
                                <?php if (empty($products)): ?>
                                    <div class="swiper-slide">
                                        <p>Товары не найдены</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($products as $product):
                                        $discountPrice = $product['discount_percentage']
                                            ? $product['price'] * (1 - $product['discount_percentage'] / 100)
                                            : null;
                                    ?>
                                        <div class="swiper-slide product-card" data-name="<?= htmlspecialchars($product['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-description="<?= htmlspecialchars($product['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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

                                        <img class="img_product-card" src="<?= htmlspecialchars($product['image'] ?? './media/popular-product/default-product.png', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                        <span><?= htmlspecialchars($product['category_id'] ?? 'Без категории', ENT_QUOTES, 'UTF-8') ?></span>
                                            <p><?= htmlspecialchars($product['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
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
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="swiper-button-next custom-next2">
                            <img src="./media/popular-product/Tabpanel.png" alt="Next">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require_once 'db.php';

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

$selectedCategory = $_GET['category'] ?? 'Все';

$sql = "SELECT * FROM products 
        WHERE (discount_percentage IS NOT NULL OR status = 'распродажа')";

if ($selectedCategory !== 'Все') {
    $sql .= " AND category = :category";
    $params[':category'] = $selectedCategory;
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
        ?>

        <div class="container">
            <div class="container_main">
                <div class="container_promotions">
                    <div class="promotions">
                        <div class="txt_preview">
                            <p  id="anchor_promotions">Акции & скидки</p>
                            <div class="btn_promotions">
                                <?php foreach ($categories as $category): ?>
                                    <a href="?category=<?= urlencode($category) ?>"
                                        class="<?= $category === $selectedCategory ? 'active' : '' ?>">
<?= htmlspecialchars($category ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="container_promotions_banner_slider">
                            
                        <a href="./catalog.php">
                                <div class="promotions_banner">
                                    <p>Суперскидки: больше товаров – меньше цен!</p>
                                        <button class="promotion_banner_btn">Купить сейчас<img src="./media/banner/иконка стрелочка.svg" alt=""></button>
                                            <img src="./media/promotions/image 662.png" alt="">
                                </div>
                        </a>
                            <div class="container_slider_3">
                                <div class="swiper-button-prev custom-prev3">
                                    <img src="./media/popular-product/Tabpanel-left.png" alt="Previous">
                                </div>
                                <div class="swiper3">
                                    <div class="swiper-wrapper" id="promotions-container">
                                        <?php foreach ($products as $product):
                                            $discountPercent = $product['discount_percentage'] ?? ($product['status'] == 'распродажа' ? 10 : null);
                                            $discountPrice = $discountPercent ? $product['price'] * (1 - $discountPercent / 100) : null;
                                        ?>
                                            <div class="swiper-slide product-card" data-name="<?= htmlspecialchars($product['name']) ?>" data-description="<?= htmlspecialchars($product['description']) ?>">
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
                                                <span><?= htmlspecialchars($product['category_id']) ?? 'Без категории' ?></span>
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
                                <div class="swiper-button-next custom-next3">
                                    <img src="./media/popular-product/Tabpanel.png" alt="Next">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="container_main">
            <div class="container_news">
                <div class="news">
                    <div class="txt_preview">
                        <p id="anchor_news">Новинки</p>
                    </div>
                    <div class="container_news_card">
                        <?php
                        require_once 'db.php';
                        $newProductsQuery = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 4");
                        $newProducts = $newProductsQuery->fetchAll(PDO::FETCH_ASSOC);
                        $classes = ['gre', 'pur', 'pin', 'yel'];
                        foreach ($newProducts as $product):
                            $discountPrice = $product['discount_percentage']
                                ? $product['price'] * (1 - $product['discount_percentage'] / 100)
                                : null;
                            $randomClass = $classes[array_rand($classes)];
                        ?>
                        <div class="card_news">
                            <div class="card_img <?= $randomClass ?>">
                                <img src="<?= htmlspecialchars($product['image'] ?? './media/news/default.png') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="txt_news">
                                <p><?= htmlspecialchars($product['name']) ?></p>
                                <div class="price-values card_news_price">
                                    <?php if ($discountPrice): ?>
                                        <p>₽<?= number_format($discountPrice, 2) ?></p>
                                        <span>₽<?= number_format($product['price'], 2) ?></span>
                                    <?php else: ?>
                                        <p>₽<?= number_format($product['price'], 2) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="btn_news_card">
                                    <!-- <button class="add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                                        <img src="./media/popular-product/иконка добавить в корзину.svg" alt="Добавить в корзину">
                                    </button> -->
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
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="container">
            <div class="container_main">
                <div class="container_about" id="about-us">
                    <div class="txt_preview">
                        <p>О компании</p>
                    </div>
                    <div class="about">
                        <div class="txt_about">
                            <p class="main_txt_about"><span>Торговый дом АРМАН</span> — занимается оптово-розничной торговлей продукции для чаепития</p>
                            <p class="txt_about_1">В интернет-магазине Арман вы найдете широкий ассортимент продукции для чаепития российского-казахстанского производства:</p>
                            <div class="txt_about_product">
                                <p>— Конфеты</p>
                                <p>— Карамель</p>
                                <p>— Зефир</p>
                                <p>— Драже и многое другое.</p>
                            </div>
                            <p class="txt_about_2">У нас свежая продукция с широким ассортиментом и постоянным обновлением. Осуществляем доставку товаров по России.</p>
                            <a href="about.php" class="btn_about">Подробности</a>
                        </div>
                        <div class="img_about">
                            <img src="./media/about/people.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- begin scroll -->
    <button class="sroll" id="scrollToTopBtn"><img src="./media/scroll/scroll.png" alt=""></button>
    <!-- end scroll -->
    <?php
    include 'footer.php';
    ?>
    <script src="js/product-modal.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            // If we need pagination
            pagination: {
                el: '.custom-pagination',
                type: 'bullets', // Тип пагинации
            },
            loop: true, // Циклическая прокрутка слайдов
            // Navigation arrows
            navigation: {
                nextEl: '.custom-next',
                prevEl: '.custom-prev',
            },
        });
        // Инициализация Swiper
        const swiper2 = new Swiper('.swiper2', {
            slidesPerView: 5,
            navigation: {
                nextEl: '.custom-next2',
                prevEl: '.custom-prev2',
            },
        });
        const swiper3 = new Swiper('.swiper3', {
            slidesPerView: 4,
            navigation: {
                nextEl: '.custom-next3',
                prevEl: '.custom-prev3',
            },
        });

        // Обработка фильтрации товаров без перезагрузки страницы
        document.querySelectorAll('.btn_preview a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Удаляем класс active у всех ссылок
                document.querySelectorAll('.btn_preview a').forEach(a => a.classList.remove('active'));
                // Добавляем класс active к нажатой ссылке
                this.classList.add('active');

                // Получаем категорию из href
                const category = this.getAttribute('href').split('=')[1];

                // Отправляем AJAX-запрос
                fetch(`get_products.php?category=${category}`)
                    .then(response => response.text())
                    .then(html => {
                        // Обновляем содержимое слайдера
                        const swiperWrapper = document.querySelector('.swiper2 .swiper-wrapper');
                        swiperWrapper.innerHTML = html;

                        // Обновляем Swiper
                        swiper2.update();
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        // Обработка фильтрации акций в реальном времени
        document.querySelectorAll('.btn_promotions a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Удаляем класс active у всех ссылок
                document.querySelectorAll('.btn_promotions a').forEach(a => a.classList.remove('active'));
                // Добавляем класс active к нажатой ссылке
                this.classList.add('active');

                // Получаем категорию из href
                const category = this.getAttribute('href').split('=')[1];

                // Отправляем AJAX-запрос
                fetch(`get_promotions.php?category=${category}`)
                    .then(response => response.text())
                    .then(html => {
                        // Обновляем содержимое слайдера
                        const swiperWrapper = document.querySelector('.swiper3 .swiper-wrapper');
                        swiperWrapper.innerHTML = html;

                        // Обновляем Swiper
                        swiper3.update();
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        // Получаем элементы кнопок
        const prevButton = document.querySelector('.custom-prev img');
        const nextButton = document.querySelector('.custom-next img');

        // Задаем новые изображения при наведении
        prevButton.addEventListener('mouseover', function() {
            this.src = './media/banner-slider/послелевая.svg';
        });

        prevButton.addEventListener('mouseout', function() {
            this.src = './media/banner-slider/стрелка слайдера левая.svg';
        });

        nextButton.addEventListener('mouseover', function() {
            this.src = './media/banner-slider/послеправая.svg';
        });

        nextButton.addEventListener('mouseout', function() {
            this.src = './media/banner-slider/стрелка слайдера правая.svg';
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
            buttonImg.src = './media/scroll/scroll-hover.png'; // Замените на путь к изображению для наведения
        });

        // Обработчик ухода мыши с изображения
        buttonImg.addEventListener('mouseout', function() {
            buttonImg.src = './media/scroll/scroll.png'; // Возвращаем исходное изображение
        });

       
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/favorites.js"></script>
    <script src="js/cart.js"></script>
</body>

</html>
</html>