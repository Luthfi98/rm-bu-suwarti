<?php 
require_once 'pages/layouts/header.php';
// require_once 'functions/contact.php';
if ($role !== 'admin') {
    header("Location: login.php");
    exit();
}


// Handle contact deletion
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    try {
        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Contact berhasil dihapus";
    } catch (PDOException $e) {
        $error_message = "Gagal menghapus contact: " . $e->getMessage();
    }
}

// Get search parameter
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch contacts with optional search
try {
    $sql = "SELECT * FROM contacts WHERE 1=1";
    $params = [];
    
    if ($search) {
        $sql .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $searchTerm = "%$search%";
        $params = [$searchTerm, $searchTerm, $searchTerm];
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
    $contacts = [];
}


?>

<?php require_once 'pages/admin/contact/data.php'; ?> 
<?php require_once 'pages/layouts/footer.php'; ?> 