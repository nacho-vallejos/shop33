<?php
/**
 * SHOP33 - Front Controller
 * Maneja routing y requests
 */

require_once __DIR__ . '/../bootstrap/init.php';

// Normalizar URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = strtolower($uri);
$uri = rtrim($uri, '/');
if (empty($uri)) $uri = '/';

// Logging
log_msg('INFO', "Request: {$_SERVER['REQUEST_METHOD']} $uri");

// Router
if ($uri === '/' || $uri === '/index.php' || $uri === '/index.html') {
    include __DIR__ . '/../index.html';
    exit;
}

// Login page
if ($uri === '/login.php' || $uri === '/login') {
    include __DIR__ . '/../views/admin/login.php';
    exit;
}

// Admin logout
if ($uri === '/admin/logout') {
    require_once __DIR__ . '/../middlewares/AuthGuard.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        logout_user();
        header('Location: /login');
    } else {
        header('Location: /admin');
    }
    exit;
}

// Admin panel
if ($uri === '/admin' || $uri === '/admin/') {
    include __DIR__ . '/../views/admin/panel.php';
    exit;
}

// Admin product create
if ($uri === '/admin/product/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../controllers/ProductsController.php';
    ProductsController\create($_POST, $_FILES);
    exit;
}

// Admin product delete
if (preg_match('#^/admin/product/([^/]+)$#', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    require_once __DIR__ . '/../middlewares/AuthGuard.php';
    require_auth();
    require_once __DIR__ . '/../controllers/ProductsController.php';
    ProductsController\delete($matches[1]);
    exit;
}

// Product detail page
if ($uri === '/product.php' || $uri === '/product' || $uri === '/product.html') {
    include __DIR__ . '/../product.html';
    exit;
}

// Static files - let Apache handle
$ext = pathinfo($uri, PATHINFO_EXTENSION);
$allowed_exts = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'mp4', 'webm', 'json', 'woff', 'woff2', 'ttf', 'eot'];

if (in_array($ext, $allowed_exts)) {
    return false;
}

// 404 - Not Found
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada | SHOP33</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #0a0a0a; color: #fff; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .error-container { text-align: center; padding: 40px; }
        h1 { font-size: 120px; color: #ff0044; margin-bottom: 20px; }
        h2 { font-size: 32px; margin-bottom: 20px; }
        p { font-size: 18px; color: #999; margin-bottom: 30px; }
        a { display: inline-block; padding: 15px 40px; background: #ff0044; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold; transition: all 0.3s; }
        a:hover { background: #ff6b35; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <h2>Página no encontrada</h2>
        <p>La página que buscas no existe o fue movida.</p>
        <a href="/">Volver al inicio</a>
    </div>
</body>
</html>
