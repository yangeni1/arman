<?php
include 'db.php';
include 'header.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';
$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : null;

// Получаем максимальную цену для текущей категории
if ($category === 'Все товары') {
    $maxPriceQuery = $pdo->query("SELECT MAX(price) as max_price FROM products");
} else {
    $stmt = $pdo->prepare("SELECT MAX(price) as max_price FROM products WHERE category = ?");
    $stmt->execute([$category]);
}
$categoryMaxPrice = ($category === 'Все товары')
    ? $pdo->query("SELECT MAX(price) as max_price FROM products")->fetch(PDO::FETCH_ASSOC)['max_price']
    : $stmt->fetch(PDO::FETCH_ASSOC)['max_price'];
$categoryMaxPrice = $categoryMaxPrice ?? 1000;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($category) ?> - Арман</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper @11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<div class="container">
    <div class="container_main">
        <div class="head_catalog">
            <p><?= htmlspecialchars($category) ?></p>
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
                <a href="catalog_products.php?category=<?= urlencode($category) ?>&subcategory_id=0"
                           class="<?= !$subcategory_id ? 'active' : '' ?>">Все</a>
                        <?php
                        // Получаем подкатегории (имена) для текущей категории
                        $sql = "SELECT p.category_id, c.name AS subcategory_name 
                                FROM products p
                                JOIN categories c ON p.category_id = c.id
                                WHERE p.category = ?
                                GROUP BY p.category_id, c.name";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$category]);
                        $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($subcategories as $sub): ?>
                            <a href="catalog_products.php?category=<?= urlencode($category) ?>&subcategory_id=<?= $sub['category_id'] ?>"
                               class="<?= ($subcategory_id == $sub['category_id']) ? 'active' : '' ?>">
                                <?= htmlspecialchars($sub['subcategory_name']) ?>
                            </a>
                        <?php endforeach; ?>
                </div>

                <!-- Фильтр по цене -->
                <div class="filter_switch">
                    <div class="cena_sbros">
                        <p>Цена</p>
                        <button onclick="resetPriceOnly()">Сброс</button>
                    </div>
                    <div class="inputs">
                        <input type="number" id="minPrice" placeholder="От" onchange="updateFromInputs()">
                        <img src="./media/filter/minus.png" alt="">
                        <input type="number" id="maxPrice" placeholder="До" onchange="updateFromInputs()">
                    </div>
                    <div class="polzynok">
                        <div class="range-slider">
                            <div class="range-progress"></div>
                            <input type="range" id="minRange" min="0" max="<?= $categoryMaxPrice ?>" step="1" oninput="updatePriceInputs()">
                            <input type="range" id="maxRange" min="0" max="<?= $categoryMaxPrice ?>" step="1" oninput="updatePriceInputs()">
                        </div>
                    </div>
                </div>

                <!-- Фильтр по брендам -->
                <div class="filter_brand">
                    <p>Бренд</p>
                    <?php
                    $stmt = $pdo->prepare("SELECT DISTINCT brand FROM products WHERE category = ?");
                    $stmt->execute([$category]);
                    $brands = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($brands as $brand): ?>
                        <div class="brand_checkbox">
                            <input type="checkbox" id="brand_<?= htmlspecialchars($brand) ?>" value="<?= htmlspecialchars($brand) ?>" onchange="applyFilters()">
                            <p><?= htmlspecialchars($brand) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="btn_sbros_filter">
                    <button onclick="resetAllFilters()">Сбросить всё</button>
                </div>
            </div>

            <!-- Блок товаров -->
            <div class="card_product_catalog_products" id="productsContainer">
                <!-- Здесь будут загружаться товары -->
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
    <script src="js/product-modal.js"></script>
    <script src="js/favorites.js"></script>
