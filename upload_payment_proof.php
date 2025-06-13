<?php
require_once 'config/database.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

if (!isset($_FILES['payment_proof']) || !isset($_POST['order_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$orderId = $_POST['order_id'];
$file = $_FILES['payment_proof'];

// Validate file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, JPEG, and PNG are allowed']);
    exit();
}

// Validate file size (max 5MB)
if ($file['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'File size too large. Maximum size is 5MB']);
    exit();
}

// Create uploads directory if it doesn't exist
$uploadDir = 'uploads/payment_proofs/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('payment_') . '.' . $extension;
$filepath = $uploadDir . $filename;

try {
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Update orders table with payment proof path
        $stmt = $conn->prepare("UPDATE orders SET payment_proof = ?, status = 'waiting confirmation' WHERE id = ?");
        $stmt->execute([$filepath, $orderId]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload',
            'file_path' => $filepath
        ]);
    } else {
        throw new Exception('Failed to move uploaded file');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal mengupload bukti pembayaran: ' . $e->getMessage()
    ]);
} 