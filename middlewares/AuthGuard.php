<?php
/**
 * SHOP33 - Auth Guard Middleware
 * Maneja autenticación y redirecciones
 */

/**
 * Require authentication
 * Redirige a login si no hay sesión activa
 */
function require_auth() {
    if (empty($_SESSION['user_id'])) {
        log_msg('WARN', 'Auth required, redirecting to /login');
        $next = rawurlencode($_SERVER['REQUEST_URI']);
        header("Location: /login.php?next=$next", true, 302);
        exit;
    }
}

/**
 * Check if user is authenticated
 */
function is_authenticated() {
    return !empty($_SESSION['user_id']);
}

/**
 * Get current user ID
 */
function current_user() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Login user
 */
function login_user($username) {
    $_SESSION['user_id'] = $username;
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['login_time'] = time();
    log_msg('INFO', "User logged in: $username");
}

/**
 * Logout user
 */
function logout_user() {
    $user = current_user();
    session_unset();
    session_destroy();
    log_msg('INFO', "User logged out: $user");
}

/**
 * Verify admin credentials
 */
function verify_admin($username, $password) {
    if ($username !== ADMIN_USER) {
        return false;
    }
    $hash = hash('sha256', $password);
    return hash_equals(ADMIN_PASS_HASH, $hash);
}
