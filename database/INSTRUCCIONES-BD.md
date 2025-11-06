# 🗄️ Instrucciones para Configurar la Base de Datos

## Paso 1: Acceder a phpMyAdmin

1. Ve a tu panel de hosting (cPanel)
2. Busca y abre **phpMyAdmin**
3. Selecciona la base de datos: `a0020110_ldx`

## Paso 2: Ejecutar el Script SQL

1. En phpMyAdmin, haz clic en la pestaña **SQL**
2. Abre el archivo `database/schema.sql`
3. Copia TODO el contenido del archivo
4. Pégalo en el área de texto de phpMyAdmin
5. Haz clic en **Continuar** o **Go**

## Paso 3: Verificar las Tablas Creadas

Deberías ver las siguientes tablas creadas:

✅ **usuarios** - Almacena los usuarios registrados con Google OAuth
- `id` - ID único del usuario
- `google_id` - ID de Google del usuario
- `email` - Email del usuario
- `nombre` - Nombre completo
- `foto` - URL de la foto de perfil
- `fecha_registro` - Cuándo se registró
- `ultimo_login` - Último inicio de sesión

✅ **planes** - Planes de suscripción disponibles
- `id` - ID del plan (1=Básico, 2=Profesional, 3=Empresarial)
- `nombre` - Nombre del plan
- `precio_mensual` - Precio mensual
- `precio_anual` - Precio anual (con descuento)

✅ **suscripciones** - Suscripciones activas/historial
- `id` - ID de la suscripción
- `usuario_id` - ID del usuario
- `plan_id` - ID del plan
- `estado` - pendiente, activa, cancelada, expirada
- `fecha_inicio` - Cuándo inició
- `fecha_fin` - Cuándo expira

✅ **pagos** - Historial de pagos con Culqi
- `id` - ID del pago
- `suscripcion_id` - ID de la suscripción
- `monto` - Monto pagado
- `culqi_charge_id` - ID del cargo en Culqi
- `estado` - completado, fallido, reembolsado

✅ **checkout_sessions** - Sesiones de checkout temporales
- Guarda el estado del checkout antes del pago

## Paso 4: Verificar Datos Iniciales

El script ya inserta 3 planes por defecto:

| ID | Nombre | Precio Mensual | Precio Anual |
|----|--------|----------------|--------------|
| 1  | Básico | S/ 29.00 | S/ 290.00 |
| 2  | Profesional | S/ 59.00 | S/ 590.00 |
| 3  | Empresarial | S/ 99.00 | S/ 990.00 |

Para verificar, ejecuta en SQL:
```sql
SELECT * FROM planes;
```

## Paso 5: Probar el Sistema

### A. Probar Login
1. Ve a: `https://ldxsoftware.com.pe/`
2. Haz clic en "Suscribirse Ahora" en cualquier plan
3. Inicia sesión con Google
4. Verifica en phpMyAdmin que se creó un registro en la tabla `usuarios`

```sql
SELECT * FROM usuarios ORDER BY id DESC LIMIT 5;
```

### B. Probar Suscripción
1. Estando logueado, selecciona un plan
2. Completa el pago con Culqi (usa tarjeta de prueba)
3. Verifica que se crearon registros en:

```sql
-- Ver suscripciones
SELECT s.*, u.email, p.nombre as plan 
FROM suscripciones s
JOIN usuarios u ON s.usuario_id = u.id
JOIN planes p ON s.plan_id = p.id
ORDER BY s.id DESC;

-- Ver pagos
SELECT * FROM pagos ORDER BY id DESC LIMIT 5;
```

## Consultas Útiles

### Ver usuarios con suscripciones activas
```sql
SELECT u.nombre, u.email, p.nombre as plan, s.estado, s.fecha_fin
FROM usuarios u
JOIN suscripciones s ON u.id = s.usuario_id
JOIN planes p ON s.plan_id = p.id
WHERE s.estado = 'activa'
ORDER BY s.fecha_creacion DESC;
```

### Ver ingresos totales
```sql
SELECT 
    COUNT(*) as total_pagos,
    SUM(monto) as ingresos_totales,
    AVG(monto) as promedio_pago
FROM pagos
WHERE estado = 'completado';
```

### Ver suscripciones por plan
```sql
SELECT 
    p.nombre as plan,
    COUNT(*) as cantidad,
    SUM(s.precio_pagado) as ingresos
FROM suscripciones s
JOIN planes p ON s.plan_id = p.id
WHERE s.estado = 'activa'
GROUP BY p.id;
```

## Mantenimiento

### Expirar suscripciones vencidas
Ejecuta esto periódicamente (o crea un cron job):

```sql
UPDATE suscripciones 
SET estado = 'expirada' 
WHERE estado = 'activa' 
AND fecha_fin < NOW();
```

### Limpiar sesiones de checkout antiguas
```sql
DELETE FROM checkout_sessions 
WHERE estado = 'pendiente' 
AND fecha_expiracion < NOW();
```

## Respaldo

### Hacer backup de la base de datos
1. En phpMyAdmin, selecciona la base de datos
2. Haz clic en **Exportar**
3. Selecciona **Método rápido**
4. Formato: **SQL**
5. Haz clic en **Continuar**

### Restaurar backup
1. En phpMyAdmin, selecciona la base de datos
2. Haz clic en **Importar**
3. Selecciona el archivo `.sql`
4. Haz clic en **Continuar**

## Solución de Problemas

### Error: "Table already exists"
Si las tablas ya existen, puedes:
1. Eliminarlas primero: `DROP TABLE IF EXISTS nombre_tabla;`
2. O modificar el script para usar `CREATE TABLE IF NOT EXISTS`

### Error de conexión
Verifica en `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'a0020110_ldx');
define('DB_PASS', 'deNEmi60ka');
define('DB_NAME', 'a0020110_ldx');
```

### Ver errores de PHP
Revisa los logs en: cPanel > Error Log

O agrega al inicio de tus archivos PHP:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Seguridad

⚠️ **IMPORTANTE:**
1. **NUNCA** subas `config.php` con credenciales a Git público
2. Usa variables de entorno en producción
3. Haz backups regulares de la base de datos
4. Mantén actualizado el sistema

---

¿Necesitas ayuda? Revisa los logs del servidor o contacta al soporte técnico.
