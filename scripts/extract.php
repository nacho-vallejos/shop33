<?php
// SHOP33 - Extractor ZIP para Donweb
// Uso: acceder via navegador a /extract.php?zip=archivo.zip
// Extrae en el mismo directorio (public_html) y elimina el zip y a sí mismo.

header('Content-Type: text/plain; charset=utf-8');

$zipParam = isset($_GET['zip']) ? basename($_GET['zip']) : '';
if ($zipParam === '') {
    echo "ERROR: falta parámetro ?zip=archivo.zip\n";
    exit(1);
}

$zipPath = __DIR__ . DIRECTORY_SEPARATOR . $zipParam;
if (!is_file($zipPath)) {
    echo "ERROR: no existe el ZIP: {$zipParam}\n";
    exit(1);
}

$za = new ZipArchive();
if ($za->open($zipPath) !== true) {
    echo "ERROR: no se pudo abrir el ZIP\n";
    exit(1);
}

$ok = $za->extractTo(__DIR__);
$za->close();

if (!$ok) {
    echo "ERROR: fallo al extraer\n";
    exit(1);
}

// Limpieza: borrar zip y este script
@unlink($zipPath);
@unlink(__FILE__);

echo "OK: extraído y limpiado.\n";
