<?php
// index.php
// Este archivo es requerido por Railway (Nixpacks) para detectar que es un proyecto PHP
// y generar el plan de construcción correcto con Caddy + PHP-FPM.
// También actúa como controlador frontal para las rutas de React Router.

// Si existe el archivo index.html (React compilado), lo servimos
if (file_exists(__DIR__ . '/index.html')) {
    header('Content-Type: text/html; charset=utf-8');
    readfile(__DIR__ . '/index.html');
    exit;
}

// Si no existe (por ejemplo, antes de compilar), delegamos a api.php
require_once __DIR__ . '/api.php';
