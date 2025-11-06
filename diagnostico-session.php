<?php
/**
 * Diagnóstico de Sesión y BD
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnóstico de Sesión</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1a1a1a; color: #fff; }
        .box { background: #2a2a2a; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #4caf50; }
        .error { border-left-color: #f44336; }
        .warning { border-left-color: #ff9800; }
        pre { background: #000; padding: 10px; overflow-x: auto; }
        h2 { color: #4caf50; margin-top: 20px; }
        .success { color: #4caf50; }
        .fail { color: #f44336; }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico Completo del Sistema</h1>
    
    <?php
    // 1. Verificar sesión
    echo '<div class="box">';
    echo '<h2>1️⃣ Estado de la Sesión PHP</h2>';
    echo '<p><strong>Session ID:</strong> ' . session_id() . '</p>';
    
    if (isset($_SESSION['user'])) {
        echo '<p class="success">✅ Sesión de usuario existe</p>';
        echo '<pre>';
        print_r($_SESSION['user']);
        echo '</pre>';
        
        // Verificar tipo de ID
        $userId = $_SESSION['user']['id'] ?? null;
        if ($userId) {
            if (is_numeric($userId) && $userId < 1000000000) {
                echo '<p class="success">✅ ID es numérico de BD: ' . $userId . '</p>';
            } else {
                echo '<p class="fail">❌ ID parece ser Google ID (string largo): ' . substr($userId, 0, 30) . '...</p>';
                echo '<p class="warning">⚠️ PROBLEMA: El usuario NO se guardó en la BD</p>';
            }
        }
    } else {
        echo '<p class="fail">❌ NO hay sesión de usuario</p>';
        echo '<p><a href="' . BASE_URL . 'auth/google">Iniciar sesión con Google</a></p>';
    }
    echo '</div>';
    
    // 2. Verificar conexión a BD
    echo '<div class="box">';
    echo '<h2>2️⃣ Conexión a Base de Datos</h2>';
    try {
        require_once __DIR__ . '/app/models/Database.php';
        $db = Database::getInstance()->getConnection();
        echo '<p class="success">✅ Conexión a BD exitosa</p>';
        echo '<p><strong>Database:</strong> ' . DB_NAME . '</p>';
        echo '<p><strong>Host:</strong> ' . DB_HOST . '</p>';
    } catch (Exception $e) {
        echo '<p class="fail">❌ Error de conexión: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    echo '</div>';
    
    // 3. Verificar tabla usuarios
    if (isset($db)) {
        echo '<div class="box">';
        echo '<h2>3️⃣ Tabla usuarios</h2>';
        
        $result = $db->query("SHOW TABLES LIKE 'usuarios'");
        if ($result && $result->num_rows > 0) {
            echo '<p class="success">✅ Tabla usuarios existe</p>';
            
            // Contar usuarios
            $result = $db->query("SELECT COUNT(*) as total FROM usuarios");
            $row = $result->fetch_assoc();
            echo '<p><strong>Total de usuarios:</strong> ' . $row['total'] . '</p>';
            
            // Mostrar últimos 3 usuarios
            if ($row['total'] > 0) {
                echo '<p><strong>Últimos usuarios registrados:</strong></p>';
                $result = $db->query("SELECT id, google_id, email, nombre, fecha_registro, ultimo_login FROM usuarios ORDER BY id DESC LIMIT 3");
                echo '<pre>';
                while ($user = $result->fetch_assoc()) {
                    echo "ID: {$user['id']}\n";
                    echo "Google ID: {$user['google_id']}\n";
                    echo "Email: {$user['email']}\n";
                    echo "Nombre: {$user['nombre']}\n";
                    echo "Último login: {$user['ultimo_login']}\n";
                    echo "---\n";
                }
                echo '</pre>';
                
                // Si hay sesión, buscar el usuario en la BD
                if (isset($_SESSION['user']['email'])) {
                    $email = $db->real_escape_string($_SESSION['user']['email']);
                    $result = $db->query("SELECT * FROM usuarios WHERE email = '$email'");
                    
                    if ($result && $result->num_rows > 0) {
                        $userInDb = $result->fetch_assoc();
                        echo '<div class="box">';
                        echo '<p class="success">✅ Tu usuario SÍ está en la BD:</p>';
                        echo '<pre>';
                        print_r($userInDb);
                        echo '</pre>';
                        
                        // Comparar IDs
                        if ($_SESSION['user']['id'] != $userInDb['id']) {
                            echo '<p class="fail">❌ PROBLEMA ENCONTRADO:</p>';
                            echo '<p>ID en sesión: ' . $_SESSION['user']['id'] . '</p>';
                            echo '<p>ID en BD: ' . $userInDb['id'] . '</p>';
                            echo '<p class="warning">⚠️ La sesión tiene el Google ID en vez del ID de la BD</p>';
                        }
                        echo '</div>';
                    } else {
                        echo '<p class="fail">❌ Tu usuario NO está en la BD</p>';
                        echo '<p>Email buscado: ' . htmlspecialchars($_SESSION['user']['email']) . '</p>';
                    }
                }
            }
        } else {
            echo '<p class="fail">❌ Tabla usuarios NO existe</p>';
            echo '<p>Ejecuta database/schema.sql en phpMyAdmin</p>';
        }
        echo '</div>';
    }
    
    // 4. Test de inserción
    if (isset($db) && isset($_SESSION['user'])) {
        echo '<div class="box">';
        echo '<h2>4️⃣ Test de Guardar Usuario</h2>';
        
        try {
            require_once __DIR__ . '/app/models/Usuario.php';
            
            // Simular datos de Google
            $testData = [
                'id' => $_SESSION['user']['google_id'] ?? $_SESSION['user']['id'],
                'email' => $_SESSION['user']['email'],
                'name' => $_SESSION['user']['name'],
                'picture' => $_SESSION['user']['picture'] ?? ''
            ];
            
            echo '<p>Intentando guardar/actualizar usuario...</p>';
            echo '<pre>Datos: ' . json_encode($testData, JSON_PRETTY_PRINT) . '</pre>';
            
            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->createOrUpdateFromGoogle($testData);
            
            if ($usuario) {
                echo '<p class="success">✅ Usuario guardado/actualizado exitosamente</p>';
                echo '<pre>';
                print_r($usuario);
                echo '</pre>';
                
                // Actualizar sesión con el ID correcto
                if ($usuario['id'] != $_SESSION['user']['id']) {
                    echo '<p class="warning">⚠️ Actualizando sesión con ID correcto...</p>';
                    $_SESSION['user']['id'] = $usuario['id'];
                    echo '<p class="success">✅ Sesión actualizada. ID nuevo: ' . $usuario['id'] . '</p>';
                }
            } else {
                echo '<p class="fail">❌ Error al guardar usuario</p>';
            }
        } catch (Exception $e) {
            echo '<p class="fail">❌ Excepción: ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        }
        
        echo '</div>';
    }
    
    // 5. Verificar API de suscripciones
    echo '<div class="box">';
    echo '<h2>5️⃣ Test de API de Suscripciones</h2>';
    echo '<p>Hacer clic en el botón para probar:</p>';
    echo '<button onclick="testAPI()" style="padding: 10px 20px; background: #4caf50; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Probar API de Suscripción
    </button>';
    echo '<div id="apiResult" style="margin-top: 10px;"></div>';
    echo '</div>';
    ?>
    
    <script>
        async function testAPI() {
            const resultDiv = document.getElementById('apiResult');
            resultDiv.innerHTML = '<p>⏳ Probando API...</p>';
            
            try {
                const response = await fetch('<?php echo BASE_URL; ?>api/iniciar-suscripcion.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        plan_id: 1,
                        tipo_pago: 'mensual',
                        precio: 29
                    })
                });
                
                const data = await response.json();
                console.log('Respuesta de API:', data);
                
                resultDiv.innerHTML = `
                    <div class="box ${data.logged_in ? '' : 'warning'}">
                        <h3>Respuesta de la API:</h3>
                        <pre>${JSON.stringify(data, null, 2)}</pre>
                        ${data.logged_in ? 
                            '<p class="success">✅ API detecta que estás logueado</p>' : 
                            '<p class="fail">❌ API NO detecta que estás logueado</p>'
                        }
                    </div>
                `;
            } catch (error) {
                console.error('Error:', error);
                resultDiv.innerHTML = `<p class="fail">❌ Error: ${error.message}</p>`;
            }
        }
    </script>
    
    <div class="box">
        <h2>📝 Acciones Recomendadas</h2>
        <ol>
            <li>Si el ID en sesión es un Google ID largo, <a href="<?php echo BASE_URL; ?>auth/logout">cierra sesión</a> y vuelve a iniciar sesión</li>
            <li>Verifica los logs del servidor en cPanel > Error Log</li>
            <li>Si todo está OK aquí, prueba seleccionar un plan en la página principal</li>
        </ol>
    </div>
    
    <div style="margin-top: 30px; padding: 20px; background: #000; border-radius: 5px;">
        <h3>🔗 Enlaces Útiles:</h3>
        <ul>
            <li><a href="<?php echo BASE_URL; ?>" style="color: #4caf50;">Ir al inicio</a></li>
            <li><a href="<?php echo BASE_URL; ?>auth/logout" style="color: #ff9800;">Cerrar sesión</a></li>
            <li><a href="<?php echo BASE_URL; ?>auth/google" style="color: #4caf50;">Iniciar sesión con Google</a></li>
            <li><a href="<?php echo BASE_URL; ?>test-db" style="color: #4caf50;">Ver test-db</a></li>
        </ul>
    </div>
</body>
</html>
