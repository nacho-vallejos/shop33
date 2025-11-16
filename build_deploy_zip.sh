#!/usr/bin/env bash
# SHOP33 - Crear ZIP de deploy para hosting compartido (DonWeb)

set -euo pipefail

# Directorio de salida
OUTPUT_DIR="${HOME}/Downloads"
OUTPUT_ZIP="${OUTPUT_DIR}/shop33-deploy.zip"

# Crear directorio de salida si no existe
mkdir -p "${OUTPUT_DIR}"

# Borrar ZIP anterior si existe
if [[ -f "${OUTPUT_ZIP}" ]]; then
  echo "Eliminando ZIP anterior: ${OUTPUT_ZIP}"
  rm -f "${OUTPUT_ZIP}"
fi

echo "Creando ZIP de deploy en: ${OUTPUT_ZIP}"
echo

# Usamos zip desde la raíz del proyecto (.)
# -r: recursivo
# -q: silencioso (menos ruido)
# . : incluir todo el contenido de la carpeta actual
# Exclusiones según reglas
zip -r -q "${OUTPUT_ZIP}" . \
  -x "node_modules/*" \
  -x ".git/*" \
  -x ".github/*" \
  -x ".vscode/*" \
  -x ".idea/*" \
  -x "tests/*" \
  -x "__tests__/*" \
  -x "coverage/*" \
  -x "docs/*" \
  -x "tmp/*" \
  -x ".cache/*" \
  -x "*.log" \
  -x "*.md"

echo "ZIP creado correctamente."

# Mostrar tamaño final del ZIP
echo
echo "Tamaño del ZIP generado:"
du -h "${OUTPUT_ZIP}"

echo
echo "Listo. Archivo de deploy: ${OUTPUT_ZIP}"
