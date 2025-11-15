#!/usr/bin/env bash
# SHOP33 - Deploy ZIP + Extractor para Donweb (Ferozo)
# Sube un ZIP al public_html y un extract.php, luego extraes desde el navegador.
# Requisitos: zip, lftp

set -euo pipefail

FTP_HOST=${FTP_HOST:-"c2880949.ferozo.com"}
FTP_USER=${FTP_USER:-"ftp@c2880949.ferozo.com"}
FTP_PASS=${FTP_PASS:-"EtTR3oBcrH"}
REMOTE_DIR=${REMOTE_DIR:-"public_html"}
MODE=${MODE:-"php"}  # php|all
AUTO_YES=${AUTO_YES:-0}

# Nombre del ZIP
TS=$(date +%Y%m%d-%H%M%S)
ZIP_NAME=${ZIP_NAME:-"shop33-${TS}.zip"}
LOCAL_DIR=${LOCAL_DIR:-"."}

# Excludes para ZIP
ZIP_EXCLUDES=(
  ".git/*" ".git" ".gitignore"
  ".vscode/*" ".vscode" ".idea/*" ".idea"
  "*.log" "shop33.zip"
)
if [[ "$MODE" == "php" ]]; then
  ZIP_EXCLUDES+=(
    "node_modules/*" "node_modules"
    "server/*" "server"
    "start.sh" "start-server.sh"
    "package.json" "package-lock.json"
  )
fi

# Mostrar resumen
echo "════════════════════════════════════════════════════════════════"
echo "  SHOP33 - Deploy ZIP a Donweb"
echo "  Host:       $FTP_HOST"
echo "  Usuario:    $FTP_USER"
echo "  Remoto:     $REMOTE_DIR"
echo "  ZIP:        $ZIP_NAME"
echo "  Modo:       $MODE"
echo "════════════════════════════════════════════════════════════════"

if [[ "${AUTO_YES}" != "1" ]]; then
  read -rp "¿Crear y subir el ZIP ahora? (y/N) " ans
  [[ "$ans" =~ ^[yY]$ ]] || { echo "Cancelado"; exit 0; }
fi

command -v zip >/dev/null 2>&1 || { echo "[ERROR] Falta 'zip'"; exit 1; }
command -v lftp >/dev/null 2>&1 || { echo "[ERROR] Falta 'lftp'"; exit 1; }

# Construir parámetros -x para zip
ZIP_X_PARAMS=()
for p in "${ZIP_EXCLUDES[@]}"; do
  ZIP_X_PARAMS+=( -x "$p" )
done

# Crear ZIP
( cd "$LOCAL_DIR" && zip -r "../$ZIP_NAME" . "${ZIP_X_PARAMS[@]}" >/dev/null )
echo "✓ ZIP creado: $ZIP_NAME"

# Subir ZIP y extractor
LFTP_CMD=$(cat <<EOF
set ftp:ssl-allow true
set ssl:verify-certificate no
set ftp:passive-mode on
set ftp:prefer-epsv false
set net:max-retries 2
set net:timeout 25
set cmd:fail-exit true
mkdir -p "$REMOTE_DIR"
cd "$REMOTE_DIR"
put -O . "$ZIP_NAME"
put -O . "scripts/extract.php" -o "extract.php"
bye
EOF
)

lftp -u "$FTP_USER","$FTP_PASS" "$FTP_HOST" -e "$LFTP_CMD"
echo "✓ ZIP y extractor subidos a ftp://$FTP_HOST/$REMOTE_DIR"

echo
cat <<TIP
Para extraer en el servidor:
1) Abre en tu navegador: http://TU-DOMINIO/extract.php?zip=$ZIP_NAME
   (reemplaza TU-DOMINIO por tu dominio apuntando a public_html)
2) Verás un OK si se extrajo correctamente; el script se auto-elimina.
TIP
