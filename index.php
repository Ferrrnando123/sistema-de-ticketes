<?php
// index.php
// Requerido por Railway para detectar el proyecto como PHP.
// Toda la lógica real está en api.php.
// El servidor Caddy sirve el frontend de React directamente desde frontend/dist/
// y pasa solo /api.php a PHP-FPM.
require_once __DIR__ . '/api.php';

