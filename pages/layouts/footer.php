  <!-- Footer Start -->
  <style>
    body {
      min-height: 100dvh;
      display: flex;
      flex-direction: column;
    }

    footer {
      margin-top: auto;
      background-color: var(--primary);
      text-align: center;
      padding: 1rem 0 3rem;
    }

    .socials {
      padding: 1rem 0;
    }

    .socials a {
      color: #fff;
      margin: 1rem;
    }

    .socials a:hover,
    .links a:hover {
      color: var(--bg);
    }

    .links {
      margin-bottom: 1.4rem;
    }

    .links a {
      color: #fff;
      padding: 0.7rem 1rem;
    }

    .credit {
      font-size: 1.5rem;
    }

    /* Menu card image standardization */
    /* .menu-card-img {
      width: 200px;
      height: 200px;
      object-fit: cover;
      object-position: center;
      border-radius: 8px;
    } */
  </style>
  <footer>
    <div class="socials">
      <a href="#"><i data-feather="instagram"></i></a>
      <a href="#"><i data-feather="twitter"></i></a>
      <a href="#"><i data-feather="facebook"></i></a>
    </div>

    <div class="links">
      <a href="index.php">Home</a>
      <a href="index.php#about">Tentang Kami</a>
      <a href="index.php#menu">Menu</a>
      <a href="index.php#contact">Kontak</a>
    </div>

    <div class="credit">
      <p>Ayam Goreng Kalasan Bu Suwarti</p>
    </div>
  </footer>
  <!-- Footer End -->

  <!-- feather icons -->
  <script>
    feather.replace();
  </script>


  <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin'): ?>
    
    
    
    
    <!-- Cart Floating -->
    <div id="cartFloating" class="cart-floating" hidden>
      <div class="cart-header">
        <h3>Keranjang</h3>
        <span class="cart-count">0</span>
      </div>
      <div class="cart-content">
        <div class="cart-items" style="top: 10px;">
      <!-- Cart items will be added here dynamically -->
    </div>
        <div class="cart-footer">
          <div class="cart-total">
            Total: <span id="cartTotal">Rp 0</span>
            <button class="btn-process" onclick="processOrder()">Proses</button>
            <button class="btn-checkout">Checkout</button>
          </div>
          <div class="cart-actions">
          </div>
        </div>
      </div>
    </div> 
  <?php endif; ?>

  <!-- My Javascript -->
  <script src="<?php echo $baseUrl ?>assets/js/main.js"></script>
  <script src="<?php echo $baseUrl ?>assets/js/script.js"></script>

  
  
  </body>
</html> 