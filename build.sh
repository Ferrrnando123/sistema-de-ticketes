#!/bin/bash
echo "--- Iniciando build del frontend ---"
cd frontend || exit 1
npm install || exit 1
npm run build || exit 1
cd ..
echo "--- Copiando archivos a la raiz ---"
cp -a frontend/dist/. ./
echo "--- Contenido de la raiz despues del build ---"
ls -la
