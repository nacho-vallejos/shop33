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
    // Acepta tanto 'user'/'pass' (legacy) como 'username'/'password' (Node.js compatible)
    $u = trim($in['username'] ?? $in['user'] ?? '');
    $p = (string)($in['password'] ?? $in['pass'] ?? '');
    
    if ($u === '' || $p === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Username and password required'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $users = users_store();
    if (!isset($users[$u])) {
        \log_msg('WARN', "Login failed: user not found: $u");
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $row = $users[$u];
    
    if (!password_verify($p, $row['pass'])) {
        \log_msg('WARN', "Login failed: wrong password for: $u");
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Regenerar session ID por seguridad
    session_regenerate_id(true);
    
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user']    = $row['user'];
    $_SESSION['role']    = $row['role'];
    $_SESSION['csrf']    = bin2hex(random_bytes(16));
    $_SESSION['login_time'] = time();
    
    // Generar un token simple (compatible con Node.js response)
    $token = base64_encode(json_encode([
        'user' => $row['user'],
        'role' => $row['role'],
        'time' => time(),
        'session' => session_id()
    ]));
    
    \log_msg('INFO', "User logged in: $u");
    
    // Respuesta compatible con Node.js
    echo json_encode([
        'token' => $token,
        'user' => $row['user'],
        'role' => $row['role']
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
        echo json_encode(['error' => 'auth_required'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Respuesta compatible con Node.js
    echo json_encode([
        'user' => [
            'user' => $_SESSION['user'] ?? null,
            'role' => $_SESSION['role'] ?? null
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
