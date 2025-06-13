
<div class="auth-container">
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <p style="text-align: center; font-size: 14px; margin-bottom: 20px; color: #666">Belum punya akun? <a href="register.php" style="color: #f06a11; text-decoration: none">Daftar disini</a></p>
        <button type="submit" class="btn">Login</button>
    </form>
</div>