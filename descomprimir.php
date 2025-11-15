<?php
// descomprimir.php

$filename = isset($_GET['file']) ? $_GET['file'] : null;

if (!$filename) {
    http_response_code(400);
    echo "Falta el parámetro 'file'.";
    exit;
}

$zipPath = __DIR__ . '/' . basename($filename);

if (!file_exists($zipPath)) {
    http_response_code(404);
    echo "No existe el archivo: " . htmlspecialchars($zipPath);
    exit;
}

$zip = new ZipArchive;
if ($zip->open($zipPath) === TRUE) {
    $zip->extractTo(__DIR__);
    $zip->close();
    unlink($zipPath); // borra el ZIP después de extraer
    echo "OK - Descomprimido y ZIP eliminado.";
} else {
    echo "Error al abrir el ZIP.";
}
