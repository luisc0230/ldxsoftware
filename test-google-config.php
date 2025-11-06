<?php
/**
 * Test Google OAuth Configuration
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Google OAuth Config</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #1a1a1a;
            color: #fff;
        }
        .status {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 18px;
        }
        .success {
            background: #10b981;
            border: 2px solid #059669;
        }
        .error {
            background: #ef4444;
            border: 2px solid #dc2626;
        }
        .info {
            background: #3b82f6;
            border: 2px solid #2563eb;
        }
        code {
            background: #374151;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        h1 {
            color: #60a5fa;
        }
    </style>
</head>
<body>
    <h1>🔍 Test de Configuración Google OAuth</h1>
    
    <h2>Estado de Credenciales:</h2>
    
    <?php if (GOOGLE_CLIENT_ID && GOOGLE_CLIENT_ID !== ''): ?>
        <div class="status success">
            ✅ GOOGLE_CLIENT_ID: <strong>Configurado</strong>
            <br><small>Valor: <?php echo substr(GOOGLE_CLIENT_ID, 0, 20); ?>...</small>
        </div>
    <?php else: ?>
        <div class="status error">
            ❌ GOOGLE_CLIENT_ID: <strong>NO configurado</strong>
        </div>
    <?php endif; ?>
    
    <?php if (GOOGLE_CLIENT_SECRET && GOOGLE_CLIENT_SECRET !== ''): ?>
        <div class="status success">
            ✅ GOOGLE_CLIENT_SECRET: <strong>Configurado</strong>
            <br><small>Valor: <?php echo substr(GOOGLE_CLIENT_SECRET, 0, 10); ?>...</small>
        </div>
    <?php else: ?>
        <div class="status error">
            ❌ GOOGLE_CLIENT_SECRET: <strong>NO configurado</strong>
        </div>
    <?php endif; ?>
    
    <div class="status info">
        📍 GOOGLE_REDIRECT_URI: <code><?php echo GOOGLE_REDIRECT_URI; ?></code>
    </div>
    
    <div class="status info">
        🌐 BASE_URL: <code><?php echo BASE_URL; ?></code>
    </div>
    
    <hr style="margin: 30px 0; border-color: #374151;">
    
    <?php if (GOOGLE_CLIENT_ID && GOOGLE_CLIENT_SECRET): ?>
        <div class="status success">
            <h3>✅ ¡Configuración Completa!</h3>
            <p>Las credenciales de Google OAuth están configuradas correctamente.</p>
            <p>Ahora puedes probar el login en: <a href="<?php echo BASE_URL; ?>#suscripciones" style="color: #60a5fa;">Ir a Suscripciones</a></p>
        </div>
    <?php else: ?>
        <div class="status error">
            <h3>❌ Configuración Incompleta</h3>
            <p>Necesitas editar <code>config/config.php</code> en el servidor y agregar tus credenciales de Google Cloud Console.</p>
            <p>Las líneas 47-48 deben tener tus valores de Client ID y Client Secret.</p>
        </div>
    <?php endif; ?>
    
    <hr style="margin: 30px 0; border-color: #374151;">
    
    <p style="text-align: center; color: #9ca3af;">
        <small>Elimina este archivo después de verificar la configuración</small>
    </p>
</body>
</html>
