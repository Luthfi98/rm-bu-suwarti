<?php
require_once 'config/database.php';
session_start();
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'guest';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ayam Goreng Bu Suwarti</title>
    <!-- logo website -->
    <link rel="shortcut icon" href="img/logojadi.png" type="image/x-icon">

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,700;1,700&display=swap"
      rel="stylesheet"
    />
 
    <!-- feather icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- My Style -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/auth.css" />
    <link rel="stylesheet" href="assets/css/modal.css" />
<script>
  // Pass user information to JavaScript
  const USER_ID = '<?php echo $userId; ?>';
</script>
  </head>
  <body>
    <div class="background-image"></div>
    <!-- Navbar start -->
    <nav class="navbar">
      <a href="index.php" class="navbar-logo">Ayam Goreng <span>Bu Suwarti</span></a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <div class="navbar-nav">
          <a href="data-menu.php">Menu</a>
          <a href="data-order.php">Order</a>
          <!-- <a href="index.php?page=adminUser">User</a> -->
          <a href="index.php?page=auth&action=logout">Logout</a>
        </div>
      <?php else: ?>
        <div class="navbar-nav">
          <a href="./">Home</a>
          <a href="./#about">Tentang Kami</a>
          <a href="./#menu">Menu</a>
          <a href="./#contact">Kontak</a>
        </div>
        <div class="navbar-extra">
          <a href="javascript:;" id="search-button"><i data-feather="search"></i></a>
          <a href="javascript:;" id="shopping-cart-button"><i data-feather="shopping-cart"></i></a>
          <a href="javascript:;" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
      <?php endif; ?>

    </nav>
    <!-- Navbar end --> 