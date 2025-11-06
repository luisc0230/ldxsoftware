<?php
/**
 * Test Callback - Debug
 */

// Mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('LDX_ACCESS', true);

echo "<h1>Test Callback Debug</h1>";
echo "<pre>";

echo "=== STEP 1: Loading config ===\n";
try {
    require_once __DIR__ . '/config/config.php';
    echo "✅ Config loaded\n";
    echo "BASE_URL: " . BASE_URL . "\n";
    echo "GOOGLE_CLIENT_ID: " . (GOOGLE_CLIENT_ID ? "✅ Set" : "❌ Not set") . "\n";
    echo "GOOGLE_CLIENT_SECRET: " . (GOOGLE_CLIENT_SECRET ? "✅ Set" : "❌ Not set") . "\n";
} catch (Exception $e) {
    echo "❌ Error loading config: " . $e->getMessage() . "\n";
    die();
}

echo "\n=== STEP 2: Loading AuthController ===\n";
try {
    require_once __DIR__ . '/app/controllers/AuthController.php';
    echo "✅ AuthController loaded\n";
} catch (Exception $e) {
    echo "❌ Error loading AuthController: " . $e->getMessage() . "\n";
    die();
}

echo "\n=== STEP 3: Checking for code parameter ===\n";
if (isset($_GET['code'])) {
    echo "✅ Code parameter found: " . substr($_GET['code'], 0, 20) . "...\n";
} else {
    echo "❌ No code parameter found\n";
    echo "GET parameters: " . print_r($_GET, true) . "\n";
}

echo "\n=== STEP 4: Creating AuthController instance ===\n";
try {
    $authController = new AuthController();
    echo "✅ AuthController instance created\n";
} catch (Exception $e) {
    echo "❌ Error creating AuthController: " . $e->getMessage() . "\n";
    die();
}

echo "\n=== STEP 5: Calling googleCallback() ===\n";
try {
    $authController->googleCallback();
    echo "✅ googleCallback() executed\n";
} catch (Exception $e) {
    echo "❌ Error in googleCallback(): " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== END ===\n";
echo "</pre>";
?>
