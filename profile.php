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

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    // Check if username or email already exists for another user
    $checkQuery = "SELECT id FROM users WHERE (username = :username OR email = :email) AND id != :user_id";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':username', $username);
    $checkStmt->bindParam(':email', $email);
    $checkStmt->bindParam(':user_id', $_SESSION['user_id']);
    $checkStmt->execute();
    $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        $error_message = "Username atau email sudah digunakan oleh pengguna lain.";
    } else {
        $updateQuery = "UPDATE users SET username = :username, email = :email WHERE id = :user_id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':username', $username);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->bindParam(':user_id', $_SESSION['user_id']);
    }
    
    if ($updateStmt->execute()) {

        $success_message = "Profile berhasil diperbarui!";
        // Refresh user data
        $userStmt->execute();
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);
        echo "<script>alert('$success_message'); window.location.href = 'profile.php';</script>";
        exit();
    } else {
        $error_message = "Gagal memperbarui profile!";
    }
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE users SET password = :password WHERE id = :user_id";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindParam(':password', $hashed_password);
            $updateStmt->bindParam(':user_id', $_SESSION['user_id']);
            
            if ($updateStmt->execute()) {
                $success_message = "Password berhasil diperbarui!";
                echo "<script>alert('$success_message'); window.location.href = 'profile.php';</script>";
                exit();
            } else {
                $error_message = "Gagal memperbarui password!";
            }
        } else {
            $error_message = "Password baru tidak cocok!";
        }
    } else {
        $error_message = "Password saat ini tidak valid!";
    }
}
?>

<style>
    .profile-container {
        width: 90%;
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #f5f5f5;
        border-radius: 10px;
        margin-top: 5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: relative;
        z-index: 1;
    }

    body {
        padding-top: 60px;
    }

    .sections-container {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .profile-section {
        background-color: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        position: relative;
        flex: 1;
        min-width: 300px;
    }

    .profile-section h2 {
        color: #333;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #4CAF50;
        padding-bottom: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #555;
    }

    .form-group input {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    .btn {
        background-color: #4CAF50;
        color: white;
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s;
        width: 100%;
    }

    .btn:hover {
        background-color: #45a049;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    @media (max-width: 768px) {
        .sections-container {
            flex-direction: column;
        }
        
        .profile-section {
            min-width: 100%;
        }
    }
</style>

<div class="profile-container">
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <div class="sections-container">
        <div class="profile-section">
            <h2>Informasi Profile</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <button type="submit" name="update_profile" class="btn">Update Profile</button>
            </form>
            <br>

            <a href="success_order.php" class="btn">Pesanan Saya</a>
        </div>

        <div class="profile-section">
            <h2>Ubah Password</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password Baru</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="update_password" class="btn">Ubah Password</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'pages/layouts/footer.php'; ?>

