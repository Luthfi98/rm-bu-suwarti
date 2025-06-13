<?php
require_once 'pages/layouts/header.php';
// require_once 'functions/user.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userQuery = "SELECT * FROM users WHERE id = :user_id";
$userStmt = $conn->prepare($userQuery);
$userStmt->bindParam(':user_id', $_SESSION['user_id']);
$userStmt->execute();
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: index.php");
    exit();
}

?>

<div class="container">
    <h1 class="text-center my-4">Profile</h1>
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Nama</h5>
                    <p class="card-text"><?= $user['username'] ?></p>
                    <h5 class="card-title">Email</h5>
                    <p class="card-text"><?= $user['email'] ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'pages/layouts/footer.php'; ?>
