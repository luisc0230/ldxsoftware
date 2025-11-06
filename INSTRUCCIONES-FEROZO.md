# 🎯 Instrucciones para Ferozo - Carpeta web/

## Tu estructura actual en Ferozo

```
public_html/
├── .htaccess          ← Ya existe (lo vas a editar)
└── web/               ← Tu aplicación está aquí
    ├── app/
    ├── config/
    ├── public/
    ├── vendor/
    └── otros archivos...
```

## 🚀 Solución: 2 archivos .htaccess

Necesitas **DOS archivos .htaccess**:

### 1️⃣ `.htaccess` en `public_html/` (RAÍZ)

**Ubicación:** `public_html/.htaccess`

**Función:** Redirige todo el tráfico de `ldxsoftware.com.pe` hacia `ldxsoftware.com.pe/web/`

**Contenido a copiar:**

```apache
# .htaccess para public_html/ (raíz)
# Redirige todo el tráfico a la carpeta web/

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Remove www (opcional)
    RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
    RewriteRule ^(.*)$ https://%1/$1 [R=301,L]

    # Si la solicitud NO es para la carpeta web, redirigir a web/
    RewriteCond %{REQUEST_URI} !^/web/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /web/$1 [L]
</IfModule>

# Disable directory browsing
Options -Indexes

# Prevent access to .htaccess
<Files .htaccess>
    Order allow,deny
    Deny from all
</Files>
```

### 2️⃣ `.htaccess` en `public_html/web/` (DENTRO DE WEB)

**Ubicación:** `public_html/web/.htaccess`

**Función:** Maneja las rutas de tu aplicación y protege carpetas sensibles

**Contenido a copiar:**

```apache
# .htaccess para public_html/web/
# Maneja las rutas de la aplicación

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /web/

    # Prevent access to sensitive directories
    RewriteRule ^app/.*$ - [F,L]
    RewriteRule ^config/.*$ - [F,L]
    RewriteRule ^vendor/.*$ - [F,L]

    # Route all requests through index.php
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

# Disable directory browsing
Options -Indexes

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>
```

## 📝 Pasos a seguir

### Paso 1: Reorganizar archivos en `web/`

Dentro de `public_html/web/`, la estructura debe ser:

```
web/
├── .htaccess          ← Crear con el contenido del archivo 2
├── index.php          ← Mover desde public/index.php
├── assets/            ← Mover desde public/assets/
├── app/               ← Ya está aquí
├── config/            ← Ya está aquí
└── vendor/            ← Ya está aquí
```

**Acciones en FTP:**
1. Entra a `public_html/web/`
2. Mueve `public/index.php` a `web/index.php`
3. Mueve `public/assets/` a `web/assets/`
4. Elimina la carpeta `public/` vacía

### Paso 2: Editar `.htaccess` en `public_html/`

1. Abre el archivo `.htaccess` que ya existe en `public_html/`
2. **REEMPLAZA TODO** su contenido con el código del archivo 1️⃣
3. Guarda los cambios

### Paso 3: Crear `.htaccess` en `public_html/web/`

1. Crea un nuevo archivo llamado `.htaccess` en `public_html/web/`
2. Copia el contenido del archivo 2️⃣
3. Guarda los cambios

### Paso 4: Verificar config.php

El archivo `config/config.php` ya está configurado con:
```php
define('BASE_URL', 'https://ldxsoftware.com.pe/web/');
```

✅ Esto es correcto para tu estructura.

## 🧪 Probar el sitio

Después de hacer los cambios, prueba:

1. **Acceso directo:** `https://ldxsoftware.com.pe/web/`
   - ✅ Debe mostrar tu sitio

2. **Acceso desde raíz:** `https://ldxsoftware.com.pe/`
   - ✅ Debe redirigir automáticamente a `/web/` y mostrar tu sitio

3. **Protección de carpetas:** `https://ldxsoftware.com.pe/web/app/`
   - ❌ Debe dar error 403 Forbidden (correcto)

## 📋 Checklist

- [ ] Mover `public/index.php` a `web/index.php`
- [ ] Mover `public/assets/` a `web/assets/`
- [ ] Editar `.htaccess` en `public_html/` (raíz)
- [ ] Crear `.htaccess` en `public_html/web/`
- [ ] Verificar que `config/config.php` tenga `BASE_URL` con `/web/`
- [ ] Probar `https://ldxsoftware.com.pe/`
- [ ] Probar `https://ldxsoftware.com.pe/web/`

## 🐛 Solución de problemas

### Error 404 en todas las páginas
- Verifica que ambos `.htaccess` estén en su lugar
- Verifica que `mod_rewrite` esté habilitado (contacta a Ferozo)

### Error 500
- Revisa los logs de error en el panel de Ferozo
- Verifica que los archivos `.htaccess` no tengan errores de sintaxis

### Las imágenes no cargan
- Verifica que la carpeta `assets/` esté en `web/assets/`
- Verifica que `BASE_URL` sea `https://ldxsoftware.com.pe/web/`

### El sitio no redirige desde la raíz
- Verifica el `.htaccess` en `public_html/` (raíz)
- Asegúrate de que la regla de redirección esté correcta

## 📞 Archivos de referencia

En tu proyecto local tienes estos archivos:
- `.htaccess.root-redirect` → Copiar a `public_html/.htaccess`
- `.htaccess.web-folder` → Copiar a `public_html/web/.htaccess`

## ✅ Resultado esperado

Cuando todo esté configurado:
- `https://ldxsoftware.com.pe/` → Redirige a → `https://ldxsoftware.com.pe/web/`
- Tu sitio funciona correctamente
- Las carpetas sensibles están protegidas
