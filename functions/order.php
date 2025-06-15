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
        // var_dump($orderData);die;
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
 * @param int $offset Offset for pagination
 * @param int $limit Limit for pagination
 * @return array Array of orders
 */
function getAllOrders($search = null, $status = null, $startDate = null, $endDate = null, $offset = 0, $limit = 10) {
    global $conn;
    try {
        $query = "SELECT o.*, u.username as customer_name
                 FROM orders o
                 JOIN users u ON o.user_id = u.id
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

        $query .= " ORDER BY o.created_at DESC LIMIT $limit OFFSET $offset";
        $params[] = $limit;
        $params[] = $offset;
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        // var_dump($e->getMessage());die;
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
         $query = "SELECT o.*, u.username
                     FROM orders o 
                     LEFT JOIN users u ON o.user_id = u.id
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

function getTotalOrders($search = null, $status = null, $startDate = null, $endDate = null) {
    global $conn;
    
    $sql = "SELECT COUNT(*) as total FROM orders o JOIN users u ON o.user_id = u.id WHERE 1=1";
    $params = array();
    
    if ($search) {
        $sql .= " AND order_number LIKE ?";
        $params[] = "%$search%";
    }
    
    if ($status) {
        $sql .= " AND status = ?";
        $params[] = $status;
    }
    
    if ($startDate) {
        $sql .= " AND DATE(created_at) >= ?";
        $params[] = $startDate;
    }
    
    if ($endDate) {
        $sql .= " AND DATE(created_at) <= ?";
        $params[] = $endDate;
    }
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->execute($params);
    } else {
        $stmt->execute();
    }
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}
?> 