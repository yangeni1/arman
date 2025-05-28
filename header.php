<?php
session_start();

// Проверяем, есть ли сообщение об успешной регистрации
if (isset($_SESSION['registration_success'])) {
    $success_message = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']); // Удаляем сообщение из сессии
}

// Проверяем, авторизован ли пользователь и является ли он администратором
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if ($is_admin) {
    header('Location: admin_panel.php');
}
?>

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
    <!-- Уведомление об успешной регистрации -->
    <?php if (isset($success_message)): ?>
        <div class="success-message" id="successMessage">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
        <script>
            // Автоматическое скрытие уведомления через 5 секунд
            setTimeout(function() {
                const successMessage = document.getElementById('successMessage');
                if (successMessage) {
                    successMessage.style.opacity = '0';
                    setTimeout(function() {
                        successMessage.style.display = 'none';
                    }, 500); // Даем 0.5 секунды на анимацию исчезновения
                }
            }, 5000);
        </script>
    <?php endif; ?>

    <header>
        <div class="container buttom">
            <div class="container_main">
                <div class="time_work_help">
                    <div class="time_work">
                        <img src="./media/header/иконка расписания.svg" alt="часы">
                        <p>Пн-Пт: с 8.00 до 18.00, Сб: с 8.00 до 14.00 без перерывов</p>
                    </div>
                    <div class="help_header">
                        <p>Нужна помощь? <a href="tel:+79526877705">+7 (952) 687-77-05</a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container buttom ">
            <div class="container_main">
                <div class="main_head_fix">
                    <div class="main_head">
                        <a class="img_logo" href="./index.php">
                            <img src="./media/header/logo_header.svg" alt="логотип">
                        </a>
                        <div class="search">
                            <input type="search" placeholder="Поиск по каталогу" id="search-input">
                            <button type="button" class="clear-button">
                                <img src="./media/header/pirat.png" alt="Очистить">
                            </button>
                            <img src="./media/header/Rectangle 1140.png" alt="Разделитель" class="separator">
                            <button type="button" class="search-button" id="search-button">
                                <img src="./media/header/иконка поиска.svg" alt="Поиск" class="search-icon">
                            </button>
                            <script>
                                document.getElementById('search-button').addEventListener('click', function() {
                                    const query = document.getElementById('search-input').value.trim();
                                    if (query) {
                                    const url = new URL(window.location.origin + '/catalog_products.php');
                                    url.searchParams.set('search', query);
                                    url.searchParams.set('subcategory_id', '0');
                                    window.location.href = url.toString();
                                    }
                                });
                                document.getElementById('search-input').addEventListener('keydown', function(event) {
                                    if (event.key === 'Enter') {
                                        event.preventDefault();
                                        document.getElementById('search-button').click();
                                    }
                                });
                            </script>
                        </div>
                        <div class="btn_fav_backet_acc">
                            <div class="btn_main_head">
                                <a href="favorites.php">
                                    <img src="./media/header/иконка избранного.svg" alt="">
                                    Избранное
                                </a>
                            </div>
                            <div class="btn_main_head">
                                <a href="basket.php">
                                    <img src="./media/header/иконка корзина.svg" alt="">
                                    Корзина
                                </a>
                            </div>
                            <div class="btn_main_head">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <a href="profile.php" class="auth_reg">
                                        <img src="./media/header/иконка аккаунта.svg" alt="">
                                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                                    </a>

                                <?php else: ?>
                                    <button class="auth_reg" id="openPopup">
                                        <img src="./media/header/иконка аккаунта.svg" alt="">
                                        Аккаунт
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container buttom">
            <div class="container_main">
                <div class="header_menu">
                    <div class="menu">
                        <a href="./catalog.php" class="btn_catalog">
                            <img src="./media/header/иконка каталога.svg" alt="">
                            <p>Каталог</p>
                        </a>
                        <div class="btn_menu_header">
                            <a href="index.php#anchor_promotions" class="promotions_and_discounts">
                                <img src="./media/header/иконка акции.svg" alt="">
                                <p>Акции & Скидки</p>
                            </a>
                            <a href="index.php#anchor_news" class="new_btn_menu_header">
                                <p>Новинки</p>
                            </a>
                            <a href="delivery.php">
                                <p>Доставка и оплата</p>
                            </a>
                            <a href="about.php">
                                <p>О нас</p>
                            </a>
                            <a href="contant.php">
                                <p>Контакты</p>
                            </a>
                        </div>
                    </div>
                    <div class="feedback_menu_header">
                        <img src="./media/header/иконка обратной связи.svg" alt="">
                        <div class="feedback_txt">
                            <p>+7 (3522) 62-33-60</p>
                            <span>Обратная связь</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Попап для авторизации и регистрации -->
    <div class="popup" id="popup" style="display: none;">
        <div class="body_popup">
            <div class="popup_content">
                <!-- Кнопка для закрытия -->
                <a href="javascript:void(0)" id="closePopup" class="popup_close">&times;</a>

                <!-- Навигация: Вход или Регистрация -->
                <div class="auth_reg_popup">
                    <a href="#" class="popup_log">Войти</a>
                    <p>|</p>
                    <a href="#" class="popup_reg">Зарегистрироваться</a>
                </div>

                <!-- Форма авторизации -->
                <form action="auth.php" method="POST">
                    <!-- Поля для входа -->
                    <div class="input_fields_login">
                        <div class="txt_labael">
                            <label for="email">Введите имя пользователя и пароль для входа.</label>
                        </div>
                        <input type="email" id="email" name="email_login" placeholder="youremail@example.com" required />
                        <div class="password-container">
                            <input type="password" id="password_login" class="password" name="password_login" placeholder="********" required />
                            <button id="togglePasswordLogin" type="button">
                                <img src="./media/popup/1.png" alt="Показать пароль">
                            </button>
                        </div>
                       
                        <button type="submit" class="login-button">Войти</button>
                    </div>
                </form>

                <!-- Форма регистрации -->
                <form action="reg.php" method="POST">
                    <!-- Поля для регистрации -->
                    <div class="input_fields_reg">
                        <div class="txt_labael reg_label">
                            <label for="email">Введите адрес электронной почты и пароль для регистрации.</label>
                        </div>
                        <input type="input" id="first_name" name="first_name" placeholder="Ваше имя" required />
                        <input type="email" id="email" name="email_reg" placeholder="Введите адрес электронной почты" required />
                        <div class="password-container">
                            <input type="password" id="password_reg" class="password" name="password_reg" placeholder="Пароль" required />
                            <button type="button" id="togglePasswordReg">
                                <img src="./media/popup/1.png" alt="Показать пароль" class="toggle-password-icon" />
                            </button>
                        </div>
                        <div class="password-container">
                            <input type="password" id="password_confirmation" class="password_confirmation" name="password_confirmation" placeholder="Подтвердите пароль" required />
                            <button type="button" id="togglePasswordConfirmation">
                                <img src="./media/popup/1.png" alt="Показать пароль" class="toggle-password-icon" />
                            </button>
                        </div>
                        <button type="submit" class="reg-button">Зарегистрироваться</button>
                    </div>
                </form>
                <div class="poloska_down"></div>
            </div>
        </div>
    </div>
    <script src="./js/modal.js"></script>
</body>
</html>