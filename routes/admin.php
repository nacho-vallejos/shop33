<?php
/**
 * SHOP33 - Admin Routes
 * Panel de administración con autenticación
 */

require_once __DIR__ . '/../bootstrap/init.php';
require_once __DIR__ . '/../middlewares/AuthGuard.php';

// Require authentication
require_auth();

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

log_msg('INFO', 'Admin route accessed by: ' . current_user());

// GET /admin - Dashboard
if ($method === 'GET' && ($uri === ADMIN_PREFIX || $uri === ADMIN_PREFIX . '/' || $uri === ADMIN_PREFIX . '/index')) {
    include __DIR__ . '/../dashboard.php';
    exit;
}

// POST /admin/login - Login (ya se maneja en login.php, esto es por si acaso)
if ($method === 'POST' && $uri === ADMIN_PREFIX . '/login') {
    header('Location: ' . ADMIN_PREFIX);
    exit;
}

// Admin API - Requiere CSRF para métodos unsafe
$is_unsafe_method = in_array($method, ['POST', 'PUT', 'DELETE']);

if ($is_unsafe_method) {
    $csrf = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!validate_csrf($csrf)) {
        log_msg('ERROR', 'CSRF validation failed');
        http_response_code(400);
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            json_response(['error' => 'Token CSRF inválido'], 400);
        } else {
            die('Error: Token CSRF inválido');
        }
    }
}

// GET /admin/products - Listar productos (admin)
if ($method === 'GET' && $uri === ADMIN_PREFIX . '/products') {
    $products = load_products();
    json_response($products);
}

// POST /admin/product - Crear producto
if ($method === 'POST' && $uri === ADMIN_PREFIX . '/product') {
    $name = $_POST['name'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $sizes = isset($_POST['sizes']) ? explode(',', $_POST['sizes']) : [];
    $sizes = array_map('trim', $sizes);
    $description = $_POST['description'] ?? '';
    
    if (empty($name)) {
        json_response(['error' => 'El nombre es requerido'], 400);
    }
    
    // Generar ID único
    $id = uniqid('prod_');
    
    // Manejar imágenes
    $images = [];
    if (isset($_FILES['images'])) {
        $files = $_FILES['images'];
        $file_count = is_array($files['name']) ? count($files['name']) : 1;
        
        for ($i = 0; $i < min($file_count, 6); $i++) {
            if (is_array($files['name'])) {
                $file_name = $files['name'][$i];
                $file_tmp = $files['tmp_name'][$i];
                $file_error = $files['error'][$i];
            } else {
                $file_name = $files['name'];
                $file_tmp = $files['tmp_name'];
                $file_error = $files['error'];
            }
            
            if ($file_error === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $new_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $dest = UPLOADS_PATH . '/' . $new_name;
                    
                    if (move_uploaded_file($file_tmp, $dest)) {
                        $images[] = '/uploads/' . $new_name;
                    }
                }
            }
        }
    }
    
    $product = [
        'id' => $id,
        'name' => $name,
        'brand' => $brand,
        'category' => $category,
        'price' => $price,
        'stock' => $stock,
        'sizes' => $sizes,
        'description' => $description,
        'images' => $images,
        'createdAt' => date('c')
    ];
    
    $products = load_products();
    $products[] = $product;
    
    if (save_products($products)) {
        log_msg('INFO', "Product created: {$product['name']} ($id)");
        json_response(['success' => true, 'product' => $product], 201);
    } else {
        log_msg('ERROR', 'Failed to save product');
        json_response(['error' => 'Error al guardar producto'], 500);
    }
}

// PUT /admin/product/:id - Actualizar producto
if ($method === 'POST' && preg_match('#^' . ADMIN_PREFIX . '/product/([^/]+)$#', $uri, $matches)) {
    $id = $matches[1];
    
    // Simular PUT con _method
    if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
        $products = load_products();
        $found = false;
        
        foreach ($products as &$product) {
            if ($product['id'] === $id) {
                $found = true;
                $product['name'] = $_POST['name'] ?? $product['name'];
                $product['brand'] = $_POST['brand'] ?? $product['brand'];
                $product['category'] = $_POST['category'] ?? $product['category'];
                $product['price'] = isset($_POST['price']) ? floatval($_POST['price']) : $product['price'];
                $product['stock'] = isset($_POST['stock']) ? intval($_POST['stock']) : $product['stock'];
                if (isset($_POST['sizes'])) {
                    $sizes = explode(',', $_POST['sizes']);
                    $product['sizes'] = array_map('trim', $sizes);
                }
                $product['description'] = $_POST['description'] ?? $product['description'];
                $product['updatedAt'] = date('c');
                break;
            }
        }
        
        if ($found && save_products($products)) {
            log_msg('INFO', "Product updated: $id");
            json_response(['success' => true]);
        } else {
            json_response(['error' => 'Producto no encontrado'], 404);
        }
    }
}

// DELETE /admin/product/:id - Eliminar producto
if ($method === 'POST' && preg_match('#^' . ADMIN_PREFIX . '/product/([^/]+)$#', $uri, $matches)) {
    $id = $matches[1];
    
    // Simular DELETE con _method
    if (isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
        $products = load_products();
        $filtered = array_filter($products, function($p) use ($id) {
            return $p['id'] !== $id;
        });
        
        if (count($filtered) < count($products)) {
            if (save_products(array_values($filtered))) {
                log_msg('INFO', "Product deleted: $id");
                json_response(['success' => true]);
            } else {
                json_response(['error' => 'Error al eliminar'], 500);
            }
        } else {
            json_response(['error' => 'Producto no encontrado'], 404);
        }
    }
}

// Si llegamos aquí, ruta admin no reconocida
log_msg('WARN', "Admin route not found: $uri");
http_response_code(404);
echo '404 - Admin route not found';
