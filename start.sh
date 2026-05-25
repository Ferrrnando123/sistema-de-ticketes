#!/bin/bash
echo "--- Iniciando PHP-FPM ---"
php-fpm -D
echo "--- Iniciando Caddy ---"
caddy run --config /app/Caddyfile --adapter caddyfile
