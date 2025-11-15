<?php
/**
 * SHOP33 - Products Controller
 * CRUD de productos
 */

namespace ProductsController;

function get_db_path() {
    return BASE_PATH . '/db-products.json';
}

function load_products() {
    $file = get_db_path();
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    return json_decode($content, true) ?: [];
}

function save_products($products) {
    $file = get_db_path();
    $json = json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents($file, $json, LOCK_EX) !== false;
}

function list_all() {
    $products = load_products();
    echo json_encode($products, JSON_UNESCAPED_UNICODE);
    exit;
}

function list_public($filters) {
    $products = load_products();
    
    // Filtros
    $category = $filters['category'] ?? '';
    $brand = $filters['brand'] ?? '';
    $search = $filters['search'] ?? '';
    $size = $filters['size'] ?? '';
    
    if ($category || $brand || $search || $size) {
        $products = array_filter($products, function($p) use ($category, $brand, $search, $size) {
            if ($category && strcasecmp($p['category'] ?? '', $category) !== 0) return false;
            if ($brand && strcasecmp($p['brand'] ?? '', $brand) !== 0) return false;
            if ($search) {
                $s = strtolower($search);
                $in_name = stripos($p['name'] ?? '', $search) !== false;
                $in_brand = stripos($p['brand'] ?? '', $search) !== false;
                $in_desc = stripos($p['description'] ?? '', $search) !== false;
                if (!$in_name && !$in_brand && !$in_desc) return false;
            }
            if ($size && isset($p['sizes']) && is_array($p['sizes'])) {
                if (!in_array($size, $p['sizes'])) return false;
            }
            return true;
        });
    }
    
    echo json_encode([
        'total' => count($products),
        'items' => array_values($products)
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function get_one($id) {
    $products = load_products();
    foreach ($products as $p) {
        if ($p['id'] === $id) {
            echo json_encode($p, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'not_found'], JSON_UNESCAPED_UNICODE);
    exit;
}

function create($post, $files) {
    // Verificar CSRF
    $csrf = $post['csrf_token'] ?? '';
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
        \log_msg('WARN', 'CSRF validation failed on product create');
        http_response_code(400);
        echo json_encode(['error' => 'Token CSRF inválido'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $name = $post['name'] ?? '';
    if (empty($name)) {
        http_response_code(400);
        echo json_encode(['error' => 'name_required'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $id = uniqid('prod_');
    $images = [];
    
    // Upload images
    if (isset($files['images'])) {
        $uploads_dir = BASE_PATH . '/uploads';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0755, true);
        }
        
        $file_list = $files['images'];
        $count = is_array($file_list['name']) ? count($file_list['name']) : 1;
        
        for ($i = 0; $i < min($count, 6); $i++) {
            $tmp = is_array($file_list['tmp_name']) ? $file_list['tmp_name'][$i] : $file_list['tmp_name'];
            $name_orig = is_array($file_list['name']) ? $file_list['name'][$i] : $file_list['name'];
            $error = is_array($file_list['error']) ? $file_list['error'][$i] : $file_list['error'];
            
            if ($error === UPLOAD_ERR_OK && !empty($name_orig)) {
                $ext = strtolower(pathinfo($name_orig, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $new_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    if (move_uploaded_file($tmp, $uploads_dir . '/' . $new_name)) {
                        $images[] = '/uploads/' . $new_name;
                        \log_msg('INFO', "Image uploaded: $new_name");
                    }
                }
            }
        }
    }
    
    $sizes = isset($post['sizes']) ? array_filter(array_map('trim', explode(',', $post['sizes']))) : [];
    
    $product = [
        'id' => $id,
        'name' => $name,
        'brand' => $post['brand'] ?? '',
        'category' => $post['category'] ?? '',
        'price' => floatval($post['price'] ?? 0),
        'stock' => intval($post['stock'] ?? 0),
        'sizes' => $sizes,
        'description' => $post['description'] ?? '',
        'images' => $images,
        'createdAt' => date('c')
    ];
    
    $products = load_products();
    $products[] = $product;
    
    if (save_products($products)) {
        \log_msg('INFO', "Product created: $name ($id) by " . ($_SESSION['user_id'] ?? 'unknown'));
        echo json_encode(['success' => true, 'product' => $product], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'save_failed'], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function update($id, $post) {
    $products = load_products();
    $found = false;
    
    foreach ($products as &$p) {
        if ($p['id'] === $id) {
            $found = true;
            $p['name'] = $post['name'] ?? $p['name'];
            $p['brand'] = $post['brand'] ?? $p['brand'];
            $p['category'] = $post['category'] ?? $p['category'];
            $p['price'] = isset($post['price']) ? floatval($post['price']) : $p['price'];
            $p['stock'] = isset($post['stock']) ? intval($post['stock']) : $p['stock'];
            if (isset($post['sizes'])) {
                $p['sizes'] = array_map('trim', explode(',', $post['sizes']));
            }
            $p['description'] = $post['description'] ?? $p['description'];
            $p['updatedAt'] = date('c');
            break;
        }
    }
    
    if ($found && save_products($products)) {
        \log_msg('INFO', "Product updated: $id");
        echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'not_found'], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function delete($id) {
    $products = load_products();
    
    // Buscar producto para eliminar sus imágenes
    $product_to_delete = null;
    foreach ($products as $p) {
        if ($p['id'] === $id) {
            $product_to_delete = $p;
            break;
        }
    }
    
    // Filtrar productos
    $filtered = array_filter($products, function($p) use ($id) {
        return $p['id'] !== $id;
    });
    
    if (count($filtered) < count($products)) {
        // Eliminar imágenes físicas
        if ($product_to_delete && !empty($product_to_delete['images'])) {
            foreach ($product_to_delete['images'] as $img_path) {
                $file = BASE_PATH . $img_path;
                if (file_exists($file)) {
                    @unlink($file);
                    \log_msg('INFO', "Image deleted: $img_path");
                }
            }
        }
        
        if (save_products(array_values($filtered))) {
            \log_msg('INFO', "Product deleted: $id");
            echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'save_failed'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'not_found'], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
