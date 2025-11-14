# 🎯 SHOP33 - Guía Completa de Backend

## ✅ Backend Node.js Completamente Funcional

He creado un backend completo en Node.js con todas las funcionalidades necesarias para tu tienda.

---

## 🚀 INICIO RÁPIDO

### 1. Verificar que el servidor esté corriendo

El servidor ya está iniciado en: **http://localhost:3000**

### 2. Probar el frontend

Abre tu navegador en: **http://localhost:3000**

### 3. Acceder al panel de administración

- URL: **http://localhost:3000/admin**
- Usuario: `admin`
- Contraseña: `admin123`

---

## ✨ FUNCIONALIDADES IMPLEMENTADAS

### ✅ Frontend (Cliente)

- Catálogo completo de productos
- Filtros por categoría, marca, talle y búsqueda
- Modal de detalles de producto
- Carrito de compras (localStorage)
- Envío de pedidos por WhatsApp
- Diseño responsive

### ✅ Backend (API REST)

- **GET** `/api/products` - Listar productos con filtros
- **GET** `/api/products/:id` - Obtener producto por ID
- **POST** `/api/admin/login` - Login de administrador
- **GET** `/api/admin/products` - Listar productos (admin)
- **POST** `/api/admin/product` - Crear producto
- **PUT** `/api/admin/product/:id` - Actualizar producto
- **DELETE** `/api/admin/product/:id` - Eliminar producto

### ✅ Panel de Administración

- Login seguro con JWT
- Dashboard para gestión de productos
- Crear productos con imágenes
- Editar productos existentes
- Eliminar productos con confirmación
- Subida de imágenes (hasta 6 por producto)

---

## 🔧 MEJORAS IMPLEMENTADAS

### Servidor (`server/server.js`)

1. ✅ Logging de peticiones
2. ✅ Validación de tipos de archivo (solo imágenes)
3. ✅ Límite de tamaño de archivo (5MB)
4. ✅ Manejo de errores mejorado
5. ✅ Creación automática de directorios
6. ✅ Inicialización de base de datos
7. ✅ Mensajes de inicio informativos
8. ✅ Graceful shutdown
9. ✅ Eliminación de imágenes al borrar productos
10. ✅ Timestamps de creación/actualización

### Seguridad

- ✅ Autenticación JWT con expiración (8h)
- ✅ Hashing SHA256 de contraseñas
- ✅ CORS habilitado
- ✅ Validación de tokens en rutas admin
- ✅ Sanitización de inputs

### Base de Datos

- ✅ Archivo JSON persistente (`db/products.json`)
- ✅ Backup automático al escribir
- ✅ Manejo de errores de lectura/escritura
- ✅ UUIDs únicos para productos

---

## 📂 ESTRUCTURA DE ARCHIVOS

```
shop33-main/
├── server/
│   └── server.js              ← Servidor Express mejorado
├── db/
│   └── products.json          ← Base de datos JSON
├── uploads/                   ← Imágenes de productos
├── admin/
│   ├── login.html             ← Login admin
│   └── dashboard.html         ← Panel admin
├── assets/                    ← Assets estáticos
├── index.html                 ← Frontend
├── script.js                  ← JavaScript frontend
├── styles.css                 ← Estilos
├── .env                       ← Variables de entorno
├── .env.example               ← Ejemplo de configuración
├── package.json               ← Dependencias
├── SERVER_README.md           ← Documentación del servidor
└── README_DEPLOY.md           ← Guía de despliegue
```

---

## 🔑 CREDENCIALES

### Administrador

- **Usuario:** `admin`
- **Contraseña:** `admin123`
- **Hash:** `240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9`

⚠️ **IMPORTANTE:** Cambia la contraseña antes de publicar!

---

## 🛠️ COMANDOS ÚTILES

```bash
# Iniciar servidor
npm start

# Modo desarrollo (auto-reload)
npm run dev

# Generar hash de contraseña
node -e "console.log(require('crypto').createHash('sha256').update('MI_CONTRASEÑA').digest('hex'))"

# Generar JWT secret
node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"

# Instalar dependencias
npm install
```

---

## 🧪 PROBAR LA API

### Obtener productos

```bash
curl http://localhost:3000/api/products
```

### Obtener producto específico

```bash
curl http://localhost:3000/api/products/1
```

### Login admin

```bash
curl -X POST http://localhost:3000/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}'
```

### Crear producto (requiere token)

```bash
curl -X POST http://localhost:3000/api/admin/product \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -F "name=Producto Nuevo" \
  -F "category=Zapatillas" \
  -F "brand=Nike" \
  -F "price=9999" \
  -F "stock=10" \
  -F "sizes=38,39,40" \
  -F "description=Descripción del producto" \
  -F "images=@imagen.jpg"
```