<script>
    let updateTimeout;
    const cardProductCatalog = document.getElementById('productsContainer');
    const minRange = document.getElementById('minRange');
    const maxRange = document.getElementById('maxRange');
    const minInput = document.getElementById('minPrice');
    const maxInput = document.getElementById('maxPrice');
    const rangeProgress = document.querySelector('.range-progress');
    const categoryMaxPrice = <?= json_encode($categoryMaxPrice) ?>;
    const selectedCategory = <?= json_encode($category) ?>;
    let currentSort = 'popularity';

    function updateProducts() {
        const minPrice = parseFloat(minInput.value) || 0;
        const maxPrice = parseFloat(maxInput.value) || categoryMaxPrice;

        const brandCheckboxes = document.querySelectorAll('.brand_checkbox input[type="checkbox"]');
        const selectedBrands = Array.from(brandCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        let url = `get_polzunok.php?category=${encodeURIComponent(selectedCategory)}&min_price=${minPrice}&max_price=${maxPrice}&sort=${currentSort}`;
        if (selectedBrands.length > 0) {
            url += `&brands=${encodeURIComponent(selectedBrands.join(','))}`;
        }
        if (<?= $subcategory_id ?>) {
            url += `&subcategory_id=<?= $subcategory_id ?>`;
        }else{
            url += `&subcategory_id=0`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cardProductCatalog.innerHTML = data.html;
                }
            });
    }

    function applyFilters() {
        const minPrice = parseFloat(minInput.value) || 0;
        const maxPrice = parseFloat(maxInput.value) || categoryMaxPrice;

        const brandCheckboxes = document.querySelectorAll('.brand_checkbox input[type="checkbox"]');
        const selectedBrands = Array.from(brandCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        let url = new URL(window.location.href);
        url.searchParams.set('min_price', minPrice);
        url.searchParams.set('max_price', maxPrice);
        url.searchParams.set('sort', currentSort);
        if (selectedBrands.length > 0) {
            url.searchParams.set('brands', selectedBrands.join(','));
        } else {
            url.searchParams.delete('brands');
        }
        if (<?= $subcategory_id ?>) {
            url.searchParams.set('subcategory_id', <?= $subcategory_id ?>);
        }

        window.location.href = url.toString();
    }

    function resetPriceOnly() {
        minInput.value = 0;
        maxInput.value = categoryMaxPrice;
        minRange.value = 0;
        maxRange.value = categoryMaxPrice;
        rangeProgress.style.left = '0%';
        rangeProgress.style.width = '100%';
        applyFilters();
    }

    function resetAllFilters() {
        document.querySelectorAll('.brand_checkbox input[type="checkbox"]').forEach(cb => cb.checked = false);
        minInput.value = 0;
        maxInput.value = categoryMaxPrice;
        minRange.value = 0;
        maxRange.value = categoryMaxPrice;
        rangeProgress.style.left = '0%';
        rangeProgress.style.width = '100%';
        applyFilters();
    }

    function updatePriceInputs() {
        const minPrice = parseFloat(minRange.value);
        const maxPrice = parseFloat(maxRange.value);

        minInput.value = minPrice;
        maxInput.value = maxPrice;

        const range = maxPrice - minPrice;
        const minPercent = (minPrice / categoryMaxPrice) * 100;
        const rangePercent = (range / categoryMaxPrice) * 100;
        rangeProgress.style.left = `${minPercent}%`;
        rangeProgress.style.width = `${rangePercent}%`;

        clearTimeout(updateTimeout);
        updateTimeout = setTimeout(() => updateProducts(), 300);
    }

    function updateFromInputs() {
        const minPrice = parseFloat(minInput.value) || 0;
        const maxPrice = parseFloat(maxInput.value) || categoryMaxPrice;

        if (minPrice > maxPrice) {
            if (event.target === minInput) {
                minInput.value = maxPrice;
                minRange.value = maxPrice;
            } else {
                maxInput.value = minPrice;
                maxRange.value = minPrice;
            }
        }

        minRange.value = minPrice;
        maxRange.value = maxPrice;

        const range = maxPrice - minPrice;
        const minPercent = (minPrice / categoryMaxPrice) * 100;
        const rangePercent = (range / categoryMaxPrice) * 100;
        rangeProgress.style.left = `${minPercent}%`;
        rangeProgress.style.width = `${rangePercent}%`;

        clearTimeout(updateTimeout);
        updateTimeout = setTimeout(() => updateProducts(), 300);
    }

    // Обработчики сортировки
    document.querySelectorAll('.sort-btn').forEach(button => {
        button.addEventListener('click', function () {
            document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentSort = this.dataset.sort;
            updateProducts();
        });
    });

    // Загрузка товаров при открытии
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const minPrice = parseFloat(urlParams.get('min_price')) || 0;
        const maxPrice = parseFloat(urlParams.get('max_price')) || categoryMaxPrice;
        const brands = urlParams.get('brands') ? urlParams.get('brands').split(',') : [];

        minInput.value = minPrice;
        maxInput.value = maxPrice;
        minRange.value = minPrice;
        maxRange.value = maxPrice;

        const range = maxPrice - minPrice;
        const minPercent = (minPrice / categoryMaxPrice) * 100;
        const rangePercent = (range / categoryMaxPrice) * 100;
        rangeProgress.style.left = `${minPercent}%`;
        rangeProgress.style.width = `${rangePercent}%`;

        brands.forEach(brand => {
            const checkbox = document.getElementById(`brand_${brand}`);
            if (checkbox) checkbox.checked = true;
        });

        const sortParam = urlParams.get('sort');
        if (sortParam) {
            currentSort = sortParam;
            document.querySelectorAll('.sort-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.sort === sortParam) btn.classList.add('active');
            });
        }

        updateProducts();
    });
</script>
<script src="js/cart.js"></script>
</body>
</html>