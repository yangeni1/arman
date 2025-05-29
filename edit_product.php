
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


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Неверный ID товара");
}
$product_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount_percentage = $_POST['discount_percentage'] ?? 0;
    $status = $_POST['status'] ?? 1;
    $category = $_POST['category'] ?? null;
    $category_id = $_POST['category_id'] ?? null;
    $brand = $_POST['brand'] ?? null;


    $image = $_POST['current_image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = 'uploads/products/';
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = uniqid('img_', true) . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {

                if ($image && file_exists($image)) {
                    unlink($image);
                }
                $image = $upload_path;
            } else {
                echo "<p class='error'>Ошибка при загрузке файла</p>";
            }
        } else {
            echo "<p class='error'>Недопустимый формат файла</p>";
        }
    }


    $stmt = $pdo->prepare("
        UPDATE products SET 
            name = ?, description = ?, price = ?,  
            discount_percentage = ?, status = ?, category = ?, category_id = ?, 
            brand = ?, image = ? 
        WHERE id = ?
    ");
    $stmt->execute([
        $name, $description, $price, $discount_percentage,
        $status, $category, $category_id, $brand, $image, $product_id
    ]);

    echo "<p class='success'>Товар успешно обновлен!</p>";

}


$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Товар не найден");
}

$stmt = $pdo->query("SELECT name FROM brand");
$brands = $stmt->fetchAll(PDO::FETCH_COLUMN);

$stmt = $pdo->query("SELECT name FROM categories");
$subcategories = $stmt->fetchAll(PDO::FETCH_COLUMN);

$main_categories = ['Конфеты', 'Вафли', 'Печенье', 'Шоколад, шоколадные пасты', 'Пастила, мармелад', 'Пряники', 'Баранки, сушки', 'Пирожные, кексы', 'Выпечка', 'Соки, напитки', 'Чай, кофе', 'Специи, приправы', 'Детские товары', 'Праздничный ассортимент', 'Консервация', 'Макаронные изделия, крупы'];

$statuses = ['', 'хит', 'новинка', 'распродажа'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать товар</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./css/admin_panel.css">
    <link rel="stylesheet" href="./css/adminpanel_header.css">
    <style>
    .success {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        text-align: center;
        margin-bottom: 20px;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border: 1px solid #f5c6cb;
        border-radius: 6px;
        text-align: center;
        margin-bottom: 20px;
    }
    .img_redact{
        width: 200px;
    }
</style>
</head>
<body>
<?php
include './header_adminpanel.php'
?>
<div class="container buttom ">
        <div class="container_main">
            <div class="container_main_admin">
                <h2>Редактировать товар</h2>

                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="current_image" value="<?= htmlspecialchars($product['image']) ?>">

                    <label>Название:
                        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                    </label>

                    <label>Описание:
                        <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
                    </label>

                    <label>Цена:
                        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
                    </label>
                    <label>Скидка (%):
                        <input type="number" step="1" name="discount_percentage" value="<?= $product['discount_percentage'] ?>">
                    </label>

                    <label>Статус:
                        <select name="status">
                            <?php foreach ($statuses as $status_option): ?>
                                <option value="<?= htmlspecialchars($status_option) ?>" <?= $status_option === $product['status'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($status_option) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                <div class="form-group">
                    <label>Категория:
                        <select name="category" required>
                            <?php foreach ($main_categories as $main_category): ?>
                                <option value="<?= htmlspecialchars($main_category) ?>" <?= $main_category === $product['category'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($main_category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                    
                    <label>Подкатегория:
                        <select name="category_id" required>
                            <?php foreach ($subcategories as $subcategory): ?>
                                <option value="<?= htmlspecialchars($subcategory) ?>" <?= $subcategory === $product['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($subcategory) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label>Бренд:
                        <select name="brand" required>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= htmlspecialchars($brand) ?>" <?= $brand === $product['brand'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($brand) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label>Изображение:
                        <input type="file" name="image">
                        <?php if ($product['image']): ?>
                            <img class="image-preview" src="/<?= htmlspecialchars($product['image']) ?>" alt="Текущее изображение">
                        <?php endif; ?>
                    </label>

                    <button type="submit">Сохранить изменения</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>