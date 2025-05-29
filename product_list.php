<?php
require_once 'db.php';

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
    <title>Список товаров</title>
    <link rel="stylesheet" href="/css/product_list.css">
</head>
<body>
    <style>
        * {
            font-family: 'Quicksand', sans-serif;
        }
    </style>
    <?php 
        include 'header_adminpanel.php'
    ?>
    <div class="container_product_list">
        <h1>Список товаров</h1>

        <div class="products-grid">

        </div>
    </div>

    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h3 class="modal-title">Подтверждение удаления</h3>
            <p>Вы уверены, что хотите удалить этот товар? Это действие нельзя отменить.</p>
            <div class="modal-footer">
                <button id="cancelDelete" class="btn btn-cancel">Отмена</button>
                <button id="confirmDelete" class="btn btn-confirm">Удалить</button>
            </div>
        </div>
    </div>

    <script src="js/product_list.js"></script>
</body>
</html>