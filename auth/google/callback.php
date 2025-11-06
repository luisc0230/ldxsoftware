<?php
/**
 * Google OAuth - Callback
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';

// Enable error logging (but not display to avoid headers already sent)
error_reporting(E_ALL);
ini_set('log_errors', 1);

// Enable debugging
error_log("=== OAuth Callback Iniciado ===");
error_log("GET params: " . json_encode($_GET));
error_log("Session ID: " . session_id());
error_log("GOOGLE_CLIENT_ID configurado: " . (GOOGLE_CLIENT_ID ? 'SI' : 'NO'));
error_log("GOOGLE_CLIENT_SECRET configurado: " . (GOOGLE_CLIENT_SECRET ? 'SI' : 'NO'));

// Check credentials first
if (!GOOGLE_CLIENT_ID || !GOOGLE_CLIENT_SECRET) {
    error_log("ERROR: Credenciales de Google no configuradas");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error de Configuración</title>
        <style>
            body { font-family: Arial; padding: 20px; background: #f5f5f5; }
            .error { background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #f44336; }
        </style>
    </head>
    <body>
        <div class="error">
            <h2>❌ ERROR: Credenciales no configuradas</h2>
            <p>Las credenciales de Google OAuth no están configuradas en el servidor.</p>
            <p><a href="<?php echo BASE_URL; ?>auth/check-config.php">Verificar Configuración</a></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Process callback - NO OUTPUT before this to allow header redirect
try {
    $authController = new AuthController();
    $authController->googleCallback();
    
    // If we reach here, redirect failed - show error
    error_log("ERROR: googleCallback() no redirigió");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error de Autenticación</title>
        <style>
            body { font-family: Arial; padding: 20px; background: #f5f5f5; }
            .error { background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #f44336; }
        </style>
    </head>
    <body>
        <div class="error">
            <h2>❌ Error: No se pudo completar la redirección</h2>
            <p>El proceso de autenticación no pudo redirigir correctamente.</p>
            <p><a href="<?php echo BASE_URL; ?>">Volver al inicio</a></p>
        </div>
    </body>
    </html>
    <?php
    
} catch (Exception $e) {
    error_log("EXCEPCIÓN en callback: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error de Autenticación</title>
        <style>
            body { font-family: Arial; padding: 20px; background: #f5f5f5; }
            .error { background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #f44336; }
            pre { background: #f9f9f9; padding: 10px; overflow-x: auto; }
        </style>
    </head>
    <body>
        <div class="error">
            <h2>❌ Error durante el callback</h2>
            <p><?php echo htmlspecialchars($e->getMessage()); ?></p>
            <pre><?php echo htmlspecialchars($e->getTraceAsString()); ?></pre>
            <p><a href="<?php echo BASE_URL; ?>">Volver al inicio</a></p>
        </div>
    </body>
    </html>
    <?php
}
?>
