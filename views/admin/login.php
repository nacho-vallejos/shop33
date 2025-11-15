<?php<!DOCTYPE html>

/**<html lang="es">

 * SHOP33 - Admin Login Page<head>

 * Session-based authentication (no JWT/localStorage)    <meta charset="UTF-8">

 */    <meta name="viewport" content="width=device-width, initial-scale=1.0">

require_once __DIR__ . '/../../bootstrap/init.php';    <title>Login - SHOP33 Admin</title>

require_once __DIR__ . '/../../middlewares/AuthGuard.php';    <link rel="stylesheet" href="/styles.css">

    <style>

// Si ya está autenticado, redirigir al panel        body {

if (is_authenticated()) {            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

    header('Location: /admin');            min-height: 100vh;

    exit;            display: flex;

}            align-items: center;

            justify-content: center;

// Procesar login            font-family: Arial, sans-serif;

$error = '';        }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {        .login-container {

    $username = trim($_POST['username'] ?? '');            background: white;

    $password = $_POST['password'] ?? '';            padding: 40px;

    $csrf = $_POST['csrf_token'] ?? '';            border-radius: 10px;

                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);

    if (empty($username) || empty($password)) {            width: 100%;

        $error = 'Por favor completa todos los campos';            max-width: 400px;

    } elseif (!validate_csrf($csrf)) {        }

        $error = 'Token de seguridad inválido';        .login-container h1 {

        log_msg('WARN', "CSRF validation failed on login for user: $username");            margin: 0 0 30px;

    } elseif (verify_admin($username, $password)) {            text-align: center;

        login_user($username);            color: #333;

        $next = $_GET['next'] ?? '/admin';        }

        header("Location: $next");        .form-group {

        exit;            margin-bottom: 20px;

    } else {        }

        $error = 'Usuario o contraseña incorrectos';        .form-group label {

        log_msg('WARN', "Failed login attempt for user: $username");            display: block;

    }            margin-bottom: 8px;

}            color: #555;

            font-weight: bold;

$csrf_token = generate_csrf();        }

?>        .form-group input {

<!DOCTYPE html>            width: 100%;

<html lang="es">            padding: 12px;

<head>            border: 1px solid #ddd;

    <meta charset="UTF-8">            border-radius: 5px;

    <meta name="viewport" content="width=device-width, initial-scale=1.0">            font-size: 14px;

    <title>Login Admin - SHOP33</title>            box-sizing: border-box;

    <style>        }

        * {        .form-group input:focus {

            margin: 0;            outline: none;

            padding: 0;            border-color: #667eea;

            box-sizing: border-box;        }

        }        .btn-login {

                    width: 100%;

        body {            padding: 14px;

            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;            background: #667eea;

            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);            color: white;

            min-height: 100vh;            border: none;

            display: flex;            border-radius: 5px;

            align-items: center;            font-size: 16px;

            justify-content: center;            font-weight: bold;

            padding: 20px;            cursor: pointer;

        }            transition: background 0.3s;

                }

        .login-container {        .btn-login:hover {

            background: white;            background: #5568d3;

            border-radius: 12px;        }

            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);        .btn-login:disabled {

            width: 100%;            background: #999;

            max-width: 420px;            cursor: not-allowed;

            padding: 40px;        }

            animation: slideUp 0.4s ease;        .error-msg {

        }            background: #fee;

                    color: #c33;

        @keyframes slideUp {            padding: 12px;

            from {            border-radius: 5px;

                opacity: 0;            margin-bottom: 20px;

                transform: translateY(30px);            display: none;

            }        }

            to {        .error-msg.show {

                opacity: 1;            display: block;

                transform: translateY(0);        }

            }        .back-link {

        }            text-align: center;

                    margin-top: 20px;

        .logo {        }

            text-align: center;        .back-link a {

            margin-bottom: 30px;            color: #667eea;

        }            text-decoration: none;

                }

        .logo h1 {        .back-link a:hover {

            font-size: 32px;            text-decoration: underline;

            color: #667eea;        }

            font-weight: 800;    </style>

            letter-spacing: -1px;</head>

        }<body>

            <div class="login-container">

        .logo p {        <h1>🛹 SHOP33 Admin</h1>

            color: #666;        <div id="errorMsg" class="error-msg"></div>

            margin-top: 5px;        <form id="loginForm">

            font-size: 14px;            <div class="form-group">

        }                <label for="username">Usuario</label>

                        <input type="text" id="username" name="username" required autocomplete="username">

        .error-msg {            </div>

            background: #fee;            <div class="form-group">

            color: #c33;                <label for="password">Contraseña</label>

            padding: 12px 16px;                <input type="password" id="password" name="password" required autocomplete="current-password">

            border-radius: 8px;            </div>

            margin-bottom: 20px;            <button type="submit" class="btn-login" id="btnLogin">Iniciar Sesión</button>

            border-left: 4px solid #c33;        </form>

            font-size: 14px;        <div class="back-link">

            animation: shake 0.3s ease;            <a href="/">← Volver a la tienda</a>

        }        </div>

            </div>

        @keyframes shake {    <script src="/public/js/admin-login.js"></script>

            0%, 100% { transform: translateX(0); }</body>

            25% { transform: translateX(-10px); }</html>

            75% { transform: translateX(10px); }
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: inherit;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .back-link {
            text-align: center;
            margin-top: 25px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .back-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .security-note {
            margin-top: 20px;
            padding: 12px;
            background: #f5f5f5;
            border-radius: 8px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🛹 SHOP33</h1>
            <p>Panel de Administración</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/login">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            
            <div class="form-group">
                <label for="username">Usuario</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required 
                    autofocus
                    autocomplete="username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    autocomplete="current-password"
                >
            </div>
            
            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>
        
        <div class="back-link">
            <a href="/">← Volver a la tienda</a>
        </div>
        
        <div class="security-note">
            🔒 Conexión segura · Session-based auth
        </div>
    </div>
</body>
</html>
