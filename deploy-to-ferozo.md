# 🚀 Guía Rápida de Deployment a Ferozo

## ⚠️ PROBLEMA ACTUAL

Tienes los archivos en `public_html/web/` pero el sitio no funciona porque:
1. La estructura no es correcta
2. Falta el archivo `.htaccess`
3. Las rutas no están configuradas correctamente

## ✅ SOLUCIÓN PASO A PASO

### Opción A: Mover archivos a la raíz (RECOMENDADO)

#### 1. Conecta por FTP a tu hosting Ferozo
- Host: ftp.ldxsoftware.com.pe
- Usuario: tu usuario de Ferozo
- Contraseña: tu contraseña

#### 2. Navega a `public_html/`

#### 3. Reorganiza los archivos así:

**DESDE:**
```
public_html/
└── web/
    ├── app/
    ├── config/
    ├── public/
    │   ├── assets/
    │   └── index.php
    └── vendor/
```

**HACIA:**
```
public_html/
├── .htaccess          ← NUEVO (copiar desde .htaccess.production)
├── index.php          ← Mover desde web/public/index.php
├── assets/            ← Mover desde web/public/assets/
├── app/               ← Mover desde web/app/
├── config/            ← Mover desde web/config/
└── vendor/            ← Mover desde web/vendor/
```

#### 4. Crear el archivo `.htaccess` en `public_html/`

Copia el contenido del archivo `.htaccess.production` que está en tu proyecto local.

**Contenido del .htaccess:**
```apache
# LDX Software - Production .htaccess

# Enable rewrite engine
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

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
```

#### 5. Verificar permisos

Asegúrate de que:
- Carpetas: `755`
- Archivos: `644`
- `app/data/`: `755` (para que pueda escribir)

#### 6. Eliminar la carpeta `web/` vacía

Una vez que hayas movido todo, elimina la carpeta `web/` que quedó vacía.

### Opción B: Mantener en subcarpeta `/web/`

Si prefieres mantener los archivos en `public_html/web/`:

#### 1. Actualiza `config/config.php`
```php
define('BASE_URL', 'https://ldxsoftware.com.pe/web/');
```

#### 2. Crea `.htaccess` en `public_html/web/`
Usa el mismo contenido del `.htaccess.production`

#### 3. Accede a tu sitio en:
```
https://ldxsoftware.com.pe/web/
```

## 📋 CHECKLIST DE VERIFICACIÓN

Después de hacer los cambios, verifica:

- [ ] ✅ `https://ldxsoftware.com.pe/` muestra la página principal
- [ ] ✅ Las imágenes se cargan correctamente
- [ ] ✅ Los estilos CSS funcionan
- [ ] ✅ El menú de navegación funciona
- [ ] ✅ Los enlaces internos funcionan
- [ ] ✅ El formulario de contacto funciona
- [ ] ❌ `https://ldxsoftware.com.pe/app/` da error 403 (correcto, por seguridad)
- [ ] ❌ `https://ldxsoftware.com.pe/config/` da error 403 (correcto, por seguridad)

## 🔧 COMANDOS VÍA SSH (Si tienes acceso)

Si Ferozo te da acceso SSH, puedes ejecutar:

```bash
# Conectar por SSH
ssh usuario@ldxsoftware.com.pe

# Ir a public_html
cd public_html

# Mover archivos de web/ a raíz
mv web/public/* ./
mv web/app ./
mv web/config ./
mv web/vendor ./
mv web/LICENSE ./
mv web/README.md ./

# Eliminar carpeta web vacía
rm -rf web

# Crear .htaccess
nano .htaccess
# (pegar el contenido del .htaccess.production)

# Ajustar permisos
chmod 755 app app/data
chmod 644 index.php config/config.php
find assets -type f -exec chmod 644 {} \;
find assets -type d -exec chmod 755 {} \;
```

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error 404 en todas las páginas
**Causa:** `.htaccess` no está funcionando o `mod_rewrite` no está habilitado

**Solución:**
1. Verifica que el archivo `.htaccess` esté en la raíz de `public_html/`
2. Contacta a soporte de Ferozo para habilitar `mod_rewrite`

### Error 500 Internal Server Error
**Causa:** Error en el código PHP o permisos incorrectos

**Solución:**
1. Revisa los logs de error en el panel de Ferozo
2. Verifica permisos de archivos y carpetas
3. Temporalmente cambia `DEBUG_MODE` a `true` en `config/config.php`

### Las imágenes no cargan
**Causa:** Ruta incorrecta de assets

**Solución:**
1. Verifica que la carpeta `assets/` esté en la raíz
2. Verifica que `BASE_URL` en `config/config.php` sea correcta
3. Verifica permisos de la carpeta `assets/`

### El formulario de contacto no funciona
**Causa:** Permisos de escritura en `app/data/`

**Solución:**
```bash
chmod 755 app/data
```

## 📞 SOPORTE FEROZO

Si necesitas ayuda con:
- Habilitar `mod_rewrite`
- Configurar PHP
- Acceso SSH
- Permisos de archivos

Contacta al soporte de Ferozo:
- Web: https://www.ferozo.com/contacto
- Teléfono: Consulta tu panel de control

## 🎯 RESUMEN RÁPIDO

1. **Mover archivos** de `web/` a raíz de `public_html/`
2. **Crear `.htaccess`** con el contenido de `.htaccess.production`
3. **Verificar** que `config/config.php` tenga `BASE_URL = 'https://ldxsoftware.com.pe/'`
4. **Probar** que el sitio funcione en `https://ldxsoftware.com.pe/`

¡Listo! Tu sitio debería estar funcionando correctamente.
