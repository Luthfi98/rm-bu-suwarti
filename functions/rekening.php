<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Get all rekenings with optional filtering
 * 
 * @param string|null $search Search term for rekenings
 * @return array Array of rekenings
 */
function getRekenings($search = null) {
    global $conn;
    
    try {
        $sql = "SELECT * FROM rekening WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (name LIKE ? OR bank LIKE ? OR number LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY name";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching rekenings: " . $e->getMessage());
        return [];
    }
}

/**
 * Get a single rekening by ID
 * 
 * @param int $id Rekening ID
 * @return array|null Rekening data or null if not found
 */
function getRekening($id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT * FROM rekening WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching rekening: " . $e->getMessage());
        return null;
    }
}

/**
 * Add a new rekening
 * 
 * @param array $data Rekening data (name, bank, number)
 * @return bool|int Returns new item ID on success, false on failure
 */
function addRekening($bank, $number, $name) {
    global $conn;
    try {
        $sql = "INSERT INTO rekening (name, bank, number) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $name,
            $bank,
            $number
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error adding rekening: " . $e->getMessage());
        return false;
    }
}

/**
 * Update an existing rekening
 * 
 * @param int $id Rekening ID
 * @param array $data Updated rekening data
 * @return bool Success status
 */
function updateRekening($id, $bank, $number, $name) {
    global $conn;
    
    try {
        $sql = "UPDATE rekening 
                SET name = ?, bank = ?, number = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $name,
            $bank,
            $number,
            $id
        ]);
    } catch (PDOException $e) {
        error_log("Error updating rekening: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete a rekening
 * 
 * @param int $id Rekening ID
 * @return bool Success status
 */
function deleteRekening($id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("DELETE FROM rekening WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Error deleting rekening: " . $e->getMessage());
        return false;
    }
} 
