<?php
/**
 * router.php — Servidor de desarrollo/producción para Railway
 * Usado con: php -S 0.0.0.0:$PORT router.php
 *
 * Lógica:
 *  1. Peticiones a /api.php → pasa a api.php (backend PHP)
 *  2. Archivos estáticos que existen en frontend/dist → los sirve directamente
 *  3. Todo lo demás → sirve frontend/dist/index.html (React Router)
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// 1. Rutas de la API → delegar a api.php
if ($uri === '/api.php' || str_starts_with($uri, '/api.php?') || str_starts_with($uri, '/api.php/')) {
    // Aseguramos que los require internos de api.php funcionen desde la raíz
    chdir(__DIR__);
    require __DIR__ . '/api.php';
    return true;
}

// 2. Archivos estáticos del frontend (JS, CSS, imágenes, etc.)
$staticFile = __DIR__ . '/frontend/dist' . $uri;
if ($uri !== '/' && file_exists($staticFile) && !is_dir($staticFile)) {
    // Detectar MIME type básico para que el navegador lo interprete bien
    $ext = strtolower(pathinfo($staticFile, PATHINFO_EXTENSION));
    $mimes = [
        'js'    => 'application/javascript',
        'mjs'   => 'application/javascript',
        'css'   => 'text/css',
        'html'  => 'text/html',
        'json'  => 'application/json',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'svg'   => 'image/svg+xml',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'eot'   => 'application/vnd.ms-fontobject',
        'webp'  => 'image/webp',
        'map'   => 'application/json',
    ];
    if (isset($mimes[$ext])) {
        header('Content-Type: ' . $mimes[$ext]);
    }
    return false; // PHP built-in server lo sirve directamente
}

// 3. Todo lo demás → index.html de React (client-side routing)
$indexFile = __DIR__ . '/frontend/dist/index.html';
if (file_exists($indexFile)) {
    header('Content-Type: text/html; charset=utf-8');
    readfile($indexFile);
} else {
    // Si el build de React no existe (error de build), mensaje de error útil
    http_response_code(500);
    echo '<h1>Error: frontend/dist/index.html no encontrado.</h1>';
    echo '<p>El build de React no se generó correctamente durante el deploy.</p>';
}
