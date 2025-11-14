# 🎯 SHOP33 - Conversión a PHP Completada

## ✅ Proyecto Convertido de Node.js a PHP

Tu proyecto SHOP33 ha sido completamente migrado de Node.js/Express a PHP puro, listo para funcionar en hosting compartido como **DonWeb** o **Ferozo**.

---

## 📂 Nueva Estructura PHP

```
shop33-main/
├── public/
│   └── index.php           ← Front Controller (routing principal)
│
├── bootstrap/
│   └── init.php            ← Inicialización (sesiones, paths, config)
│
├── middlewares/
│   └── AuthGuard.php       ← Autenticación y permisos
│
├── routes/
│   ├── api.php             ← API REST para productos
│   └── admin.php           ← Rutas del panel admin
│
├── storage/
│   ├── logs/               ← Logs de la aplicación
│   └── sessions/           ← Sesiones PHP
│
├── index.html              ← Catálogo frontend
├── product.html            ← Detalle de producto
├── dashboard.php           ← Panel admin (requiere auth)
├── login.php               ← Login admin
├── script.js               ← JavaScript frontend
├── styles.css              ← Estilos CSS
├── db-products.json        ← Base de datos JSON
├── .htaccess               ← Configuración Apache
├── .env                    ← Variables de entorno
└── uploads/                ← Imágenes de productos
```

---

## 🚀 Instalación y Configuración

### 1. Requisitos del Servidor

- **PHP 7.4+** (recomendado 8.0+)
- **Apache** con `mod_rewrite` habilitado
- **Permisos de escritura** en carpetas: `storage/`, `uploads/`, `db-products.json`

### 2. Configuración de Permisos

```bash
# En el servidor, ejecuta:
chmod 755 bootstrap/ middlewares/ routes/
chmod 644 bootstrap/*.php middlewares/*.php routes/*.php
chmod 755 storage/ uploads/
chmod 666 db-products.json
chmod 644 .htaccess
```

### 3. Configurar .env

Edita el archivo `.env` en la raíz:

```env
# Admin credentials
ADMIN_USER=admin
ADMIN_PASS_HASH=240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9

# JWT Secret (genera uno nuevo)
JWT_SECRET=tu_secret_key_aleatorio_aqui

# App config
APP_ENV=production
```

**Generar hash de contraseña:**

```bash
php -r "echo hash('sha256', 'tu_nueva_contraseña');"
```

### 4. Verificar mod_rewrite

En tu hosting, asegúrate de que `mod_rewrite` esté activo. El archivo `.htaccess` ya está configurado.

---

## 🔑 Credenciales por Defecto

- **Usuario:** `admin`
- **Contraseña:** `admin123`

⚠️ **IMPORTANTE:** Cambia estas credenciales antes de subir a producción.

---

## 📡 API REST Endpoints

### Públicos (sin autenticación)

```
GET  /api/products              # Listar todos los productos
GET  /api/products?category=X   # Filtrar por categoría
GET  /api/products?brand=X      # Filtrar por marca
GET  /api/products?search=X     # Buscar productos
GET  /api/products/{id}         # Obtener producto específico
GET  /api/brands                # Listar marcas únicas
GET  /api/categories            # Listar categorías únicas
```

### Admin (requiere autenticación)

```
GET    /admin                   # Dashboard admin
GET    /admin/products          # Listar productos (admin)
POST   /admin/product           # Crear producto
POST   /admin/product/{id}      # Actualizar producto (_method=PUT)
POST   /admin/product/{id}      # Eliminar producto (_method=DELETE)
```

---

## 🔐 Sistema de Autenticación

### Login

1. Usuario accede a `/login.php` o `/admin` (redirige a login si no está autenticado)
2. Ingresa credenciales
3. Si son válidas, se crea sesión PHP
4. Redirección al dashboard `/admin`

### Logout

- Acceder a `/logout.php` destruye la sesión y redirige al inicio

### Protección CSRF

- Todos los formularios admin incluyen token CSRF
- Validación automática en métodos POST/PUT/DELETE
- **No se valida en GET** (evita errores 403)

---

## 📋 Rutas y Navegación

### URLs Funcionales

```
/                     → Catálogo de productos (index.html)
/product.html?id=X    → Detalle de producto
/login.php            → Login admin
/admin                → Dashboard admin (requiere auth)
/logout.php           → Cerrar sesión
```

### Redirecciones Automáticas

- `/Admin` → `/admin` (lowercase, 301 redirect)
- `/admin` sin sesión → `/login.php?next=/admin`
- Login exitoso → Redirige a la página solicitada

---

## 🛠️ Funcionalidades Implementadas

### ✅ Frontend

- Catálogo de productos con filtros
- Búsqueda en tiempo real
- Modal de detalles de producto
- Carrito de compras (localStorage)
- Integración WhatsApp

### ✅ Backend PHP

- API REST completa
- Autenticación con sesiones PHP
- CRUD de productos
- Subida de imágenes (hasta 6 por producto)
- Base de datos JSON persistente
- Sistema de logging

### ✅ Panel Admin

- Login seguro con hash SHA256
- Dashboard de productos
- Crear/editar/eliminar productos
- Subida de imágenes
- Protección CSRF
- Modal de confirmación para eliminaciones

### ✅ Seguridad

- Headers de seguridad (X-Frame-Options, X-Content-Type-Options, etc.)
- Validación CSRF en operaciones unsafe
- Sesiones seguras (HttpOnly, SameSite)
- Protección de archivos sensibles vía .htaccess
- Logging de accesos y errores
- Sin exposición de rutas internas

---

## 🐛 Debugging y Logs

### Ver Logs

Los logs se guardan en `storage/logs/app.log`:

```bash
tail -f storage/logs/app.log
```

