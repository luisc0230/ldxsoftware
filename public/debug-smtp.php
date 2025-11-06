<?php
// Debug detallado del sistema SMTP

header('Content-Type: text/html; charset=UTF-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Detallado del Sistema SMTP</h1>";

// Configuración SMTP
$smtpConfig = [
    'host' => 'a0020110.ferozo.com',
    'port' => 465,
    'username' => 'contacto@ldxsoftware.com.pe',
    'password' => 'R/zOx1Ao',
    'from_email' => 'contacto@ldxsoftware.com.pe',
    'from_name' => 'LDX Software',
    'to_email' => 'contacto@ldxsoftware.com.pe'
];

echo "<h2>1. Configuración SMTP:</h2>";
echo "<ul>";
foreach ($smtpConfig as $key => $value) {
    if ($key === 'password') {
        echo "<li><strong>$key:</strong> " . str_repeat('*', strlen($value)) . "</li>";
    } else {
        echo "<li><strong>$key:</strong> $value</li>";
    }
}
echo "</ul>";

echo "<h2>2. Verificación de Funciones PHP:</h2>";
$functions = ['stream_socket_client', 'stream_context_create', 'fwrite', 'fgets', 'base64_encode'];
foreach ($functions as $func) {
    $exists = function_exists($func);
    echo "<p>" . ($exists ? "✅" : "❌") . " <strong>$func:</strong> " . ($exists ? "Disponible" : "No disponible") . "</p>";
}

echo "<h2>3. Verificación de Extensiones:</h2>";
$extensions = ['openssl', 'sockets'];
foreach ($extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "<p>" . ($loaded ? "✅" : "❌") . " <strong>$ext:</strong> " . ($loaded ? "Cargada" : "No cargada") . "</p>";
}

echo "<h2>4. Test de Conexión Básica:</h2>";

try {
    echo "<p>🔍 Intentando resolver DNS...</p>";
    $ip = gethostbyname($smtpConfig['host']);
    echo "<p>✅ DNS resuelto: {$smtpConfig['host']} → $ip</p>";
    
    echo "<p>🔍 Creando contexto SSL...</p>";
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ]);
    echo "<p>✅ Contexto SSL creado</p>";
    
    echo "<p>🔍 Intentando conectar a {$smtpConfig['host']}:{$smtpConfig['port']}...</p>";
    
    $smtp = stream_socket_client(
        "ssl://{$smtpConfig['host']}:{$smtpConfig['port']}", 
        $errno, 
        $errstr, 
        10, 
        STREAM_CLIENT_CONNECT, 
        $context
    );

    if (!$smtp) {
        throw new Exception("Error de conexión: $errstr (Código: $errno)");
    }
    
    echo "<p>✅ Conexión SSL establecida exitosamente</p>";
    
    // Leer saludo del servidor
    echo "<p>🔍 Leyendo saludo del servidor...</p>";
    $response = fgets($smtp, 515);
    echo "<p><strong>Respuesta del servidor:</strong> <code>" . htmlspecialchars(trim($response)) . "</code></p>";
    
    if (substr($response, 0, 3) !== '220') {
        throw new Exception("Saludo SMTP inválido: $response");
    }
    
    echo "<p>✅ Saludo SMTP válido recibido</p>";
    
    // EHLO
    echo "<p>🔍 Enviando comando EHLO...</p>";
    fwrite($smtp, "EHLO " . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n");
    $response = fgets($smtp, 515);
    echo "<p><strong>Respuesta EHLO:</strong> <code>" . htmlspecialchars(trim($response)) . "</code></p>";
    
    if (substr($response, 0, 3) !== '250') {
        throw new Exception("Error en EHLO: $response");
    }
    
    echo "<p>✅ EHLO exitoso</p>";
    
    // AUTH LOGIN
    echo "<p>🔍 Iniciando autenticación...</p>";
    fwrite($smtp, "AUTH LOGIN\r\n");
    $response = fgets($smtp, 515);
    echo "<p><strong>Respuesta AUTH:</strong> <code>" . htmlspecialchars(trim($response)) . "</code></p>";
    
    if (substr($response, 0, 3) !== '334') {
        throw new Exception("Error en AUTH LOGIN: $response");
    }
    
    // Username
    echo "<p>🔍 Enviando username...</p>";
    fwrite($smtp, base64_encode($smtpConfig['username']) . "\r\n");
    $response = fgets($smtp, 515);
    echo "<p><strong>Respuesta username:</strong> <code>" . htmlspecialchars(trim($response)) . "</code></p>";
    
    if (substr($response, 0, 3) !== '334') {
        throw new Exception("Error en username: $response");
    }
    
    // Password
    echo "<p>🔍 Enviando password...</p>";
    fwrite($smtp, base64_encode($smtpConfig['password']) . "\r\n");
    $response = fgets($smtp, 515);
    echo "<p><strong>Respuesta password:</strong> <code>" . htmlspecialchars(trim($response)) . "</code></p>";
    
    if (substr($response, 0, 3) !== '235') {
        throw new Exception("Error en autenticación: $response");
    }
    
    echo "<p>✅ Autenticación exitosa</p>";
    
    // QUIT
    echo "<p>🔍 Cerrando conexión...</p>";
    fwrite($smtp, "QUIT\r\n");
    fclose($smtp);
    
    echo "<p>✅ Conexión cerrada correctamente</p>";
    
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 20px 0;'>";
    echo "<h3>🎉 ¡Test de Conexión SMTP Exitoso!</h3>";
    echo "<p>El servidor SMTP está funcionando correctamente. El problema puede estar en el envío del email específico.</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 20px 0;'>";
    echo "<h3>❌ Error en Test de Conexión SMTP</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "</div>";
    
    if (isset($smtp) && is_resource($smtp)) {
        fclose($smtp);
    }
}

echo "<h2>5. Información del Sistema:</h2>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "<li><strong>OS:</strong> " . php_uname() . "</li>";
echo "<li><strong>Server:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido') . "</li>";
echo "<li><strong>OpenSSL:</strong> " . (extension_loaded('openssl') ? OPENSSL_VERSION_TEXT : 'No disponible') . "</li>";
echo "</ul>";

echo "<h2>6. Test de Funciones de Red:</h2>";

// Test de fsockopen
echo "<p>🔍 Test con fsockopen...</p>";
$fp = @fsockopen('ssl://' . $smtpConfig['host'], $smtpConfig['port'], $errno, $errstr, 10);
if ($fp) {
    echo "<p>✅ fsockopen exitoso</p>";
    fclose($fp);
} else {
    echo "<p>❌ fsockopen falló: $errstr ($errno)</p>";
}

// Test de curl si está disponible
if (function_exists('curl_init')) {
    echo "<p>🔍 Test con cURL...</p>";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://{$smtpConfig['host']}:{$smtpConfig['port']}");
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "<p>⚠️ cURL: $error</p>";
    } else {
        echo "<p>✅ cURL conectó (código: $httpCode)</p>";
    }
}

echo "<hr>";
echo "<p><small>Debug realizado el " . date('Y-m-d H:i:s') . "</small></p>";
?>
