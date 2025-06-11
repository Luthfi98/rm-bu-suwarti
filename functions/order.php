<?php
require_once __DIR__ . '/../config/database.php';

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
if ($isAjax) {
    if (isset($_GET['status']) && isset($_GET['id'])) {
        updateStatus($_GET['id'], $_GET['status']);
        echo json_encode([
            'success' => true
        ]);
        exit;
    }else{
        $orderData = getOrderItems($_GET['id']);
        echo json_encode([
            'success' => true,
            'order' => $orderData['order'],
            'orderItems' => $orderData['orderItems']
        ]);
        exit;
    }
}

/**
 * Get all orders with their details
 * @param string|null $search Search term for order number
 * @param string|null $status Filter by status
 * @param string|null $startDate Filter by start date
 * @param string|null $endDate Filter by end date
 * @return array Array of orders
 */
function getAllOrders($search = null, $status = null, $startDate = null, $endDate = null) {
    global $conn;
    try {
        $query = "SELECT o.* 
                 FROM orders o
                 WHERE 1=1";
        $params = [];

        if ($search) {
            $query .= " AND o.order_number LIKE ?";
            $params[] = "%$search%";
        }

        if ($status) {
            $query .= " AND o.status = ?";
            $params[] = $status;
        }

        if ($startDate) {
            $query .= " AND DATE(o.created_at) >= ?";
            $params[] = $startDate;
        }

        if ($endDate) {
            $query .= " AND DATE(o.created_at) <= ?";
            $params[] = $endDate;
        }

        $query .= " ORDER BY o.created_at DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error getting orders: " . $e->getMessage());
        return [];
    }
}

/**
 * Get order details by ID
 * @param int $orderId Order ID
 * @return array|null Order details or null if not found
 */
function getOrderById($orderId) {
    global $conn;
    try {
        $query = "SELECT o.*
                 FROM orders o 
                 WHERE o.id = :order_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error getting order: " . $e->getMessage());
        return null;
    }
}

/**
 * Update order status
 * @param int $orderId Order ID
 * @param string $status New status
 * @return bool True if successful, false otherwise
 */
function updateOrderStatus($orderId, $status) {
    global $conn;
    try {
        $query = "UPDATE orders SET status = :status, updated_at = NOW() WHERE id = :order_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        return $stmt->execute();
    } catch(PDOException $e) {
        error_log("Error updating order status: " . $e->getMessage());
        return false;
    }
}

/**
 * Get order items by order ID
 * @param int $orderId Order ID
 * @return array Array of order items
 */
function getOrderItems($orderId) {
    global $conn;
    try {
         $query = "SELECT o.*
                     FROM orders o 
                     WHERE o.id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                throw new Exception('Order not found');
            }

            // Get order items
            $query = "SELECT oi.*, m.name, m.price 
                     FROM order_items oi 
                     LEFT JOIN menu_items m ON oi.menu_item_id = m.id 
                     WHERE oi.order_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$orderId]);
            $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'order' => $order,
                'orderItems' => $orderItems
            ];
    } catch(PDOException $e) {
        error_log("Error getting order items: " . $e->getMessage());
        return [];
    }
}


function updateStatus($orderId, $status) {
    global $conn;
        try {
            $stmt = $conn->prepare("
                UPDATE orders 
                SET status = ?
                WHERE id = ?
            ");
            return $stmt->execute([$status, $orderId]);
        } catch(PDOException $e) {
            return false;
        }
    }
?> 