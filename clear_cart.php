<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

// Проверяем, авторизован ли пользователь
if (isset($_SESSION['user_id'])) {
    try {
        // Удаляем все товары из корзины пользователя
        $sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$_SESSION['user_id']]);

        if ($result) {
            // Отправляем успешный ответ
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('Ошибка при выполнении запроса');
        }
    } catch (Exception $e) {
        // В случае ошибки отправляем сообщение об ошибке
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    // Если пользователь не авторизован
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Пользователь не авторизован']);
}
?> 