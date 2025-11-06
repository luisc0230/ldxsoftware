<?php
/**
 * Google OAuth - Callback DEBUG
 */

define('LDX_ACCESS', true);
require_once '../../../config/config.php';

// Mostrar errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Debug OAuth Callback</h1>";
echo "<h2>Configuración:</h2>";
echo "<pre>";
echo "GOOGLE_CLIENT_ID: " . (GOOGLE_CLIENT_ID ? "✅ Configurado" : "❌ NO configurado") . "\n";
echo "GOOGLE_CLIENT_SECRET: " . (GOOGLE_CLIENT_SECRET ? "✅ Configurado" : "❌ NO configurado") . "\n";
echo "BASE_URL: " . BASE_URL . "\n";
echo "GOOGLE_REDIRECT_URI: " . GOOGLE_REDIRECT_URI . "\n";
echo "</pre>";

echo "<h2>Datos recibidos:</h2>";
echo "<pre>";
echo "Code: " . ($_GET['code'] ?? 'NO recibido') . "\n";
echo "Scope: " . ($_GET['scope'] ?? 'NO recibido') . "\n";
echo "Error: " . ($_GET['error'] ?? 'Ninguno') . "\n";
echo "</pre>";

if (isset($_GET['code'])) {
    echo "<h2>Intentando obtener token...</h2>";
    
    $code = $_GET['code'];
    
    $params = [
        'code' => $code,
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri' => GOOGLE_REDIRECT_URI,
        'grant_type' => 'authorization_code'
    ];
    
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    echo "<pre>";
    echo "HTTP Code: " . $httpCode . "\n";
    if ($curlError) {
        echo "cURL Error: " . $curlError . "\n";
    }
    echo "Response: " . $response . "\n";
    echo "</pre>";
    
    if ($httpCode === 200) {
        $tokenData = json_decode($response, true);
        
        if (isset($tokenData['access_token'])) {
            echo "<h2>✅ Token obtenido correctamente</h2>";
            echo "<h3>Obteniendo información del usuario...</h3>";
            
            $ch2 = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $tokenData['access_token']
            ]);
            
            $userResponse = curl_exec($ch2);
            $userHttpCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
            curl_close($ch2);
            
            echo "<pre>";
            echo "User HTTP Code: " . $userHttpCode . "\n";
            echo "User Response: " . $userResponse . "\n";
            echo "</pre>";
            
            if ($userHttpCode === 200) {
                $userInfo = json_decode($userResponse, true);
                echo "<h2>✅ Información del usuario obtenida</h2>";
                echo "<pre>";
                print_r($userInfo);
                echo "</pre>";
                
                echo "<h3>Guardando en sesión...</h3>";
                $_SESSION['user'] = [
                    'id' => $userInfo['id'],
                    'email' => $userInfo['email'],
                    'name' => $userInfo['name'],
                    'picture' => $userInfo['picture'] ?? '',
                    'logged_in' => true,
                    'login_time' => time()
                ];
                
                echo "<pre>";
                echo "Sesión guardada:\n";
                print_r($_SESSION['user']);
                echo "</pre>";
                
                echo "<h2>✅ TODO CORRECTO</h2>";
                echo "<p><a href='" . BASE_URL . "'>Ir al inicio</a></p>";
            } else {
                echo "<h2>❌ Error al obtener información del usuario</h2>";
            }
        } else {
            echo "<h2>❌ No se recibió access_token</h2>";
        }
    } else {
        echo "<h2>❌ Error al obtener token de Google</h2>";
    }
} else {
    echo "<h2>❌ No se recibió el código de autorización</h2>";
}

echo "<hr>";
echo "<h2>Sesión actual:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
