<?php 
require_once 'pages/layouts/header.php'; 
?>
<?php
$sql = "SELECT * FROM menu_items ORDER BY category, name";
$stmt = $conn->prepare($sql);
$stmt->execute();
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Hero Section Star -->
<section class="hero" id="home">
  <main class="content">
    <h1>Citarasa Melegenda!</h1>
    <p>
      Rasakan kelezatan ayam goreng kalasan dengan kremes yang renyah bikin nagih. Cocok disantap dengan lalapan segar dan sambal pedas menggoda!
    </p>
    <a href="#" class="cta">Beli Sekarang</a>
  </main>
</section>
<!-- Hero Section End -->

<!-- About Section Start -->
<section id="about" class="about">
  <h2>Tentang <span>Kami</span></h2>

  <div class="row">
    <div class="about-img">
      <img src="assets/img/tentangkamiayam.png" alt="Tentang Kami" />
    </div>

    <div class="content">
      <h3>Ayam Goreng Kalasan Bu Suwarti</h3>
      <p>
        Setiap potong ayam kami dimasak dengan penuh cinta menggunakan
        resep turun-temurun khas Kalasan. Gurihnya bumbu yang meresap,
        dipadukan dengan kremesan renyah, sambal pedas, dan lalapan segar menjad
        ikan setiap suapan tak hanya mengenyan
        gkan, tapi juga menghadirkan kenangan rasa rumaha
        n yang otentik dan tak terlupakan.
      </p>

      <p>Bukan sekadar ayam goreng biasa, ini adalah warisan rasa yang dijaga sejak dulu.
         Di sini, kami menyajikan lebih dari sekadar makanan – kami 
         menghadirkan pengalaman kuliner yang membumi,
         jujur, dan selalu bikin rindu. Ayam Goreng Kalasan Bu Suwarti, sekali coba pasti kembali.
         </p>
    </div>
  </div>
</section>
<!-- About Section End -->

<!-- Menu Section start -->
<section id="menu" class="menu">
  <h2><span>Menu</span> Kami</h2>
  <p>Menu andalan di Ayam Goreng Kalasan Bu Suwarti</p>

  <div class="menu-tabs">
    <button class="tab-btn active" data-category="all">Semua</button>
    <button class="tab-btn" data-category="food">Makanan</button>
    <button class="tab-btn" data-category="drink">Minuman</button>
  </div>

  <div class="row"> 
    <?php foreach($menuItems as $item): ?>
    <div class="menu-card" data-category="<?= $item['category'] ?>" data-id="<?= $item['id'] ?>">
      <div class="menu-card-inner">
        <img src="assets/img/menu/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="menu-card-img">
        <div class="menu-card-content">
          <h3 class="menu-card-title">- <?= $item['name'] ?> -</h3>
          <p class="menu-card-desc"><?= $item['description'] ?></p>
          <p class="menu-card-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
          <div class="menu-card-actions">
            <button class="btn-detail" onclick="showDetail(<?= $item['id'] ?>)">Lihat Detail</button>
            <button class="btn-cart" onclick="addToCart(<?= $item['id'] ?>)">+ Keranjang</button>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<!-- Menu Section end -->

<!-- Contact Section Start -->
<section id="contact" class="contact">
  <h2><span>Kontak</span> Kami</h2>
  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse, ratione!</p>

  <div class="row">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.310418030083!2d110.47835547420611!3d-7.756864876919365!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5ba21c6e4e35%3A0x69ea28734920b9b!2sAyam%20Goreng%20Kalasan%20Bu%20Suwarti!5e0!3m2!1sid!2sid!4v1747458160668!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>
    
    <form action="index.php?page=contact&action=submit" method="POST">
      <div class="input-group">
        <i data-feather="user"></i>
        <input type="text" name="name" placeholder="nama" required>
      </div>
      <div class="input-group">
        <i data-feather="mail"></i>
        <input type="email" name="email" placeholder="email" required>
      </div>
      <div class="input-group">
        <i data-feather="phone"></i>
        <input type="text" name="phone" placeholder="no hp" required>
      </div>
      <button type="submit" class="btn">kirim pesan</button>
    </form>
  </div>
</section>
<!-- Contact Section End -->

<?php require_once 'pages/layouts/footer.php'; ?>

<!-- Modal Detail -->
<div id="detailModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div class="modal-body">
      <img id="modalImage" src="" alt="Menu Item">
      <div class="modal-info">
        <h3 id="modalTitle"></h3>
        <p id="modalDescription"></p>
        <p id="modalPrice"></p>
        <div class="modal-actions">
          <button class="btn-modal-cart" onclick="addToCartFromModal()">+ Tambah ke Keranjang</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Cart Floating -->
<div id="cartFloating" class="cart-floating">
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