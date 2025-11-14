<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - SHOP33</title>
    <link rel="stylesheet" href="/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid white;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
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
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .stat-card h3 {
            margin: 0 0 10px;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
        }
        .stat-card .value {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
        }
        .section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .section h2 {
            margin: 0 0 20px;
            color: #333;
        }
        .btn-primary {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
        }
        .products-table th,
        .products-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .products-table th {
            background: #f9f9f9;
            font-weight: bold;
            color: #666;
        }
        .loading {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛹 SHOP33 - Panel de Administración</h1>
        <div class="user-info">
            <span id="userName">Cargando...</span>
            <button class="btn-logout" onclick="logout()">Cerrar Sesión</button>
        </div>
    </div>

    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Productos</h3>
                <div class="value" id="totalProducts">-</div>
            </div>
            <div class="stat-card">
                <h3>Categorías</h3>
                <div class="value" id="totalCategories">-</div>
            </div>
            <div class="stat-card">
                <h3>Stock Total</h3>
                <div class="value" id="totalStock">-</div>
            </div>
        </div>

        <div class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Productos</h2>
                <button class="btn-primary" onclick="window.location.href='/admin/product/new'">+ Nuevo Producto</button>
            </div>
            <div id="productsContainer">
                <div class="loading">Cargando productos...</div>
            </div>
        </div>
    </div>

    <script>
        // Verificar autenticación
        async function checkAuth() {
            try {
                const token = localStorage.getItem('admin_token');
                if (!token) {
                    window.location.href = '/login';
                    return null;
                }
                
                const res = await fetch('/api/admin/me', {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                if (!res.ok) {
                    localStorage.removeItem('admin_token');
                    window.location.href = '/login';
                    return null;
                }
                const data = await res.json();
                return data.user;
            } catch (err) {
                console.error('Error checking auth:', err);
                localStorage.removeItem('admin_token');
                window.location.href = '/login';
                return null;
            }
        }

        // Cargar dashboard
        async function loadDashboard() {
            try {
                const res = await fetch('/api/admin/dashboard');
                if (!res.ok) throw new Error('Failed to load dashboard');
                const data = await res.json();
                
                document.getElementById('totalProducts').textContent = data.stats?.totalProducts || 0;
                document.getElementById('totalCategories').textContent = data.stats?.categories || 0;
                document.getElementById('totalStock').textContent = data.stats?.totalStock || 0;
            } catch (err) {
                console.error('Error loading dashboard:', err);
            }
        }

        // Cargar productos
        async function loadProducts() {
            try {
                const token = localStorage.getItem('admin_token');
                if (!token) {
                    window.location.href = '/login';
                    return;
                }
                
                const res = await fetch('/api/admin/products', {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                if (!res.ok) throw new Error('Failed to load products');
                const products = await res.json();
                
                const container = document.getElementById('productsContainer');
                if (!products || products.length === 0) {
                    container.innerHTML = '<p style="text-align: center; color: #999;">No hay productos registrados</p>';
                    return;
                }
                
                let html = '<table class="products-table"><thead><tr>';
                html += '<th>ID</th><th>Nombre</th><th>Marca</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Acciones</th>';
                html += '</tr></thead><tbody>';
                
                products.forEach(p => {
                    html += `<tr>
                        <td>${p.id}</td>
                        <td>${p.name}</td>
                        <td>${p.brand || '-'}</td>
                        <td>${p.category || '-'}</td>
                        <td>$${p.price || 0}</td>
                        <td>${p.stock || 0}</td>
                        <td>
                            <button onclick="editProduct('${p.id}')" style="margin-right: 5px;">Editar</button>
                            <button onclick="deleteProduct('${p.id}')" style="background: #c33; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Eliminar</button>
                        </td>
                    </tr>`;
                });
                
                html += '</tbody></table>';
                container.innerHTML = html;
            } catch (err) {
                console.error('Error loading products:', err);
                document.getElementById('productsContainer').innerHTML = '<p style="color: #c33;">Error al cargar productos</p>';
            }
        }

        // Logout
        async function logout() {
            try {
                const token = localStorage.getItem('admin_token');
                if (token) {
                    await fetch('/api/admin/logout', { 
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`
                        }
                    });
                }
            } catch (err) {
                console.error('Logout error:', err);
            }
            localStorage.removeItem('admin_token');
            window.location.href = '/login';
        }

        // Editar producto
        function editProduct(id) {
            window.location.href = `/admin/product/${id}`;
        }

        // Eliminar producto
        async function deleteProduct(id) {
            if (!confirm('¿Estás seguro de eliminar este producto?')) return;
            
            try {
                const token = localStorage.getItem('admin_token');
                if (!token) {
                    window.location.href = '/login';
                    return;
                }
                
                const res = await fetch(`/api/admin/product/${id}`, { 
                    method: 'DELETE',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                if (res.ok) {
                    alert('Producto eliminado');
                    loadProducts();
                } else {
                    alert('Error al eliminar producto');
                }
            } catch (err) {
                console.error('Delete error:', err);
                alert('Error al eliminar producto');
            }
        }

        // Inicializar
        (async () => {
            const user = await checkAuth();
            if (user) {
                document.getElementById('userName').textContent = user.username || user.user || 'Admin';
                await Promise.all([loadDashboard(), loadProducts()]);
            }
        })();
    </script>
</body>
</html>
