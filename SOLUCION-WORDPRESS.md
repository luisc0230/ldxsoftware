# 🔧 Solución: WordPress + Tu App en /web/

## 🎯 Problema identificado

Tienes **WordPress instalado en `public_html/`** y tu aplicación LDX en `public_html/web/`.

El WordPress está interceptando todas las peticiones, por eso no funciona ni:
- `https://ldxsoftware.com.pe/` (muestra WordPress)
- `https://ldxsoftware.com.pe/web/` (da 404)

## ✅ Solución

Necesitas configurar 2 archivos `.htaccess` correctamente:

### 1️⃣ `.htaccess` en `public_html/` (RAÍZ)

**Ubicación:** `public_html/.htaccess`

**Reemplaza TODO el contenido** con esto:

```apache
# .htaccess para public_html/ (raíz con WordPress)

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Remove www (opcional)
    RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
    RewriteRule ^(.*)$ https://%1/$1 [R=301,L]

    # Si la URL empieza con /web/, dejar que web/.htaccess lo maneje
    RewriteCond %{REQUEST_URI} ^/web/
    RewriteRule ^ - [L]

    # WordPress rules (mantener las reglas existentes de WordPress)
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.php [L]
</IfModule>

# Disable directory browsing
Options -Indexes
```

### 2️⃣ `.htaccess` en `public_html/web/` (DENTRO DE WEB)

**Ubicación:** `public_html/web/.htaccess`

**Contenido:**

```apache
# .htaccess para public_html/web/

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /web/

    # Prevent access to sensitive directories
    RewriteRule ^app/.*$ - [F,L]
    RewriteRule ^config/.*$ - [F,L]
    RewriteRule ^vendor/.*$ - [F,L]
    RewriteRule ^\.git/.*$ - [F,L]

    # Prevent access to sensitive files
    RewriteRule ^composer\.(json|lock)$ - [F,L]

    # Route all requests through public/index.php
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/index.php [QSA,L]
</IfModule>

# Disable directory browsing
Options -Indexes

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>
```

## 📝 Pasos a seguir

### Paso 1: Editar `.htaccess` en `public_html/`

1. Abre el archivo `.htaccess` en `public_html/`
2. **REEMPLAZA TODO** su contenido con el código del archivo 1️⃣
3. Guarda los cambios

### Paso 2: Editar/Crear `.htaccess` en `public_html/web/`

1. Si existe `.htaccess` en `public_html/web/`, ábrelo
2. Si NO existe, créalo
3. Copia el contenido del archivo 2️⃣
4. Guarda los cambios

### Paso 3: Verificar estructura en `web/`

La estructura en `public_html/web/` debe ser:

```
web/
├── .htaccess          ← Archivo del paso 2
├── app/               ← Ya existe
├── config/            ← Ya existe
├── public/            ← Ya existe
│   ├── index.php      ← Debe existir
│   └── assets/        ← Debe existir
└── vendor/            ← Ya existe
```

**IMPORTANTE:** NO muevas nada de la carpeta `public/`. El `.htaccess` redirigirá a `public/index.php`.

### Paso 4: Actualizar `config.php`

El archivo `config/config.php` debe tener:

```php
define('BASE_URL', 'https://ldxsoftware.com.pe/web/');
```

✅ Ya está configurado correctamente.

## 🧪 Probar el sitio

Después de hacer los cambios:

1. **WordPress (raíz):** `https://ldxsoftware.com.pe/`
   - ✅ Debe mostrar WordPress

2. **Tu aplicación:** `https://ldxsoftware.com.pe/web/`
   - ✅ Debe mostrar tu sitio LDX

3. **Protección:** `https://ldxsoftware.com.pe/web/app/`
   - ❌ Debe dar error 403 Forbidden (correcto)

## 📋 Checklist

- [ ] Editar `.htaccess` en `public_html/` (raíz)
- [ ] Crear/editar `.htaccess` en `public_html/web/`
- [ ] Verificar que `public/index.php` exista en `web/public/`
- [ ] Verificar que `config/config.php` tenga `BASE_URL` con `/web/`
- [ ] Probar `https://ldxsoftware.com.pe/` (WordPress)
- [ ] Probar `https://ldxsoftware.com.pe/web/` (Tu app)

## 🐛 Solución de problemas

### Sigue dando 404 en /web/
**Causa:** El `.htaccess` de `web/` no está funcionando

**Solución:**
1. Verifica que el archivo `.htaccess` esté en `public_html/web/.htaccess`
2. Verifica que tenga el contenido correcto
3. Verifica que `public/index.php` exista

### Error 500 en /web/
**Causa:** Error en el `.htaccess` o en el código PHP

**Solución:**
1. Revisa los logs de error en el panel de Ferozo
2. Verifica que no haya errores de sintaxis en `.htaccess`
3. Temporalmente cambia `DEBUG_MODE` a `true` en `config/config.php`

### WordPress deja de funcionar
**Causa:** El `.htaccess` de la raíz está mal configurado

**Solución:**
1. Restaura el `.htaccess` original de WordPress
2. Agrega solo las reglas para `/web/` antes de las reglas de WordPress

## 🎯 Resumen

1. **Raíz (`public_html/`)** → WordPress
2. **Subcarpeta (`public_html/web/`)** → Tu aplicación LDX
3. **Dos `.htaccess` separados** para manejar cada aplicación

## 📞 Archivos de referencia

En tu proyecto local:
- `.htaccess.root-with-wordpress` → Para `public_html/.htaccess`
- `.htaccess.web-folder` → Para `public_html/web/.htaccess`

---

**Nota:** Si quieres que tu aplicación LDX sea la principal y WordPress esté en una subcarpeta, necesitarías reorganizar todo de manera diferente.
