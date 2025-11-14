# 📦 Guía de Despliegue a DonWeb

## Archivos a Subir

Sube los siguientes archivos y carpetas a la raíz de tu hosting en DonWeb:

```
├── .htaccess          ← Importante para las rutas
├── index.html
├── product.html
├── script.js
├── styles.css
├── products.json      ← Base de datos estática
├── admin/
│   ├── dashboard.html
│   └── login.html
├── assets/
│   ├── images/
│   └── videos/
└── uploads/           ← Carpeta para imágenes de productos
```

## ⚠️ NO subas estos archivos:

- `node_modules/`
- `server/`
- `db/`
- `.env`
- `.venv/`
- `package.json`
- `package-lock.json`

## 🔧 Configuración

### 1. Permisos de Carpetas

Asegúrate de que las siguientes carpetas tengan permisos de escritura (755):

- `uploads/`
- `assets/`

### 2. Archivo .htaccess

El archivo `.htaccess` ya está configurado para:

- Permitir acceso a archivos estáticos
- Redirigir rutas SPA a index.html

### 3. Base de Datos

La página usa `products.json` como base de datos estática.
Para agregar/editar productos, modifica este archivo directamente.

## 📱 Funcionalidades Activas

✅ Catálogo de productos
✅ Filtros por categoría, marca y talle
✅ Búsqueda de productos
✅ Modal de detalles
✅ Carrito de compras (localStorage)
✅ Envío por WhatsApp
✅ Diseño responsive

## ⚠️ Funcionalidades Deshabilitadas (Requieren Backend)

❌ Panel de administración (login/dashboard)
❌ Subida de imágenes desde el panel
❌ Edición de productos en tiempo real
❌ Gestión de stock automática

## 🔗 Configuración de WhatsApp

Edita el número de WhatsApp en `script.js`:

```javascript
const phoneNumber = "5493417214862"; // Cambia por tu número
```

## ✅ Verificación Post-Deploy

1. Verifica que `index.html` cargue correctamente
2. Comprueba que los productos se vean en el catálogo
3. Prueba el carrito y el envío por WhatsApp
4. Verifica que las imágenes carguen correctamente
5. Prueba los filtros y búsqueda

## 🐛 Solución de Problemas

### Error 403 Forbidden

- Verifica permisos de archivos (644) y carpetas (755)
- Asegúrate de que `index.html` esté en la raíz

### No cargan los productos

- Verifica que `products.json` esté en la raíz
- Revisa la consola del navegador (F12) para errores

### CSS no carga

- Verifica que `styles.css` esté en la raíz
- Limpia la caché del navegador

### Imágenes rotas

- Verifica que la carpeta `assets/` y `uploads/` existan
- Comprueba las rutas en `products.json`
