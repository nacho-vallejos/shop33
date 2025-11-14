# ✅ PROYECTO REESTRUCTURADO PARA DONWEB

## 🎯 Cambios Realizados

### 1. Estructura de Archivos

✅ Todos los archivos movidos a la **raíz del proyecto**
✅ Carpeta `public/` **eliminada**
✅ Estructura limpia y lista para hosting

### 2. Archivos en Raíz

```
shop33-main/
├── index.html          ← Catálogo principal
├── product.html        ← Detalle de producto
├── script.js           ← JavaScript
├── styles.css          ← Estilos
├── admin/              ← Panel admin
├── assets/             ← Imágenes y videos
├── server/             ← Backend (solo si tienes Node.js)
└── .htaccess           ← Configuración Apache
```

### 3. Rutas Actualizadas

#### index.html

- ✅ `./styles.css` (antes `/styles.css`)
- ✅ `./script.js` (antes `/script.js`)
- ✅ `./assets/images/...` (antes `/assets/images/...`)
- ✅ `./assets/videos/...` (antes `/assets/videos/...`)
- ✅ `./admin` (antes `/admin`)

#### product.html

- ✅ `./styles.css`
- ✅ Links a `./` (raíz)

#### admin/login.html

- ✅ `../styles.css` (sube un nivel)
- ✅ `../` para volver al inicio

#### admin/dashboard.html

- ✅ `../styles.css`
- ✅ API calls correctos

---

## 🚀 SERVIDOR EN LOCALHOST

### Estado Actual

✅ **Servidor corriendo en:** http://localhost:3000
✅ **Panel admin:** http://localhost:3000/admin
✅ **Credenciales:** admin / admin123

### Probar Localmente

```bash
# El servidor ya está corriendo
# Abre en tu navegador:
http://localhost:3000
```

---

## 📤 SUBIR A DONWEB

### ⚠️ IMPORTANTE: DonWeb NO soporta Node.js

Tienes **2 opciones**:

---

## OPCIÓN 1: Solo Frontend (Sin Panel Admin)

### Archivos a subir a DonWeb:

```
✅ index.html
✅ product.html
✅ script.js
✅ styles.css
✅ .htaccess
✅ assets/ (completa)
✅ products.json (en raíz, como base de datos estática)
❌ server/ (NO subir)
❌ db/ (NO subir)
❌ uploads/ (NO subir)
❌ node_modules/ (NO subir)
❌ .env (NO subir)
```

### Modificar script.js para DonWeb

**Cambiar línea 1 de script.js:**

**Antes (con backend):**

```javascript
const API_URL = window.location.origin;
```

**Después (sin backend):**

```javascript
const API_URL = "";
```

**Cambiar función loadProducts():**

**Antes (con backend):**

```javascript
async function loadProducts() {
  const res = await fetch(`${API_URL}/api/products`);
  const data = await res.json();
  allProducts = data.items || data;
  // ...
}
```

**Después (sin backend - usa products.json):**

```javascript
async function loadProducts() {
  const res = await fetch("./products.json");
  const data = await res.json();
  allProducts = data;
  // ...
}
```

### Crear products.json en raíz

Copia el contenido de `db/products.json` a un nuevo archivo `products.json` en la raíz del proyecto.

### Lo que NO funcionará en DonWeb:

- ❌ Panel de administración
- ❌ Agregar/editar/eliminar productos
- ❌ Subir imágenes
- ❌ Autenticación

### Lo que SÍ funcionará:

- ✅ Ver catálogo de productos
- ✅ Filtros y búsqueda
- ✅ Carrito de compras (localStorage)
- ✅ Enviar pedidos por WhatsApp
- ✅ Diseño responsive

---

## OPCIÓN 2: Backend Completo (Recomendado)

### Usar un VPS con Node.js

**Servicios recomendados:**

