<?php
require 'db.php';

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

$stmt = $pdo->query("
    SELECT 
        COUNT(*) AS total_products,
        AVG(price) AS average_price,
        AVG(rating) AS average_rating,
        COUNT(CASE WHEN discount_percentage > 0 THEN 1 END) AS discounted_products,
        MAX(price) AS max_price,
        MIN(price) AS min_price
    FROM products
");
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

$order_stats = [];

$stmt = $pdo->query("SELECT COUNT(*) AS total_orders FROM orders");
$order_stats['total_orders'] = $stmt->fetchColumn();

// Среднее количество товаров на заказ
$stmt = $pdo->query("SELECT AVG(quantity) AS avg_quantity_per_order FROM orders");
$order_stats['avg_quantity_per_order'] = $stmt->fetchColumn();

// Количество заказов по статусам
$status_order_stats = [];
$stmt = $pdo->query("
    SELECT 
        status,
        COUNT(id) AS count
    FROM 
        orders
    GROUP BY 
        status
");
foreach ($stmt as $row) {
    $status_order_stats[$row['status']] = $row['count'];
}

// Общая сумма всех заказов
$stmt = $pdo->query("
    SELECT 
        SUM(o.quantity * p.price) AS total_revenue
    FROM 
        orders o
    JOIN 
        products p ON o.product_id = p.id
");
$order_stats['total_revenue'] = $stmt->fetchColumn();


// Статистика заказов за месяц
$monthly_order_stats = [];
$stmt = $pdo->query("
    SELECT 
        DATE(o.datetime) AS order_date,
        COUNT(*) AS order_count
    FROM 
        orders o
    WHERE 
        MONTH(o.datetime) = MONTH(CURDATE()) AND YEAR(o.datetime) = YEAR(CURDATE())
    GROUP BY 
        DATE(o.datetime)
    ORDER BY 
        DATE(o.datetime)
");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $monthly_order_stats[$row['order_date']] = $row['order_count'];
}


// Топ-5 самых популярных товаров
$top_products = [];
$stmt = $pdo->query("
    SELECT 
        p.name AS product_name,
        SUM(o.quantity) AS total_sold
    FROM 
        orders o
    JOIN 
        products p ON o.product_id = p.id
    GROUP BY 
        o.product_id
    ORDER BY 
        total_sold DESC
    LIMIT 5
");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $top_products[] = [
        'name' => htmlspecialchars($row['product_name']),
        'sold' => number_format($row['total_sold'], 0, '', ' ')
    ];
}



?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Статистика</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            padding: 40px;
            color: #333;
        }

        .container1 {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .stat-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f1f3f6;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: background-color 0.3s ease;
        }

        .stat-card:hover {
            background-color: #e9ecef;
        }

        .stat-title {
            font-size: 16px;
            font-weight: bold;
        }

        .stat-value {
            font-size: 18px;
            color: #007bff;
        }

        .status-stats {
            margin-top: 40px;
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .status-name {
            font-weight: 500;
        }

        .status-count {
            color: #28a745;
            font-weight: bold;
        }

        .order-stats {
            margin-top: 40px;
        }

        .order-stat {
            margin-bottom: 15px;
        }

        .top-products {
            margin-top: 40px;
        }

        .top-product-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
    </style>
		    <script src="https://cdn.jsdelivr.net/npm/chart.js "></script>

</head>
<body>

<?php 
require 'header_adminpanel.php';
?>

<div class="container1">
    <h1>📊 Статистика</h1>

    <!-- Статистика товаров -->
    <h2>Товары</h2>
    <div class="stat-card">
        <div class="stat-title">Общее количество товаров</div>
        <div class="stat-value"><?= number_format($stats['total_products'], 0, '', ' ') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Средняя цена</div>
        <div class="stat-value"><?= number_format($stats['average_price'], 2, ',', ' ') ?> ₽</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Товаров со скидкой</div>
        <div class="stat-value"><?= number_format($stats['discounted_products'], 0, '', ' ') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Самый дорогой товар</div>
        <div class="stat-value"><?= number_format($stats['max_price'], 2, ',', ' ') ?> ₽</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Самый дешёвый товар</div>
        <div class="stat-value"><?= number_format($stats['min_price'], 2, ',', ' ') ?> ₽</div>
    </div>
    <!-- Статистика заказов -->
    <h2>Заказы</h2>
    <div class="order-stats">
        <div class="order-stat">
            <div class="stat-card">
                <div class="stat-title">Общее количество заказов</div>
                <div class="stat-value"><?= number_format($order_stats['total_orders'], 0, '', ' ') ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Среднее количество товаров на заказ</div>
                <div class="stat-value"><?= number_format($order_stats['avg_quantity_per_order'], 2, ',', ' ') ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Общая сумма всех заказов</div>
                <div class="stat-value"><?= number_format($order_stats['total_revenue'], 2, ',', ' ') ?> ₽</div>
            </div>
        </div>

<h3>📅 Заказы за текущий месяц</h3>
        <canvas id="monthlyOrderChart" class="chart-container"></canvas>

       <script>
        const monthlyOrderData = {
            labels: <?php echo json_encode(array_keys($monthly_order_stats)); ?>,
            datasets: [{
                label: 'Количество заказов',
                data: <?php echo json_encode(array_values($monthly_order_stats)); ?>,
                backgroundColor: '#007bff',
                borderColor: '#fff',
                borderWidth: 1
            }]
        };

        // Конфигурация диаграммы
        const monthlyConfig = {
            type: 'bar',
            data: monthlyOrderData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Количество заказов за текущий месяц'
                    }
                },
            },
        };

        const monthlyCtx = document.getElementById('monthlyOrderChart').getContext('2d');
        new Chart(monthlyCtx, monthlyConfig);
    </script>



        <div class="top-products">
            <h3>🏆 Топ-5 самых популярных товаров</h3>
            <ul>
                <?php foreach ($top_products as $product): ?>
                    <li class="top-product-item">
                        <span><?= $product['name'] ?></span>
                        <span>(<?= $product['sold'] ?> шт.)</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

</body>
</html>