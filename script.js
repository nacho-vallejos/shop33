/* ============================================
   SHOP33 - SKATE STORE FRONTEND JS
   API Connection & Interactive Features (PHP Backend)
   ============================================ */

const API_URL = window.location.origin;

// DOM Elements
const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => Array.from(document.querySelectorAll(selector));

// State
let allProducts = [];
let currentFilters = {
  category: "",
  brand: "",
  size: "",
  search: "",
};

/* ============================================
   INITIALIZATION
   ============================================ */
document.addEventListener("DOMContentLoaded", () => {
  initializeApp();
  initializeAdbar();
});

async function initializeApp() {
  updateCartCount();
  setupEventListeners();
  await loadProducts();
  hidePageLoader();
}

function hidePageLoader() {
  const loader = $("#page-loader");
  if (loader) {
    setTimeout(() => {
      loader.classList.add("hidden");
    }, 500);
  }
}

/* ============================================
   ADBAR CAROUSEL
   ============================================ */
function initializeAdbar() {
  const adbar = $("#adbar-animated");
  if (!adbar) return;

  // Duplicar el contenido para crear un loop infinito suave
  const adbarContent = adbar.innerHTML;
  adbar.innerHTML = adbarContent + adbarContent;
}

/* ============================================
   EVENT LISTENERS
   ============================================ */
function setupEventListeners() {
  // Category filters (nav menu)
  $$(".nav-link").forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      // Solo aplicar filtros si tiene data-filter
      if (e.target.dataset.filter !== undefined) {
        $$(".nav-link").forEach((l) => l.classList.remove("active"));
        e.target.classList.add("active");
        currentFilters.category = e.target.dataset.filter || "";
        applyFilters();
      }
    });
  });

  // Dropdown links
  $$(".dropdown-link").forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      $$(".nav-link").forEach((l) => l.classList.remove("active"));
      currentFilters.category = e.target.dataset.filter || "";
      applyFilters();
    });
  });

  // Brand filter
  const brandFilter = $("#brand-filter");
  if (brandFilter) {
    brandFilter.addEventListener("change", (e) => {
      currentFilters.brand = e.target.value;
      applyFilters();
    });
  }

  // Size filter
  const sizeFilter = $("#size-filter");
  if (sizeFilter) {
    sizeFilter.addEventListener("change", (e) => {
      currentFilters.size = e.target.value;
      applyFilters();
    });
  }

  // Search input
  const searchInput = $("#search");
  if (searchInput) {
    let searchTimeout;
    searchInput.addEventListener("input", (e) => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        currentFilters.search = e.target.value.toLowerCase();
        applyFilters();
      }, 300);
    });
  }

  // Modal close
  const modalBackdrop = $("#modal-backdrop");
  const modalClose = $("#modal-close");

  if (modalBackdrop) {
    modalBackdrop.addEventListener("click", closeModal);
  }

  if (modalClose) {
    modalClose.addEventListener("click", closeModal);
  }

  // Cart icon
  const cartIcon = $("#cart-icon");
  if (cartIcon) {
    cartIcon.addEventListener("click", () => {
      openCartModal();
    });
  }

  // Cart modal events
  const cartBackdrop = $("#cart-backdrop");
  const cartClose = $("#cart-close");
  const checkoutBtn = $("#cart-checkout-btn");

  if (cartBackdrop) {
    cartBackdrop.addEventListener("click", closeCartModal);
  }

  if (cartClose) {
    cartClose.addEventListener("click", closeCartModal);
  }

  if (checkoutBtn) {
    checkoutBtn.addEventListener("click", sendWhatsAppOrder);
  }

  // Menu toggle for mobile
  const menuToggle = $("#menu-toggle");
  const navMenu = $("#nav-menu");
  if (menuToggle && navMenu) {
    menuToggle.addEventListener("click", () => {
      navMenu.classList.toggle("show");
    });
  }
}

/* ============================================
   API CALLS
   ============================================ */
