<?php 
require_once 'pages/layouts/header.php';
require_once 'functions/menu.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle delete request
if (isset($_GET['type']) && $_GET['type'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (deleteMenuItem($id)) {
        echo '<script>alert("Menu berhasil dihapus!"); window.location.href="data-menu.php";</script>';
        exit;
    } else {
        $error = "Gagal menghapus menu";
    }
}

// Get filter parameters
$search = isset($_GET['search']) ? $_GET['search'] : null;
$category = isset($_GET['category']) ? $_GET['category'] : null;

// Get menu items with filters
$menuItems = getMenuItems($search, $category);
?>

<?php require_once 'pages/admin/menu/data.php'; ?> 
<?php require_once 'pages/layouts/footer.php'; ?> 