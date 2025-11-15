<?php
/**
 * SHOP33 - Admin Panel
 * Gestión completa de productos con tabs
 */
require_once __DIR__ . '/../../bootstrap/init.php';
require_once __DIR__ . '/../../middlewares/AuthGuard.php';

// Verificar autenticación
require_auth();

$user = current_user();
$csrf_token = generate_csrf();

// Cargar productos
$products = load_products();
$total_products = count($products);
$categories = array_unique(array_filter(array_column($products, 'category')));
$total_stock = array_sum(array_column($products, 'stock'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - SHOP33</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f7fa;
            color: #333;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 700;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .btn-logout {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 2px solid white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-logout:hover {
            background: white;
            color: #667eea;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }
        
        .stat-card h3 {
            font-size: 13px;
            text-transform: uppercase;
            color: #888;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        
        .stat-card .value {
            font-size: 42px;
            font-weight: 800;
            color: #667eea;
            line-height: 1;
        }
        
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .tab {
            padding: 14px 24px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: #666;
            transition: all 0.3s;
            position: relative;
            top: 2px;
        }
        
        .tab:hover {
            color: #667eea;
        }
        
        .tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        
        .tab-content {
            display: none;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .products-table th,
        .products-table td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .products-table th {
            background: #f8f9fa;
            font-weight: 700;
            color: #555;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .products-table tr:hover {
            background: #f8f9fa;
        }
        
        .products-table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .btn-danger {
            background: #ff4757;
            color: white;
        }
        
        .btn-danger:hover {
            background: #ee2f3f;
        }
        
        .btn-secondary {
            background: #f5f7fa;
            color: #667eea;
            border: 1px solid #e0e0e0;
        }
        
        .btn-secondary:hover {
            background: #e8ecef;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .upload-zone {
            border: 3px dashed #e0e0e0;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #fafafa;
        }
        
        .upload-zone:hover {
            border-color: #667eea;
            background: #f5f7ff;
        }
        
        .upload-zone.drag-over {
            border-color: #667eea;
            background: #e8ecff;
        }
        
        .image-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e0e0e0;
        }
        
        .preview-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }
        
        .preview-item .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 71, 87, 0.9);
            color: white;
            border: none;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
            transition: all 0.3s;
        }
        
        .preview-item .remove-btn:hover {
            background: #ff4757;
            transform: scale(1.1);
        }
        
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .loading {
            text-align: center;
            padding: 60px;
            color: #999;
        }
        
        .loading::after {
            content: '...';
            animation: dots 1.5s steps(4, end) infinite;
        }
        
        @keyframes dots {
            0%, 20% { content: '.'; }
            40% { content: '..'; }
            60%, 100% { content: '...'; }
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }
        
        .empty-state svg {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛹 SHOP33 - Panel Admin</h1>
        <div class="header-right">
            <div class="user-badge">👤 <?= htmlspecialchars($user) ?></div>
            <form method="POST" action="/admin/logout" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <!-- Stats Dashboard -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Productos</h3>
                <div class="value"><?= $total_products ?></div>
            </div>
            <div class="stat-card">
                <h3>Categorías</h3>
                <div class="value"><?= count($categories) ?></div>
            </div>
            <div class="stat-card">
                <h3>Stock Total</h3>
                <div class="value"><?= $total_stock ?></div>
            </div>
        </div>
        
        <!-- Tabs Navigation -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab('products')">📦 Productos</button>
            <button class="tab" onclick="switchTab('create')">➕ Crear Producto</button>
        </div>
        
        <!-- Tab: Products List -->
        <div id="tab-products" class="tab-content active">
            <h2 style="margin-bottom: 20px;">Listado de Productos</h2>
            
            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3>No hay productos registrados</h3>
                    <p>Crea tu primer producto usando la pestaña "Crear Producto"</p>
                </div>
            <?php else: ?>
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($product['images'])): ?>
                                        <img src="<?= htmlspecialchars($product['images'][0]) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <?php else: ?>
                                        <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #999;">📷</div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= htmlspecialchars($product['name']) ?></strong></td>
                                <td><?= htmlspecialchars($product['brand'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($product['category'] ?? '-') ?></td>
                                <td><strong>$<?= number_format($product['price'] ?? 0, 2) ?></strong></td>
                                <td><?= htmlspecialchars($product['stock'] ?? 0) ?></td>
                                <td>
                                    <button class="btn btn-secondary" onclick="editProduct('<?= htmlspecialchars($product['id']) ?>')">✏️ Editar</button>
                                    <button class="btn btn-danger" onclick="deleteProduct('<?= htmlspecialchars($product['id']) ?>', '<?= htmlspecialchars($product['name']) ?>')">🗑️ Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Tab: Create Product -->
        <div id="tab-create" class="tab-content">
            <h2 style="margin-bottom: 20px;">Crear Nuevo Producto</h2>
            
            <div id="createMessage"></div>
            
            <form id="createForm" method="POST" action="/admin/product/create" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nombre del Producto *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="brand">Marca</label>
                        <input type="text" id="brand" name="brand">
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="category">Categoría</label>
                        <select id="category" name="category">
                            <option value="">Seleccionar...</option>
                            <option value="tablas">Tablas</option>
                            <option value="trucks">Trucks</option>
                            <option value="ruedas">Ruedas</option>
                            <option value="rodamientos">Rodamientos</option>
                            <option value="protecciones">Protecciones</option>
                            <option value="ropa">Ropa</option>
                            <option value="accesorios">Accesorios</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Precio *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="stock">Stock *</label>
                        <input type="number" id="stock" name="stock" min="0" value="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sizes">Talles/Tamaños (separados por coma)</label>
                        <input type="text" id="sizes" name="sizes" placeholder="Ej: S, M, L, XL">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Imágenes (máx. 6)</label>
                    <div class="upload-zone" onclick="document.getElementById('images').click()">
                        <p style="font-size: 48px; margin-bottom: 10px;">📷</p>
                        <p style="color: #666; font-weight: 600;">Click para seleccionar imágenes</p>
                        <p style="color: #999; font-size: 13px; margin-top: 8px;">O arrastra y suelta aquí</p>
                    </div>
                    <input 
                        type="file" 
                        id="images" 
                        name="images[]" 
                        accept="image/*" 
                        multiple 
                        style="display: none;"
                        onchange="previewImages(this)"
                    >
                    <div id="imagePreview" class="image-preview"></div>
                </div>
                
                <button type="submit" class="btn btn-primary" style="padding: 14px 32px; font-size: 15px;">
                    ✨ Crear Producto
                </button>
            </form>
        </div>
    </div>
    
    <script>
        // Tab switching
        function switchTab(tabName) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        }
        
        // Image preview
        let selectedFiles = [];
        
        function previewImages(input) {
            const preview = document.getElementById('imagePreview');
            const files = Array.from(input.files);
            
            if (files.length + selectedFiles.length > 6) {
                alert('Máximo 6 imágenes permitidas');
                return;
            }
            
            selectedFiles = files;
            preview.innerHTML = '';
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-btn" onclick="removeImage(${index})">×</button>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
        
        function removeImage(index) {
            selectedFiles.splice(index, 1);
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            document.getElementById('images').files = dt.files;
            previewImages(document.getElementById('images'));
        }
        
        // Drag and drop
        const uploadZone = document.querySelector('.upload-zone');
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadZone.addEventListener(eventName, () => {
                uploadZone.classList.add('drag-over');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, () => {
                uploadZone.classList.remove('drag-over');
            }, false);
        });
        
        uploadZone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('images').files = files;
            previewImages(document.getElementById('images'));
        }, false);
        
        // Form submit
        document.getElementById('createForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('createMessage');
            const submitBtn = this.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Creando...';
            
            try {
                const res = await fetch('/admin/product/create', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                
                if (res.ok && data.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success">✅ Producto creado exitosamente</div>';
                    this.reset();
                    document.getElementById('imagePreview').innerHTML = '';
                    selectedFiles = [];
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-error">❌ Error: ' + (data.error || 'No se pudo crear el producto') + '</div>';
                }
            } catch (err) {
                messageDiv.innerHTML = '<div class="alert alert-error">❌ Error de conexión</div>';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = '✨ Crear Producto';
            }
        });
        
        // Delete product
        async function deleteProduct(id, name) {
            if (!confirm(`¿Estás seguro de eliminar "${name}"?`)) return;
            
            try {
                const res = await fetch('/admin/product/' + id, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?= $csrf_token ?>'
                    }
                });
                
                if (res.ok) {
                    alert('✅ Producto eliminado');
                    window.location.reload();
                } else {
                    alert('❌ Error al eliminar');
                }
            } catch (err) {
                alert('❌ Error de conexión');
            }
        }
        
        // Edit product (placeholder - implementar modal o página separada)
        function editProduct(id) {
            alert('Función de edición en desarrollo.\nPor ahora, elimina y vuelve a crear el producto.');
        }
    </script>
</body>
</html>
