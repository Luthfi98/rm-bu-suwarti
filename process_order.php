<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config/database.php';
// session_start();

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit();
}

// Get cart data from POST request
$cartData = json_decode($_POST['cart'], true);

if (!$cartData) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid cart data'
    ]);
    exit();
}

function generateOrderNumber() {
    // Generate order number format: ORD-YYYYMMDD-XXXX
    // Where XXXX is a random 4-digit number
    $date = date('Ymd');
    $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    return "ORD-{$date}-{$random}";
}

function processOrder($cartData) {
    global $conn;
    try {
        // Start transaction
        $conn->beginTransaction();

        // Generate order number
        $orderNumber = generateOrderNumber();
        $totalAmount = 0;

        // Calculate total amount
        foreach ($cartData as $item) {
            $totalAmount += ($item['price'] * $item['quantity']);
        }

        // Insert into orders table
        $orderSql = "INSERT INTO orders (order_number, total_amount, status, created_at) VALUES (?, ?, 'pending', NOW())";
        $orderStmt = $conn->prepare($orderSql);
        $orderStmt->execute([$orderNumber, $totalAmount]);
        
        // Get the last inserted order ID
        $orderId = $conn->lastInsertId();

        // Insert into order_items table
        $itemSql = "INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)";
        $itemStmt = $conn->prepare($itemSql);

        foreach ($cartData as $item) {
            $itemStmt->execute([
                $orderId,
                $item['id'],
                $item['quantity'],
                $item['price']
            ]);
        }

        // Commit transaction
        $conn->commit();
        $_SESSION['orderIds'][] = $orderNumber;
        return [
            'success' => true,
            'message' => 'Order berhasil dibuat',
            'orderNumber' => $orderNumber,
            'orderId' => $orderId,
            'redirectUrl' => "success_order.php?orderNumber={$orderNumber}"
        ];

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollBack();
        
        return [
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ];
    }
}

// Process the order
$result = processOrder($cartData);

// Send response
if ($result['success']) {
    http_response_code(200);
} else {
    http_response_code(500);
}

echo json_encode($result);
?>
