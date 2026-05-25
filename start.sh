#!/bin/bash
set -e

echo "--- Verificando build del frontend ---"
if [ ! -f "frontend/dist/index.html" ]; then
  echo "--- frontend/dist/index.html no existe. Generando build ---"
  cd frontend
  npm install
  npm run build
  cd ..
else
  echo "--- Build del frontend encontrado ---"
fi

echo "--- Iniciando servidor PHP ---"
exec php -S 0.0.0.0:${PORT:-8080} router.php
