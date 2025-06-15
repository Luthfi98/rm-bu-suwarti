<?php
require_once 'pages/layouts/header.php';

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

<style>
    .profile-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2rem;
        margin: 2rem auto;
        width: 90%;
        background-color: #f5f5f5;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .profile-info h1 {
        margin-bottom: 1rem;
    }

    .profile-info p {
        margin-bottom: 2rem;
    }

    .profile-info a {
        background-color: #4CAF50;
        color: white;
        padding: 1rem 2rem;
        border-radius: 5px;
        text-decoration: none;
    }

    @media (min-width: 768px) {
        .profile-container {
            flex-direction: row;
            justify-content: space-between;
        }

        .profile-info {
            align-items: flex-start;
            text-align: left;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-info">
        <h1>Profile</h1>
        <p>Nama: <?= $user['username'] ?></p>
        <p>Email: <?= $user['email'] ?></p>
        <a href="edit-profile.php">Edit Profile</a>
    </div>
</div>

<?php require_once 'pages/layouts/footer.php'; ?>

