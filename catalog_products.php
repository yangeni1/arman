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
        
        // Получаем максимальную цену для текущей категории
        $category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : 'Все товары';
        $maxPriceQuery = $pdo->prepare("SELECT MAX(price) as max_price FROM products WHERE category = :category");
        $maxPriceQuery->execute([':category' => $category]);
        $categoryMaxPrice = $maxPriceQuery->fetch(PDO::FETCH_ASSOC)['max_price'] ?? 1000;
    ?>
    <div class="container">
        <div class="container_main">
            <div class="head_catalog">
                <p><?php 
                    echo $category;
                ?></p>
                <div class="filter_price">
                    <button class="sort-btn active" data-sort="popularity">По популярности</button>
                    <button class="sort-btn" data-sort="price_asc">По цене Вверх</button>
                    <button class="sort-btn" data-sort="price_desc">По цене Вниз</button>
                </div>
            </div>
            <div class="container_catalog_filter">
                <div class="catalog_main_menu_list">
                    <p>Категории товаров</p>
                    <div class="catalog_main_menu_list_item">
                        <a href="catalog_products.php?category=шоколадные конфеты" <?php echo ($category === 'шоколадные конфеты') ? 'class="active"' : ''; ?>>Шоколадные конфеты</a>
                        <a href="catalog_products.php?category=драже" <?php echo ($category === 'драже') ? 'class="active"' : ''; ?>>Драже</a>
                        <a href="catalog_products.php?category=карамель" <?php echo ($category === 'карамель') ? 'class="active"' : ''; ?>>Карамель</a>
                        <a href="catalog_products.php?category=конфеты желейные" <?php echo ($category === 'конфеты желейные') ? 'class="active"' : ''; ?>>Конфеты желейные</a>
                        <a href="catalog_products.php?category=батончики" <?php echo ($category === 'батончики') ? 'class="active"' : ''; ?>>Батончики</a>
                        <a href="catalog_products.php?category=ирис, ирисовые конфеты" <?php echo ($category === 'ирис, ирисовые конфеты') ? 'class="active"' : ''; ?>>Ирис</a>
                        <a href="catalog_products.php?category=леденцы" <?php echo ($category === 'леденцы') ? 'class="active"' : ''; ?>>Леденцы</a>
                        <a href="catalog_products.php?category=гематоген" <?php echo ($category === 'гематоген') ? 'class="active"' : ''; ?>>Гематоген</a>
                    </div>
                    <div class="filter_switch">
                        <div class="cena_sbros">
                            <p>Цена</p>
                            <button onclick="resetPriceOnly()">Сброс</button>
                        </div>
                        <div class="inputs">
                            <input type="number" id="minPrice" placeholder="1" onchange="updateFromInputs()">
                            <img src="./media/filter/minus.png" alt="">
                            <input type="number" id="maxPrice" placeholder="0" onchange="updateFromInputs()">
                        </div>
                        <div class="polzynok">
                            <div class="range-slider">
                                <div class="range-progress"></div>
                                <input type="range" id="minRange" min="0" max="<?php echo $categoryMaxPrice; ?>" step="1" oninput="updatePriceInputs()">
                                <input type="range" id="maxRange" min="0" max="<?php echo $categoryMaxPrice; ?>" step="1" oninput="updatePriceInputs()">
                            </div>
                        </div>
                    </div>
                    <div class="filter_brand">
                        <p>Бренд</p>
                        <?php
                        // Получаем уникальные бренды из базы данных
                        $brandsSql = "SELECT DISTINCT brand FROM products WHERE category = :category ORDER BY brand";
                        $brandsQuery = $pdo->prepare($brandsSql);
                        $brandsQuery->bindParam(':category', $category);
                        $brandsQuery->execute();
                        $brands = $brandsQuery->fetchAll(PDO::FETCH_COLUMN);

                        foreach ($brands as $brand):
                        ?>
                        <div class="brand_checkbox">
                            <input type="checkbox" id="brand_<?= htmlspecialchars($brand) ?>" value="<?= htmlspecialchars($brand) ?>" onchange="applyFilters()">
                            <p><?= htmlspecialchars($brand) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="btn_sbros_filter">
                        <button>Сбросить все</button>
                    </div>
                    <div class="promotions_banner">
                        <p>Суперскидки: больше товаров – меньше цен!</p>
                        <button class="promotion_banner_btn">Купить сейчас<img src="./media/banner/иконка стрелочка.svg" alt=""></button>
                        <img src="./media/promotions/image 662.png" alt="">
                    </div>
                </div>
                <div class="card_product_catalog_products">
                    <?php
                    $category = isset($_GET['category']) ? $_GET['category'] : '';
                    $minPrice = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
                    $maxPrice = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $categoryMaxPrice;
                    $selectedBrands = isset($_GET['brands']) ? explode(',', $_GET['brands']) : [];

                    $sql = "SELECT * FROM products WHERE category = :category";
                    $params = [':category' => $category];

                    // Фильтрация по минимальной цене
                    if ($minPrice > 0) {
                        $sql .= " AND price >= :min_price";
                        $params[':min_price'] = $minPrice;
                    }

                    // Фильтрация по максимальной цене
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

                    // Добавляем сортировку
                    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'popularity';
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

                    foreach ($products as $product):
                        $discountPrice = $product['discount_percentage'] 
                            ? $product['price'] * (1 - $product['discount_percentage'] / 100)
                            : null;
                    ?>
                    <div class="swiper-slide product-card-catalog-1" data-description="<?= htmlspecialchars($product['description']) ?>">
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
                            <button class="add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                                <img src="./media/popular-product/иконка добавить в корзину.svg" alt="Добавить в корзину">
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
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
    <script>
        let updateTimeout;
        const cardProductCatalog = document.querySelector('.card_product_catalog_products');
        const minRange = document.getElementById('minRange');
        const maxRange = document.getElementById('maxRange');
        const minInput = document.getElementById('minPrice');
        const maxInput = document.getElementById('maxPrice');
        const rangeProgress = document.querySelector('.range-progress');
        const categoryMaxPrice = <?php echo $categoryMaxPrice; ?>;
        let currentSort = 'popularity'; // Добавляем переменную для текущей сортировки

        // Добавляем обработчик для кнопок сортировки
        document.querySelectorAll('.sort-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Убираем активный класс у всех кнопок
                document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.remove('active'));
                // Добавляем активный класс текущей кнопке
                this.classList.add('active');
                // Обновляем текущую сортировку
                currentSort = this.dataset.sort;
                // Обновляем товары
                updateProducts();
            });
        });

        // Устанавливаем максимальное значение для ползунков
        minRange.max = categoryMaxPrice;
        maxRange.max = categoryMaxPrice;

        function updateFromInputs() {
            const minValue = parseFloat(minInput.value) || 0;
            const maxValue = parseFloat(maxInput.value) || categoryMaxPrice;

            // Убедимся, что значения не превышают максимальную цену категории
            if (maxValue > categoryMaxPrice) {
                maxInput.value = categoryMaxPrice;
                maxRange.value = categoryMaxPrice;
            }

            // Убедимся, что минимальное значение не превышает максимальное
            if (minValue > maxValue) {
                if (event.target === minInput) {
                    minInput.value = maxValue;
                    minRange.value = maxValue;
                } else {
                    maxInput.value = minValue;
                    maxRange.value = minValue;
                }
            }

            // Обновляем значения ползунков
            minRange.value = minValue;
            maxRange.value = maxValue;

            // Обновляем стиль ползунка
            const range = maxValue - minValue;
            const minPercent = (minValue / categoryMaxPrice) * 100;
            const rangePercent = (range / categoryMaxPrice) * 100;
            
            rangeProgress.style.left = `${minPercent}%`;
            rangeProgress.style.width = `${rangePercent}%`;

            // Очищаем предыдущий таймаут
            clearTimeout(updateTimeout);
            
            // Устанавливаем новый таймаут для обновления
            updateTimeout = setTimeout(() => {
                updateProducts();
            }, 300);
        }

        function updatePriceInputs() {
            const minValue = parseFloat(minRange.value) || 0;
            const maxValue = parseFloat(maxRange.value) || categoryMaxPrice;

            // Убедимся, что значения не превышают максимальную цену категории
            if (maxValue > categoryMaxPrice) {
                maxRange.value = categoryMaxPrice;
                maxInput.value = categoryMaxPrice;
            }

            // Убедимся, что минимальное значение не превышает максимальное
            if (minValue > maxValue) {
                if (minRange === event.target) {
                    minRange.value = maxValue;
                    minInput.value = maxValue;
                } else {
                    maxRange.value = minValue;
                    maxInput.value = minValue;
                }
            }

            // Обновляем значения в полях ввода
            minInput.value = minRange.value;
            maxInput.value = maxRange.value;

            // Обновляем стиль ползунка
            const range = maxValue - minValue;
            const minPercent = (minValue / categoryMaxPrice) * 100;
            const rangePercent = (range / categoryMaxPrice) * 100;
            
            rangeProgress.style.left = `${minPercent}%`;
            rangeProgress.style.width = `${rangePercent}%`;
            
            // Очищаем предыдущий таймаут
            clearTimeout(updateTimeout);
            
            // Устанавливаем новый таймаут для обновления
            updateTimeout = setTimeout(() => {
                updateProducts();
            }, 300);
        }

        function updateProducts() {
            const minPrice = parseFloat(minInput.value) || 0;
            const maxPrice = parseFloat(maxInput.value) || categoryMaxPrice;

            // Убедимся, что значения не превышают максимальную цену категории
            if (maxPrice > categoryMaxPrice) {
                maxInput.value = categoryMaxPrice;
                maxRange.value = categoryMaxPrice;
            }

            const brandCheckboxes = document.querySelectorAll('.brand_checkbox input[type="checkbox"]');
            const selectedBrands = Array.from(brandCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            // Получаем текущие параметры URL
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('category') || '';

            // Формируем URL для AJAX запроса с текущей сортировкой
            let url = `get_polzunok.php?category=${encodeURIComponent(category)}&min_price=${minPrice}&max_price=${maxPrice}&sort=${currentSort}`;
            if (selectedBrands.length > 0) {
                url += `&brands=${encodeURIComponent(selectedBrands.join(','))}`;
            }

            // Отправляем AJAX запрос
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cardProductCatalog.innerHTML = data.html;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function applyFilters() {
            const minPrice = parseFloat(minInput.value) || 0;
            const maxPrice = parseFloat(maxInput.value) || categoryMaxPrice;

            // Убедимся, что значения не превышают максимальную цену категории
            if (maxPrice > categoryMaxPrice) {
                maxInput.value = categoryMaxPrice;
                maxRange.value = categoryMaxPrice;
            }

            const brandCheckboxes = document.querySelectorAll('.brand_checkbox input[type="checkbox"]');
            const selectedBrands = Array.from(brandCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            let url = new URL(window.location.href);
            url.searchParams.set('min_price', minPrice);
            url.searchParams.set('max_price', maxPrice);
            url.searchParams.set('sort', currentSort); // Добавляем текущую сортировку
            
            if (selectedBrands.length > 0) {
                url.searchParams.set('brands', selectedBrands.join(','));
            } else {
                url.searchParams.delete('brands');
            }

            window.location.href = url.toString();
        }

        function resetPriceOnly() {
            let url = new URL(window.location.href);
            url.searchParams.delete('min_price');
            url.searchParams.delete('max_price');
            
            // Сбрасываем значения ползунков и полей ввода
            minRange.value = 0;
            maxRange.value = categoryMaxPrice;
            minInput.value = 0;
            maxInput.value = categoryMaxPrice;
            
            // Обновляем визуальное отображение ползунка
            rangeProgress.style.left = '0%';
            rangeProgress.style.width = '100%';
            
            window.location.href = url.toString();
        }

        function resetAllFilters() {
            let url = new URL(window.location.href);
            
            // Сбрасываем все параметры фильтрации
            url.searchParams.delete('min_price');
            url.searchParams.delete('max_price');
            url.searchParams.delete('brands');
            url.searchParams.delete('sort');
            
            // Оставляем только категорию
            const category = url.searchParams.get('category');
            url = new URL(`${url.origin}${url.pathname}?category=${category}`);
            
            // Сбрасываем текущую сортировку
            currentSort = 'popularity';
            
            // Обновляем активную кнопку сортировки
            document.querySelectorAll('.sort-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.sort === 'popularity') {
                    btn.classList.add('active');
                }
            });
            
            window.location.href = url.toString();
        }

        // Добавляем обработчики событий для кнопок сброса
        document.addEventListener('DOMContentLoaded', function() {
            // Находим кнопки
            const resetPriceButton = document.querySelector('.cena_sbros button');
            const resetAllButton = document.querySelector('.btn_sbros_filter button');
            
            // Добавляем обработчики
            if (resetPriceButton) {
                resetPriceButton.onclick = resetPriceOnly;
            }
            if (resetAllButton) {
                resetAllButton.onclick = resetAllFilters;
            }

            const urlParams = new URLSearchParams(window.location.search);
            const minPrice = parseFloat(urlParams.get('min_price')) || 0;
            const maxPrice = parseFloat(urlParams.get('max_price')) || categoryMaxPrice;
            const brands = urlParams.get('brands') ? urlParams.get('brands').split(',') : [];

            minInput.value = minPrice;
            maxInput.value = maxPrice;
            minRange.value = minPrice;
            maxRange.value = maxPrice;

            // Устанавливаем начальный стиль ползунка
            const range = maxPrice - minPrice;
            const minPercent = (minPrice / categoryMaxPrice) * 100;
            const rangePercent = (range / categoryMaxPrice) * 100;
            
            rangeProgress.style.left = `${minPercent}%`;
            rangeProgress.style.width = `${rangePercent}%`;

            brands.forEach(brand => {
                const checkbox = document.getElementById(`brand_${brand}`);
                if (checkbox) checkbox.checked = true;
            });

            // Устанавливаем начальную сортировку из URL
            currentSort = urlParams.get('sort') || 'popularity';
            
            // Устанавливаем активную кнопку сортировки
            document.querySelectorAll('.sort-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.sort === currentSort) {
                    btn.classList.add('active');
                }
            });
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