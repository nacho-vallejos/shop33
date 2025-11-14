<?php
/**
 * SHOP33 - Auth Controller
 * Maneja login, logout y sesiones
 */

namespace AuthController;

function users_store() {
    // Demo: usuario hardcoded
    // Password: admin123 (hash bcrypt)
    $users = [
        'admin' => [
            'id' => 1,
            'user' => 'admin',
            'pass' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // admin123
            'role' => 'admin'
        ]
    ];
    return $users;
}

function login(array $in) {
    $u = trim($in['user'] ?? '');
    $p = (string)($in['pass'] ?? '');
    
    if ($u === '' || $p === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'missing_fields'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $users = users_store();
    if (!isset($users[$u])) {
        \log_msg('WARN', "Login failed: user not found: $u");
        http_response_code(401);
        echo json_encode(['ok' => false, 'error' => 'invalid_credentials'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $row = $users[$u];
    
    if (!password_verify($p, $row['pass'])) {
        \log_msg('WARN', "Login failed: wrong password for: $u");
        http_response_code(401);
        echo json_encode(['ok' => false, 'error' => 'invalid_credentials'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Regenerar session ID por seguridad
    session_regenerate_id(true);
    
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user']    = $row['user'];
    $_SESSION['role']    = $row['role'];
    $_SESSION['csrf']    = bin2hex(random_bytes(16));
    $_SESSION['login_time'] = time();
    
    \log_msg('INFO', "User logged in: $u");
    
    echo json_encode([
        'ok' => true,
        'data' => [
            'user' => $row['user'],
            'role' => $row['role'],
            'csrf' => $_SESSION['csrf']
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function logout() {
    $user = $_SESSION['user'] ?? 'unknown';
    session_regenerate_id(true);
    $_SESSION = [];
    session_destroy();
    
    \log_msg('INFO', "User logged out: $user");
    
    echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
    exit;
}

function me() {
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'error' => 'auth_required'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    echo json_encode([
        'ok' => true,
        'data' => [
            'user' => $_SESSION['user'] ?? null,
            'role' => $_SESSION['role'] ?? null,
            'csrf' => $_SESSION['csrf'] ?? null
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
