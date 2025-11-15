#!/usr/bin/env bash
# SHOP33 - Deploy a Donweb (Ferozo) via FTP
# Requisitos: lftp instalado (sudo dnf install -y lftp | sudo apt-get install -y lftp)

set -euo pipefail

# ==============================
# Configuración (se puede sobreescribir por variables de entorno)
# ==============================
FTP_HOST=${FTP_HOST:-"c2880949.ferozo.com"}
FTP_USER=${FTP_USER:-"ftp@c2880949.ferozo.com"}
FTP_PASS=${FTP_PASS:-"EtTR3oBcrH"}
# Directorio remoto de publicación: normalmente "public_html" en Donweb
REMOTE_DIR=${REMOTE_DIR:-"public_html"}
# Directorio local a subir; por defecto el repo actual
LOCAL_DIR=${LOCAL_DIR:-"."}
# Paralelismo de subida
PARALLEL=${PARALLEL:-4}

# Patrones a excluir del deploy
EXCLUDES=(
  ".git"
  ".gitignore"
  "node_modules"
  ".vscode"
  ".idea"
  "*.log"
  "shop33.zip"
)

# ==============================
# Comprobaciones
# ==============================
if ! command -v lftp >/dev/null 2>&1; then
  echo "[ERROR] lftp no está instalado."
  echo "Instálalo y reintenta:"
  echo "  Fedora:   sudo dnf install -y lftp"
  echo "  Ubuntu/Debian: sudo apt-get install -y lftp"
  exit 1
fi

# Construir flags de exclusión para lftp mirror
EXC=""
for p in "${EXCLUDES[@]}"; do
  EXC+=" --exclude-glob \"$p\""
done

# Mostrar resumen
echo "════════════════════════════════════════════════════════════════"
echo "  SHOP33 - Deploy a Donweb (Ferozo)"
echo "  Host:       $FTP_HOST"
echo "  Usuario:    $FTP_USER"
echo "  Remoto:     $REMOTE_DIR"
# No mostramos la contraseña por seguridad
echo "  Local:      $LOCAL_DIR"
echo "  Paralelo:   $PARALLEL"
echo "════════════════════════════════════════════════════════════════"

# Confirmación (omitible con AUTO_YES=1)
if [[ "${AUTO_YES:-0}" != "1" ]]; then
  read -rp "¿Continuar con el deploy? (y/N) " ans
  if [[ ! "$ans" =~ ^[yY]$ ]]; then
    echo "Cancelado."
    exit 0
  fi
fi

# Comando de lftp a ejecutar
LFTP_CMD=$(cat <<EOF
set ftp:ssl-allow true
set ssl:verify-certificate no
set net:max-retries 2
set net:timeout 25
set cmd:fail-exit true
mkdir -p "$REMOTE_DIR"
cd "$REMOTE_DIR"
# Subida recursiva (mirror reverse) con reintentos, reanudación y paralelo
mirror -R --continue --only-newer --parallel=$PARALLEL --verbose=1 $EXC "$LOCAL_DIR" .
bye
EOF
)

# Ejecutar
# shellcheck disable=SC2086
lftp -u "$FTP_USER","$FTP_PASS" "$FTP_HOST" -e "$LFTP_CMD"

echo "✅ Deploy finalizado: ftp://$FTP_HOST/$REMOTE_DIR"
