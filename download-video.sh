#!/bin/bash

# Script para descargar un video de ejemplo de skater
# Este video es de dominio público desde Pexels

echo "🛹 Descargando video de skater de ejemplo..."

cd /workspaces/shop33/public/assets/videos

# Video de skateboarding de Pexels (ejemplo gratuito)
# Este es un video de dominio público que puedes usar
curl -L -o skater.mp4 "https://videos.pexels.com/video-files/5607087/5607087-uhd_2560_1440_25fps.mp4"

if [ -f "skater.mp4" ]; then
    echo "✅ Video descargado exitosamente!"
    echo "📁 Ubicación: /workspaces/shop33/public/assets/videos/skater.mp4"
    echo "🔍 Tamaño: $(du -h skater.mp4 | cut -f1)"
    echo ""
    echo "🚀 Reinicia el servidor y recarga http://localhost:3000 para ver el video de fondo"
else
    echo "❌ Error al descargar el video"
    echo "Por favor, descarga manualmente un video y guárdalo como skater.mp4"
fi
