<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Заказы</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./css/admin_panel.css" />
    <link rel="stylesheet" href="./css/adminpanel_header.css" />
    <style>
        .order-card {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .order-header {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .order-info {
            margin-bottom: 5px;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: #74B1F1;
        }
        .pagination a.active {
            font-weight: bold;
            text-decoration: underline;
            color: #72CE6C;
        }
        .sort-select {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?php
    include './header_adminpanel.php';
    include './db.php';

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
    // Handle status update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
        $orderId = (int)$_POST['order_id'];
        $status = $_POST['status'];
        if ($status === '') {
            $status = null;
        } else {
            $status = (int)$status;
        }
        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status, PDO::PARAM_INT | PDO::PARAM_NULL);
        $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $statusFilter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
        $location = "admin_orders.php?page=$page";
        if ($statusFilter !== '') {
            $location .= "&status_filter=$statusFilter";
        }
        header("Location: $location");
        exit;
    }

    // настройки пагинации
    $perPage = 10;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $perPage;

    $statusFilter = isset($_GET['status_filter']) && in_array($_GET['status_filter'], ['0', '1', '2']) ? $_GET['status_filter'] : null;

    if ($statusFilter !== null) {
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE status = :status");
        $countStmt->bindValue(':status', $statusFilter, PDO::PARAM_INT);
        $countStmt->execute();
    } else {
        $countStmt = $pdo->query("SELECT COUNT(*) FROM orders");
    }
    $totalOrders = $countStmt->fetchColumn();
    $totalPages = ceil($totalOrders / $perPage);

    $sql = "SELECT o.id as order_id, o.quantity, o.datetime, o.status, o.address, o.phone,
                   p.name as product_name,
                   u.username as customer_name
            FROM orders o
            LEFT JOIN products p ON o.product_id = p.id
            LEFT JOIN users u ON o.user_id = u.id";

    if ($statusFilter !== null) {
        $sql .= " WHERE o.status = :status";
    }

    $sql .= " ORDER BY o.datetime DESC
              LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    if ($statusFilter !== null) {
        $stmt->bindValue(':status', $statusFilter, PDO::PARAM_INT);
    }
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    function getStatusLabel($status) {
        if ($status === 0) return 'в обработке';
        if ($status === 1) return 'доставляется';
        if ($status === 2) return 'отменён';
        return 'неизвестно';
    }
    ?>

    <div class="container buttom">
        <div class="container_main">
            <div class="container_main_admin">
                <h1>Заказы</h1>

                <form method="get" class="sort-select">
                    <label for="status_filter">Фильтр по статусу:</label>
                    <select name="status_filter" id="status_filter" onchange="this.form.submit()">
                        <option value="" <?= $statusFilter === null ? 'selected' : '' ?>>Все статусы</option>
                        <option value="0" <?= $statusFilter === '0' ? 'selected' : '' ?>>в обработке</option>
                        <option value="1" <?= $statusFilter === '1' ? 'selected' : '' ?>>доставляется</option>
                        <option value="2" <?= $statusFilter === '2' ? 'selected' : '' ?>>отменён</option>
                    </select>
                </form>

                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">Заказ №<?= htmlspecialchars((string)($order['order_id'] ?? '')) ?></div>
                        <div class="order-info"><strong>Что заказали:</strong> <?= htmlspecialchars((string)($order['product_name'] ?? '')) ?></div>
                        <div class="order-info"><strong>Количество:</strong> <?= htmlspecialchars((string)($order['quantity'] ?? '')) ?></div>
                        <div class="order-info"><strong>Дата:</strong> <?= htmlspecialchars((string)($order['datetime'] ?? '')) ?></div>
                        <div class="order-info"><strong>Имя заказчика:</strong> <?= htmlspecialchars((string)($order['customer_name'] ?? '')) ?></div>
                        <div class="order-info"><strong>Телефон:</strong> <?= htmlspecialchars((string)($order['phone'] ?? '')) ?></div>
                        <div class="order-info"><strong>Адрес:</strong> <?= htmlspecialchars((string)($order['address'] ?? '')) ?></div>

                        <form method="post" style="margin-top:10px;">
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars((string)($order['order_id'] ?? '')) ?>" />
                            <label for="status_<?= $order['order_id'] ?>">Статус заказа:</label>
                            <select name="status" id="status_<?= $order['order_id'] ?>" onchange="this.form.submit()">
                                <option value="0" <?= $order['status'] === 0 ? 'selected' : '' ?>>в обработке</option>
                                <option value="1" <?= $order['status'] === 1 ? 'selected' : '' ?>>доставляется</option>
                                <option value="2" <?= $order['status'] === 2 ? 'selected' : '' ?>>отменён</option>
                            </select>
                        </form>
                    </div>
                <?php endforeach; ?>

                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?><?php if ($statusFilter !== null) echo '&status_filter=' . htmlspecialchars($statusFilter); ?>">&laquo; Назад</a>
                    <?php endif; ?>

                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <a href="?page=<?= $p ?><?php if ($statusFilter !== null) echo '&status_filter=' . htmlspecialchars($statusFilter); ?>" class="<?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?><?php if ($statusFilter !== null) echo '&status_filter=' . htmlspecialchars($statusFilter); ?>">Вперед &raquo;</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