async function loadProducts() {
  try {
    showLoader();

    // Cargar desde API backend
    const response = await fetch(`${API_URL}/api/products?limit=100`);

    if (!response.ok) {
      throw new Error("Error al cargar productos");
    }

    const data = await response.json();
    allProducts = Array.isArray(data) ? data : data.items || [];

    populateBrandFilter();
    renderProducts(allProducts);
    hideLoader();
  } catch (error) {
    console.error("Error loading products:", error);
    showError("Error al cargar el catálogo. Por favor, intenta de nuevo.");
    hideLoader();
  }
}

async function loadProductById(id) {
  try {
    // Primero buscar en memoria
    let product = allProducts.find((p) => p.id === id);

    if (!product) {
      // Si no está en memoria, consultar API
      const response = await fetch(`${API_URL}/api/products/${id}`);
      if (!response.ok) {
        throw new Error("Producto no encontrado");
      }
      product = await response.json();
    }

    return product;
  } catch (error) {
    console.error("Error loading product:", error);
    return null;
  }
}

/* ============================================
   FILTERS
   ============================================ */
function applyFilters() {
  let filtered = [...allProducts];

  // Filter by category
  if (currentFilters.category) {
    filtered = filtered.filter(
      (p) =>
        p.category &&
        p.category.toLowerCase() === currentFilters.category.toLowerCase()
    );
  }

  // Filter by brand
  if (currentFilters.brand) {
    filtered = filtered.filter(
      (p) =>
        p.brand &&
        p.brand.toLowerCase().includes(currentFilters.brand.toLowerCase())
    );
  }

  // Filter by size
  if (currentFilters.size) {
    filtered = filtered.filter(
      (p) => p.sizes && p.sizes.includes(currentFilters.size)
    );
  }

  // Filter by search
  if (currentFilters.search) {
    filtered = filtered.filter((p) => {
      const searchText = `${p.name} ${p.brand} ${p.description || ""} ${
        p.category || ""
      }`.toLowerCase();
      return searchText.includes(currentFilters.search);
    });
  }

  renderProducts(filtered);
}

function populateBrandFilter() {
  const brandFilter = $("#brand-filter");
  if (!brandFilter) return;

  const brands = [
    ...new Set(allProducts.map((p) => p.brand).filter(Boolean)),
  ].sort();

  brandFilter.innerHTML =
    '<option value="">Todas las marcas</option>' +
    brands
      .map((brand) => `<option value="${brand}">${brand}</option>`)
      .join("");
}

/* ============================================
   RENDER PRODUCTS
   ============================================ */
function renderProducts(products) {
  const container = $("#products");
  const noResults = $("#no-results");
  const productCount = $("#product-count");

  if (!container) return;

  // Update count
  if (productCount) {
    productCount.textContent = products.length;
  }

  // Show/hide no results message
  if (products.length === 0) {
    container.innerHTML = "";
    if (noResults) noResults.classList.remove("hidden");
    return;
  }

  if (noResults) noResults.classList.add("hidden");

  // Render product cards
  container.innerHTML = products
    .map((product) => createProductCard(product))
    .join("");

  // Add click listeners
  $$(".product-card").forEach((card) => {
    card.addEventListener("click", () => {
      const productId = card.dataset.id;
      openProductModal(productId);
    });
  });
}

function createProductCard(product) {
  const imageUrl =
    product.images && product.images.length
      ? product.images[0]
      : "https://via.placeholder.com/400x300?text=Sin+Imagen";

  const isOutOfStock = product.stock === 0;
  const stockClass = isOutOfStock ? "out-of-stock" : "in-stock";
  const stockText = isOutOfStock ? "SIN STOCK" : `Stock: ${product.stock}`;

  const sizes =
    product.sizes && product.sizes.length
      ? product.sizes
          .slice(0, 4)
          .map((size) => `<span class="size-badge">${size}</span>`)
          .join("")
      : "";

  return `
    <div class="product-card" data-id="${product.id}">
      <div class="product-image-container">
        <img src="${imageUrl}" alt="${product.name}" class="product-image" />
        ${
          isOutOfStock
            ? '<div class="out-of-stock-overlay">SIN STOCK</div>'
            : ""
        }
        ${
          product.brand
            ? `<span class="product-brand-badge">${product.brand}</span>`
            : ""
        }
      </div>
      <div class="product-body">
        <span class="product-category-tag">${
          product.category || "Sin categoría"
        }</span>
        <h3 class="product-name">${product.name}</h3>
        <div class="product-footer">
          <div class="product-price">$${product.price.toLocaleString(
            "es-AR"
          )}</div>
          <div class="product-stock ${stockClass}">${stockText}</div>
        </div>
        ${sizes ? `<div class="product-sizes">${sizes}</div>` : ""}
        <button class="btn-view-details">VER DETALLES</button>
      </div>
    </div>
  `;
}