### Formato de Log

```
[2025-11-11 14:30:00] [INFO] [IP:192.168.1.1] [URI:/api/products] Products loaded: 10
[2025-11-11 14:30:15] [WARN] [IP:192.168.1.1] [URI:/admin] Auth required, redirecting to /login
[2025-11-11 14:30:20] [INFO] [IP:192.168.1.1] [URI:/login.php] User logged in: admin
```

### Activar Modo Debug

En desarrollo, puedes ver errores PHP editando `bootstrap/init.php`:

```php
// Ya está activado por defecto:
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

En producción, desactívalo:

```php
error_reporting(0);
ini_set('display_errors', 0);
```

---

## 🔄 Migración de Datos

### Importar productos desde Node.js

Si tenías productos en el servidor Node.js anterior, copia el archivo:

```bash
cp db/products.json db-products.json
```

El formato JSON es compatible.

---

## 📤 Subir a DonWeb / Hosting Compartido

### 1. Archivos a Subir

Sube **TODOS** estos archivos vía FTP:

```
✅ public/
✅ bootstrap/
✅ middlewares/
✅ routes/
✅ storage/ (vacía, se creará automáticamente)
✅ uploads/
✅ assets/
✅ index.html
✅ product.html
✅ dashboard.php
✅ login.php
✅ script.js
✅ styles.css
✅ db-products.json
✅ .htaccess
✅ .env
```

### 2. Configurar Document Root

En el panel de DonWeb, configura el **Document Root** a:

```
/public_html/public
```

O si no puedes cambiar el root, mueve el contenido de `public/` a la raíz.

### 3. Verificar Permisos

Asegúrate de que estas carpetas tengan permisos 755 y sean escribibles:

- `storage/`
- `storage/logs/`
- `storage/sessions/`
- `uploads/`

Y que `db-products.json` tenga permiso 666 (lectura/escritura).

### 4. Probar la Instalación

1. Accede a tu dominio: `https://tudominio.com`
2. Deberías ver el catálogo
3. Ve a `https://tudominio.com/admin`
4. Loguéate con `admin` / `admin123`
5. Prueba crear un producto

---

## ⚠️ Problemas Comunes

### Error 403 en /admin

**Causa:** Archivo `.htaccess` no está funcionando o mod_rewrite deshabilitado.

**Solución:**

1. Verifica que `.htaccess` existe en la raíz
2. Contacta a DonWeb para habilitar `mod_rewrite`
3. O usa las rutas completas: `/public/index.php?route=admin`

### Error 500 Internal Server

**Causa:** Error de sintaxis PHP o permisos incorrectos.

**Solución:**

1. Revisa `storage/logs/app.log`
2. Verifica permisos de archivos (644 para PHP, 755 para carpetas)
3. Contacta soporte del hosting

### No se guardan productos

**Causa:** `db-products.json` no es escribible.

**Solución:**

```bash
chmod 666 db-products.json
```

### Sesiones no funcionan

**Causa:** Carpeta `storage/sessions/` no existe o no es escribible.

**Solución:**

```bash
mkdir -p storage/sessions
chmod 755 storage/sessions
```

### Imágenes no se suben

**Causa:** Carpeta `uploads/` no es escribible.

**Solución:**

```bash
chmod 755 uploads/
```

---

## 🎨 Personalización

### Cambiar Logo

Reemplaza los archivos en `assets/images/`:

- `logoLuikeBlanco.png`
- `logoShop33Blanco.png`

### Modificar Colores

Edita `styles.css`, busca las variables CSS:

```css
:root {
  --accent-red: #ff0044;
  --accent-orange: #ff6b35;
  --accent-neon: #00ffd5;
  --bg-dark: #0a0a0a;
  --bg-panel: #1a1a1a;
}
```

### Agregar Categorías

Edita `dashboard.php`, busca el select de categorías:

```html
<select name="category" class="form-select">
  <option value="Zapatillas">Zapatillas</option>
  <option value="Tu Nueva Categoría">Tu Nueva Categoría</option>
</select>
```

---

## 📊 Estadísticas y Métricas

El sistema registra:

- ✅ Todas las peticiones HTTP
- ✅ Intentos de login (exitosos y fallidos)
- ✅ Creación/edición/eliminación de productos
- ✅ Errores y warnings

Revisa `storage/logs/app.log` para analytics básicos.

---

## 🔒 Seguridad en Producción

### Checklist Antes de Lanzar

- [ ] Cambiar contraseña de admin en `.env`
- [ ] Generar nuevo `JWT_SECRET` en `.env`
- [ ] Deshabilitar `display_errors` en `bootstrap/init.php`
- [ ] Verificar permisos de archivos (no 777)
- [ ] Configurar HTTPS en el hosting
- [ ] Hacer backup de `db-products.json`
- [ ] Probar todas las funcionalidades

---

## 📞 Soporte

Si tienes problemas:

1. Revisa los logs: `storage/logs/app.log`
2. Verifica permisos de archivos
3. Consulta la sección de problemas comunes
4. Contacta al soporte de tu hosting

---

## ✅ Resumen de la Conversión

### Antes (Node.js)

- ❌ Requería VPS con Node.js
- ❌ No compatible con hosting compartido
- ❌ Más costoso

### Ahora (PHP)

- ✅ Compatible con hosting compartido
- ✅ Funciona en DonWeb, Ferozo, etc.
- ✅ Más económico
- ✅ Mismas funcionalidades
- ✅ Mejor para hostings económicos

---

**¡Tu tienda SHOP33 está lista para funcionar en cualquier hosting PHP! 🚀**

**Credenciales de prueba:**

- URL: `https://tudominio.com/admin`
- User: `admin`
- Pass: `admin123`
