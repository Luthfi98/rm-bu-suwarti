// Toggle class active untuk hamburger menu
const navbarNav = document.querySelector(".navbar-nav");
// ketika hamburger menu di klik
document.querySelector("#hamburger-menu").onclick = () => {
  navbarNav.classList.toggle("active");
};
//hilangin side bar menu diklik dimana aja
const hamburger = document.querySelector("#hamburger-menu");

document.addEventListener("click", function (e) {
  if (!hamburger.contains(e.target) && !navbarNav.contains(e.target)) {
    navbarNav.classList.remove("active");
  }
});

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM fully loaded');
  
  // Modal functionality
  const modal = document.getElementById('detailModal');
  const closeBtn = document.querySelector('.close');
  const cartFloating = document.getElementById('cartFloating');
  const shoppingCartButton = document.getElementById('shopping-cart-button');
  
  let cart = [];
  let currentModalItemId = null;

  // Add cart badge to shopping cart button
  const cartBadge = document.createElement('span');
  cartBadge.className = 'cart-badge';
  cartBadge.textContent = '0';
  shoppingCartButton.appendChild(cartBadge);

  // Initialize cart display on page load
  if (USER_ROLE == 'customer') {
      loadCart();
      // return;
    }

  // Toggle cart expansion
  cartFloating.addEventListener('click', function(e) {
    // Don't toggle if clicking on cart items or their actions
    if (e.target.closest('.cart-item-actions')) {
      return;
    }
    
    // Don't toggle if clicking on checkout button
    if (e.target.closest('.btn-checkout')) {
      return;
    }

    cartFloating.classList.toggle('expanded');
  });

  // Toggle cart when clicking shopping cart button
  shoppingCartButton.addEventListener('click', function(e) {
    e.preventDefault();
    e.stopPropagation(); // Prevent event from bubbling up
    cartFloating.classList.toggle('expanded');
  });

  // Close cart when clicking outside
  document.addEventListener('click', function(e) {
    if (!cartFloating.contains(e.target) && !shoppingCartButton.contains(e.target)) {
      // cartFloating.classList.remove('expanded');
    }
  });

  // Function to load cart from database
  async function loadCart() {
    try {
      const response = await fetch('functions/cart_operations.php');
      const data = await response.json();
      if (data.success) {
        cart = data.cart;
        updateCart();
      }
    } catch (error) {
      console.error('Error loading cart:', error);
    }
  }

  // Function to show modal with item details
  window.showDetail = function(itemId) {
    currentModalItemId = itemId;
    // Get item details from the menu card
    const menuCard = document.querySelector(`[data-id="${itemId}"]`);
    const image = menuCard.querySelector('.menu-card-img').src;
    const title = menuCard.querySelector('.menu-card-title').textContent;
    const description = menuCard.querySelector('.menu-card-desc').textContent;
    const price = menuCard.querySelector('.menu-card-price').textContent;

    // Update modal content
    document.getElementById('modalImage').src = image;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalDescription').textContent = description;
    document.getElementById('modalPrice').textContent = price;

    // Show modal with animation
    modal.style.display = 'block';
    // Trigger reflow
    modal.offsetHeight;
    modal.classList.add('show');
  }

  // Function to close modal with animation
  function closeModal() {
    modal.classList.remove('show');
    setTimeout(() => {
      modal.style.display = 'none';
    }, 300);
  }

  // Function to add item to cart
  window.addToCart = async function(itemId) {
    if (!USER_ID) {
      alert('Harap login terlebih dahulu');
      window.location.href = 'login.php';
      return;
    }

    const menuCard = document.querySelector(`[data-id="${itemId}"]`);
    const item = {
      id: itemId,
      name: menuCard.querySelector('.menu-card-title').textContent,
      price: parseInt(menuCard.querySelector('.menu-card-price').textContent.replace(/[^0-9]/g, '')),
      quantity: 1
    };

    // Create flying cart animation
    const flyingItem = document.createElement('div');
    flyingItem.className = 'flying-cart-item';
    flyingItem.textContent = '+1';
    
    // Get positions
    const buttonRect = menuCard.querySelector('.btn-cart').getBoundingClientRect();
    const cartButtonRect = document.getElementById('shopping-cart-button').getBoundingClientRect();
    
    // Set initial position
    flyingItem.style.left = `${buttonRect.left + buttonRect.width/2}px`;
    flyingItem.style.top = `${buttonRect.top + buttonRect.height/2}px`;
    
    // Add to document
    document.body.appendChild(flyingItem);
    
    // Animate to cart
    flyingItem.style.transform = `translate(${cartButtonRect.left - buttonRect.left}px, ${cartButtonRect.top - buttonRect.top}px)`;
    
    // Remove element after animation
    setTimeout(() => {
      flyingItem.remove();
    }, 800);

    try {
      const response = await fetch('functions/cart_operations.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          menuItemId: itemId,
          quantity: 1
        })
      });

      const data = await response.json();
      if (data.success) {
        await loadCart(); // Reload cart after adding item
      } else {
        alert('Gagal menambahkan item ke keranjang');
      }
    } catch (error) {
      console.error('Error adding to cart:', error);
      alert('Terjadi kesalahan saat menambahkan ke keranjang');
    }
  }

  // Function to add item to cart from modal
  window.addToCartFromModal = function() {
    if (currentModalItemId) {
      addToCart(currentModalItemId);
      closeModal();
    }
  }

  // Function to update cart display
  function updateCart() {
    const cartItems = document.querySelector('.cart-items');
    cartItems.innerHTML = '';
    let total = 0;
    let totalItems = 0;

    cart.forEach(item => {
      const itemTotal = item.price * item.quantity;
      total += itemTotal;
      totalItems += item.quantity;

      const cartItem = document.createElement('div');
      cartItem.className = 'cart-item';
      cartItem.innerHTML = `
        <div class="cart-item-info">
          <h4>${item.name}</h4>
          <p>Rp ${item.price.toLocaleString('id-ID')} x ${item.quantity}</p>
        </div>
        <div class="cart-item-actions">
          <button onclick="updateQuantity(${item.menu_item_id}, ${item.quantity - 1})">-</button>
          <input type="number" value="${item.quantity}" min="1" 
                 onchange="updateQuantityFromInput(${item.menu_item_id}, this.value)"
                 onkeyup="if(event.key === 'Enter') updateQuantityFromInput(${item.menu_item_id}, this.value)">
          <button onclick="updateQuantity(${item.menu_item_id}, ${item.quantity + 1})">+</button>
          <button onclick="removeFromCart(${item.menu_item_id})">×</button>
        </div>
      `;
      cartItems.appendChild(cartItem);
    });

    document.getElementById('cartTotal').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.querySelector('.cart-count').textContent = totalItems;
    cartBadge.textContent = totalItems;
  }

  // Function to update quantity from input
  window.updateQuantityFromInput = function(itemId, newValue) {
    const quantity = parseInt(newValue);
    if (isNaN(quantity) || quantity < 1) {
      updateQuantity(itemId, 1);
    } else {
      updateQuantity(itemId, quantity);
    }
  }

  // Function to update item quantity
  window.updateQuantity = async function(itemId, newQuantity) {
    if (!USER_ID) {
      alert('Harap login terlebih dahulu');
      window.location.href = 'login.php';
      return;
    }
    
    if (newQuantity < 1) {
      removeFromCart(itemId);
      return;
    }

    try {
      const response = await fetch('functions/cart_operations.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          menuItemId: itemId,
          quantity: newQuantity
        })
      });

      const data = await response.json();
      if (data.success) {
        await loadCart(); // Reload cart after updating quantity
      } else {
        alert('Gagal mengubah jumlah item');
      }
    } catch (error) {
      console.error('Error updating quantity:', error);
      alert('Terjadi kesalahan saat mengubah jumlah item');
    }
  }

  // Function to remove item from cart
  window.removeFromCart = async function(itemId) {
    try {
      const response = await fetch('functions/cart_operations.php', {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          menuItemId: itemId
        })
      });

      const data = await response.json();
      if (data.success) {
        await loadCart(); // Reload cart after removing item
      } else {
        alert('Gagal menghapus item dari keranjang');
      }
    } catch (error) {
      console.error('Error removing from cart:', error);
      alert('Terjadi kesalahan saat menghapus item dari keranjang');
    }
  }

  // Function to process order
  window.processOrder = function() {
    if (cart.length === 0) {
      alert('Keranjang masih kosong!');
      return;
    }

    // Show loading state
    const processButton = document.querySelector('.btn-process');
    const originalText = processButton.textContent;
    processButton.textContent = 'Memproses...';
    processButton.disabled = true;

    // Prepare data for server
    const orderData = {
      cart: JSON.stringify(cart)
    };

    // Send order to server
    fetch('process_order.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams(orderData)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Clear cart after successful order
        cart = [];
        updateCart();
        const orderIds = JSON.parse(localStorage.getItem('orderIds') || '[]');
        orderIds.push(data.orderNumber);
        localStorage.setItem('orderIds', JSON.stringify(orderIds));
        // Redirect to order detail page
        window.location.href = data.redirectUrl;
      } else {
        alert('Gagal membuat pesanan: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Terjadi kesalahan saat memproses pesanan');
    })
    .finally(() => {
      // Reset button state
      processButton.textContent = originalText;
      processButton.disabled = false;
    });
  }

  // Close modal when clicking the close button
  if (closeBtn) {
    closeBtn.onclick = function() {
      closeModal();
    }
  } else {
    console.error('Error: closeBtn is null');
  }

  // Close modal when clicking outside
  window.onclick = function(event) {
    if (event.target == modal) {
      closeModal();
    }
  }
});
