<?php
/**
 * router.php - Servidor para Railway con React + PHP.
 * Usado con: php -S 0.0.0.0:$PORT router.php
 *
 * Logica:
 *  1. /api.php -> delega al backend PHP
 *  2. Archivos estaticos de frontend/dist -> se sirven con MIME correcto
 *  3. Todo lo demas -> frontend/dist/index.html (SPA fallback)
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$distDir = __DIR__ . '/frontend/dist';

// 1) API PHP
if ($uri === '/api.php' || str_starts_with($uri, '/api.php?') || str_starts_with($uri, '/api.php/')) {
    chdir(__DIR__);
    require __DIR__ . '/api.php';
    return true;
}

// 2) Estaticos del frontend
$staticFile = $distDir . $uri;
$isStaticRequest = (bool) preg_match('/\.(js|mjs|css|html|json|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot|webp|map)$/i', $uri);

if ($uri !== '/' && $isStaticRequest) {
    if (!file_exists($staticFile) || is_dir($staticFile)) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Archivo estatico no encontrado: ' . $uri;
        return true;
    }

    $ext = strtolower(pathinfo($staticFile, PATHINFO_EXTENSION));
    $mimes = [
        'js' => 'text/javascript; charset=utf-8',
        'mjs' => 'text/javascript; charset=utf-8',
        'css' => 'text/css; charset=utf-8',
        'html' => 'text/html; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'webp' => 'image/webp',
        'map' => 'application/json; charset=utf-8',
    ];

    header('Content-Type: ' . ($mimes[$ext] ?? 'application/octet-stream'));
    header('Content-Length: ' . filesize($staticFile));
    readfile($staticFile);
    return true;
}

// 3) Fallback SPA
$indexFile = $distDir . '/index.html';
if (file_exists($indexFile)) {
    header('Content-Type: text/html; charset=utf-8');
    readfile($indexFile);
} else {
    http_response_code(500);
    echo '<h1>Error: frontend/dist/index.html no encontrado.</h1>';
    echo '<p>El build de React no se genero correctamente durante el deploy.</p>';
}