<?php require_once 'pages/layouts/header.php';
require_once 'functions/order.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 ?>


<?php require_once 'pages/admin/order/data.php'; ?> 
<?php require_once 'pages/layouts/footer.php'; ?> 