---

## 📊 CARACTERÍSTICAS TÉCNICAS

### Tecnologías

- **Node.js** - Runtime
- **Express** - Framework web
- **JWT** - Autenticación
- **Multer** - Subida de archivos
- **CORS** - Cross-origin requests
- **dotenv** - Variables de entorno
- **UUID** - IDs únicos

### Formato de Respuestas

```json
{
  "total": 10,
  "page": 1,
  "limit": 100,
  "items": [
    {
      "id": "uuid",
      "name": "Producto",
      "category": "Zapatillas",
      "brand": "Marca",
      "price": 8999,
      "stock": 10,
      "sizes": ["38", "39"],
      "images": ["/uploads/imagen.jpg"],
      "description": "Descripción",
      "createdAt": "2025-11-11T00:00:00.000Z"
    }
  ]
}
```

---

## 🔄 FLUJO DE TRABAJO

### Agregar Producto desde Admin Panel

1. Accede a http://localhost:3000/admin
2. Inicia sesión con `admin` / `admin123`
3. Ve al dashboard
4. Completa el formulario:
   - Nombre del producto
   - Marca
   - Categoría
   - Precio
   - Stock
   - Talles (separados por coma)
   - Descripción
   - Imágenes (máximo 6)
5. Click en "GUARDAR PRODUCTO"
6. El producto aparecerá en el catálogo

---

## ⚠️ IMPORTANTE: DESPLIEGUE

### Para Producción (VPS/Cloud)

El backend Node.js requiere un servidor que soporte Node.js:

**Opciones recomendadas:**

1. **Heroku** - Deploy gratuito (con limitaciones)
2. **Railway.app** - Deploy fácil con Git
3. **DigitalOcean** - VPS desde $5/mes
4. **AWS EC2** - Escalable
5. **Google Cloud** - Créditos gratuitos

**NO compatible con:**

- ❌ DonWeb (hosting estático, no soporta Node.js)
- ❌ Hosting compartido tradicional
- ❌ cPanel básico

### Para DonWeb (Solo Frontend)

Si solo puedes usar DonWeb, usa la versión estática:

- Sube solo archivos HTML, CSS, JS
- Usa `products.json` como base de datos
- Sin panel de administración funcional

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: EADDRINUSE (puerto en uso)

```bash
# Cambiar puerto en .env
PORT=3001
```

### Error: Cannot find module

```bash
npm install
```

### Error: Admin password not configured

Verifica que `.env` tenga `ADMIN_PASS_HASH` configurado

### Base de datos se resetea

El archivo `db/products.json` se sobrescribe. Haz backups regulares.

### Imágenes no cargan

Verifica que la carpeta `uploads/` exista y tenga permisos de escritura

---

## 📈 PRÓXIMOS PASOS

### Mejoras Sugeridas

- [ ] Base de datos real (MongoDB, PostgreSQL)
- [ ] Autenticación de usuarios (registro/login)
- [ ] Procesamiento de pagos (MercadoPago, Stripe)
- [ ] Email confirmación de pedidos
- [ ] Sistema de cupones/descuentos
- [ ] Historial de pedidos
- [ ] Panel de estadísticas
- [ ] Búsqueda avanzada
- [ ] Categorías dinámicas
- [ ] Reviews de productos

---

## 📞 SOPORTE

### Logs del Servidor

El servidor muestra logs en consola:

```
2025-11-11T12:00:00.000Z - GET /api/products
2025-11-11T12:00:01.000Z - POST /api/admin/login
Product created: Nike SB (uuid-1234)
```

### Archivos de Configuración

- `.env` - Variables de entorno
- `db/products.json` - Base de datos
- `SERVER_README.md` - Documentación completa

---

## ✅ CHECKLIST DE VERIFICACIÓN

- [x] Servidor iniciado en puerto 3000
- [x] Frontend accesible en http://localhost:3000
- [x] API REST funcionando
- [x] Panel admin accesible
- [x] Login admin funcional
- [x] CRUD de productos operativo
- [x] Subida de imágenes funcional
- [x] Carrito de compras operativo
- [x] WhatsApp integration activo
- [x] Base de datos persistente
- [x] Autenticación JWT segura

---

## 🎉 ¡TODO LISTO!

Tu backend está completamente funcional. Puedes:

1. ✅ Ver productos en http://localhost:3000
2. ✅ Administrar desde http://localhost:3000/admin
3. ✅ Usar la API REST
4. ✅ Subir productos con imágenes
5. ✅ Gestionar inventario

**¡Disfruta tu tienda SHOP33!** 🛹
