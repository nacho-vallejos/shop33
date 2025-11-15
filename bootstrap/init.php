<?php
/**
 * SHOP33 - Bootstrap & Initialization
 * Configura sesiones, paths, logging y seguridad
 */

// Error reporting para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir constantes de paths
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('DATA_PATH', BASE_PATH . '/data');
define('LOG_PATH', STORAGE_PATH . '/logs/app.log');
define('SESSION_PATH', STORAGE_PATH . '/sessions');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// Configuración de la aplicación
define('APP_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
define('ADMIN_PREFIX', '/admin');

// Crear directorios necesarios si no existen
$dirs = [STORAGE_PATH, dirname(LOG_PATH), SESSION_PATH, DATA_PATH, UPLOADS_PATH];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Configurar session path si no es escribible
if (!is_writable(session_save_path())) {
    ini_set('session.save_path', SESSION_PATH);
}

// Configurar sesiones seguras
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    if (!@session_start()) {
        log_msg('ERROR', 'Failed to start session');
    }
}

// Headers de seguridad
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');

// Cargar .env si existe
$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

// Configuración de admin desde .env o defaults
// Usuario fijo: Admin
// Contraseña segura (hash SHA-256 de "Luike_2025!Sk8_admin")
define('ADMIN_USER', 'Admin');
define('ADMIN_PASS_HASH', getenv('ADMIN_PASS_HASH') ?: 'f1c9ca16b53fed7c133db5430a7b3f0d8f97b270af224c526f346442dbbb0f75');
define('JWT_SECRET', getenv('JWT_SECRET') ?: bin2hex(random_bytes(32)));

/**
 * Logger helper
 */
function log_msg($level, $msg) {
    $date = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $uri = $_SERVER['REQUEST_URI'] ?? 'N/A';
    $entry = "[$date] [$level] [IP:$ip] [URI:$uri] $msg\n";
    
    if (!is_dir(dirname(LOG_PATH))) {
        mkdir(dirname(LOG_PATH), 0755, true);
    }
    
    @file_put_contents(LOG_PATH, $entry, FILE_APPEND | LOCK_EX);
}

/**
 * JSON response helper
 */
function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/**
 * Cargar productos desde JSON
 */
function load_products() {
    $file = BASE_PATH . '/db-products.json';
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    return json_decode($content, true) ?: [];
}

/**
 * Guardar productos a JSON
 */
function save_products($products) {
    $file = BASE_PATH . '/db-products.json';
    $json = json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents($file, $json, LOCK_EX) !== false;
}

/**
 * Generar CSRF token
 */
function generate_csrf() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validar CSRF token
 */
function validate_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

log_msg('INFO', 'Bootstrap initialized');
