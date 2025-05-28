<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Арман</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        /* Стили для корзины */
        .basket_main {
            margin-top: 98px;
            padding: 40px 0;
            width: 100%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .basket_main_txt {
            text-align: center;
            margin-bottom: 30px;
            line-height: 1.5;
            color: #253D4E;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: 16px;
            background-color: #F9F9F9;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .basket_main_txt a {
            color: #5E9F67;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .basket_main_txt a:hover {
            color: #4A8A52;
        }

        .basket_items {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }

        .basket_item {
            display: flex;
            align-items: center;
            padding: 20px;
            background: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            transition: transform 0.3s ease;
        }

        .basket_item:hover {
            transform: translateY(-2px);
        }

        .basket_item img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-right: 20px;
            border-radius: 5px;
        }

        .basket_item_info {
            flex: 1;
            min-width: 0;
        }

        .basket_item_info h3 {
            color: #253D4E;
            font-size: 16px;
            margin-bottom: 10px;
            font-family: "Quicksand", sans-serif;
            font-weight: 600;
        }

        .basket_item_price {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .basket_item_price .price {
            color: #5E9F67;
            font-size: 18px;
            font-weight: 500;
            font-family: "Quicksand", sans-serif;
        }

        .basket_item_price .old-price {
            color: #999;
            font-size: 14px;
            text-decoration: line-through;
            font-family: "Quicksand-regular", sans-serif;
        }

        .basket_item_quantity {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 30px;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #E5E5E5;
            background: #FFFFFF;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #253D4E;
            transition: all 0.3s ease;
            font-family: "Quicksand", sans-serif;
        }

        .quantity-btn:hover {
            background: #F5F5F5;
            border-color: #5E9F67;
            color: #5E9F67;
        }

        .basket_item_quantity input {
            width: 50px;
            height: 30px;
            border: 1px solid #E5E5E5;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            color: #253D4E;
            font-family: "Quicksand-regular", sans-serif;
        }

        .basket_item_total {
            font-size: 18px;
            font-weight: 500;
            color: #253D4E;
            margin: 0 30px;
            min-width: 100px;
            text-align: right;
            font-family: "Quicksand", sans-serif;
        }

        .remove-item {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 24px;
            height: 24px;
            border: none;
            background: none;
            color: #999;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
        }

        .remove-item:hover {
            color: #FF4444;
        }

        .basket_total {
            margin-top: 30px;
            padding: 20px;
            background: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .basket_total p {
            font-size: 18px;
            color: #253D4E;
            font-family: "Quicksand", sans-serif;
            font-weight: 600;
        }

        .basket_total span {
            font-weight: 500;
            color: #5E9F67;
            font-family: "Quicksand", sans-serif;
        }

        .checkout-btn {
            padding: 12px 30px;
            background: #5E9F67;
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            font-family: "Quicksand", sans-serif;
        }

        .checkout-btn:hover {
            background: #4A8A52;
        }

        /* Стили для анимации оформления заказа */
        .order-processing {
            display: none;
            text-align: center;
            padding: 40px 20px;
            background: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .order-processing.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        .processing-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            position: relative;
        }

        .processing-icon:before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border: 4px solid #F0F0F0;
            border-top-color: #5E9F67;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .processing-icon.success:before {
            animation: none;
            border: none;
            background: #5E9F67;
            border-radius: 50%;
            transform: scale(0.8);
        }

        .processing-icon.success:after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 40px;
            font-weight: bold;
        }

        .processing-text {
            color: #253D4E;
            font-family: "Quicksand", sans-serif;
            font-size: 18px;
            margin-bottom: 15px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }

        .processing-text.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .payment-info {
            color: #5E9F67;
            font-family: "Quicksand", sans-serif;
            font-size: 16px;
            margin-top: 20px;
            padding: 15px;
            background: #F7F8FA;
            border-radius: 8px;
            display: inline-block;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }

        .payment-info.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .basket_items.hidden {
            display: none;
        }

        /* Адаптивность для корзины */
        @media (max-width: 768px) {
            .basket_main {
                padding: 20px;
                margin-top: 50px;
            }

            .basket_item {
                flex-wrap: wrap;
                padding: 15px;
            }

            .basket_item img {
                width: 80px;
                height: 80px;
                margin-right: 15px;
            }

            .basket_item_info {
                flex: 0 0 calc(100% - 95px);
            }

            .basket_item_quantity {
                margin: 15px 0;
                order: 3;
            }

            .basket_item_total {
                margin: 0;
                order: 4;
            }

            .basket_total {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
        .order-form{
            display: table;
            margin: 0 auto;
            width: 310px;
            font-family: "Quicksand", sans-serif;
        }
        .order-form select{
            width: 308px;
            padding-top: 12px;
            padding-bottom: 12px;
            padding-left: 14px;
            border: 1px solid #EAEAEA;
            border-radius: 5px;
            margin-bottom: 17px;
            box-sizing: border-box;
        }
        .order-form a{
            font-size: 14px;
            color: #66B158;
        }
    </style>
</head>
<body>
    <?php
        include 'db.php';
        include 'header.php';
    ?>
    <div class="container">
        <div class="container_main">
            <div class="basket_main">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="basket_main_txt">
                        <a href="#" id="loginLink">Войдите в личный кабинет,</a>
                        <p>чтобы добавить товары в корзину</p>
                    </div>
                <?php else: ?>
                    <?php
                    // Получаем товары из корзины пользователя
                    $sql = "SELECT c.*, p.name, p.price, p.image, p.discount_percentage 
                           FROM cart c 
                           JOIN products p ON c.product_id = p.id 
                           WHERE c.user_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$_SESSION['user_id']]);
                    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (empty($cart_items)): ?>
                        <div class="basket_main_txt">
                            <p>Ваша корзина пуста</p>
                        </div>
                    <?php else: ?>
                        <?php
                        $order_success = false;
                        $error_message = '';
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
                            // Validate phone number format
                            $phone = trim($_POST['phone']);
                            $address_id = (int)$_POST['address_id'];
                            if (!preg_match('/^\+7\(\d{3}\) \d{3} \d{2}-\d{2}$/', $phone)) {
                                $error_message = 'Неверный формат номера телефона. Используйте +7(xxx) xxx xx-xx.';
                            } else {
                                // Get address string from DB
                                $stmt = $pdo->prepare('SELECT adress FROM adress WHERE id = ? AND user_id = ?');
                                $stmt->execute([$address_id, $_SESSION['user_id']]);
                                $address_row = $stmt->fetch();
                                if (!$address_row) {
                                    $error_message = 'Выбранный адрес не найден.';
                                } else {
                                    $address = $address_row['adress'];
                                    // Insert orders for each cart item
                                    $pdo->beginTransaction();
                                    try {
                                        // Get status id for 'new'
                                        // Use integer status 0 for new orders as per user feedback
                                        $statusId = 0;

                                        $now = date('Y-m-d H:i:s');
                                        foreach ($cart_items as $item) {
                                            $stmt = $pdo->prepare('INSERT INTO orders (user_id, product_id, quantity, datetime, status, address, phone) VALUES (?, ?, ?, ?, ?, ?, ?)');
                                            $stmt->execute([
                                                $_SESSION['user_id'],
                                                $item['product_id'],
                                                $item['quantity'],
                                                $now,
                                                $statusId,
                                                $address,
                                                $phone
                                            ]);
                                        }
                                        // Clear cart
                                        $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = ?');
                                        $stmt->execute([$_SESSION['user_id']]);
                                        $pdo->commit();
                                        $order_success = true;
                                    } catch (Exception $e) {
                                        $pdo->rollBack();
                                        $error_message = 'Ошибка при оформлении заказа. Попробуйте позже. Ошибка: ' . $e->getMessage();
                                    }
                                }
                            }
                        }
                        ?>
                        <?php if ($order_success): ?>
                            <div class="basket_main_txt">
                                <p>Заказ оформлен! Оплата доступна при получении. С вами свяжутся для уточнения заказа.</p>
                            </div>
                        <?php else: ?>
                            <div class="basket_items<?= $order_success ? ' hidden' : '' ?>">
                                <?php 
                                $total = 0;
                                foreach ($cart_items as $item): 
                                    $price = $item['discount_percentage'] 
                                        ? $item['price'] * (1 - $item['discount_percentage'] / 100)
                                        : $item['price'];
                                    $item_total = $price * $item['quantity'];
                                    $total += $item_total;
                                ?>
                                    <div class="basket_item" data-cart-id="<?= $item['id'] ?>">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                        <div class="basket_item_info">
                                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                                            <div class="basket_item_price">
                                                <?php if ($item['discount_percentage']): ?>
                                                    <span class="price">₽<?= number_format($price, 2) ?></span>
                                                    <span class="old-price">₽<?= number_format($item['price'], 2) ?></span>
                                                <?php else: ?>
                                                    <span class="price">₽<?= number_format($price, 2) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="basket_item_quantity">
                                            <button class="quantity-btn minus">-</button>
                                            <input type="number" value="<?= $item['quantity'] ?>" min="1" readonly>
                                            <button class="quantity-btn plus">+</button>
                                        </div>
                                        <div class="basket_item_total">
                                            ₽<?= number_format($item_total, 2) ?>
                                        </div>
                                        <button class="remove-item">×</button>
                                    </div>
                                <?php endforeach; ?>
                                <div class="basket_total">
                                    <p>Итого: <span>₽<?= number_format($total, 2) ?></span></p>
                                </div>
                            </div>
                            <form method="post" class="order-form" style="max-width: 600px; margin-top: 20px;">
                                <?php if ($error_message): ?>
                                    <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error_message) ?></p>
                                <?php endif; ?>
                                <label for="address_id">Выберите адрес доставки:</label>
                                <select name="address_id" id="address_id" required>
                                    <option value="">Выберите адрес</option>
                                    <?php
                                    $stmt = $pdo->prepare('SELECT id, adress FROM adress WHERE user_id = ?');
                                    $stmt->execute([$_SESSION['user_id']]);
                                    $user_addresses = $stmt->fetchAll();
                                    foreach ($user_addresses as $addr):
                                    ?>
                                        <option value="<?= $addr['id'] ?>"><?= htmlspecialchars($addr['adress']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <p><a href="my_adress_view.php">Добавить новый адрес</a></p>
                                <label for="phone">Номер телефона:</label>
                                <input type="text" name="phone" id="phone" pattern="^\+7\(\d{3}\) \d{3} \d{2}-\d{2}$" maxlength="17" placeholder="+7(999) 999 99-99" required>
                                <button type="submit" name="place_order" class="checkout-btn">Оформить заказ</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
        include 'footer.php';
    ?>
    <script src="js/modal.js"></script>
    <script src="js/basket.js"></script>
    <script src="js/checkout.js"></script>
    <script>
        document.getElementById('loginLink')?.addEventListener('click', function(e) {
            e.preventDefault();
            const popup = document.querySelector('.popup');
            if (popup) {
                popup.style.display = 'flex';
                const popupLogButton = document.querySelector('.popup_log');
                const popupRegButton = document.querySelector('.popup_reg');
                const inputFieldsReg = document.querySelector('.input_fields_reg');
                const inputFieldsLog = document.querySelector('.input_fields_login');
                
                popupLogButton.style.color = "#5E9F67";
                popupRegButton.style.color = "#253D4E";
                inputFieldsReg.style.display = 'none';
                inputFieldsLog.style.display = 'block';
            }
        });
    </script>
</body>
</html>