<?php 
require_once 'pages/layouts/header.php'; 
?>
<?php
$sql = "SELECT * FROM menu_items ORDER BY category, name";
$stmt = $conn->prepare($sql);
$stmt->execute();
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <style>
      .menu .row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
      }

      /* Medium screens */
      @media screen and (max-width: 992px) {
        .menu .row {
          grid-template-columns: repeat(2, 1fr);
          gap: 15px;
          padding: 15px;
        }
      }

      /* Small screens */
      @media screen and (max-width: 576px) {
        .menu .row {
          grid-template-columns: 1fr;
          gap: 15px;
          padding: 10px;
        }
      }

      .menu-card {
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
      }

      .menu-card:hover {
        transform: translateY(-5px);
      }

      .menu-card-inner {
        height: 100%;
        display: flex;
        flex-direction: column;
        background-color: #ffffff;
        border-radius: 8px;
        overflow: hidden;
      }

      .menu-card-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
      }

      .menu-card-content {
        flex: 1;
        padding: 15px;
        background-color: #ffffff;
      }

      .menu-card-title {
        font-size: 1.1rem;
        margin-bottom: 8px;
        color: #333;
      }

      .menu-card-desc {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 10px;
      }

      .menu-card-price {
        font-size: 1.1rem;
        font-weight: bold;
        color: #28a745;
        margin-bottom: 15px;
      }

      .menu-card-actions {
        display: flex;
        gap: 10px;
      }

      .btn-detail, .btn-cart {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.2s ease;
      }

      .btn-detail {
        background-color: #f8f9fa;
        color: #333;
      }

      .btn-cart {
        background-color: #28a745;
        color: white;
      }

      .btn-detail:hover {
        background-color: #e9ecef;
      }

      .btn-cart:hover {
        background-color: #218838;
      }

      /* Bootstrap-like Modal Styles */
      .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        overflow-x: hidden;
        overflow-y: auto;
        outline: 0;
      }

      .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 90%;
        max-width: 500px;
        margin: 1.75rem auto;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 0.3rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        animation: modalFadeIn 0.3s ease-out;
        z-index: 10000;
      }

      @keyframes modalFadeIn {
        from {
          opacity: 0;
          transform: translateY(-50px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .modal-body {
        position: relative;
        flex: 1 1 auto;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }

      .modal-body img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 0.3rem;
      }

      .modal-info {
        padding: 0.5rem;
      }

      .modal-info h3 {
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
        font-weight: 500;
      }

      .modal-info p {
        margin-bottom: 0.5rem;
        color: #6c757d;
      }

      .close {
        position: absolute;
        right: 1rem;
        top: 1rem;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #000;
        opacity: 0.5;
        background: none;
        border: 0;
        padding: 0;
        cursor: pointer;
        z-index: 1;
      }

      .close:hover {
        opacity: 0.75;
      }

      .btn-modal-cart {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
        cursor: pointer;
        width: 100%;
      }

      .btn-modal-cart:hover {
        background-color: #218838;
        border-color: #1e7e34;
      }

      .modal-actions {
        margin-top: 1rem;
      }
    </style>

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
      <img src="<?php echo $baseUrl ?>assets/img/tentangkamiayam.png" alt="Tentang Kami" />
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
        <img src="<?php echo $baseUrl ?>assets/img/menu/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="menu-card-img">
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
    
    <form id="contactForm" onsubmit="submitContact(event)">
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
        <!-- <button class="btn-checkout">Checkout</button> -->
      </div>
      <div class="cart-actions">
      </div>
    </div>
  </div>
</div>

<script>
async function submitContact(event) {
  event.preventDefault();
  
  const form = event.target;
  const formData = new FormData(form);
  
  try {
    const response = await fetch('functions/contact.php', {
      method: 'POST',
      body: formData
    });
    
    const result = await response.json();
    
    if (result.success) {
      alert('Pesan berhasil dikirim!');
      form.reset();
    } else {
      alert('Gagal mengirim pesan: ' + result.message);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat mengirim pesan');
  }
}
</script> 