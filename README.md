# 🛹 SHOP33 — Skate Store E-commerce

![Shop33 Banner](https://via.placeholder.com/1200x300/0a0a0b/ff0044?text=SHOP33+SKATE+STORE)

E-commerce multimarca estilo skater/urbano con frontend moderno y backend Node.js/Express completo.

## 🎨 Características

### Frontend
- ✅ **Diseño skater urbano** con dark theme y acentos neón
- ✅ **Hero section** con video de skaters
- ✅ **Catálogo dinámico** con filtros por categoría, marca, talle y búsqueda
- ✅ **Cards de productos** con hover effects y animaciones suaves
- ✅ **Modal de producto** con galería de imágenes y selector de talles
- ✅ **Carrito simulado** con localStorage
- ✅ **Responsive design** (desktop, tablet, mobile)
- ✅ **Loader animado** estilo skateboard
- ✅ **Tipografía urbana** (Bebas Neue + Inter)

### Backend
- ✅ **API REST completa** con Express
- ✅ **Autenticación JWT** para admin
- ✅ **CRUD de productos** (crear, leer, actualizar, eliminar)
- ✅ **Subida de imágenes** con Multer
- ✅ **Base de datos JSON** (fácil migración a MongoDB)
- ✅ **Validaciones** de datos
- ✅ **CORS habilitado**

### Panel Admin
- ✅ **Login seguro** con SHA256 + JWT
- ✅ **Dashboard moderno** para gestión de productos
- ✅ **Formulario completo** de carga (nombre, marca, categoría, precio, stock, talles, descripción, imágenes)
- ✅ **Listado de productos** con opciones de eliminar
- ✅ **Notificaciones visuales**

## 📂 Estructura del Proyecto

```
shop33/
├── server/
│   └── server.js          # Backend Express con API REST
├── public/
│   ├── index.html         # Catálogo principal
│   ├── product.html       # Vista individual de producto
│   ├── styles.css         # Estilos skater completos
│   ├── script.js          # Lógica frontend + API calls
│   └── admin/
│       ├── login.html     # Login administrador
│       └── dashboard.html # Panel de gestión
├── db/
│   └── products.json      # Base de datos JSON
├── uploads/               # Carpeta para imágenes subidas
├── .env                   # Variables de entorno (contraseñas, JWT)
├── .env.example           # Ejemplo de configuración
├── package.json           # Dependencias npm
└── README.md              # Este archivo
```

## 🚀 Instalación y Uso

### 1. Clonar o descargar el proyecto

```bash
git clone https://github.com/nacho-vallejos/shop33.git
cd shop33
```

### 2. Instalar dependencias

```bash
npm install
```

### 3. Configurar variables de entorno

El archivo `.env` ya está configurado con valores por defecto para testing:

```env
PORT=3000
ADMIN_USER=admin
ADMIN_PASS_HASH=240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9
JWT_SECRET=shop33_secret_key_change_in_production_2025
```

**Credenciales de admin por defecto:**
- Usuario: `admin`
- Contraseña: `admin123`

**⚠️ IMPORTANTE:** En producción, cambia el `JWT_SECRET` y genera un nuevo hash de contraseña:

```bash
node -e "console.log(require('crypto').createHash('sha256').update('tu_password').digest('hex'))"
```

### 4. Iniciar el servidor

```bash
npm start
```

El servidor estará disponible en: **http://localhost:3000**

Para desarrollo con auto-restart:

```bash
npm run dev
```

## 🌐 Rutas de la Aplicación

### Frontend (Público)
- **`/`** → Catálogo principal con filtros
- **`/product.html?id=<ID>`** → Vista individual de producto
- **`/admin`** → Login del administrador

### Admin (Protegido)
- **`/admin/dashboard.html`** → Panel de gestión (requiere login)

### API Endpoints

#### Públicos
```
GET  /api/products              # Listar todos los productos
GET  /api/products/:id          # Obtener producto por ID
```

#### Administrador (requieren JWT token)
```
POST   /api/admin/login         # Login (devuelve JWT)
GET    /api/admin/products      # Listar productos (admin)
POST   /api/admin/product       # Crear producto
PUT    /api/admin/product/:id   # Editar producto
DELETE /api/admin/product/:id   # Eliminar producto
```

## 🎯 Uso del Panel Admin

### 1. Acceder al login
Ve a `http://localhost:3000/admin` y usa las credenciales:
- Usuario: `admin`
- Contraseña: `admin123`

### 2. Subir productos
En el dashboard, completa el formulario con:
- **Nombre**: Nombre del producto (requerido)
- **Marca**: Vans, Supreme, Thrasher, etc.
- **Categoría**: Selecciona del dropdown (requerido)
- **Precio**: En pesos (requerido)
- **Stock**: Cantidad disponible (requerido)
- **Talles**: Separados por coma (ej: S, M, L, XL)
- **Descripción**: Descripción del producto
- **Imágenes**: Hasta 6 imágenes (jpg, png)

### 3. Gestionar productos
- Ver listado completo de productos cargados
- Eliminar productos con un click
- Stock y precios visibles en tiempo real

## 🛠️ Tecnologías Utilizadas

### Frontend
- **HTML5** + **CSS3** (variables CSS, Grid, Flexbox)
- **JavaScript** (ES6+, Fetch API, async/await)
- **Google Fonts** (Bebas Neue, Inter)
- Animaciones CSS puras (sin librerías)

### Backend
- **Node.js** v18+
- **Express** v4 (servidor web)
- **Multer** (subida de archivos)
- **JWT** (autenticación)
- **dotenv** (variables de entorno)
- **CORS** (cross-origin requests)
- **uuid** (IDs únicos)

### Base de Datos
- **JSON file** (migración fácil a MongoDB/SQLite)

## 📱 Responsive Design

La aplicación está optimizada para:
- 💻 **Desktop** (1200px+)
- 📱 **Tablet** (768px - 1024px)
- 📱 **Mobile** (< 768px)

## 🎨 Paleta de Colores

```css
--bg-dark: #0a0a0b           /* Fondo principal */
--bg-panel: #111113          /* Paneles y cards */
--accent-red: #ff0044        /* Rojo principal (Vans/Supreme) */
--accent-neon: #00ffd5       /* Verde neón (detalles) */
--accent-orange: #ff6b35     /* Naranja hover */
--text-primary: #ffffff      /* Texto principal */
--text-secondary: #b0b0b0    /* Texto secundario */
```

## 🔐 Seguridad

- ✅ Contraseñas hasheadas con SHA256
- ✅ JWT con expiración de 8 horas
- ✅ Validación de tokens en rutas admin
- ✅ CORS configurado
- ⚠️ **Nota**: Este es un proyecto demo. Para producción, implementa:
  - Rate limiting
  - Sanitización de inputs
  - HTTPS obligatorio
  - Validación de tipos de archivo
  - Límite de tamaño de archivos

## 📦 Dependencias Principales

```json
{
  "express": "^4.18.2",
  "jsonwebtoken": "^9.0.0",
  "multer": "^1.4.5-lts.1",
  "cors": "^2.8.5",
  "dotenv": "^16.3.1",
  "uuid": "^9.0.0"
}
```

## 🚧 Mejoras Futuras

- [ ] Migrar a MongoDB/PostgreSQL
- [ ] Implementar carrito funcional con checkout
- [ ] Integración con pasarela de pagos (MercadoPago, Stripe)
- [ ] Sistema de usuarios (registro, login, perfil)
- [ ] Wishlist (lista de deseos)
- [ ] Sistema de comentarios/reviews
- [ ] Panel de estadísticas (ventas, stock, categorías)
- [ ] Filtros avanzados (rango de precio, ordenamiento)
- [ ] Paginación del catálogo
- [ ] Envío de emails (confirmación de orden)
- [ ] Tests unitarios e integración

## 📄 Licencia

MIT License - Proyecto educativo y de demostración.

## 👨‍💻 Autor

**Nacho Vallejos** - [GitHub](https://github.com/nacho-vallejos)

---

## 🎉 ¡Listo para usar!

```bash
npm install
npm start
```

Abre tu navegador en **http://localhost:3000** y disfruta tu tienda skater 🛹

Para acceder al admin: **http://localhost:3000/admin**
- Usuario: `admin`
- Password: `admin123`

