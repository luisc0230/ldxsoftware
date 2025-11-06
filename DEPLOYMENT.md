# Guía de Deployment para Ferozo

## Estructura actual en Ferozo

Según la imagen, tienes los archivos en: `public_html/web/`

## Problema

La aplicación busca los archivos en la carpeta `public/` pero el servidor no está configurado correctamente.

## Solución: Reorganizar archivos en el servidor

### Paso 1: Estructura correcta en Ferozo

Debes organizar los archivos así en tu servidor:

```
public_html/
├── .htaccess          ← Archivo de redirección (crear nuevo)
├── index.php          ← Copiar desde public/index.php
├── assets/            ← Copiar desde public/assets/
│   ├── css/
│   ├── js/
│   └── images/
├── app/               ← Carpeta completa
├── config/            ← Carpeta completa
├── vendor/            ← Carpeta completa
├── LICENSE
└── README.md
```

### Paso 2: Crear .htaccess en public_html

Crea un archivo `.htaccess` en `public_html/` con este contenido:

```apache
# Enable rewrite engine
RewriteEngine On

# Redirect to HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Remove www
RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
RewriteRule ^(.*)$ https://%1/$1 [R=301,L]

# Prevent access to sensitive directories
RewriteRule ^(app|config|vendor)/.*$ - [F,L]

# Route all requests through index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

### Paso 3: Modificar config.php

Ya está configurado con:
```php
define('BASE_URL', 'https://ldxsoftware.com.pe/');
define('DEBUG_MODE', true); // Cambiar a false en producción
```

**IMPORTANTE**: Cambia `DEBUG_MODE` a `false` después de verificar que funciona.

### Paso 4: Modificar index.php

El archivo `index.php` que copies a `public_html/` debe tener las rutas correctas.

### Paso 5: Verificar permisos

Asegúrate de que los permisos sean:
- Carpetas: 755
- Archivos PHP: 644
- Carpeta `app/data/`: 755 (para escritura)

## Comandos para reorganizar (vía FTP o SSH)

Si tienes acceso SSH:

```bash
cd public_html

# Mover contenido de public/ a raíz
mv web/public/* ./

# Mover carpetas de aplicación
mv web/app ./
mv web/config ./
mv web/vendor ./

# Eliminar carpeta web vacía
rm -rf web
```

## Alternativa: Usar subcarpeta web/

Si prefieres mantener la estructura en `web/`, accede a:
`https://ldxsoftware.com.pe/web/`

Y configura:
```php
define('BASE_URL', 'https://ldxsoftware.com.pe/web/');
```

## Verificación

Después de reorganizar, verifica:

1. ✅ `https://ldxsoftware.com.pe/` - Debe mostrar la home
2. ✅ `https://ldxsoftware.com.pe/portfolio` - Debe funcionar
3. ✅ `https://ldxsoftware.com.pe/contact` - Debe funcionar
4. ❌ `https://ldxsoftware.com.pe/app/` - Debe dar 403 Forbidden
5. ❌ `https://ldxsoftware.com.pe/config/` - Debe dar 403 Forbidden

## Problemas comunes

### Error 404 en todas las páginas
- Verifica que `.htaccess` esté en la raíz
- Verifica que `mod_rewrite` esté habilitado en Apache

### Error 500
- Revisa los logs de error de PHP
- Verifica permisos de archivos
- Cambia `DEBUG_MODE` a `true` temporalmente

### Rutas de assets no funcionan
- Verifica que la carpeta `assets/` esté en la raíz
- Verifica `BASE_URL` en `config.php`

## Contacto Ferozo

Si necesitas habilitar `mod_rewrite` o cambiar configuraciones de PHP, contacta al soporte de Ferozo.
