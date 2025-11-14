# 🛹 SHOP33 - Backend Node.js

## 🚀 Inicio Rápido

### 1. Instalar Dependencias

```bash
npm install
```

### 2. Configurar Variables de Entorno

Copia `.env.example` a `.env`:

```bash
cp .env.example .env
```

### 3. Generar Hash de Contraseña

```bash
node -e "console.log(require('crypto').createHash('sha256').update('TU_CONTRASEÑA').digest('hex'))"
```

Copia el hash generado y pégalo en `.env`:

```
ADMIN_PASS_HASH=tu_hash_aqui
```

### 4. Iniciar Servidor

```bash
npm start
```

El servidor estará disponible en: **http://localhost:3000**

---

## 📋 Comandos Disponibles

| Comando         | Descripción                                         |
| --------------- | --------------------------------------------------- |
| `npm start`     | Inicia el servidor en modo producción               |
| `npm run dev`   | Inicia el servidor en modo desarrollo (con nodemon) |
| `npm run setup` | Instala dependencias                                |

---

## 🔌 API Endpoints

### Públicos (Sin autenticación)

#### Obtener Productos

```http
GET /api/products
```

**Query Parameters:**

- `category` - Filtrar por categoría
- `brand` - Filtrar por marca
- `size` - Filtrar por talle
- `q` - Búsqueda por texto
- `page` - Número de página (default: 1)
- `limit` - Productos por página (default: 100)

**Ejemplo:**

```bash
curl http://localhost:3000/api/products?category=Zapatillas&brand=Vans
```

#### Obtener Producto por ID

```http
GET /api/products/:id
```

**Ejemplo:**

```bash
curl http://localhost:3000/api/products/123
```

---

### Admin (Requieren autenticación)

#### Login

```http
POST /api/admin/login
Content-Type: application/json

{
  "username": "admin",
  "password": "tu_contraseña"
}
```

**Respuesta:**

```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

#### Listar Productos (Admin)

```http
GET /api/admin/products
Authorization: Bearer <token>
```

#### Crear Producto

```http
POST /api/admin/product
Authorization: Bearer <token>
Content-Type: multipart/form-data

name: "Nombre del producto"
category: "Zapatillas"
brand: "Vans"
price: 8999
stock: 10
sizes: "38,39,40,41"
description: "Descripción..."
images: [archivo1.jpg, archivo2.jpg]
```

#### Actualizar Producto

```http
PUT /api/admin/product/:id
Authorization: Bearer <token>
Content-Type: multipart/form-data
```

#### Eliminar Producto

```http
DELETE /api/admin/product/:id
Authorization: Bearer <token>
```

---

## 📁 Estructura del Proyecto

```
shop33-main/
├── server/
│   └── server.js          ← Servidor Express
├── db/
│   └── products.json      ← Base de datos JSON
├── uploads/               ← Imágenes subidas
├── admin/                 ← Panel de administración
│   ├── login.html
│   └── dashboard.html
├── assets/                ← Assets estáticos
├── index.html             ← Frontend principal
├── script.js              ← JavaScript del frontend
├── styles.css             ← Estilos
├── .env                   ← Variables de entorno
└── package.json
```

---

## 🔒 Seguridad

### Autenticación

- El sistema usa JWT (JSON Web Tokens) para autenticación
- Los tokens expiran en 8 horas
- Las contraseñas se hashean con SHA256

### Variables de Entorno

```env
PORT=3000
ADMIN_USER=admin
ADMIN_PASS_HASH=tu_hash_sha256
JWT_SECRET=clave_secreta_aleatoria
```

---

## 🗄️ Base de Datos

El sistema usa un archivo JSON (`db/products.json`) como base de datos.

**Estructura de un producto:**

```json
{
  "id": "uuid-v4",
  "name": "Producto",
  "category": "Zapatillas",
  "brand": "Marca",
  "price": 8999,
  "stock": 10,
  "sizes": ["38", "39", "40"],
  "images": ["/uploads/imagen.jpg"],
  "description": "Descripción del producto",
  "createdAt": "2025-11-11T00:00:00.000Z"
}
```

---

## 📤 Subida de Archivos

- **Formato:** Solo imágenes (jpg, png, webp, gif)
- **Tamaño máximo:** 5MB por archivo
- **Límite:** 6 imágenes por producto
- **Carpeta:** `uploads/`

---

## 🐛 Solución de Problemas

### Error: "Admin password not configured"

Asegúrate de tener el archivo `.env` con `ADMIN_PASS_HASH` configurado.

### Error: "Cannot find module"

Ejecuta `npm install` para instalar las dependencias.

### Puerto en uso

Cambia el puerto en `.env`:

```env
PORT=3001
```

### Base de datos vacía

El archivo `db/products.json` se crea automáticamente. Si quieres datos de ejemplo, copia desde `products.json` de la raíz.

---

## 🔄 Actualizar Productos

### Desde el Panel Admin

1. Accede a http://localhost:3000/admin
2. Ingresa con tu usuario y contraseña
3. Usa el dashboard para agregar/editar/eliminar productos

### Manualmente

Edita directamente `db/products.json` y reinicia el servidor.

---

## 📝 Logs

El servidor registra todas las peticiones en consola:

```
2025-11-11T12:00:00.000Z - GET /api/products
2025-11-11T12:00:01.000Z - POST /api/admin/login
```

---

## ⚡ Modo Desarrollo

Para desarrollo con auto-reload:

```bash
npm run dev
```

Usa nodemon que reinicia automáticamente el servidor cuando detecta cambios.

---

## 🌐 Despliegue

Para producción, considera usar:

- **Heroku** - Soporte nativo para Node.js
- **Railway** - Deploy con Git
- **DigitalOcean** - VPS con Node.js
- **Vercel** - Requiere configuración serverless

⚠️ **Nota:** DonWeb no soporta Node.js directamente. Necesitas un VPS o servicio con soporte Node.js.

---

## 📞 Soporte

Para problemas o preguntas:

1. Revisa los logs del servidor
2. Verifica que `.env` esté configurado correctamente
3. Asegúrate de que todas las dependencias estén instaladas
