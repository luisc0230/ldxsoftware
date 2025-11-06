<?php
/**
 * Google OAuth - Callback
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';

// Enable debugging
error_log("=== OAuth Callback Iniciado ===");
error_log("GET params: " . json_encode($_GET));
error_log("Session ID: " . session_id());
?>
<!DOCTYPE html>
<html>
<head>
    <title>Autenticando...</title>
    <script>
        console.log("=== OAuth Callback Cargado ===");
        console.log("URL actual:", window.location.href);
        console.log("GET params:", <?php echo json_encode($_GET); ?>);
    </script>
</head>
<body>
    <div id="status">Procesando autenticación...</div>
    <script>
        console.log("Iniciando proceso de callback...");
    </script>
</body>
</html>
<?php

$authController = new AuthController();
$authController->googleCallback();
