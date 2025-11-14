#!/usr/bin/env php
<?php
/**
 * SHOP33 - Verificación de Instalación
 * Comprueba que todo esté configurado correctamente
 */

echo "\n";
echo "╔════════════════════════════════════════╗\n";
echo "║   SHOP33 - Verificación PHP            ║\n";
echo "╚════════════════════════════════════════╝\n\n";

$errors = [];
$warnings = [];
$success = [];

// Verificar versión de PHP
echo "→ Verificando PHP...\n";
$phpVersion = phpversion();
if (version_compare($phpVersion, '7.4', '>=')) {
    $success[] = "✓ PHP $phpVersion (OK)";
} else {
    $errors[] = "✗ PHP $phpVersion (Se requiere >= 7.4)";
}

// Verificar archivos críticos
echo "→ Verificando archivos...\n";
$files = [
    'bootstrap/init.php',
    'middlewares/AuthGuard.php',
    'routes/api.php',
    'routes/admin.php',
    'public/index.php',
    'login.php',
    'dashboard.php',
    'index.html',
    '.htaccess',
    'db-products.json'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $success[] = "✓ $file existe";
    } else {
        $errors[] = "✗ $file NO EXISTE";
    }
}

// Verificar directorios escribibles
echo "→ Verificando permisos...\n";
$dirs = [
    'storage',
    'storage/logs',
    'storage/sessions',
    'uploads'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    
    if (is_writable($dir)) {
        $success[] = "✓ $dir es escribible";
    } else {
        $errors[] = "✗ $dir NO es escribible (chmod 755)";
    }
}

// Verificar db-products.json
if (file_exists('db-products.json')) {
    if (is_writable('db-products.json')) {
        $success[] = "✓ db-products.json es escribible";
    } else {
        $errors[] = "✗ db-products.json NO es escribible (chmod 666)";
    }
    
    $content = @file_get_contents('db-products.json');
    $data = @json_decode($content, true);
    if (is_array($data)) {
        $success[] = "✓ db-products.json es JSON válido (" . count($data) . " productos)";
    } else {
        $errors[] = "✗ db-products.json contiene JSON inválido";
    }
}

// Verificar .env
echo "→ Verificando configuración...\n";
if (file_exists('.env')) {
    $success[] = "✓ .env existe";
    
    $env = file_get_contents('.env');
    if (strpos($env, 'ADMIN_USER') !== false) {
        $success[] = "✓ ADMIN_USER configurado";
    } else {
        $warnings[] = "⚠ ADMIN_USER no configurado en .env";
    }
    
    if (strpos($env, 'ADMIN_PASS_HASH') !== false) {
        $success[] = "✓ ADMIN_PASS_HASH configurado";
    } else {
        $warnings[] = "⚠ ADMIN_PASS_HASH no configurado en .env";
    }
} else {
    $warnings[] = "⚠ .env no existe (se usarán valores por defecto)";
}

// Verificar extensiones PHP
echo "→ Verificando extensiones PHP...\n";
$extensions = ['json', 'session', 'hash'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        $success[] = "✓ Extensión $ext cargada";
    } else {
        $errors[] = "✗ Extensión $ext NO DISPONIBLE";
    }
}

// Mostrar resultados
echo "\n";
echo "════════════════════════════════════════\n";
echo "RESULTADOS:\n";
echo "════════════════════════════════════════\n\n";

if (!empty($success)) {
    echo "ÉXITOS (" . count($success) . "):\n";
    foreach ($success as $msg) {
        echo "  $msg\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "ADVERTENCIAS (" . count($warnings) . "):\n";
    foreach ($warnings as $msg) {
        echo "  $msg\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "ERRORES (" . count($errors) . "):\n";
    foreach ($errors as $msg) {
        echo "  $msg\n";
    }
    echo "\n";
}

// Conclusión
echo "════════════════════════════════════════\n";
if (empty($errors)) {
    echo "✓ INSTALACIÓN CORRECTA\n";
    echo "\nPróximos pasos:\n";
    echo "1. Sube los archivos a tu hosting\n";
    echo "2. Configura el Document Root a /public\n";
    echo "3. Accede a https://tudominio.com/admin\n";
    echo "4. Login: admin / admin123\n";
    echo "\n";
    exit(0);
} else {
    echo "✗ HAY ERRORES QUE CORREGIR\n";
    echo "\nSoluciona los errores listados arriba.\n\n";
    exit(1);
}
