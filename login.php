<?php
/**
 * SHOP33 - Login Page
 */

require_once __DIR__ . '/bootstrap/init.php';
require_once __DIR__ . '/middlewares/AuthGuard.php';

$error = '';
$next = $_GET['next'] ?? ADMIN_PREFIX;

// Si ya está autenticado, redirigir
if (is_authenticated()) {
    header("Location: $next");
    exit;
}

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (verify_admin($username, $password)) {
        login_user($username);
        header("Location: $next");
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
        log_msg('WARN', "Failed login attempt for: $username");
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin Login — SHOP33 Skate Store</title>
    <link rel="stylesheet" href="./styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-panel) 100%);
            position: relative;
            overflow: hidden;
        }
        .login-container::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 0, 68, 0.1) 0%, transparent 70%);
            top: -200px;
            right: -200px;
            animation: pulse 8s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .login-box {
            background: var(--bg-panel);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 50px 40px;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
            position: relative;
            z-index: 1;
        }
        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }
        .login-logo {
            font-family: var(--font-heading);
            font-size: 48px;
            letter-spacing: 4px;
            margin-bottom: 10px;
            color: var(--text-primary);
        }
        .login-logo .accent {
            color: var(--accent-red);
        }
        .login-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            letter-spacing: 2px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-size: 14px;
            letter-spacing: 1px;
        }
        .form-input {
            width: 100%;
            padding: 15px;
            background: var(--bg-dark);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--accent-red);
            box-shadow: 0 0 0 3px rgba(255, 0, 68, 0.1);
        }
        .login-btn {
            width: 100%;
            padding: 15px;
            background: var(--accent-red);
            color: white;
            border: none;
            border-radius: 8px;
            font-family: var(--font-heading);
            font-size: 18px;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(255, 0, 68, 0.4);
        }
        .login-btn:hover:not(:disabled) {
            background: var(--accent-orange);
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(255, 107, 53, 0.5);
        }
        .error-message {
            background: rgba(255, 0, 68, 0.1);
            border: 1px solid var(--accent-red);
            color: var(--accent-red);
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .back-link {
            text-align: center;
            margin-top: 25px;
        }
        .back-link a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        .back-link a:hover {
            color: var(--accent-red);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1 class="login-logo">SHOP<span class="accent">33</span></h1>
                <p class="login-subtitle">PANEL DE ADMINISTRACIÓN</p>
            </div>

            <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label">USUARIO</label>
                    <input type="text" name="username" class="form-input" placeholder="admin" required autocomplete="username" />
                </div>

                <div class="form-group">
                    <label class="form-label">CONTRASEÑA</label>
                    <input type="password" name="password" class="form-input" placeholder="••••••••" required autocomplete="current-password" />
                </div>

                <button type="submit" class="login-btn">INGRESAR</button>
            </form>

            <div class="back-link">
                <a href="/">← Volver al inicio</a>
            </div>
        </div>
    </div>
</body>
</html>
