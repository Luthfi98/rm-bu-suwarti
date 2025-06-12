<?php 
require_once 'pages/layouts/header.php';
require_once 'functions/menu.php';

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars(trim($_POST['description'] ?? ''), ENT_QUOTES, 'UTF-8');
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_INT);
    $category = htmlspecialchars(trim($_POST['category'] ?? ''), ENT_QUOTES, 'UTF-8');

    // Validate required fields
    if (empty($name) || empty($description) || !$price || empty($category)) {
        $error = "Semua field harus diisi";
    } else {
        // Handle image upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '<?php echo $baseUrl ?>assets/img/menu/';
            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                $error = "Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.";
            } else {
                // Generate unique filename
                $image = uniqid() . '.' . $fileExtension;
                $uploadFile = $uploadDir . $image;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    // Image uploaded successfully
                } else {
                    $error = "Gagal mengupload gambar";
                }
            }
        } else {
            $error = "Gambar harus diupload";
        }

        if (!$error) {
            // Prepare data for saving
            $menuData = [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category' => $category,
                'image' => $image
            ];

            // Save menu item
            $result = addMenuItem($menuData);
            if ($result) {
            echo '<script>alert("Menu '.$name.' berhasil ditambahkan!"); window.location.href="data-menu.php";</script>';
            } else {
                $error = "Gagal menambahkan menu";
            }
        }
    }
}
?>

<?php require_once 'pages/admin/menu/add.php'; ?>


<?php require_once 'pages/layouts/footer.php'; ?> 