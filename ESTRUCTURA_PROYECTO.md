# 📁 Estructura del Proyecto SHOP33

```
shop33-main/
├── 📄 index.html              # Página principal (catálogo)
├── 📄 product.html            # Página de detalle de producto
├── 📄 script.js               # JavaScript principal
├── 📄 styles.css              # Estilos globales
├── 📄 .htaccess               # Configuración Apache (hosting)
├── 📄 .env                    # Variables de entorno
├── 📄 .env.example            # Ejemplo de configuración
├── 📄 package.json            # Dependencias Node.js
├── 📄 products.json           # Base de datos estática (backup)
│
├── 📁 admin/                  # Panel de administración
│   ├── login.html             # Login admin
│   └── dashboard.html         # Dashboard admin
│
├── 📁 server/                 # Backend Node.js
│   └── server.js              # Servidor Express
│
├── 📁 db/                     # Base de datos JSON
│   └── products.json          # Productos
│
├── 📁 uploads/                # Imágenes subidas
│   └── (archivos de productos)
│
├── 📁 assets/                 # Assets estáticos
│   ├── images/                # Imágenes del sitio
│   └── videos/                # Videos de fondo
│
└── 📁 docs/                   # Documentación
    ├── GUIA_COMPLETA.md
    ├── SERVER_README.md
    └── README_DEPLOY.md
```

## 🔑 Archivos Principales

### Frontend (Raíz)

- `index.html` - Catálogo de productos
- `product.html` - Vista individual de producto
- `script.js` - Lógica del frontend
- `styles.css` - Estilos CSS

### Admin

- `admin/login.html` - Autenticación
- `admin/dashboard.html` - Gestión de productos

### Backend

- `server/server.js` - API REST con Express
- `db/products.json` - Base de datos
- `.env` - Configuración del servidor

## 📦 Rutas de Assets

Todos los assets se referencian desde la raíz:

- Estilos: `./styles.css`
- Scripts: `./script.js`
- Imágenes: `./assets/images/...`
- Videos: `./assets/videos/...`
- Admin: `./admin/...`

## ⚙️ Configuración

El servidor sirve archivos estáticos desde la raíz del proyecto, permitiendo que DonWeb o cualquier hosting estático lea correctamente todos los archivos.

## 🚀 Para DonWeb (Hosting Estático)

**IMPORTANTE:** DonWeb no soporta Node.js. Para subir a DonWeb necesitas:

1. **Solo archivos frontend:**

   - index.html
   - product.html
   - script.js
   - styles.css
   - admin/ (sin funcionalidad)
   - assets/
   - .htaccess

2. **Base de datos estática:**

   - Usa `products.json` en la raíz
   - Modifica `script.js` para leer desde ese archivo JSON estático

3. **Sin panel admin funcional:**
   - El panel admin requiere backend Node.js
   - En DonWeb solo podrás ver la tienda, no administrarla

## 🔥 Para servidor con Node.js

Si quieres usar el backend completo:

- **Heroku**, **Railway**, **DigitalOcean**, **AWS**, **Google Cloud**
- Todos los archivos incluyendo `server/` y `db/`
