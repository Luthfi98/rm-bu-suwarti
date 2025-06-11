<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Get all menu items with optional filtering
 * 
 * @param string|null $search Search term for menu items
 * @param string|null $category Filter by category (food/drink)
 * @return array Array of menu items
 */
function getMenuItems($search = null, $category = null) {
    global $conn;
    
    try {
        $sql = "SELECT * FROM menu_items WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (name LIKE ? OR description LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY category, name";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching menu items: " . $e->getMessage());
        return [];
    }
}

/**
 * Get a single menu item by ID
 * 
 * @param int $id Menu item ID
 * @return array|null Menu item data or null if not found
 */
function getMenuItem($id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching menu item: " . $e->getMessage());
        return null;
    }
}

/**
 * Add a new menu item
 * 
 * @param array $data Menu item data (name, description, price, category, image)
 * @return bool|int Returns new item ID on success, false on failure
 */
function addMenuItem($data) {
    global $conn;
    
    try {
        $sql = "INSERT INTO menu_items (name, description, price, category, image) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['category'],
            $data['image']
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error adding menu item: " . $e->getMessage());
        return false;
    }
}

/**
 * Update an existing menu item
 * 
 * @param int $id Menu item ID
 * @param array $data Updated menu item data
 * @return bool Success status
 */
function updateMenuItem($id, $data) {
    global $conn;
    
    try {
        $sql = "UPDATE menu_items 
                SET name = ?, description = ?, price = ?, category = ?, image = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['category'],
            $data['image'],
            $id
        ]);
    } catch (PDOException $e) {
        error_log("Error updating menu item: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete a menu item
 * 
 * @param int $id Menu item ID
 * @return bool Success status
 */
function deleteMenuItem($id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Error deleting menu item: " . $e->getMessage());
        return false;
    }
} 