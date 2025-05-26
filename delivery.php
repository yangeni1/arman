<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доставка и оплата - Арман</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .delivery_main {
            margin-top: 98px;
            padding: 40px 0;
            width: 100%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .delivery_content {
            background: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .delivery_content h2 {
            color: #253D4E;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .delivery_content p {
            color: #253D4E;
            font-family: "Quicksand", sans-serif;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .delivery_content ul {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }

        .delivery_content li {
            color: #253D4E;
            font-family: "Quicksand", sans-serif;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }

        .delivery_content li:before {
            content: "•";
            color: #5E9F67;
            font-size: 20px;
            position: absolute;
            left: 0;
            top: -2px;
        }

        .map-container {
            width: 100%;
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 30px;
        }

        #map {
            width: 100%;
            height: 100%;
            border: none;
        }

        .delivery_section {
            margin-bottom: 30px;
        }

        .delivery_section:last-child {
            margin-bottom: 0;
        }

        .delivery_section h3 {
            color: #5E9F67;
            font-family: "Quicksand", sans-serif;
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .delivery_main {
                padding: 20px;
                margin-top: 50px;
            }

            .delivery_content {
                padding: 20px;
            }

            .map-container {
                height: 300px;
            }
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
            <div class="delivery_main">
                <div class="delivery_content">
                    <h2>Доставка и оплата</h2>
                    
                    <div class="delivery_section">
                        <h3>Способы доставки</h3>
                        <ul>
                            <li>Курьерская доставка по Кургану и Тюмени</li>
                            <li>Самовывоз из магазина</li>
                            <li>Доставка в регионы России</li>
                        </ul>
                        <p>Стоимость доставки по Кургану и Тюмени - от 300 рублей. При заказе от 1500 рублей доставка бесплатная.</p>
                    </div>

                    <div class="delivery_section">
                        <h3>Сроки доставки</h3>
                        <ul>
                            <li>По Кургану и Тюмени - в день заказа или на следующий день</li>
                            <li>В регионы России - от 3 до 7 рабочих дней</li>
                        </ul>
                    </div>

                    <div class="delivery_section">
                        <h3>Способы оплаты</h3>
                        <ul>
                            <li>Наличными при получении</li>
                            <li>Банковской картой при получении</li>
                            <li>Онлайн оплата банковской картой</li>
                        </ul>
                    </div>

                    <div class="delivery_section">
                        <h3>Наши магазины</h3>
                        <p>Вы можете посетить наши магазины в Кургане и Тюмени:</p>
                        <ul>
                            <li>г. Курган, ул. Пушкина, д. 167</li>
                            <li>г. Тюмень, ул. Барабинская, д. 3, корп. 17</li>
                        </ul>
                    </div>

                    <div class="map-container">
                        <iframe src="https://yandex.ru/map-widget/v1/?ll=65.434500,56.310500&z=6&l=map&pt=65.341200,55.468700,pm2rdm1~65.527800,57.152200,pm2rdm2&text=г. Курган, ул. Пушкина, 167~г. Тюмень, ул. Барабинская, д. 3, корпус 17" width="100%" height="400" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html> 