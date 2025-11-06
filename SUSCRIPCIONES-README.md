# 🚀 Sistema de Suscripciones con Culqi y Google OAuth

## 📋 Características Implementadas

✅ **3 Planes de Suscripción**
- Plan Básico: S/ 99/mes
- Plan Profesional: S/ 199/mes (Destacado)
- Plan Empresarial: S/ 399/mes

✅ **Autenticación con Google OAuth**
- Login seguro con cuenta de Google
- No requiere registro manual
- Información del usuario automática

✅ **Integración con Culqi**
- Procesamiento de pagos seguro
- Suscripciones recurrentes mensuales
- Gestión de tarjetas y clientes

✅ **Interfaz Moderna**
- Diseño responsive con Tailwind CSS
- Animaciones y efectos visuales
- Modal de login elegante
- Página de éxito con confetti

## 🔧 Configuración Requerida

### 1. Configurar Google OAuth

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Crea un nuevo proyecto o selecciona uno existente
3. Habilita la API de Google+ 
4. Ve a "Credenciales" y crea credenciales OAuth 2.0
5. Configura las URIs autorizadas:
   - URI de redirección: `https://ldxsoftware.com.pe/auth/google/callback`
   - Orígenes autorizados: `https://ldxsoftware.com.pe`

6. Copia las credenciales y actualiza `config/config.php`:
```php
define('GOOGLE_CLIENT_ID', 'tu-client-id.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'tu-client-secret');
```

### 2. Configurar Culqi

Las llaves de prueba ya están configuradas en `config/config.php`:
```php
define('CULQI_PUBLIC_KEY', 'pk_test_pFFwfwNWeARhXrgN');
define('CULQI_SECRET_KEY', 'sk_test_1JdA4a8tJsBlrCpG');
```

Para producción, reemplaza con tus llaves reales de Culqi.

### 3. Crear Directorios de Datos

Asegúrate de que existan estos directorios con permisos de escritura:
```bash
mkdir -p app/data
chmod 755 app/data
```

Los siguientes archivos JSON se crearán automáticamente:
- `app/data/users.json` - Usuarios registrados
- `app/data/culqi_plans.json` - Planes de Culqi
- `app/data/culqi_customers.json` - Clientes de Culqi
- `app/data/subscriptions.json` - Suscripciones activas

## 📁 Archivos Creados

### Controladores
- `app/controllers/AuthController.php` - Autenticación con Google
- `app/controllers/SubscriptionController.php` - Gestión de suscripciones con Culqi

### Vistas
- `app/includes/suscripciones.php` - Sección de planes de suscripción
- `public/checkout.php` - Página de checkout
- `public/success.php` - Página de éxito

### API Endpoints
- `public/api/subscription/process.php` - Procesar suscripción
- `public/auth/google/index.php` - Iniciar OAuth
- `public/auth/google/callback.php` - Callback OAuth

### Configuración
- `config/config.php` - Actualizado con credenciales de Culqi y Google

## 🎯 Flujo de Usuario

1. **Usuario visita la página** → Ve los 3 planes de suscripción
2. **Selecciona un plan** → Se abre modal de login
3. **Inicia sesión con Google** → Redirige a Google OAuth
4. **Google autentica** → Regresa a la aplicación
5. **Usuario guardado** → Redirige a checkout
6. **Completa pago con Culqi** → Ingresa datos de tarjeta
7. **Suscripción creada** → Redirige a página de éxito
8. **Confirmación** → Usuario recibe confirmación

## 🔐 Seguridad

- ✅ Autenticación OAuth 2.0 con Google
- ✅ Tokens de Culqi procesados en servidor
- ✅ Validación de sesiones
- ✅ Protección CSRF
- ✅ Comunicación HTTPS
- ✅ Datos sensibles no expuestos al cliente

## 🧪 Modo de Prueba

El sistema está configurado en modo de prueba con las llaves de Culqi proporcionadas.

**Tarjetas de prueba de Culqi:**
- Visa: `4111 1111 1111 1111`
- Mastercard: `5111 1111 1111 1118`
- CVV: `123`
- Fecha: Cualquier fecha futura
- Email: Cualquier email válido

## 📊 Gestión de Suscripciones

### Ver suscripciones activas
Las suscripciones se guardan en `app/data/subscriptions.json`

### Cancelar una suscripción
Puedes crear un endpoint adicional o usar el panel de Culqi.

### Webhooks de Culqi
Para recibir notificaciones de eventos (cargos exitosos, fallidos, etc.), configura un webhook en el panel de Culqi apuntando a:
```
https://ldxsoftware.com.pe/api/webhooks/culqi
```

## 🎨 Personalización

### Cambiar precios de planes
Edita `app/includes/suscripciones.php` y actualiza los precios en:
- HTML de las tarjetas
- Función `iniciarSuscripcion()`

También actualiza `SubscriptionController.php` en el método `getPlanData()`.

### Agregar más planes
1. Agrega una nueva tarjeta en `suscripciones.php`
2. Actualiza el método `getPlanData()` en `SubscriptionController.php`
3. Actualiza la validación en `api/subscription/process.php`

### Personalizar diseño
Los estilos usan Tailwind CSS. Modifica las clases en los archivos PHP.

## 🐛 Solución de Problemas

### Error: "No autenticado"
- Verifica que las credenciales de Google estén correctas
- Asegúrate de que la URI de redirección coincida exactamente

### Error al crear suscripción
- Verifica las llaves de Culqi
- Revisa los logs en `app/logs/error.log`
- Verifica que los directorios `app/data/` tengan permisos de escritura

### Modal no se abre
- Verifica que JavaScript esté habilitado
- Revisa la consola del navegador para errores

### Culqi Checkout no aparece
- Verifica que la llave pública de Culqi sea correcta
- Asegúrate de que el script de Culqi se cargue correctamente

## 📞 Soporte

Para más información sobre:
- **Culqi**: https://docs.culqi.com/
- **Google OAuth**: https://developers.google.com/identity/protocols/oauth2

## 🚀 Próximos Pasos

1. **Configurar Google OAuth** con tus credenciales
2. **Probar el flujo completo** en modo de prueba
3. **Configurar webhooks** de Culqi
4. **Cambiar a llaves de producción** cuando estés listo
5. **Agregar panel de administración** para gestionar suscripciones

---

**¡El sistema está listo para usar!** Solo necesitas configurar las credenciales de Google OAuth.
