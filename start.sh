#!/bin/bash
set -e
echo "--- Iniciando PHP-FPM en background ---"
php-fpm -D
echo "--- PHP-FPM iniciado. Iniciando Caddy ---"
exec caddy run --config /app/Caddyfile --adapter caddyfile
