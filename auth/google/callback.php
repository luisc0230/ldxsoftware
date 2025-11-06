<?php
/**
 * Google OAuth - Callback
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';

// Enable full error display
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Enable debugging
error_log("=== OAuth Callback Iniciado ===");
error_log("GET params: " . json_encode($_GET));
error_log("Session ID: " . session_id());
error_log("GOOGLE_CLIENT_ID configurado: " . (GOOGLE_CLIENT_ID ? 'SI' : 'NO'));
error_log("GOOGLE_CLIENT_SECRET configurado: " . (GOOGLE_CLIENT_SECRET ? 'SI' : 'NO'));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Autenticando...</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        #status { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .error { color: red; }
        .success { color: green; }
        pre { background: #f9f9f9; padding: 10px; overflow-x: auto; }
    </style>
    <script>
        console.log("=== OAuth Callback Cargado ===");
        console.log("URL actual:", window.location.href);
        console.log("GET params:", <?php echo json_encode($_GET); ?>);
        console.log("GOOGLE_CLIENT_ID configurado:", <?php echo GOOGLE_CLIENT_ID ? 'true' : 'false'; ?>);
        console.log("GOOGLE_CLIENT_SECRET configurado:", <?php echo GOOGLE_CLIENT_SECRET ? 'true' : 'false'; ?>);
    </script>
</head>
<body>
    <h1>Procesando Autenticación...</h1>
    <div id="status">
        <p>✅ Callback cargado correctamente</p>
        <p>✅ Código recibido de Google</p>
        <p>⏳ Intercambiando código por token...</p>
        
        <h3>Configuración:</h3>
        <pre>
GOOGLE_CLIENT_ID: <?php echo GOOGLE_CLIENT_ID ? '✅ Configurado (' . strlen(GOOGLE_CLIENT_ID) . ' chars)' : '❌ NO CONFIGURADO'; ?>

GOOGLE_CLIENT_SECRET: <?php echo GOOGLE_CLIENT_SECRET ? '✅ Configurado (' . strlen(GOOGLE_CLIENT_SECRET) . ' chars)' : '❌ NO CONFIGURADO'; ?>

REDIRECT_URI: <?php echo GOOGLE_REDIRECT_URI; ?>
        </pre>
        
        <?php if (!GOOGLE_CLIENT_ID || !GOOGLE_CLIENT_SECRET): ?>
        <div class="error">
            <h2>❌ ERROR: Credenciales no configuradas</h2>
            <p>Las credenciales de Google OAuth no están configuradas en el servidor.</p>
            <p><a href="<?php echo BASE_URL; ?>auth/check-config.php">Verificar Configuración</a></p>
        </div>
        <?php endif; ?>
    </div>
    <script>
        console.log("Iniciando proceso de callback...");
    </script>
<?php
// Solo intentar el callback si las credenciales están configuradas
if (!GOOGLE_CLIENT_ID || !GOOGLE_CLIENT_SECRET) {
    echo '<div class="error"><h2>⚠️ Detenido: Configura las credenciales primero</h2></div>';
    echo '<p><a href="' . BASE_URL . '">Volver al inicio</a></p>';
    echo '</body></html>';
    exit;
}

echo '<p>Procesando...</p>';
flush();

try {
    $authController = new AuthController();
    $authController->googleCallback();
} catch (Exception $e) {
    echo '<div class="error">';
    echo '<h2>❌ Error durante el callback:</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
    error_log("EXCEPCIÓN en callback: " . $e->getMessage());
}
?>
