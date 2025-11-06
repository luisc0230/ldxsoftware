<?php
/**
 * ARREGLAR SESIÓN - Guardar usuario en BD
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Arreglar Sesión</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #1a1a1a; color: #fff; max-width: 900px; margin: 0 auto; }
        .box { background: #2a2a2a; padding: 20px; margin: 15px 0; border-radius: 8px; border-left: 5px solid #4caf50; }
        .error { border-left-color: #f44336; }
        .warning { border-left-color: #ff9800; }
        pre { background: #000; padding: 15px; overflow-x: auto; border-radius: 5px; }
        h1 { color: #4caf50; }
        h2 { color: #2196F3; margin-top: 25px; }
        .btn { background: #4caf50; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #45a049; }
        .success { color: #4caf50; font-weight: bold; }
        .fail { color: #f44336; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        td { padding: 8px; border-bottom: 1px solid #444; }
        td:first-child { font-weight: bold; width: 200px; }
    </style>
</head>
<body>
    <h1>🔧 Arreglar Sesión y BD</h1>
    
    <?php
    echo '<div class="box">';
    echo '<h2>📊 Estado Actual</h2>';
    
    // 1. Verificar sesión
    echo '<h3>1️⃣ Sesión PHP</h3>';
    echo '<table>';
    echo '<tr><td>Session ID:</td><td>' . session_id() . '</td></tr>';
    echo '<tr><td>Usuario en sesión:</td><td>' . (isset($_SESSION['user']) ? '✅ SI' : '❌ NO') . '</td></tr>';
    
    if (isset($_SESSION['user'])) {
        echo '<tr><td>Email:</td><td>' . htmlspecialchars($_SESSION['user']['email'] ?? 'N/A') . '</td></tr>';
        echo '<tr><td>Nombre:</td><td>' . htmlspecialchars($_SESSION['user']['name'] ?? 'N/A') . '</td></tr>';
        
        $userId = $_SESSION['user']['id'] ?? null;
        $isNumericId = is_numeric($userId) && $userId < 1000000000;
        
        if ($isNumericId) {
            echo '<tr><td>ID en sesión:</td><td class="success">' . $userId . ' ✅ (ID de BD)</td></tr>';
        } else {
            echo '<tr><td>ID en sesión:</td><td class="fail">' . htmlspecialchars($userId) . ' ❌ (Google ID)</td></tr>';
        }
        
        if (isset($_SESSION['user']['google_id'])) {
            echo '<tr><td>Google ID:</td><td>' . htmlspecialchars($_SESSION['user']['google_id']) . '</td></tr>';
        }
    }
    echo '</table>';
    echo '</div>';
    
    // 2. Test de conexión BD
    echo '<div class="box">';
    echo '<h3>2️⃣ Conexión a Base de Datos</h3>';
    
    try {
        require_once __DIR__ . '/app/models/Database.php';
        $db = Database::getInstance()->getConnection();
        
        echo '<p class="success">✅ Conexión exitosa</p>';
        echo '<table>';
        echo '<tr><td>Host:</td><td>' . DB_HOST . '</td></tr>';
        echo '<tr><td>Database:</td><td>' . DB_NAME . '</td></tr>';
        echo '<tr><td>User:</td><td>' . DB_USER . '</td></tr>';
        echo '</table>';
        
        // Verificar tabla usuarios
        $result = $db->query("SHOW TABLES LIKE 'usuarios'");
        if ($result && $result->num_rows > 0) {
            echo '<p class="success">✅ Tabla usuarios existe</p>';
            
            // Contar usuarios
            $result = $db->query("SELECT COUNT(*) as total FROM usuarios");
            $row = $result->fetch_assoc();
            echo '<p>Total usuarios en BD: <strong>' . $row['total'] . '</strong></p>';
        } else {
            echo '<p class="fail">❌ Tabla usuarios NO existe</p>';
            echo '<p>Ejecuta database/schema.sql en phpMyAdmin</p>';
        }
        
    } catch (Exception $e) {
        echo '<p class="fail">❌ Error de conexión: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    echo '</div>';
    
    // 3. Intentar guardar usuario si está logueado
    if (isset($_SESSION['user']) && isset($db)) {
        echo '<div class="box warning">';
        echo '<h3>3️⃣ Guardar Usuario en BD</h3>';
        
        try {
            require_once __DIR__ . '/app/models/Usuario.php';
            
            // Preparar datos
            $googleId = $_SESSION['user']['google_id'] ?? $_SESSION['user']['id'];
            $email = $_SESSION['user']['email'];
            $nombre = $_SESSION['user']['name'];
            $foto = $_SESSION['user']['picture'] ?? '';
            
            echo '<p><strong>Datos del usuario:</strong></p>';
            echo '<table>';
            echo '<tr><td>Google ID:</td><td>' . htmlspecialchars($googleId) . '</td></tr>';
            echo '<tr><td>Email:</td><td>' . htmlspecialchars($email) . '</td></tr>';
            echo '<tr><td>Nombre:</td><td>' . htmlspecialchars($nombre) . '</td></tr>';
            echo '</table>';
            
            // Verificar si ya existe en BD
            $stmt = $db->prepare("SELECT id, google_id, email FROM usuarios WHERE google_id = ? OR email = ?");
            $stmt->bind_param("ss", $googleId, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $userInDb = $result->fetch_assoc();
                echo '<p class="success">✅ Usuario YA existe en la BD</p>';
                echo '<pre>';
                print_r($userInDb);
                echo '</pre>';
                
                // Actualizar sesión con ID correcto
                if ($_SESSION['user']['id'] != $userInDb['id']) {
                    echo '<p class="warning">⚠️ Corrigiendo ID en sesión...</p>';
                    $_SESSION['user']['id'] = $userInDb['id'];
                    $_SESSION['user']['google_id'] = $userInDb['google_id'];
                    echo '<p class="success">✅ Sesión actualizada con ID: ' . $userInDb['id'] . '</p>';
                }
            } else {
                echo '<p class="warning">⚠️ Usuario NO existe en BD. Insertando...</p>';
                
                // Insertar nuevo usuario
                $stmt = $db->prepare("INSERT INTO usuarios (google_id, email, nombre, foto, fecha_registro, ultimo_login) VALUES (?, ?, ?, ?, NOW(), NOW())");
                $stmt->bind_param("ssss", $googleId, $email, $nombre, $foto);
                
                if ($stmt->execute()) {
                    $newId = $db->insert_id;
                    echo '<p class="success">✅ Usuario insertado con ID: ' . $newId . '</p>';
                    
                    // Actualizar sesión
                    $_SESSION['user']['id'] = $newId;
                    $_SESSION['user']['google_id'] = $googleId;
                    
                    echo '<p class="success">✅ Sesión actualizada</p>';
                } else {
                    echo '<p class="fail">❌ Error al insertar: ' . $stmt->error . '</p>';
                }
            }
            
        } catch (Exception $e) {
            echo '<p class="fail">❌ Excepción: ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        }
        
        echo '</div>';
        
        // 4. Mostrar sesión actualizada
        echo '<div class="box">';
        echo '<h3>4️⃣ Sesión Actualizada</h3>';
        echo '<pre>';
        print_r($_SESSION['user']);
        echo '</pre>';
        echo '</div>';
    }
    
    // 5. Acciones
    echo '<div class="box">';
    echo '<h2>🎯 Próximos Pasos</h2>';
    
    if (isset($_SESSION['user'])) {
        $userId = $_SESSION['user']['id'] ?? null;
        $isFixed = is_numeric($userId) && $userId < 1000000000;
        
        if ($isFixed) {
            echo '<p class="success">✅ ¡PROBLEMA RESUELTO!</p>';
            echo '<p>Tu sesión ahora tiene el ID correcto de la base de datos.</p>';
            echo '<p><a href="' . BASE_URL . '" class="btn">Ir al Inicio</a></p>';
            echo '<p>Ahora intenta suscribirte a un plan. Ya NO debería aparecer el modal de login.</p>';
        } else {
            echo '<p class="warning">⚠️ El problema persiste</p>';
            echo '<p>Intenta:</p>';
            echo '<ol>';
            echo '<li><a href="' . BASE_URL . 'auth/logout">Cerrar sesión</a></li>';
            echo '<li><a href="' . BASE_URL . 'auth/google">Iniciar sesión de nuevo</a></li>';
            echo '<li>Volver a esta página</li>';
            echo '</ol>';
        }
    } else {
        echo '<p class="warning">⚠️ No hay sesión activa</p>';
        echo '<p><a href="' . BASE_URL . 'auth/google" class="btn">Iniciar Sesión con Google</a></p>';
    }
    
    echo '</div>';
    ?>
    
    <div style="margin-top: 30px; text-align: center; padding: 20px; background: #000; border-radius: 8px;">
        <p><a href="<?php echo BASE_URL; ?>">← Volver al Inicio</a></p>
    </div>
</body>
</html>
