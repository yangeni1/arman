<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты - Арман</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php
        include 'db.php';
        include 'header.php';
    ?>
    <main style="margin-top: 100px; margin-bottom: 100px; display: flex; flex-direction: column; align-items: center; text-align: center;">
        <h1 class="center" style="font-size: 36px; font-weight: 700; color: #253D4E;">Контакты</h1>
        <section class="" style="display: flex; flex-direction: column; align-items: center; gap: 20px; font-family: 'Quicksand', sans-serif; color: #253D4E;">
            <div  class="center">
                <p style="font-weight: 600; font-size: 20px; margin-bottom: 10px;">Розничный магазин:</p>
                <p>г. Курган, ул. Пушкина, д. 167</p>
                <p>г. Тюмень, ул. Барабинская, д. 3, корп. 17</p>
            </div>
        </section>
        <section class="" style="margin-top: 50px; font-family: 'Quicksand', sans-serif; color: #253D4E;">
            <p style="font-weight: 600; font-size: 20px; margin-bottom: 20px;">Остались вопросы? Мы всегда на связи:</p>
            <div class="" style="display: flex; justify-content: center; gap: 30px; margin-bottom: 30px;">
                <a href="tel:+79920300072" style="display: flex; align-items: center;">
                    <img src="./media/footer/viber.png" alt="Viber" style="width: 50px; height: 50px;">
                </a>
                <a href="tel:+79920300072" style="display: flex; align-items: center;">
                    <img src="./media/footer/whatsapp.png" alt="WhatsApp" style="width: 50px; height: 50px;">
                </a>
                <a href="tel:+79920300072" style="display: flex; align-items: center;">
                    <img src="./media/footer/telegram.png" alt="Telegram" style="width: 50px; height: 50px;">
                </a>
            </div>
            <p style="font-size: 18px; font-weight: 500;">Телефон: <a href="tel:+7 (3522) 62-33-60" style="color: #5E9F67; text-decoration: none; font-weight: 700;">+7 (3522) 62-33-60</a></p>
        </section>
    </main>
    <?php
        include 'footer.php';
    ?>
</body>
</html>
