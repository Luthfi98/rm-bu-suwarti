<?php
require_once __DIR__ . '/../../config/database.php';

/**
 * Get all orders with their details
 * @return array Array of orders
 */
function getAllOrders() {
    global $conn;
    try {
        $query = "SELECT o.*, c.name as customer_name, c.phone as customer_phone 
                 FROM orders o 
                 LEFT JOIN customers c ON o.customer_id = c.id 
                 ORDER BY o.created_at DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
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
        $query = "SELECT o.*, c.name as customer_name, c.phone as customer_phone 
                 FROM orders o 
                 LEFT JOIN customers c ON o.customer_id = c.id 
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
        $query = "SELECT oi.*, m.name as menu_name, m.price as menu_price 
                 FROM order_items oi 
                 LEFT JOIN menu m ON oi.menu_id = m.id 
                 WHERE oi.order_id = :order_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error getting order items: " . $e->getMessage());
        return [];
    }
}
?> 