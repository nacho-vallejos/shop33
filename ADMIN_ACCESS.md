# 🛹 SHOP33 - Panel de Administración

## ✅ Sistema Activo: Node.js Backend

### 🔑 Credenciales

```
Usuario: admin
Contraseña: admin123
```

### 🌐 URLs de Acceso

**Local:**
- http://localhost:3000/admin/login.html

**Producción (Donweb):**
- https://shop33.store/admin/login.html

### ⚙️ Cómo Funciona

1. Servidor Node.js en puerto 3000
2. Login vía API: `POST /api/admin/login`
3. Token JWT guardado en localStorage
4. Dashboard protegido con token

### 🚀 Comandos

**Iniciar servidor:**
```bash
cd ~/shop33
npm start
```

**Verificar servidor:**
```bash
curl http://localhost:3000
```

**Reiniciar:**
```bash
pkill -f "node server/server.js"
npm start
```

### 🔧 Cambiar Contraseña

```bash
node -e "console.log(require('crypto').createHash('sha256').update('NUEVA_PASS').digest('hex'))"
```

Actualiza `ADMIN_PASS_HASH` en `.env`

---

**Nota:** Usuario case-insensitive (admin, Admin, ADMIN - todos funcionan)
