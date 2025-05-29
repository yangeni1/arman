<?php
require './db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: index.php');
    exit;
}
if ($user['role'] === 'user' ) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить товар</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./css/admin_panel.css">
    <link rel="stylesheet" href="./css/adminpanel_header.css">
</head>

<body>
    <?php
    include './header_adminpanel.php'
    ?>
    <!-- Добавление категорий -->
    <div class="container buttom ">
        <div class="container_main">
            <div class="container_main_admin">
                <h1>Добавить новую подкатегорию</h1>
                <form id="categoryForm">
                    <div class="form-group">
                        <label for="category_name">Название подкатегории:</label>
                        <input type="text" id="category_name" name="category_name" required maxlength="255">
                    </div>
                    <button type="submit">Добавить подкатегорию</button>
                </form>
                <div id="categoryResponseMessage"></div>
            </div>
        </div>
    </div>
    <!-- Добавление категорий конец -->

    <!-- Добавление Бренда -->
    <div class="container_main_admin">
        <h1>Добавить новый бренд товаров</h1>
        <form id="brandForm">
            <div class="form-group">
                <label for="brand_name">Название бренда:</label>
                <input type="text" id="brand_name" name="brand_name" required maxlength="300">
            </div>
            <button type="submit">Добавить категорию</button>
        </form>
        <div id="brandResponseMessage"></div>
    </div>
    <!-- Добавление Бренда конец -->

    <!-- Добавление товаров -->
    <div class="container_main_admin">
        <h1>Добавить новый товар</h1>
        <form id="productForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="status_id">Статус:</label>
                <select id="status_id" name="status_id" >
                    <option value="">Выберите статус</option>
                    <option value="хит">Хит</option>
                    <option value="новинка">Новинка</option>
                    <option value="распродажа">Распродажа</option>
                </select>
            </div>

            <div class="form-group">
                <label for="category_id">Категория:</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Выберите категорию</option>
                    <option value="Конфеты">Конфеты</option>
                    <option value="Вафли">Вафли</option>
                    <option value="Печенье">Печенье</option>
                    <option value="Шоколад, шоколадные пасты">Шоколад, шоколадные пасты</option>
                    <option value="Пастила, мармелад">Пастила, мармелад</option>
                    <option value="Пряники">Пряники</option>
                    <option value="Баранки, сушки">Баранки, сушки</option>
                    <option value="Пирожные, кексы">Пирожные, кексы</option>
                    <option value="Выпечка">Выпечка</option>
                    <option value="Соки, напитки">Соки, напитки</option>
                    <option value="Чай, кофе">Чай, кофе</option>
                    <option value="Специи, приправы">Специи, приправы</option>
                    <option value="Детские товары">Детские товары</option>
                    <option value="Праздничный ассортимент">Праздничный ассортимент</option>
                    <option value="Консервация">Консервация</option>
                    <option value="Макаронные изделия, крупы">Макаронные изделия, крупы</option>
                </select>
            </div>

            <div class="form-group">
                <label for="categories">Подкатегория:</label>
                <select id="categories" name="categories" required>
                    <option value="">Выберите подкатегорию</option>
                </select>
            </div>



            <div class="form-group">
                <label for="brand_id">Бренд:</label>
                <select id="brand_id" name="brand_id" required>
                    <option value="">Выберите бренд</option>
                </select>
            </div>

            <div class="form-group">
                <label for="discount">Скидка (%):</label>
                <input type="number" id="discount" name="discount" min="0" max="99" step="0.01" placeholder="0-99%">
                <small>Можно указать для любого товара</small>
            </div>

            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="price">Цена:</label>
                <input type="number" id="price" name="price" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label>Изображения товара:</label>
                <div id="imageUploads">
                    <div class="image-upload-item">
                        <input type="file" name="image" accept="image/*" class="image-input" required>
                        <img src="#" alt="Предпросмотр" class="image-preview" style="display: none;">
                        <button type="button" class="remove-image-btn" style="display: none;">×</button>
                        <div class="image-meta">
                            <label>
                                <!-- Removed main image radio since only one image is allowed and it is main by default -->
                                <input type="radio" name="main_image" value="0" checked hidden>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit">Добавить товар</button>
        </form>

        <div id="responseMessage"></div>
    </div>
    <script src="js/category_add.js"></script>
    <script src="js/status_add.js"></script>
    <script src="js/brand_add.js"></script>
    <script src="js/product_add.js"></script>
</body>

</html>