/* ============================================
   MODAL
   ============================================ */
async function openProductModal(productId) {
  const modal = $("#product-modal");
  const modalBody = $("#modal-body");

  if (!modal || !modalBody) return;

  // Show modal with loader
  modalBody.innerHTML =
    '<div class="loader-container"><div class="skate-loader"><div class="skateboard"></div><p>CARGANDO...</p></div></div>';
  modal.classList.remove("hidden");

  // Load product data
  const product = await loadProductById(productId);

  if (!product) {
    modalBody.innerHTML =
      '<div class="error-message"><h2>😔 Producto no encontrado</h2></div>';
    return;
  }

  // Render product details
  const images =
    product.images && product.images.length
      ? product.images
      : ["https://via.placeholder.com/600x600?text=Sin+Imagen"];

  const mainImage = images[0];
  const thumbnails = images
    .map(
      (img, idx) =>
        `<img src="${img}" alt="${product.name} ${
          idx + 1
        }" class="thumbnail" onclick="changeMainImage('${img}')" />`
    )
    .join("");

  const sizes =
    product.sizes && product.sizes.length
      ? product.sizes
          .map(
            (size) =>
              `<button class="size-option" onclick="selectSize(this, '${size}')">${size}</button>`
          )
          .join("")
      : '<p class="no-sizes">Sin talles disponibles</p>';

  const stockStatus =
    product.stock > 0
      ? `<span class="stock-available">✓ ${product.stock} en stock</span>`
      : `<span class="stock-unavailable">✗ SIN STOCK</span>`;

  modalBody.innerHTML = `
    <div class="product-detail">
      <div class="product-gallery">
        <div class="main-image-container">
          <img id="modal-main-image" src="${mainImage}" alt="${
    product.name
  }" class="main-image" />
          ${
            product.stock === 0
              ? '<div class="out-of-stock-badge">SIN STOCK</div>'
              : ""
          }
        </div>
        <div class="thumbnails-container">
          ${thumbnails}
        </div>
      </div>

      <div class="product-info">
        <div class="product-category">${
          product.category || "Sin categoría"
        }</div>
        <h1 class="product-title">${product.name}</h1>
        <div class="product-brand">${product.brand || "Sin marca"}</div>
        
        <div class="product-price">
          <span class="currency">$</span>
          <span class="amount">${product.price.toLocaleString("es-AR")}</span>
        </div>

        <div class="product-stock">
          ${stockStatus}
        </div>

        <div class="product-description">
          <h3>Descripción</h3>
          <p>${
            product.description || "Producto sin descripción disponible."
          }</p>
        </div>

        <div class="size-selector">
          <h3>Seleccionar talle</h3>
          <div class="sizes-container">
            ${sizes}
          </div>
          <input type="hidden" id="modal-selected-size" value="" />
        </div>

        <button 
          class="btn btn-add-cart" 
          ${product.stock === 0 ? "disabled" : ""}
          onclick="addToCart('${product.id}', '${product.name.replace(
    /'/g,
    "\\'"
  )}', ${product.price}, '${mainImage}', '${product.brand || "Sin marca"}')">
          ${product.stock > 0 ? "🛒 AGREGAR AL CARRITO" : "✗ SIN STOCK"}
        </button>

        <div class="product-details">
          <div class="detail-item">
            <span class="detail-label">Marca:</span>
            <span class="detail-value">${product.brand || "N/A"}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Categoría:</span>
            <span class="detail-value">${product.category || "N/A"}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">ID:</span>
            <span class="detail-value">${product.id}</span>
          </div>
        </div>
      </div>
    </div>
  `;
}

function closeModal() {
  const modal = $("#product-modal");
  if (modal) {
    modal.classList.add("hidden");
  }
}

