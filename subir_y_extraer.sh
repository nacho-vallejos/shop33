#!/usr/bin/env bash
set -euo pipefail

### CONFIGURACIÓN ###

# 📌 Proyecto local
LOCAL_DIR="/home/ruler/Downloads/shop33-main/"

# 📌 FTP del nuevo dominio (TUS DATOS DE FEROZO)
FTP_HOST="c2880949.ferozo.com"
FTP_USER="ftp@c2880949.ferozo.com"
FTP_PASS="EtTR3oBcrH"

# 📌 Credenciales HTTP BASIC AUTH (para acceder a descomprimir.php)
# 👉 Si al entrar al sitio por navegador usás OTRO usuario/clave, ponelos acá.
HTTP_USER="ftp@c2880949.ferozo.com"
HTTP_PASS="EtTR3oBcrH"

# 📌 Dominio público (para llamar al PHP que descomprime)
SITE_URL="http://c2880949.ferozo.com"

# 📁 Nombre del ZIP que se va a generar
ZIP_NAME="deploy_$(date +%Y%m%d_%H%M%S).zip"

# 📄 Nombre del script PHP que descomprime en el servidor
UNZIP_SCRIPT="descomprimir.php"

#####################

echo "[*] Entrando al proyecto: $LOCAL_DIR"
cd "$LOCAL_DIR"

echo "[*] Generando ZIP: $ZIP_NAME"
zip -r "$ZIP_NAME" . \
  -x "*.git*" "*node_modules*" "*.DS_Store" "deploy_*.zip" ".venv/*" "shop33.zip"

echo "[*] Subiendo por FTP a $FTP_HOST..."

lftp -u "$FTP_USER","$FTP_PASS" "$FTP_HOST" <<EOF
set ftp:ssl-allow yes
# Si al conectarte ves una carpeta public_html y NO estás dentro, descomentá esta línea:
# cd public_html
put "$ZIP_NAME"
put "$UNZIP_SCRIPT"
bye
EOF

echo "[*] Llamando al descompresor en el servidor (con HTTP Basic Auth)..."

HTTP_CODE=$(curl -s -o /tmp/deploy_unzip.log -w "%{http_code}" \
  -u "$HTTP_USER:$HTTP_PASS" \
  "$SITE_URL/$UNZIP_SCRIPT?file=$ZIP_NAME")

echo "[*] Código HTTP: $HTTP_CODE"

if [ "$HTTP_CODE" != "200" ]; then
  echo "[!] Error llamando al descompresor. Revisá /tmp/deploy_unzip.log para ver la respuesta completa."
  exit 1
fi

echo "[*] OK - ZIP descomprimido en el servidor."

