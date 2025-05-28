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
        include 'header.php';
    ?>
    <div class="container_catalog">
        <div class="container">
            <div class="container_main">
                <div class="title_catalog">
                    <p>Все категории</p>
                </div>
                <div class="container_card_catalog">
                <a class="card_catalog" id="card_1" href="catalog_products.php?category=Конфеты&subcategory_id=0">
                    <p>Конфеты</p>
                    <img src="./media/catalog_card_image/Candies.png" alt="">
                </a>
                <a class="card_catalog" id="card_2" href="catalog_products.php?category=Вафли&subcategory_id=0"">
                    <p>Вафли</p>
                    <img src="./media/catalog_card_image/waffles.png" alt="">
                </a>
                <a class="card_catalog" id="card_3" href="catalog_products.php?category=Печенье&subcategory_id=0"">
                    <p>Печенье</p>
                    <img src="./media/catalog_card_image/cookie.png" alt="">
                </a>
                <a class="card_catalog" id="card_4" href="catalog_products.php?category=Шоколад, шоколадные пасты&subcategory_id=0"">
                    <p>Шоколад, шоколадные пасты</p>
                    <img src="./media/catalog_card_image/Chocolate.png" alt="">
                </a>
                <a class="card_catalog" id="card_5" href="catalog_products.php?category=Пастила, мармелад&subcategory_id=0"">
                    <p>Пастила, мармелад, восточные сладости</p>
                    <img src="./media/catalog_card_image/Marshmallow.png" alt="">
                </a>
                <a class="card_catalog" id="card_6" href="catalog_products.php?category=Пряники&subcategory_id=0"">
                    <p>Пряники</p>
                    <img src="./media/catalog_card_image/Gingerbread.png" alt="">
                </a>
                <a class="card_catalog" id="card_7" href="catalog_products.php?category=Баранки, сушки&subcategory_id=0"">
                    <p>Баранки, сушки</p>
                    <img src="./media/catalog_card_image/Baranki.png" alt="">
                </a>
                <a class="card_catalog" id="card_8" href="catalog_products.php?category=Пирожные, кексы&subcategory_id=0"">
                    <p>Пирожные, кексы</p>
                    <img src="./media/catalog_card_image/Cakes.png" alt="">
                </a>
                <a class="card_catalog" id="card_9" href="catalog_products.php?category=Выпечка&subcategory_id=0"">
                    <p>Выпечка</p>
                    <img src="./media/catalog_card_image/Baked_goods.png" alt="">
                </a>
                <a class="card_catalog" id="card_10" href="catalog_products.php?category=Соки, напитки&subcategory_id=0"">
                    <p>Соки, напитки, вода</p>
                    <img src="./media/catalog_card_image/Juices.png" alt="">
                </a>
                <a class="card_catalog" id="card_11" href="catalog_products.php?category=Чай, кофе&subcategory_id=0">
                    <p>Чай, кофе</p>
                    <img src="./media/catalog_card_image/tea.png" alt="">
                </a>
                <a class="card_catalog" id="card_12" href="catalog_products.php?category=Специи, приправы&subcategory_id=0"">
                    <p>Специи, приправы</p>
                    <img src="./media/catalog_card_image/spices.png" alt="">
                </a>
                <a class="card_catalog" id="card_13" href="catalog_products.php?category=Детские товары&subcategory_id=0"">
                    <p>Детские товары</p>
                    <img src="./media/catalog_card_image/children.png" alt="">
                </a>
                <a class="card_catalog" id="card_14" href="catalog_products.php?category=Праздничный ассортимент&subcategory_id=0"">
                    <p>Праздничный ассортимент</p>
                    <img src="./media/catalog_card_image/holiday.png" alt="">
                </a>
                <a class="card_catalog" id="card_15" href="catalog_products.php?category=Консервация&subcategory_id=0"">
                    <p>Консервация</p>
                    <img src="./media/catalog_card_image/Canned_goods.png" alt="">
                </a>
                <a class="card_catalog" id="card_16" href="catalog_products.php?category=Макаронные изделия, крупы&subcategory_id=0"">
                    <p>Макаронные изделия, крупы</p>
                    <img src="./media/catalog_card_image/Pasta.png" alt="">
                </a>
                </div>
            </div>
        </div>
    </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html>