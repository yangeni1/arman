<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require 'db.php';

// Обработка удаления адреса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_address_id'])) {
    $delete_id = (int)$_POST['delete_address_id'];
    $stmt = $pdo->prepare('DELETE FROM adress WHERE id = ? AND user_id = ?');
    $stmt->execute([$delete_id, $_SESSION['user_id']]);
    header('Location: my_adress_view.php');
    exit;
}

// Обработка добавления нового адреса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    $city = trim($_POST['city']);
    $street = trim($_POST['street']);
    $house = trim($_POST['house']);
    $apartment = trim($_POST['apartment']);
    $notes = trim($_POST['notes']);

    $adress = $city . ', ' . $street . ', ' . $house . ', ' . $apartment;

    $stmt = $pdo->prepare('INSERT INTO adress (user_id, adress, notes) VALUES (?, ?, ?)');
    $stmt->execute([$_SESSION['user_id'], $adress, $notes]);
    header('Location: my_adress_view.php');
    exit;
}

// Получаем адреса пользователя
$stmt = $pdo->prepare('SELECT * FROM adress WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$addresses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои адреса - Арман</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .container_adresses {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: "Quicksand", sans-serif;
        }
        .address-list {
            margin-top: 20px;
            max-width: 600px;
            width: 50%;
        }
        .address-item {
            position: relative;
            padding: 10px 40px 10px 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .delete-btn {
            position: absolute;
            right: 10px;
            top: 10px;
            color: #c00;
            font-weight: bold;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 18px;
            line-height: 1;
        }
        .add-address-form {
            margin-top: 30px;
            max-width: 600px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
        }
        .add-address-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .add-address-form input, .add-address-form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="container_main">
            <div class="profile-container">
                <h1>Личный кабинет</h1>
            </div>
            <div class="profile-menu">
                <a href="profile.php" class="profile-menu-item">Вернуться назад</a>
                <a href="my_orders_view.php" class="profile-menu-item">Мои заказы</a>
                <a href="my_adress_view.php" class="profile-menu-item">Мои адреса</a>
                <a href="setting_profile_view.php" class="profile-menu-item">Настройки профиля</a>
                <a href="logout.php" class="profile-menu-item">Выйти</a>
            </div>
        </div>
    </div>
    <h2 class="center">Мои адреса</h2>
    <div class="container_adresses">
            <div class="address-list">
                <?php if (count($addresses) === 0): ?>
                    <p>Адреса не добавлены.</p>
                <?php else: ?>
                    <?php foreach ($addresses as $address): ?>
                        <div class="address-item">
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="delete_address_id" value="<?= htmlspecialchars($address['id']) ?>">
                                <button type="submit" class="delete-btn" title="Удалить адрес" onclick="return confirm('Удалить этот адрес?');">&times;</button>
                            </form>
                            <div><strong>Адрес:</strong> <?= htmlspecialchars($address['adress']) ?></div>
                            <div><strong>Примечания:</strong> <?= nl2br(htmlspecialchars($address['notes'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <form class="add-address-form" method="post">
                <h2>Добавить новый адрес</h2>
                <input type="hidden" name="add_address" value="1">
                <label for="city">Город</label>
                <input type="text" id="city" name="city" required>
                <label for="street">Улица</label>
                <input type="text" id="street" name="street" required>
                <label for="house">Дом</label>
                <input type="text" id="house" name="house" required>
                <label for="apartment">Квартира</label>
                <input type="text" id="apartment" name="apartment" required>
                <label for="notes">Особые примечания</label>
                <textarea id="notes" name="notes" rows="3"></textarea>
                <button class="login-button" type="submit">Добавить адрес</button>
            </form>
            
</div>
    <?php include 'footer.php'; ?>
</body>
</html>
