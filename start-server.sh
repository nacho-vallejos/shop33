#!/bin/bash

# SHOP33 - Start Script
# Este script inicia el servidor de desarrollo

echo "╔════════════════════════════════════════╗"
echo "║       SHOP33 - STARTING SERVER        ║"
echo "╚════════════════════════════════════════╝"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found!"
    echo "Creating .env from .env.example..."
    cp .env.example .env
    echo "✓ Please edit .env and set your admin password"
    echo ""
fi

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing dependencies..."
    npm install
    echo ""
fi

# Start server
echo "🚀 Starting server..."
npm start