1. **Railway.app** (Más fácil)

   - Conecta tu repositorio GitHub
   - Deploy automático
   - Gratis hasta cierto uso

2. **Heroku** (Popular)

   - Plan gratuito disponible
   - Fácil configuración

3. **DigitalOcean** (Profesional)

   - Desde $5/mes
   - Control total

4. **AWS / Google Cloud**
   - Créditos gratuitos iniciales
   - Escalable

### Archivos a subir (TODOS):

```
✅ index.html
✅ product.html
✅ script.js
✅ styles.css
✅ admin/ (completa)
✅ server/ (completa)
✅ db/ (completa)
✅ assets/ (completa)
✅ .htaccess
✅ package.json
✅ .env (configurar en el servidor)
```

### Lo que SÍ funcionará:

- ✅ Todo el sitio completo
- ✅ Panel de administración funcional
- ✅ Agregar/editar/eliminar productos
- ✅ Subir imágenes
- ✅ Base de datos persistente
- ✅ Autenticación segura

---

## 📝 VERIFICACIÓN FINAL

### Antes de subir a DonWeb:

1. ✅ Archivos en la raíz (no en `public/`)
2. ✅ Rutas relativas (`./ ` en lugar de `/`)
3. ✅ `.htaccess` configurado
4. ✅ `products.json` en raíz con datos
5. ✅ `script.js` modificado para leer `products.json`
6. ✅ Sin carpetas `node_modules`, `server`, `db`

### Estructura final para DonWeb:

```
Tu-FTP-DonWeb/
├── index.html
├── product.html
├── script.js
├── styles.css
├── products.json
├── .htaccess
├── assets/
│   ├── images/
│   └── videos/
└── admin/ (opcional, no funcionará)
```

---

## 🧪 PROBAR ANTES DE SUBIR

### Test local sin backend:

1. **Crear `products.json` en raíz:**

   ```bash
   cp db/products.json ./products.json
   ```

2. **Modificar `script.js`:**

   - Cambiar `const API_URL = window.location.origin;`
   - Por: `const API_URL = '';`
   - Cambiar fetch de `/api/products` a `./products.json`

3. **Abrir directamente index.html:**

   - Doble click en `index.html`
   - Debería ver los productos del JSON

4. **Si funciona:** Listo para subir a DonWeb

---

## 🔧 SOLUCIÓN DE PROBLEMAS

### Error 403 en DonWeb

- Verifica que `.htaccess` esté en la raíz
- Verifica permisos de archivos (644 para archivos, 755 para carpetas)

### No carga productos

- Verifica que `products.json` exista en la raíz
- Verifica que `script.js` lea desde `./products.json`
- Abre la consola del navegador (F12) para ver errores

### Imágenes no cargan

- Verifica rutas en `products.json`: `./assets/images/...`
- Verifica que la carpeta `assets/` esté subida

### CSS/JS no carga

- Verifica rutas en `index.html`: `./styles.css` y `./script.js`
- Verifica que los archivos estén en la raíz

---

## ✅ RESUMEN

**Opción DonWeb (Solo Frontend):**

- Rápido y gratis
- Sin panel admin
- Base de datos estática (JSON)
- Perfecto para catálogo simple

**Opción VPS (Backend Completo):**

- Panel admin funcional
- Base de datos dinámica
- Subida de imágenes
- Requiere hosting con Node.js ($$$)

---

## 📞 PRÓXIMOS PASOS

1. **Decidir:** ¿DonWeb (frontend) o VPS (backend completo)?

2. **Si eliges DonWeb:**

   - Modificar `script.js` como se indica arriba
   - Crear `products.json` en raíz
   - Subir solo archivos frontend
   - Probar en DonWeb

3. **Si eliges VPS:**
   - Crear cuenta en Railway/Heroku
   - Conectar repositorio GitHub
   - Configurar variables de entorno
   - Deploy automático

---

**Tu servidor local está corriendo y listo para usar:**
http://localhost:3000
