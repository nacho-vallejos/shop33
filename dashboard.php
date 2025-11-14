<?php
require_once __DIR__ . '/bootstrap/init.php';
require_once __DIR__ . '/middlewares/AuthGuard.php';
require_auth();
$csrf_token = generate_csrf();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin Dashboard — SHOP33 Skate Store</title>
    <link rel="stylesheet" href="./styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;600;800&display=swap"
      rel="stylesheet"
    />
    <style>
      .admin-header {
        background: var(--bg-panel);
        border-bottom: 1px solid var(--border-color);
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
      }

      .admin-title {
        font-family: var(--font-heading);
        font-size: 32px;
        letter-spacing: 2px;
        margin: 0;
      }

      .admin-actions {
        display: flex;
        gap: 15px;
        align-items: center;
      }

      .btn-logout {
        padding: 10px 20px;
        background: transparent;
        border: 2px solid var(--accent-red);
        color: var(--accent-red);
        font-family: var(--font-heading);
        font-size: 14px;
        letter-spacing: 1px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .btn-logout:hover {
        background: var(--accent-red);
        color: white;
      }

      .admin-main {
        max-width: 1600px;
        margin: 0 auto;
        padding: 30px 20px;
      }

      .admin-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
      }

      .admin-panel {
        background: var(--bg-panel);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 30px;
      }

      .panel-title {
        font-family: var(--font-heading);
        font-size: 24px;
        letter-spacing: 2px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--accent-red);
      }

      .form-group {
        margin-bottom: 20px;
      }

      .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 8px;
        font-size: 13px;
        letter-spacing: 1px;
        text-transform: uppercase;
      }

      .form-input,
      .form-select,
      .form-textarea {
        width: 100%;
        padding: 12px;
        background: var(--bg-dark);
        border: 1px solid var(--border-color);
        border-radius: 6px;
        color: var(--text-primary);
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
      }

      .form-input:focus,
      .form-select:focus,
      .form-textarea:focus {
        outline: none;
        border-color: var(--accent-red);
        box-shadow: 0 0 0 3px rgba(255, 0, 68, 0.1);
      }

      .form-textarea {
        resize: vertical;
        min-height: 80px;
      }

      .form-file {
        width: 100%;
        padding: 10px;
        background: var(--bg-dark);
        border: 2px dashed var(--border-color);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .form-file:hover {
        border-color: var(--accent-red);
      }

      .btn-submit {
        width: 100%;
        padding: 15px;
        background: var(--accent-red);
        color: white;
        border: none;
        border-radius: 8px;
        font-family: var(--font-heading);
        font-size: 16px;
        letter-spacing: 2px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
      }

      .btn-submit:hover:not(:disabled) {
        background: var(--accent-orange);
        transform: translateY(-2px);
      }

      .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }

      .products-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-height: 600px;
        overflow-y: auto;
      }

      .product-item {
        background: var(--bg-dark);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        transition: all 0.3s ease;
      }

      .product-item:hover {
        border-color: var(--accent-red);
      }

      .product-info {
        flex: 1;
      }

      .product-info strong {
        font-size: 16px;
        color: var(--text-primary);
        display: block;
        margin-bottom: 5px;
      }

      .product-info small {
        color: var(--text-secondary);
        display: block;
        line-height: 1.6;
      }

      .product-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
      }

      .btn-edit,
      .btn-delete {
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
      }

      .btn-edit {
        background: rgba(0, 255, 213, 0.1);
        color: var(--accent-neon);
        border: 1px solid var(--accent-neon);
      }

      .btn-edit:hover {
        background: var(--accent-neon);
        color: var(--bg-dark);
      }

      .btn-delete {
        background: rgba(255, 0, 68, 0.1);
        color: var(--accent-red);
        border: 1px solid var(--accent-red);
      }

      .btn-delete:hover {
        background: var(--accent-red);
        color: white;
      }

      .notification {
        position: fixed;
        top: 30px;
        right: 30px;
        background: var(--accent-red);
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        z-index: 3000;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
      }

      .notification.show {
        opacity: 1;
        transform: translateY(0);
      }

      .notification.success {
        background: #4ade80;
      }

      .notification.error {
        background: var(--accent-red);
      }

      @media (max-width: 1024px) {
        .admin-grid {
          grid-template-columns: 1fr;
        }
      }

      /* Confirm Modal */
      .confirm-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
      }

      .confirm-modal.show {
        opacity: 1;
        visibility: visible;
      }

      .confirm-container {
        background: var(--bg-panel);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 40px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        transform: scale(0.9);
        transition: transform 0.3s ease;
      }

      .confirm-modal.show .confirm-container {
        transform: scale(1);
      }

      .confirm-icon {
        text-align: center;
        font-size: 64px;
        margin-bottom: 20px;
      }

      .confirm-title {
        font-family: var(--font-heading);
        font-size: 28px;
        letter-spacing: 2px;
        text-align: center;
        color: var(--accent-red);
        margin-bottom: 15px;
      }

      .confirm-message {
        text-align: center;
        color: var(--text-secondary);
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 30px;
      }

      .confirm-product-name {
        color: var(--text-primary);
        font-weight: 700;
        display: block;
        margin-top: 10px;
      }

      .confirm-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
      }

      .btn-confirm,
      .btn-cancel {
        padding: 12px 30px;
        font-family: var(--font-heading);
        font-size: 14px;
        letter-spacing: 1px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        min-width: 140px;
      }

      .btn-confirm {
        background: var(--accent-red);
        color: white;
      }

      .btn-confirm:hover {
        background: #cc0036;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 0, 68, 0.4);
      }

      .btn-cancel {
        background: transparent;
        color: var(--text-primary);
        border: 2px solid var(--border-color);
      }

      .btn-cancel:hover {
        border-color: var(--text-primary);
        background: rgba(255, 255, 255, 0.05);
      }
    </style>
  </head>
  <body>
    <!-- Header -->
    <header class="admin-header">
      <h1 class="admin-title">PANEL DE ADMINISTRACIÓN</h1>
      <div class="admin-actions">
        <span id="admin-user" style="color: var(--text-secondary)"></span>
        <button id="logout-btn" class="btn-logout">CERRAR SESIÓN</button>
      </div>
    </header>

    <!-- Main Content -->
    <main class="admin-main">
      <div class="admin-grid">
        <!-- Formulario: Crear producto -->
        <div class="admin-panel">
          <h2 class="panel-title">✏️ SUBIR NUEVA PRENDA</h2>

          <form id="product-form" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            
            <div class="form-group">
              <label class="form-label">Nombre *</label>
              <input
                type="text"
                name="name"
                class="form-input"
                placeholder="Ej: Vans Old Skool"
                required
              />
            </div>

            <div class="form-group">
              <label class="form-label">Marca</label>
              <input
                type="text"
                name="brand"
                class="form-input"
                placeholder="Ej: Vans, Supreme, Thrasher"
              />
            </div>

            <div class="form-group">
              <label class="form-label">Categoría *</label>
              <select name="category" class="form-select" required>
                <option value="">Seleccionar categoría</option>
                <option value="Zapatillas">Zapatillas</option>
                <option value="Remeras">Remeras</option>
                <option value="Pantalones">Pantalones</option>
                <option value="Buzos / Camperas">Buzos / Camperas</option>
                <option value="Mochilas / Accesorios">
                  Mochilas / Accesorios
                </option>
                <option value="Hombre">Hombre</option>
                <option value="Mujer">Mujer</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Precio ($) *</label>
              <input
                type="number"
                name="price"
                class="form-input"
                placeholder="8999"
                min="0"
                step="0.01"
                required
              />
            </div>

            <div class="form-group">
              <label class="form-label">Stock *</label>
              <input
                type="number"
                name="stock"
                class="form-input"
                placeholder="10"
                min="0"
                required
              />
            </div>

            <div class="form-group">
              <label class="form-label">Talles (separados por coma)</label>
              <input
                type="text"
                name="sizes"
                class="form-input"
                placeholder="S, M, L, XL o 38, 39, 40, 41"
              />
            </div>

            <div class="form-group">
              <label class="form-label">Descripción</label>
              <textarea
                name="description"
                class="form-textarea"
                placeholder="Descripción del producto..."
              ></textarea>
            </div>

            <div class="form-group">
              <label class="form-label">Imágenes (máx 6)</label>
              <input
                type="file"
                name="images"
                class="form-file"
                accept="image/*"
                multiple
              />
            </div>

            <button type="submit" class="btn-submit" id="submit-btn">
              GUARDAR PRODUCTO
            </button>
          </form>
        </div>

        <!-- Listado de productos -->
        <div class="admin-panel">
          <h2 class="panel-title">📦 PRODUCTOS CARGADOS</h2>
          <div id="products-list" class="products-list">
            <div
              style="
                text-align: center;
                padding: 40px 20px;
                color: var(--text-secondary);
              "
            >
              Cargando productos...
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Notification -->
    <div id="notification" class="notification"></div>

    <!-- Confirm Modal -->
    <div id="confirm-modal" class="confirm-modal">
      <div class="confirm-container">
        <div class="confirm-icon">⚠️</div>
        <h2 class="confirm-title">CONFIRMAR ELIMINACIÓN</h2>
        <p class="confirm-message" id="confirm-message">
          ¿Estás seguro de que deseas eliminar este producto?
          <span class="confirm-product-name" id="confirm-product-name"></span>
        </p>
        <div class="confirm-buttons">
          <button class="btn-cancel" id="btn-cancel">CANCELAR</button>
          <button class="btn-confirm" id="btn-confirm">ELIMINAR</button>
        </div>
      </div>
    </div>

    <script>
      const API_URL = window.location.origin;
      
      // Elements
      const productForm = document.getElementById("product-form");
      const productsList = document.getElementById("products-list");
      const submitBtn = document.getElementById("submit-btn");
      const logoutBtn = document.getElementById("logout-btn");
      const notification = document.getElementById("notification");

      // Logout
      logoutBtn.addEventListener("click", () => {
        window.location.href = "/logout.php";
      });

      // Show notification
      function showNotification(message, type = "success") {
        notification.textContent = message;
        notification.className = `notification ${type} show`;

        setTimeout(() => {
          notification.classList.remove("show");
        }, 4000);
      }

      // Load products
      async function loadProducts() {
        try {
          const response = await fetch(`${API_URL}/admin/products`);

          if (!response.ok) {
            if (response.status === 401 || response.status === 302) {
              window.location.href = "/login.php";
              return;
            }
            throw new Error("Error al cargar productos");
          }

          const products = await response.json();
          renderProducts(products);
        } catch (error) {
          console.error("Error:", error);
          productsList.innerHTML = `
            <div style="text-align:center; padding: 40px 20px; color: var(--accent-red);">
              Error al cargar productos
            </div>
          `;
        }
      }

      // Render products
      function renderProducts(products) {
        if (products.length === 0) {
          productsList.innerHTML = `
            <div style="text-align:center; padding: 40px 20px; color: var(--text-secondary);">
              No hay productos cargados
            </div>
          `;
          return;
        }

        productsList.innerHTML = products
          .map(
            (p) => `
          <div class="product-item" data-id="${p.id}">
            <div class="product-info">
              <strong>${p.name}</strong>
              <small>
                ${p.brand || "Sin marca"} · ${p.category || "Sin categoría"}<br>
                Precio: $${p.price.toLocaleString("es-AR")} | Stock: ${
              p.stock
            }<br>
                Talles: ${
                  p.sizes && p.sizes.length ? p.sizes.join(", ") : "N/A"
                }
              </small>
            </div>
            <div class="product-actions">
              <button class="btn-delete" onclick="deleteProduct('${
                p.id
              }', '${p.name.replace(/'/g, "\\'")}')">ELIMINAR</button>
            </div>
          </div>
        `
          )
          .join("");
      }

      // Create product
      productForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        submitBtn.disabled = true;
        submitBtn.textContent = "GUARDANDO...";

        try {
          const formData = new FormData(productForm);

          const response = await fetch(`${API_URL}/admin/product`, {
            method: "POST",
            body: formData,
          });

          const data = await response.json();

          if (!response.ok) {
            throw new Error(data.error || "Error al guardar producto");
          }

          showNotification("✓ Producto creado con éxito", "success");
          productForm.reset();
          // Regenerar CSRF token
          const csrf = document.querySelector('input[name="csrf_token"]');
          if (csrf) csrf.value = "<?php echo $csrf_token; ?>";
          loadProducts();
        } catch (error) {
          console.error("Error:", error);
          showNotification("✗ " + error.message, "error");
        } finally {
          submitBtn.disabled = false;
          submitBtn.textContent = "GUARDAR PRODUCTO";
        }
      });

      // Delete product
      window.deleteProduct = async function (id, name) {
        showConfirmModal(name, async () => {
          try {
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            formData.append('csrf_token', '<?php echo $csrf_token; ?>');
            
            const response = await fetch(`${API_URL}/admin/product/${id}`, {
              method: "POST",
              body: formData
            });

            if (!response.ok) {
              throw new Error("Error al eliminar producto");
            }

            showNotification("✓ Producto eliminado", "success");
            loadProducts();
          } catch (error) {
            console.error("Error:", error);
            showNotification("✗ " + error.message, "error");
          }
        });
      };

      // Confirm Modal
      const confirmModal = document.getElementById("confirm-modal");
      const confirmProductName = document.getElementById(
        "confirm-product-name"
      );
      const btnConfirm = document.getElementById("btn-confirm");
      const btnCancel = document.getElementById("btn-cancel");
      let confirmCallback = null;

      function showConfirmModal(productName, onConfirm) {
        confirmProductName.textContent = productName;
        confirmCallback = onConfirm;
        confirmModal.classList.add("show");
        document.body.style.overflow = "hidden";
      }

      function hideConfirmModal() {
        confirmModal.classList.remove("show");
        document.body.style.overflow = "";
        confirmCallback = null;
      }

      btnConfirm.addEventListener("click", () => {
        if (confirmCallback) {
          confirmCallback();
        }
        hideConfirmModal();
      });

      btnCancel.addEventListener("click", hideConfirmModal);

      confirmModal.addEventListener("click", (e) => {
        if (e.target === confirmModal) {
          hideConfirmModal();
        }
      });

      // ESC key to close modal
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && confirmModal.classList.contains("show")) {
          hideConfirmModal();
        }
      });

      // Initialize
      loadProducts();
    </script>
  </body>
</html>