// Global functions for modal interactions
window.changeMainImage = function (imageSrc) {
  const mainImage = $("#modal-main-image");
  if (mainImage) {
    mainImage.src = imageSrc;
  }
};

window.selectSize = function (element, size) {
  $$(".size-option").forEach((el) => el.classList.remove("selected"));
  element.classList.add("selected");
  const sizeInput = $("#modal-selected-size");
  if (sizeInput) {
    sizeInput.value = size;
  }
};

/* ============================================
   CART FUNCTIONALITY
   ============================================ */
function updateCartCount() {
  const cart = getCart();
  const cartCount = $("#cart-count");
  if (cartCount) {
    const totalItems = cart.reduce(
      (sum, item) => sum + (item.quantity || 1),
      0
    );
    cartCount.textContent = totalItems;
  }
}

function getCart() {
  try {
    return JSON.parse(localStorage.getItem("cart") || "[]");
  } catch {
    return [];
  }
}

function saveCart(cart) {
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
  renderCartItems();
}

window.addToCart = function (
  productId,
  productName,
  productPrice,
  productImage,
  productBrand
) {
  const cart = getCart();
  const selectedSize = $("#modal-selected-size")?.value || "Sin talle";

  // Buscar si ya existe el producto con el mismo talle
  const existingIndex = cart.findIndex(
    (item) => item.id === productId && item.size === selectedSize
  );

  if (existingIndex > -1) {
    // Si existe, aumentar cantidad
    cart[existingIndex].quantity = (cart[existingIndex].quantity || 1) + 1;
  } else {
    // Si no existe, agregar nuevo
    cart.push({
      id: productId,
      name: productName,
      price: productPrice,
      image: productImage,
      brand: productBrand,
      size: selectedSize,
      quantity: 1,
      addedAt: Date.now(),
    });
  }

  saveCart(cart);
  showNotification(`✓ ${productName} agregado al carrito`);
  closeModal();
};

// Abrir modal del carrito
function openCartModal() {
  const cartModal = $("#cart-modal");
  if (cartModal) {
    cartModal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
    renderCartItems();
  }
}

// Cerrar modal del carrito
function closeCartModal() {
  const cartModal = $("#cart-modal");
  if (cartModal) {
    cartModal.classList.add("hidden");
    document.body.style.overflow = "";
  }
}

// Renderizar items del carrito
function renderCartItems() {
  const cart = getCart();
  const cartBody = $("#cart-body");
  const cartTotal = $("#cart-total");
  const checkoutBtn = $("#cart-checkout-btn");

  if (!cartBody) return;

  if (cart.length === 0) {
    cartBody.innerHTML = '<p class="cart-empty">Tu carrito está vacío</p>';
    if (cartTotal) cartTotal.textContent = "$0";
    if (checkoutBtn) checkoutBtn.disabled = true;
    return;
  }

  // Calcular total
  const total = cart.reduce((sum, item) => {
    const price = parseFloat(item.price) || 0;
    const quantity = item.quantity || 1;
    return sum + price * quantity;
  }, 0);

  if (cartTotal) {
    cartTotal.textContent = `$${total.toLocaleString("es-AR")}`;
  }
  if (checkoutBtn) {
    checkoutBtn.disabled = false;
  }

  // Renderizar items
  cartBody.innerHTML = `
    <div class="cart-items">
      ${cart
        .map(
          (item, index) => `
        <div class="cart-item">
          <img src="${item.image || "placeholder.jpg"}" alt="${
            item.name
          }" class="cart-item-image" />
          <div class="cart-item-details">
            <p class="cart-item-name">${item.name}</p>
            <p class="cart-item-brand">${item.brand || "Sin marca"}</p>
            <p class="cart-item-size">Talle: ${item.size}</p>
            <p class="cart-item-price">$${(
              parseFloat(item.price) || 0
            ).toLocaleString("es-AR")}</p>
            <div class="cart-item-actions">
              <div class="cart-item-qty">
                <button class="qty-btn" onclick="decreaseQuantity(${index})">-</button>
                <span class="qty-number">${item.quantity || 1}</span>
                <button class="qty-btn" onclick="increaseQuantity(${index})">+</button>
              </div>
              <button class="cart-item-remove" onclick="removeFromCart(${index})">
                🗑️ Quitar
              </button>
            </div>
          </div>
        </div>
      `
        )
        .join("")}
    </div>
  `;
}

