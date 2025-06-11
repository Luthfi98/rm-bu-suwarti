<?php
require_once 'pages/layouts/header.php';
?>
<?php
// Count total orders
$stmt = $conn->prepare("SELECT COUNT(*) FROM orders");
$stmt->execute();
$totalOrders = $stmt->fetchColumn();

// Count total revenue from completed orders
$stmt = $conn->prepare("SELECT SUM(total_amount) FROM orders WHERE status = 'completed'");
$stmt->execute();
$totalRevenue = $stmt->fetchColumn();

// Count total revenue from pending orders
$stmt = $conn->prepare("SELECT SUM(total_amount) FROM orders WHERE status = 'pending'");
$stmt->execute();
$pendingRevenue = $stmt->fetchColumn();

require_once 'pages/admin/dashboard.php';
require_once 'pages/layouts/footer.php';
?>
