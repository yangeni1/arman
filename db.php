<?php
$host = "localhost"; // Хост
$db_name = "sonyaa64_td"; // Имя базы данных
$username = "root"; // Имя пользователя
$password = ""; // Пароль

try {
    // Подключение к базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    
    // Настройки PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Включаем режим исключений для ошибок
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Отключаем эмуляцию подготовленных выражений
} catch (PDOException $e) {
    // Логируем ошибку (в production)
    error_log("Ошибка подключения к базе данных: " . $e->getMessage());
    
    // Выводим общее сообщение об ошибке
    die("Произошла ошибка при подключении к базе данных. Пожалуйста, попробуйте позже.");
}
// Настройка логирования
define('LOG_FILE', __DIR__.'/logs/app.log');

function logMessage($message) {
    $timestamp = date("Y-m-d H:i:s");
    $log = "[$timestamp] $message" . PHP_EOL;
    file_put_contents(LOG_FILE, $log, FILE_APPEND);
}
?>