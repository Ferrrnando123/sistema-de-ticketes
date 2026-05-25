<?php
// index.php
// Archivo requerido por Railway para detectar PHP.
// Controlador frontal para React Router.

$rootIndex = __DIR__ . '/index.html';
$distIndex = __DIR__ . '/frontend/dist/index.html';

if (file_exists($rootIndex)) {
    header('Content-Type: text/html; charset=utf-8');
    readfile($rootIndex);
    exit;
} elseif (file_exists($distIndex)) {
    header('Content-Type: text/html; charset=utf-8');
    readfile($distIndex);
    exit;
}

// Si no se encuentra React, mostramos un error descriptivo para debuguear en Railway.
http_response_code(500);
echo "<h1>Error en el despliegue</h1>";
echo "<p>No se encontró el build de React (index.html). Verifica que Railway esté ejecutando 'npm run build' en tu nixpacks.toml.</p>";
exit;

