# 🎯 SHOP33 - Guía Rápida de Testing

## ✅ Estado del Proyecto

**Frontend completamente recreado** con las siguientes características:

### 🎨 Diseño Visual
- ✅ Dark theme con paleta skater (negro, rojo #ff0044, neón #00ffd5)
- ✅ Hero section con video de skaters de fondo
- ✅ Tipografía urbana (Bebas Neue + Inter de Google Fonts)
- ✅ Loader animado tipo skateboard girando
- ✅ Animaciones hover suaves en todas las cards
- ✅ Responsive design completo (mobile, tablet, desktop)

### 🛒 Funcionalidad del Catálogo
- ✅ Grid dinámico de productos con 12 productos de ejemplo
- ✅ Filtros por categoría (navbar horizontal)
- ✅ Filtro por marca (dropdown dinámico)
- ✅ Filtro por talle (S, M, L, XL, 38-45)
- ✅ Buscador en tiempo real
- ✅ Contador de productos disponibles
- ✅ Indicador visual "SIN STOCK" para productos agotados
- ✅ Modal de producto individual con:
  - Galería de imágenes (thumbnails clicables)
  - Selector de talles
  - Botón agregar al carrito
  - Stock visible
- ✅ Carrito simulado con localStorage
- ✅ Notificaciones visuales al agregar productos

### 🔐 Panel Admin
- ✅ Login seguro con SHA256 + JWT (8 horas de expiración)
- ✅ Dashboard moderno con diseño mejorado
- ✅ Formulario completo para crear productos:
  - Nombre, marca, categoría, precio, stock, talles
  - Descripción
  - Upload de hasta 6 imágenes
- ✅ Listado de productos con opción eliminar
- ✅ Notificaciones de éxito/error
- ✅ Validación de autenticación (redirect si token inválido)

---

## 🚀 Cómo Testear

### 1. Verificar que el servidor esté corriendo

El servidor ya debería estar ejecutándose. Si no, ejecuta:

```bash
npm start
```

Verifica en la terminal que veas:
```
Server running on http://localhost:3000
```

### 2. Probar el Frontend Público

Abre el navegador en: **http://localhost:3000**

**Checklist visual:**
- [ ] Se ve el hero con video de fondo (o placeholder si no carga)
- [ ] Título "URBAN SKATEWEAR" con estilo Bebas Neue
- [ ] Navbar sticky con logo "SHOP33"
- [ ] Botón "EXPLORAR CATÁLOGO" que hace scroll
- [ ] Grid de productos (12 productos visibles)
- [ ] Cards con hover effect (zoom + sombra roja)
- [ ] Cada card muestra: imagen, nombre, marca, precio, stock, talles
- [ ] Badge de marca en esquina superior derecha
- [ ] Footer con redes sociales

**Checklist funcional:**
- [ ] Filtro por categoría (clic en navbar links)
- [ ] Filtro por marca (dropdown se llena automáticamente)
- [ ] Filtro por talle (dropdown manual)
- [ ] Búsqueda en tiempo real (escribe "vans", "nike", etc.)
- [ ] Contador de productos se actualiza con filtros
- [ ] Mensaje "No se encontraron productos" si no hay resultados

**Checklist modal de producto:**
- [ ] Clic en cualquier card abre el modal
- [ ] Modal muestra imagen grande + thumbnails
- [ ] Clic en thumbnail cambia imagen principal
- [ ] Se ve precio, stock, descripción completa
- [ ] Selector de talles (botones clicables)
- [ ] Botón "AGREGAR AL CARRITO" funcional
- [ ] Notificación aparece al agregar (esquina inferior derecha)
- [ ] Contador del carrito (🛒) se actualiza
- [ ] Botón "✕" cierra el modal
- [ ] Clic fuera del modal también lo cierra

### 3. Probar el Carrito

- [ ] Agrega varios productos
- [ ] Contador del carrito aumenta
- [ ] Clic en icono 🛒 muestra alert con lista de productos
- [ ] Los productos persisten al recargar la página (localStorage)

### 4. Probar la Página de Producto Individual

Abre: **http://localhost:3000/product.html?id=1**

**Checklist:**
- [ ] Se carga la información del producto
- [ ] Galería de imágenes funcional
- [ ] Selector de talles (botones)
- [ ] Botón "Agregar al carrito" funciona
- [ ] Link "Volver al catálogo" funciona
- [ ] Responsive en mobile

### 5. Probar el Panel Admin

#### a) Login

Abre: **http://localhost:3000/admin**

**Credenciales:**
- Usuario: `admin`
- Contraseña: `admin123`

**Checklist:**
- [ ] Formulario centrado con estilo skater
- [ ] Campos usuario y contraseña funcionan
- [ ] Botón "INGRESAR" cambia a "VERIFICANDO..."
- [ ] Login correcto → redirect a dashboard
- [ ] Login incorrecto → muestra mensaje de error
- [ ] Error desaparece después de 5 segundos

#### b) Dashboard

Después del login, deberías estar en: **http://localhost:3000/admin/dashboard.html**

**Checklist visual:**
- [ ] Header con título "PANEL DE ADMINISTRACIÓN"
- [ ] Botón "CERRAR SESIÓN" funcional
- [ ] Grid de 2 columnas (formulario | listado)
- [ ] Diseño coherente con el frontend

**Checklist funcional - Crear producto:**
- [ ] Formulario con todos los campos visibles
- [ ] Campos obligatorios marcados con *
- [ ] Dropdown de categorías funciona
- [ ] Upload de imágenes permite múltiples archivos
- [ ] Botón "GUARDAR PRODUCTO" cambia a "GUARDANDO..."
- [ ] Notificación verde "✓ Producto creado con éxito"
- [ ] Producto aparece inmediatamente en el listado
- [ ] Formulario se limpia después de guardar

**Checklist funcional - Listar productos:**
- [ ] Listado muestra los 12 productos de ejemplo
- [ ] Cada item muestra: nombre, marca, categoría, precio, stock, talles
- [ ] Botones "ELIMINAR" visibles en cada item

**Checklist funcional - Eliminar producto:**
- [ ] Clic en "ELIMINAR" muestra confirmación
- [ ] Confirmación → producto desaparece del listado
- [ ] Notificación "✓ Producto eliminado"
- [ ] Cancelar → no elimina nada

**Checklist funcional - Cerrar sesión:**
- [ ] Clic en "CERRAR SESIÓN"
- [ ] Redirect a página de login
- [ ] Token borrado de localStorage
- [ ] No se puede volver al dashboard sin login

### 6. Probar Autenticación

**Sin token:**
- [ ] Intenta acceder a `/admin/dashboard.html` sin loguearte
- [ ] Debería redirigir automáticamente a `/admin`

**Con token expirado/inválido:**
- [ ] Modifica el token en localStorage del browser
- [ ] Recarga `/admin/dashboard.html`
- [ ] Debería redirigir a login

### 7. Probar Responsive Design

**Desktop (1200px+):**
- [ ] Grid de productos: 4-5 columnas
- [ ] Navbar completo visible
- [ ] Admin dashboard: 2 columnas

**Tablet (768px - 1024px):**
- [ ] Grid de productos: 3 columnas
- [ ] Navbar se adapta
- [ ] Admin dashboard: 1 columna

**Mobile (<768px):**
- [ ] Grid de productos: 1 columna
- [ ] Navbar con menú hamburguesa (si implementado)
- [ ] Modal ocupa 95% del ancho
- [ ] Formularios ocupan todo el ancho
- [ ] Hero section ajusta altura

---

## 🐛 Testing de Errores

### 1. Producto inexistente
- Abre: `http://localhost:3000/product.html?id=999`
- [ ] Muestra mensaje "😔 Producto no encontrado"
- [ ] Botón "Volver al catálogo" funciona

### 2. API caída
- Para el servidor (Ctrl+C en terminal)
- Recarga la página
- [ ] Muestra loader o mensaje de error
- [ ] No crashea la aplicación

### 3. Sin JavaScript
- Desactiva JavaScript en el browser
- [ ] HTML básico se carga
- [ ] Estilos CSS funcionan

---

## 📊 Datos de Testing

### Productos de ejemplo en la DB

| ID | Nombre | Marca | Categoría | Precio | Stock |
|----|--------|-------|-----------|--------|-------|
| 1 | Vans Old Skool Black/White | Vans | Zapatillas | $8,999 | 12 |
| 2 | Remera Thrasher Magazine Logo | Thrasher | Remeras | $3,999 | 24 |
| 3 | Buzo Supreme Box Logo Hoodie | Supreme | Buzos | $16,999 | 5 |
| 4 | Nike SB Dunk Low Pro | Nike | Zapatillas | $12,499 | 8 |
| 5 | Pantalón Dickies 874 Original | Dickies | Pantalones | $7,499 | 15 |
| 6 | Remera Volcom Stone Stack | Volcom | Remeras | $3,499 | 18 |
| 7 | Mochila Herschel Little America | Herschel | Accesorios | $9,999 | 10 |
| 8 | Gorra New Era 9FIFTY Snapback | New Era | Accesorios | $4,299 | 20 |
| 9 | Campera Carhartt WIP Detroit | Carhartt | Buzos | $18,999 | 6 |
| 10 | Adidas Skateboarding Busenitz | Adidas | Zapatillas | $10,999 | 14 |
| 11 | Remera Polar Skate Co. Big Boy | Polar | Remeras | $4,599 | **0** |
| 12 | Pantalón Carhartt Single Knee | Carhartt | Pantalones | $8,999 | 11 |

**Nota:** El producto ID 11 (Polar) tiene stock = 0 para probar el estado "SIN STOCK".

---

## 🎯 Casos de Uso Completos

### Caso 1: Usuario busca zapatillas Nike
1. Abre http://localhost:3000
2. En el buscador escribe "nike"
3. Resultado: 1 producto (Nike SB Dunk Low Pro)
4. Clic en la card
5. Modal se abre con detalles
6. Selecciona talle 42
7. Clic en "AGREGAR AL CARRITO"
8. Notificación aparece
9. Contador del carrito muestra 1

### Caso 2: Admin sube nuevo producto
1. Abre http://localhost:3000/admin
2. Login: admin / admin123
3. Dashboard se abre
4. Completa formulario:
   - Nombre: "Remera Stüssy World Tour"
   - Marca: "Stüssy"
   - Categoría: "Remeras"
   - Precio: 5999
   - Stock: 20
   - Talles: S, M, L, XL
   - Descripción: "Remera con gráfica World Tour de Stüssy"
5. Sube 1-3 imágenes
6. Clic en "GUARDAR PRODUCTO"
7. Notificación verde aparece
8. Producto aparece en el listado
9. Abre http://localhost:3000 en otra pestaña
10. Verifica que el producto aparece en el catálogo

### Caso 3: Usuario filtra por categoría y marca
1. Abre http://localhost:3000
2. Clic en "ZAPATILLAS" en la navbar
3. Resultado: 3 productos (Vans, Nike, Adidas)
4. En filtro de marca, selecciona "Vans"
5. Resultado: 1 producto (Vans Old Skool)
6. Limpia filtros (clic en "TODAS")
7. Vuelven a aparecer los 12 productos

---

## ✅ Checklist Final

- [ ] Servidor corriendo sin errores
- [ ] Frontend carga correctamente
- [ ] 12 productos visibles en el catálogo
- [ ] Todos los filtros funcionan
- [ ] Modal de producto funciona
- [ ] Carrito simula agregado
- [ ] Login admin funciona
- [ ] Dashboard permite crear/eliminar productos
- [ ] Responsive en mobile funciona
- [ ] No hay errores en la consola del navegador
- [ ] README.md completo y actualizado

---

## 🎉 Resultado Esperado

Un e-commerce skater completamente funcional con:
- Frontend moderno y visualmente atractivo
- Backend robusto con API REST
- Panel admin protegido y funcional
- Experiencia de usuario fluida
- Código limpio y bien estructurado

**¡Todo listo para usar y demostrar!** 🛹
