# Guía de Depuración OAuth - Cambios Realizados

## Problema Identificado
Cuando te logueabas con Google OAuth, eras redirigido a la URL de callback pero no se completaba el proceso de login y no aparecían console.logs.

## Causa del Problema
El archivo `.htaccess` estaba redirigiendo todas las peticiones (incluyendo `auth/google/callback`) al `index.php`, impidiendo que el archivo `callback.php` se ejecutara correctamente.

## Cambios Realizados

### 1. **Modificación del .htaccess** ✅
Agregué reglas específicas para las rutas de autenticación:

```apache
# Allow auth routes to their respective files
RewriteRule ^auth/google/?$ auth/google/index.php [L]
RewriteRule ^auth/google/callback$ auth/google/callback.php [QSA,L]
RewriteRule ^auth/logout$ auth/logout.php [L]
```

**Esto permite que:**
- `https://ldxsoftware.com.pe/auth/google` → `auth/google/index.php`
- `https://ldxsoftware.com.pe/auth/google/callback` → `auth/google/callback.php`
- `https://ldxsoftware.com.pe/auth/logout` → `auth/logout.php`

### 2. **Console.log en callback.php** ✅
Agregué debugging visual en el callback:

```javascript
console.log("=== OAuth Callback Cargado ===");
console.log("URL actual:", window.location.href);
console.log("GET params:", {...});
console.log("Iniciando proceso de callback...");
```

### 3. **Error logging en AuthController.php** ✅
Agregué logs detallados en cada paso del proceso:

```php
error_log("googleCallback() iniciado");
error_log("Código recibido: ...");
error_log("Intentando obtener token...");
error_log("Token obtenido exitosamente");
error_log("Usuario obtenido: ...");
error_log("Usuario guardado en sesión. Session ID: ...");
```

### 4. **Console.log en index.php** ✅
Agregué verificación de sesión en la página principal:

```javascript
console.log("✅ Usuario logueado:", {...});
console.log("✅ Login exitoso detectado!");
```

## Cómo Verificar que Funciona

### Opción 1: Probar el Login
1. Ve a: `https://ldxsoftware.com.pe/auth/google`
2. Inicia sesión con tu cuenta de Google
3. **Abre la Consola del Navegador (F12)**
4. Deberías ver:
   ```
   === OAuth Callback Cargado ===
   URL actual: https://ldxsoftware.com.pe/auth/google/callback?code=...
   GET params: {code: "...", scope: "...", ...}
   Iniciando proceso de callback...
   ```
5. Después serás redirigido al inicio y verás:
   ```
   ✅ Usuario logueado: {id: "...", email: "...", name: "..."}
   ✅ Login exitoso detectado!
   ```

### Opción 2: Usar el Archivo de Debug
1. Ve directamente a: `https://ldxsoftware.com.pe/auth/google/callback-debug.php`
2. Inicia sesión con Google
3. Verás un reporte detallado del proceso

## Problemas Potenciales

### Si Aún No Funciona, Verifica:

1. **Credenciales de Google OAuth:**
   ```php
   // En config.php
   define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
   define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');
   ```
   
   Asegúrate de que las variables de entorno estén configuradas en tu servidor:
   - En cPanel: PHP Variables o .htaccess
   - En servidor: archivo .env o configuración de PHP

2. **URI de Redirección en Google Console:**
   Ve a: https://console.cloud.google.com/apis/credentials
   
   Verifica que tengas configurado:
   ```
   https://ldxsoftware.com.pe/auth/google/callback
   ```

3. **Permisos de Sesión:**
   ```bash
   # Asegúrate de que PHP pueda escribir sesiones
   chmod 755 /var/lib/php/sessions
   ```

4. **Revisa los Logs del Servidor:**
   - En cPanel: Error Log
   - En servidor: `/var/log/apache2/error.log` o `/var/log/php-fpm/error.log`
   
   Busca los mensajes que empiezan con:
   ```
   === OAuth Callback Iniciado ===
   googleCallback() iniciado
   ```

## Comandos Útiles para Debugging

### Ver los últimos logs de error (si tienes acceso SSH):
```bash
tail -f /var/log/apache2/error.log
tail -f /var/log/php-fpm/error.log
```

### Verificar que .htaccess se está aplicando:
```bash
cat .htaccess
apache2ctl -M | grep rewrite
```

### Probar la configuración de PHP:
```bash
php -i | grep session
php -i | grep GOOGLE
```

## Siguiente Paso

1. **Sube los cambios al servidor:**
   ```bash
   git add .
   git commit -m "Fix: OAuth callback routing and add debugging"
   git push origin main
   ```

2. **Prueba el login nuevamente**

3. **Revisa la consola del navegador (F12)** para ver los mensajes de depuración

4. **Si persiste el problema**, envíame:
   - Captura de la consola del navegador
   - Logs del servidor (error.log)
   - Captura del callback-debug.php

---

**Nota Importante:** Los console.log y error_log son para depuración. Una vez que funcione correctamente, puedes considerar eliminarlos o ponerlos detrás de una flag de DEBUG_MODE.
