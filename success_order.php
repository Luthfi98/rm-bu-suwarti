
<?php
// session_start();
// setcookie('orderIds', '', 0);
?>

<?php 

// Get cart data from local storage using JavaScript

require_once 'pages/layouts/header.php'; 
$orderIds = isset($_SESSION['orderIds']) ? $_SESSION['orderIds'] : [];
// var_dump($_SESSION);die;

$orderIdsStr = "'" . implode("','", $orderIds) . "'";

// Get order number from URL parameter
$orderNumber = isset($_GET['orderNumber']) ? $_GET['orderNumber'] : null;



// Fetch all orders for the list
$ordersSql = "SELECT * FROM orders WHERE order_number IN ($orderIdsStr) ORDER BY created_at DESC";
// var_dump($ordersSql);
$ordersStmt = $conn->prepare($ordersSql);
$ordersStmt->execute();
$allOrders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);

// If no specific order is selected, show the first order
if (!$orderNumber && !empty($allOrders)) {
    $orderNumber = $allOrders[0]['order_number'];
}

// Fetch selected order details
if ($orderNumber) {
    $orderSql = "SELECT * FROM orders WHERE order_number = ? AND order_number IN ($orderIdsStr)";
    $orderStmt = $conn->prepare($orderSql);
    $orderStmt->execute([$orderNumber]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // Fetch order items
        $itemsSql = "SELECT oi.*, mi.name 
                    FROM order_items oi 
                    JOIN menu_items mi ON oi.menu_item_id = mi.id 
                    WHERE oi.order_id = ?";
        $itemsStmt = $conn->prepare($itemsSql);
        $itemsStmt->execute([$order['id']]);
        $orderItems = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<link rel="stylesheet" href="<?php echo $baseUrl ?>assets/css/order-detail.css">

<div class="order-container">
    <!-- Left Side - Order List -->
    <div class="order-list">
        <div class="order-list-header">
            <h3>Daftar Pesanan</h3>
        </div>
        <div class="order-list-body">
            <?php if (count($allOrders) == 0): ?>
            <div class="no-order">
                <h3>Belum memiliki daftar pesanan</h3>
                <p></p>
            </div>
            <?php endif; ?>
            
            <?php foreach ($allOrders as $listOrder): ?>
            <a href="?orderNumber=<?= $listOrder['order_number'] ?>" 
               class="order-list-item <?= ($orderNumber == $listOrder['order_number']) ? 'active' : '' ?>">
                <div class="order-list-item-header">
                    <span class="order-number"><?= $listOrder['order_number'] ?></span>
                    <span class="order-status <?= $listOrder['status'] ?>"><?= ucfirst($listOrder['status']) ?></span>
                </div>
                <div class="order-list-item-details">
                    <span class="order-date"><?= date('d M Y H:i', strtotime($listOrder['created_at'])) ?></span>
                    <span class="order-total">Rp <?= number_format($listOrder['total_amount'], 0, ',', '.') ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Right Side - Order Details -->
    <div class="order-detail">
        <?php if (isset($order) && isset($orderItems)): ?>
        <div class="order-detail-card">
            <div class="order-detail-header">
                <h3>Detail Pesanan</h3>
            </div>
            <div class="order-detail-body">
                <div class="order-info">
                    <h4>Informasi Pesanan</h4>
                    <p><strong>Nomor Pesanan:</strong> <?= $order['order_number'] ?></p>
                    <p><strong>Tanggal:</strong> <?= date('d F Y H:i', strtotime($order['created_at'])) ?></p>
                    <p><strong>Status:</strong> <span class="badge bg-primary"><?= ucfirst($order['status']) ?></span></p>
                </div>

                <div class="order-items">
                    <h4>Item Pesanan</h4>
                    <table class="order-table" width="100%">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td><?= $item['name'] ?></td>
                                <td class="text-end">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td class="text-end">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-center">
                    <a href="index.php" class="back-button">Kembali ke Menu</a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="order-detail-card">
            <div class="order-detail-body">
                <div class="no-order">
                    <h3>Tidak ada pesanan yang dipilih</h3>
                    <p>Silakan pilih pesanan dari daftar di sebelah kiri</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'pages/layouts/footer.php'; ?> 