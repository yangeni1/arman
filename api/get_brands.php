<?php
// Включение отладки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Очистка буфера
while (ob_get_level()) ob_end_clean();

// Подключение БД (используйте правильный путь)
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

try {
    // Проверка подключения
    if (!$pdo) {
        throw new Exception("Нет подключения к БД");
    }

    // Явная проверка таблицы
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'brand'")->fetch();
    if (!$tableCheck) {
        throw new Exception("Таблица 'brand' не существует");
    }

    // Получение данных
    $stmt = $pdo->query("SELECT id, name AS name FROM `brand`");
    
    if ($stmt === false) {
        throw new Exception("Ошибка выполнения запроса");
    }
    
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($data)) {
        $data = [['id' => 0, 'name' => 'Нет статусов в БД']];
    }

    echo json_encode($data, JSON_PRETTY_PRINT);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
exit;