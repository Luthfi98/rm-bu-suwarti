<?php 
require_once 'pages/layouts/header.php';
require_once 'config/database.php';

// Function to get menu_items item by ID
function getMenuItem($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM menu_items WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return $e->getMessage();
    }
}

// Function to update menu_items item
function updateMenuItem($id, $name, $description, $price, $category, $image = null) {
    global $conn;
    try {
        if ($image) {
            // If new image is uploaded
            $stmt = $conn->prepare("UPDATE menu_items SET name = :name, description = :description, 
                                  price = :price, category = :category, image = :image 
                                  WHERE id = :id");
            $stmt->bindParam(':image', $image);
        } else {
            // If no new image
            $stmt = $conn->prepare("UPDATE menu_items SET name = :name, description = :description, 
                                  price = :price, category = :category 
                                  WHERE id = :id");
        }
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category', $category);
        
        return $stmt->execute();
    } catch(PDOException $e) {
        return false;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_GET['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category = $_POST['category'] ?? '';
    
    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'img/menu/';
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $image = $fileName;
        }
    }
    
    if (updateMenuItem($id, $name, $description, $price, $category, $image)) {
        echo '<script>alert("Menu '.$name.' berhasil diupdate!"); window.location.href="data-menu.php";</script>';
        exit;
    } else {
        $error = "Gagal memperbarui menu";
    }
}

// Get menu_items item data
$id = $_GET['id'] ?? null;
$item = getMenuItem($id);
// var_dump($item);die;

if (!$item) {
    header('Location: data-menu.php');
    exit;
}

require_once 'pages/admin/menu/edit.php'; 
require_once 'pages/layouts/footer.php'; 
?> 