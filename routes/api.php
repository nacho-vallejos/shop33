<?php
/**
 * SHOP33 - API Routes
 * Endpoints para productos y operaciones
 */

require_once __DIR__ . '/../bootstrap/init.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// GET /api/products - Listar productos
if ($method === 'GET' && ($uri === '/api/products' || $uri === '/api/products/')) {
    $products = load_products();
    
    // Filtros opcionales
    $category = $_GET['category'] ?? '';
    $brand = $_GET['brand'] ?? '';
    $search = $_GET['search'] ?? '';
    $size = $_GET['size'] ?? '';
    
    $filtered = array_filter($products, function($p) use ($category, $brand, $search, $size) {
        if ($category && (!isset($p['category']) || strcasecmp($p['category'], $category) !== 0)) {
            return false;
        }
        if ($brand && (!isset($p['brand']) || strcasecmp($p['brand'], $brand) !== 0)) {
            return false;
        }
        if ($search) {
            $searchLower = strtolower($search);
            $inName = isset($p['name']) && stripos($p['name'], $search) !== false;
            $inBrand = isset($p['brand']) && stripos($p['brand'], $search) !== false;
            $inDesc = isset($p['description']) && stripos($p['description'], $search) !== false;
            if (!$inName && !$inBrand && !$inDesc) {
                return false;
            }
        }
        if ($size && isset($p['sizes']) && is_array($p['sizes'])) {
            if (!in_array($size, $p['sizes'])) {
                return false;
            }
        }
        return true;
    });
    
    log_msg('INFO', 'Products loaded: ' . count($filtered));
    
    json_response([
        'total' => count($filtered),
        'items' => array_values($filtered)
    ]);
}

// GET /api/products/:id - Obtener producto específico
if ($method === 'GET' && preg_match('#^/api/products/([^/]+)$#', $uri, $matches)) {
    $id = $matches[1];
    $products = load_products();
    
    foreach ($products as $product) {
        if (isset($product['id']) && $product['id'] === $id) {
            log_msg('INFO', "Product found: $id");
            json_response($product);
        }
    }
    
    log_msg('WARN', "Product not found: $id");
    json_response(['error' => 'Producto no encontrado'], 404);
}

// GET /api/brands - Obtener marcas únicas
if ($method === 'GET' && ($uri === '/api/brands' || $uri === '/api/brands/')) {
    $products = load_products();
    $brands = array_unique(array_filter(array_column($products, 'brand')));
    sort($brands);
    json_response(['brands' => array_values($brands)]);
}

// GET /api/categories - Obtener categorías únicas
if ($method === 'GET' && ($uri === '/api/categories' || $uri === '/api/categories/')) {
    $products = load_products();
    $categories = array_unique(array_filter(array_column($products, 'category')));
    sort($categories);
    json_response(['categories' => array_values($categories)]);
}

// Si llegamos aquí, endpoint no encontrado
log_msg('WARN', "API endpoint not found: $uri");
json_response(['error' => 'Endpoint no encontrado'], 404);
