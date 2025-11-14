# 📦 Lista de Archivos para Subir a DonWeb

## ✅ SUBIR ESTOS ARCHIVOS/CARPETAS

### 📁 Carpetas PHP (OBLIGATORIAS)

```
✅ bootstrap/
   └── init.php

✅ middlewares/
   └── AuthGuard.php

✅ routes/
   ├── api.php
   └── admin.php

✅ public/
   └── index.php

✅ storage/
   ├── logs/ (vacía, se crea auto)
   └── sessions/ (vacía, se crea auto)
```

### 📁 Carpetas de Assets

```
✅ assets/
   ├── images/
   └── videos/

✅ uploads/
   (para imágenes de productos)
```

### 📄 Archivos HTML/JS/CSS

```
✅ index.html
✅ product.html
✅ dashboard.php
✅ login.php
✅ script.js
✅ styles.css
```

### 📄 Archivos de Configuración

```
✅ .htaccess
✅ .env
✅ db-products.json
```

### 📄 Archivos Opcionales (Documentación)

```
⚪ README_PHP.md
⚪ CONVERSION_PHP_COMPLETA.md
⚪ verificar.php (útil para debugging)
```

---

## ❌ NO SUBIR ESTOS ARCHIVOS

```
❌ server/
❌ node_modules/
❌ .venv/
❌ package.json
❌ package-lock.json
❌ start.sh
❌ start-server.sh
❌ .git/
❌ .gitignore
❌ *.zip
```

---

## 🔧 Configuración de Permisos (SSH/FTP)

Después de subir, ejecuta en SSH o configura en FTP:

```bash
# Carpetas escribibles
chmod 755 storage/
chmod 755 storage/logs/
chmod 755 storage/sessions/
chmod 755 uploads/

# Base de datos escribible
chmod 666 db-products.json

# Archivos PHP
chmod 644 bootstrap/*.php
chmod 644 middlewares/*.php
chmod 644 routes/*.php
chmod 644 public/index.php
chmod 644 login.php
chmod 644 dashboard.php

# .htaccess
chmod 644 .htaccess

# .env (protegido)
chmod 600 .env
```

---

## 📋 Estructura en el Servidor DonWeb

```
/public_html/
├── public/                     ← Document Root apunta aquí
│   └── index.php
├── bootstrap/
├── middlewares/
├── routes/
├── storage/
├── uploads/
├── assets/
├── index.html
├── product.html
├── dashboard.php
├── login.php
├── script.js
├── styles.css
├── db-products.json
├── .htaccess
└── .env
```

**IMPORTANTE:** Configura el Document Root en el panel de DonWeb:

```
Document Root: /public_html/public
```

---

## 🧪 Verificar Instalación

Después de subir los archivos, accede a:

1. **Home:** `https://tudominio.com/`

   - Debería ver el catálogo

2. **Admin:** `https://tudominio.com/admin`

   - Debería redirigir a login

3. **Login:** `https://tudominio.com/login.php`

   - Ingresar: admin / admin123
   - Debería redireccionar a `/admin`

4. **API:** `https://tudominio.com/api/products`
   - Debería devolver JSON con productos

---

## ⚠️ Si algo no funciona

1. Verifica que `.htaccess` esté en la raíz
2. Verifica permisos de `storage/` y `uploads/`
3. Contacta a DonWeb para habilitar `mod_rewrite`
4. Revisa logs del servidor (panel de DonWeb)

---

## 📊 Tamaño Total Aproximado

- **Con productos vacíos:** ~5 MB
- **Con 50 productos e imágenes:** ~50-100 MB

La mayoría del peso son:

- Imágenes en `/uploads`
- Videos en `/assets/videos`

---

## ✅ Checklist de Subida

- [ ] Subir carpeta `bootstrap/`
- [ ] Subir carpeta `middlewares/`
- [ ] Subir carpeta `routes/`
- [ ] Subir carpeta `public/`
- [ ] Crear carpeta `storage/` (vacía)
- [ ] Subir carpeta `uploads/`
- [ ] Subir carpeta `assets/`
- [ ] Subir `index.html`
- [ ] Subir `product.html`
- [ ] Subir `dashboard.php`
- [ ] Subir `login.php`
- [ ] Subir `script.js`
- [ ] Subir `styles.css`
- [ ] Subir `db-products.json`
- [ ] Subir `.htaccess`
- [ ] Subir `.env`
- [ ] Configurar permisos
- [ ] Configurar Document Root
- [ ] Probar acceso a `/`
- [ ] Probar acceso a `/admin`
- [ ] Probar login
- [ ] Probar crear producto

---

**¡Listo para producción! 🚀**
