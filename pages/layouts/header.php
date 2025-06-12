<?php
require_once 'config/database.php';
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$baseUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
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
    <link rel="stylesheet" href="<?php echo $baseUrl ?>assets/css/style.css" />
    <link rel="stylesheet" href="<?php echo $baseUrl ?>assets/css/auth.css" />
    <?php if (!$userId): ?>
    <link rel="stylesheet" href="<?php echo $baseUrl ?>assets/css/modal.css" />
    <?php endif; ?>

    <script>
      var BASEURL = '<?php echo $baseUrl; ?>';
    </script>
<script>
  // Pass user information to JavaScript
  const USER_ID = '<?php echo $userId; ?>';
</script>
  </head>
  <body>
    <div class="background-image"></div>
    <!-- Navbar start -->
    <nav class="navbar">
      <a href="<?php echo $baseUrl ?>" class="navbar-logo">Ayam Goreng <span>Bu Suwarti</span></a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <div class="navbar-nav">
          <a href="<?php echo $baseUrl ?>data-menu.php">Menu</a>
          <a href="<?php echo $baseUrl ?>data-order.php">Order</a>
          <!-- <a href="<?php echo $baseUrl ?>index.php?page=adminUser">User</a> -->
          <a href="<?php echo $baseUrl ?>logout.php">Logout</a>
        </div>
        <div class="navbar-extra">
          <a href="javascript:;" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
      <?php else: ?>
        <div class="navbar-nav">
          <a href="<?php echo $baseUrl ?>">Home</a>
          <a href="<?php echo $baseUrl ?>#about">Tentang Kami</a>
          <a href="<?php echo $baseUrl ?>#menu">Menu</a>
          <a href="<?php echo $baseUrl ?>#contact">Kontak</a>
        </div>
        <div class="navbar-extra">
          <a href="javascript:;" id="search-button"><i data-feather="search"></i></a>
          <a href="javascript:;" id="shopping-cart-button"><i data-feather="shopping-cart"></i></a>
          <a href="javascript:;" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
      <?php endif; ?>

    </nav>
    <!-- Navbar end --> 