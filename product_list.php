<?php
require_once 'db.php';
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
    <?php 
        include 'header_adminpanel.php'
    ?>
    <div class="container_product_list">
        <h1>Список товаров</h1>

        <div class="products-grid">
            <!-- Товары будут загружены через AJAX -->
        </div>
    </div>

    <!-- Модальное окно подтверждения удаления -->
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