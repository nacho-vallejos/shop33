#!/bin/bash

# ============================================
# SHOP33 - Script de Inicio Rápido
# ============================================

echo "🛹 SHOP33 - Skate Store E-commerce"
echo "===================================="
echo ""

# Verificar si Node.js está instalado
if ! command -v node &> /dev/null
then
    echo "❌ Error: Node.js no está instalado"
    echo "   Instala Node.js desde: https://nodejs.org/"
    exit 1
fi

echo "✅ Node.js detectado: $(node -v)"
echo ""

# Verificar si las dependencias están instaladas
if [ ! -d "node_modules" ]; then
    echo "📦 Instalando dependencias..."
    npm install
    echo ""
fi

# Verificar si existe el archivo .env
if [ ! -f ".env" ]; then
    echo "⚠️  Advertencia: No se encontró el archivo .env"
    echo "   Copiando desde .env.example..."
    cp .env.example .env
    echo ""
fi

echo "🚀 Iniciando servidor..."
echo ""
echo "📍 El servidor estará disponible en:"
echo "   👉 http://localhost:3000"
echo ""
echo "🔐 Credenciales admin por defecto:"
echo "   Usuario: admin"
echo "   Password: admin123"
echo ""
echo "📄 Documentación disponible en:"
echo "   - README.md (guía principal)"
echo "   - TESTING.md (guía de testing)"
echo "   - VISUAL_GUIDE.md (guía visual)"
echo "   - RESUMEN_EJECUTIVO.md (resumen completo)"
echo ""
echo "⏸️  Para detener el servidor: Ctrl+C"
echo ""
echo "===================================="
echo ""

# Iniciar el servidor
npm start
