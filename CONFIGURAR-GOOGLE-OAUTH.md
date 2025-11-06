# 🔐 Configurar Google OAuth en el Servidor

## ⚠️ Importante - Seguridad

Las credenciales de Google OAuth **NO están en el código** por seguridad. Debes configurarlas directamente en tu servidor.

## 📝 Dónde están tus credenciales

Tus credenciales de Google OAuth están en:
- **Google Cloud Console**: https://console.cloud.google.com/
- Sección: APIs & Services → Credentials
- Cliente OAuth 2.0 que creaste

## 🔧 Opción 1: Variables de Entorno (Recomendado)

### Método A: Archivo .htaccess

Edita `.htaccess` en `public_html/` y agrega:

```apache
# Variables de entorno para Google OAuth
SetEnv GOOGLE_CLIENT_ID "tu-client-id.apps.googleusercontent.com"
SetEnv GOOGLE_CLIENT_SECRET "tu-client-secret"
```

### Método B: Archivo .user.ini

Si Ferozo no permite SetEnv, crea `.user.ini` en `public_html/`:

```ini
; Variables de entorno
env[GOOGLE_CLIENT_ID] = "tu-client-id.apps.googleusercontent.com"
env[GOOGLE_CLIENT_SECRET] = "tu-client-secret"
```

## 🔧 Opción 2: Editar config.php en el Servidor

Si las variables de entorno no funcionan:

1. Conéctate por FTP a tu servidor
2. Edita `public_html/config/config.php`
3. Busca las líneas:
```php
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');
```

4. Reemplázalas con tus credenciales reales:
```php
define('GOOGLE_CLIENT_ID', 'TU-CLIENT-ID-AQUI');
define('GOOGLE_CLIENT_SECRET', 'TU-CLIENT-SECRET-AQUI');
```

**⚠️ IMPORTANTE:** 
- Solo edita el archivo **en el servidor**
- NO edites el archivo local ni lo subas a Git
- Mantén tus credenciales privadas

## 🧪 Verificar Configuración

Crea un archivo temporal `test-oauth.php` en `public_html/`:

```php
<?php
define('LDX_ACCESS', true);
require_once 'config/config.php';

echo "<h2>Verificación de Google OAuth</h2>";
echo "Client ID: " . (GOOGLE_CLIENT_ID ? "✅ Configurado" : "❌ No configurado") . "<br>";
echo "Client Secret: " . (GOOGLE_CLIENT_SECRET ? "✅ Configurado" : "❌ No configurado") . "<br>";
echo "Redirect URI: " . GOOGLE_REDIRECT_URI;
?>
```

Accede a `https://ldxsoftware.com.pe/test-oauth.php`

**Elimina el archivo después de verificar.**

## 🎯 Probar el Sistema Completo

1. Ve a: `https://ldxsoftware.com.pe/#suscripciones`
2. Haz clic en "Suscribirse Ahora"
3. Haz clic en "Continuar con Google"
4. Deberías ser redirigido a Google

## 🐛 Solución de Problemas

### Error: "Client ID no configurado"
- Verifica que configuraste las variables correctamente
- Reinicia el servidor si usaste .user.ini
- Prueba la Opción 2 (editar directamente)

### Error: "redirect_uri_mismatch"
Verifica en Google Console que tengas exactamente:
```
https://ldxsoftware.com.pe/auth/google/callback
```

### No redirige a Google
- Verifica que exista `public/auth/google/index.php`
- Revisa los logs de error de PHP en el panel de Ferozo

## 📞 Soporte

Si necesitas ayuda, contacta al soporte de Ferozo y pregunta:
- "¿Cómo configuro variables de entorno PHP?"
- "¿Puedo usar SetEnv en .htaccess?"
- "¿Soportan archivos .user.ini?"

---

**Recuerda:** Nunca subas tus credenciales a Git. Usa siempre variables de entorno o configúralas directamente en el servidor.
