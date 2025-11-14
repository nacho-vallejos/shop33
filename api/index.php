<?php
/**
 * SHOP33 - API Router
 * Endpoints REST sin frameworks
 */

require_once __DIR__ . '/../bootstrap/init.php';
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$reqPath   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$scriptDir = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/'); // ej: /shop33/api

// Recorta el directorio real donde vive este index.php (…/api)
if ($scriptDir && str_starts_with($reqPath, $scriptDir)) {
  $path = substr($reqPath, strlen($scriptDir));
} else {
  $path = $reqPath;
}

// Fallback: si aún trae /api al inicio, quítalo.
$path = preg_replace('#^/api#i', '', $path);

// Normaliza a "/algo" sin barra final (excepto raíz)
$path = '/' . ltrim($path, '/');
$path = rtrim($path, '/');
if ($path === '') $path = '/';

// Log de diagnóstico
log_msg('INFO', "API route: method={$method} scriptDir={$scriptDir} reqPath={$reqPath} normPath={$path}");

// Helpers
function json_input() {
    $raw = file_get_contents('php://input') ?: '';
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function ok($data = [], $code = 200) {
    http_response_code($code);
    echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

function bad($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

function require_auth_api() {
    if (empty($_SESSION['user_id'])) {
        bad('auth_required', 401);
    }
}

// Cargar controllers
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/ProductsController.php';

try {
    log_msg('INFO', "API Request: $method $path");
    
    // Endpoint de prueba
    if (($path === '/ping' || $path === '/ping/') && $method === 'GET') {
        echo json_encode(['ok'=>true,'data'=>'pong'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // AUTH ENDPOINTS
    if (($path === '/auth/login' || $path === '/auth/login/') && $method === 'POST') {
        AuthController\login(json_input());
    }
    
    if (($path === '/auth/logout' || $path === '/auth/logout/') && $method === 'POST') {
        AuthController\logout();
    }
    
    if (($path === '/auth/me' || $path === '/auth/me/') && $method === 'GET') {
        AuthController\me();
    }
    
    // ADMIN ENDPOINTS (protegidos)
    if (($path === '/admin/dashboard' || $path === '/admin/dashboard/') && $method === 'GET') {
        require_auth_api();
        AdminController\dashboard();
    }
    
    if (($path === '/admin/products' || $path === '/admin/products/') && $method === 'GET') {
        require_auth_api();
        ProductsController\list_all();
    }
    
    if (($path === '/admin/product' || $path === '/admin/product/') && $method === 'POST') {
        require_auth_api();
        ProductsController\create($_POST, $_FILES);
    }
    
    if (preg_match('#^/admin/product/([^/]+)/?$#', $path, $m) && $method === 'POST') {
        require_auth_api();
        $action = $_POST['_method'] ?? 'POST';
        if ($action === 'DELETE') {
            ProductsController\delete($m[1]);
        } elseif ($action === 'PUT') {
            ProductsController\update($m[1], $_POST);
        }
    }
    
    // PUBLIC ENDPOINTS (productos)
    if (($path === '/products' || $path === '/products/') && $method === 'GET') {
        ProductsController\list_public($_GET);
    }
    
    if (preg_match('#^/products/([^/]+)/?$#', $path, $m) && $method === 'GET') {
        ProductsController\get_one($m[1]);
    }
    
    // Si no matchea nada:
    log_msg('WARN', "API endpoint not found: $path");
    bad('endpoint_not_found', 404);
    
} catch (Throwable $e) {
    log_msg('ERROR', 'API exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    bad('server_error: ' . $e->getMessage(), 500);
}
