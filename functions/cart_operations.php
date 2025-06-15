<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../config/database.php';

session_start();

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST, GET, and DELETE requests
if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'GET', 'DELETE'])) {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit();
}

// Get user ID from session
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$userId) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'User not authenticated'
    ]);
    exit();
}

// Function to get cart items
function getCartItems($userId) {
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT c.*, m.name, m.price, m.image 
            FROM cart c 
            JOIN menu_items m ON c.menu_item_id = m.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting cart items: " . $e->getMessage());
        return [];
    }
}

// Function to add/update cart item
function updateCartItem($userId, $menuItemId, $quantity) {
    global $conn;
    try {
        // Check if item already exists in cart
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND menu_item_id = ?");
        $stmt->execute([$userId, $menuItemId]);
        $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingItem) {
            // Update quantity
            // $quantity = $existingItem['quantity'] + $quantity;
            
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND menu_item_id = ?");
            return $stmt->execute([$quantity, $userId, $menuItemId]);
        } else {
            // Insert new item
            $stmt = $conn->prepare("INSERT INTO cart (user_id, menu_item_id, quantity) VALUES (?, ?, ?)");
            return $stmt->execute([$userId, $menuItemId, $quantity]);
        }
    } catch (PDOException $e) {
        error_log("Error updating cart item: " . $e->getMessage());
        return false;
    }
}

// Function to remove cart item
function removeCartItem($userId, $menuItemId) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND menu_item_id = ?");
        return $stmt->execute([$userId, $menuItemId]);
    } catch (PDOException $e) {
        error_log("Error removing cart item: " . $e->getMessage());
        return false;
    }
}

// Handle different request methods
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $cartItems = getCartItems($userId);
        echo json_encode([
            'success' => true,
            'cart' => $cartItems
        ]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['menuItemId']) || !isset($data['quantity'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing required parameters'
            ]);
            exit();
        }

        $success = updateCartItem($userId, $data['menuItemId'], $data['quantity']);
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Cart updated successfully' : 'Failed to update cart'
        ]);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['menuItemId'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing menu item ID'
            ]);
            exit();
        }

        $success = removeCartItem($userId, $data['menuItemId']);
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Item removed from cart' : 'Failed to remove item from cart'
        ]);
        break;
} 