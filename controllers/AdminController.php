<?php
/**
 * SHOP33 - Admin Controller
 * Endpoints del panel de administración
 */

namespace AdminController;

function dashboard() {
    // Cargar productos para stats
    $products = [];
    $file = BASE_PATH . '/db-products.json';
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $products = json_decode($content, true) ?: [];
    }
    
    // Calcular estadísticas
    $totalProducts = count($products);
    $categories = array_unique(array_column($products, 'category'));
    $totalStock = array_sum(array_column($products, 'stock'));
    
    echo json_encode([
        'ok' => true,
        'stats' => [
            'totalProducts' => $totalProducts,
            'categories' => count($categories),
            'totalStock' => $totalStock
        ],
        'user' => $_SESSION['user'] ?? null
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
