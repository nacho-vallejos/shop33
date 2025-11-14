# ✅ REESTRUCTURACIÓN COMPLETADA

## 📂 Nueva Estructura (Todo en Raíz)

```
shop33-main/
├── index.html              ← Catálogo principal
├── product.html            ← Detalle de producto
├── login.html              ← Login admin (antes en admin/)
├── dashboard.html          ← Dashboard admin (antes en admin/)
├── script.js               ← JavaScript principal
├── styles.css              ← Estilos globales
├── db-products.json        ← Base de datos (antes db/products.json)
├── .htaccess               ← Config Apache
├── .env                    ← Variables de entorno
│
├── assets/                 ← Imágenes y videos
├── uploads/                ← Imágenes de productos
└── server/                 ← Backend Node.js
    └── server.js
```

## 🔄 Cambios Realizados

### 1. Archivos Movidos

✅ `admin/login.html` → `login.html` (raíz)
✅ `admin/dashboard.html` → `dashboard.html` (raíz)
✅ `db/products.json` → `db-products.json` (raíz)
✅ Carpetas `admin/` y `db/` eliminadas

### 2. Rutas Actualizadas

#### login.html

- ✅ CSS: `../styles.css` → `./styles.css`
- ✅ Link de volver: `../` → `./index.html`
- ✅ Redirect a dashboard: `./dashboard.html`

#### dashboard.html

- ✅ CSS: `../styles.css` → `./styles.css`
- ✅ Redirect a login: `./login.html`

#### index.html

- ✅ Link Admin: `./admin` → `./login.html`

#### server/server.js

- ✅ DB_PATH: `db/products.json` → `db-products.json`
- ✅ Eliminada creación de carpeta `db/`

### 3. Estructura Final

Todos los archivos HTML están en la raíz del proyecto, sin subcarpetas.

## 🚀 Para Iniciar el Servidor

```bash
# Desde la raíz del proyecto
node server/server.js

# O con npm
npm start
```

## 🌐 URLs del Proyecto

- **Tienda:** http://localhost:3000/index.html
- **Admin Login:** http://localhost:3000/login.html
- **Dashboard:** http://localhost:3000/dashboard.html (requiere login)

## 📋 Para DonWeb (Hosting Estático)

### Archivos a subir:

```
✅ index.html
✅ product.html
✅ login.html (no funcionará sin backend)
✅ dashboard.html (no funcionará sin backend)
✅ script.js
✅ styles.css
✅ .htaccess
✅ assets/ (completa)
✅ db-products.json (renombrar a products.json)
```

### ⚠️ Nota Importante

DonWeb NO soporta Node.js, por lo que:

- ❌ No funcionará el login admin
- ❌ No funcionará el dashboard
- ❌ No podrás agregar/editar productos
- ✅ Sí funcionará el catálogo (si modificas script.js para leer products.json)

## 🔧 Modificar para DonWeb (Sin Backend)

Si quieres subir a DonWeb sin backend:

1. **Renombrar base de datos:**

   ```bash
   cp db-products.json products.json
   ```

2. **Modificar script.js:**

   Buscar y cambiar:

   ```javascript
   // Línea ~1
   const API_URL = window.location.origin;
   ```

   Por:

   ```javascript
   const API_URL = "";
   ```

   Y cambiar:

   ```javascript
   // Función loadProducts
   const res = await fetch(`${API_URL}/api/products`);
   ```

   Por:

   ```javascript
   const res = await fetch("./products.json");
   ```

3. **Subir solo archivos frontend:**
   - index.html
   - product.html
   - script.js (modificado)
   - styles.css
   - products.json (renombrado)
   - assets/
   - .htaccess

## ✅ Verificación

- [x] Archivos admin en raíz
- [x] Base de datos en raíz
- [x] Todas las rutas actualizadas
- [x] Server.js apunta a db-products.json
- [x] Sin carpetas admin/ ni db/
- [x] Estructura plana y lista para hosting

---

**Estado:** ✅ Listo para desarrollo local o despliegue
