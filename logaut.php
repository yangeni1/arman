<?php
session_start(); // Начинаем сессию

// Удаляем все переменные сессии
$_SESSION = array();

// Если нужно уничтожить сессию, также удаляем сессионный блок данных cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Наконец, уничтожаем сессию
session_destroy();

// Перенаправляем на страницу входа или главную страницу
header("Location: index.php"); // Укажите нужный URL для перенаправления
exit();
?>