// Aumentar cantidad
window.increaseQuantity = function (index) {
  const cart = getCart();
  if (cart[index]) {
    cart[index].quantity = (cart[index].quantity || 1) + 1;
    saveCart(cart);
  }
};

// Disminuir cantidad
window.decreaseQuantity = function (index) {
  const cart = getCart();
  if (cart[index]) {
    if (cart[index].quantity > 1) {
      cart[index].quantity -= 1;
      saveCart(cart);
    } else {
      removeFromCart(index);
    }
  }
};

// Quitar del carrito
window.removeFromCart = function (index) {
  const cart = getCart();
  if (cart[index]) {
    const itemName = cart[index].name;
    cart.splice(index, 1);
    saveCart(cart);
    showNotification(`✓ ${itemName} eliminado del carrito`);
  }
};

// Enviar pedido por WhatsApp
function sendWhatsAppOrder() {
  const cart = getCart();

  if (cart.length === 0) {
    showNotification("❌ Tu carrito está vacío");
    return;
  }

  // Construir mensaje
  let message = "*🛹 PEDIDO SHOP33*\n\n";

  let total = 0;
  cart.forEach((item, index) => {
    const itemTotal = (parseFloat(item.price) || 0) * (item.quantity || 1);
    total += itemTotal;

    message += `${index + 1}. *${item.name}*\n`;
    message += `   Marca: ${item.brand || "N/A"}\n`;
    message += `   Talle: ${item.size}\n`;
    message += `   Cantidad: ${item.quantity || 1}\n`;
    message += `   Precio: $${(parseFloat(item.price) || 0).toLocaleString(
      "es-AR"
    )}\n`;
    message += `   Subtotal: $${itemTotal.toLocaleString("es-AR")}\n\n`;
  });

  message += `*TOTAL: $${total.toLocaleString("es-AR")}*\n\n`;
  message += "¡Gracias por tu pedido! 🛒";

  // Codificar para URL
  const encodedMessage = encodeURIComponent(message);

  // Número de WhatsApp (reemplazar con el número real)
  const phoneNumber = "5493417214862"; // Formato: código país + número sin +

  // Abrir WhatsApp
  const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
  window.open(whatsappURL, "_blank");

  // Opcional: Vaciar carrito después de enviar
  // if (confirm('¿Vaciar el carrito?')) {
  //   localStorage.removeItem('cart');
  //   saveCart([]);
  //   closeCartModal();
  // }
}

window.clearCart = function () {
  if (confirm("¿Vaciar el carrito?")) {
    localStorage.removeItem("cart");
    updateCartCount();
    renderCartItems();
    showNotification("Carrito vaciado");
  }
};

/* ============================================
   NOTIFICATIONS
   ============================================ */
function showNotification(message, duration = 3000) {
  const notification = document.createElement("div");
  notification.className = "cart-notification";
  notification.textContent = message;
  document.body.appendChild(notification);

  setTimeout(() => notification.classList.add("show"), 100);

  setTimeout(() => {
    notification.classList.remove("show");
    setTimeout(() => notification.remove(), 300);
  }, duration);
}

function showError(message) {
  const container = $("#products");
  if (container) {
    container.innerHTML = `
      <div class="error-message">
        <h2>😔 Error</h2>
        <p>${message}</p>
      </div>
    `;
  }
}

/* ============================================
   LOADERS
   ============================================ */
function showLoader() {
  const container = $("#products");
  if (container) {
    container.innerHTML = `
      <div class="loader-container">
        <div class="skate-loader">
          <div class="skateboard"></div>
          <p>CARGANDO PRODUCTOS...</p>
        </div>
      </div>
    `;
  }
}

function hideLoader() {
  // Loader is replaced by products
}

/* ============================================
   UTILITY FUNCTIONS
   ============================================ */
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Export for use in other scripts if needed
window.SHOP33 = {
  API_URL,
  getCart,
  saveCart,
  updateCartCount,
  showNotification,
  loadProducts,
  loadProductById,
};
