<?php
/**
 * Test Database Connection
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/models/Database.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test BD</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; }
        .success { border-left: 4px solid #4caf50; }
        .error { border-left: 4px solid #f44336; }
        pre { background: #f9f9f9; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔍 Test de Base de Datos</h1>
    
    <?php
    try {
        $db = Database::getInstance()->getConnection();
        echo '<div class="box success">';
        echo '<h2>✅ Conexión a BD exitosa</h2>';
        echo '<p>Host: ' . DB_HOST . '</p>';
        echo '<p>Database: ' . DB_NAME . '</p>';
        echo '</div>';
        
        // Verificar tablas
        echo '<div class="box">';
        echo '<h2>📋 Tablas en la base de datos:</h2>';
        $result = $db->query("SHOW TABLES");
        if ($result->num_rows > 0) {
            echo '<ul>';
            while ($row = $result->fetch_array()) {
                echo '<li>' . $row[0] . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p style="color: red;">❌ No hay tablas creadas. Ejecuta database/schema.sql en phpMyAdmin.</p>';
        }
        echo '</div>';
        
        // Verificar tabla usuarios
        $result = $db->query("SHOW TABLES LIKE 'usuarios'");
        if ($result->num_rows > 0) {
            echo '<div class="box success">';
            echo '<h2>✅ Tabla usuarios existe</h2>';
            
            // Contar usuarios
            $result = $db->query("SELECT COUNT(*) as total FROM usuarios");
            $row = $result->fetch_assoc();
            echo '<p>Total de usuarios: ' . $row['total'] . '</p>';
            
            // Mostrar últimos usuarios
            if ($row['total'] > 0) {
                echo '<h3>Últimos usuarios registrados:</h3>';
                $result = $db->query("SELECT id, google_id, email, nombre, fecha_registro FROM usuarios ORDER BY id DESC LIMIT 5");
                echo '<pre>';
                while ($user = $result->fetch_assoc()) {
                    print_r($user);
                }
                echo '</pre>';
            }
            echo '</div>';
        } else {
            echo '<div class="box error">';
            echo '<h2>❌ Tabla usuarios NO existe</h2>';
            echo '<p>Debes ejecutar el script database/schema.sql en phpMyAdmin</p>';
            echo '</div>';
        }
        
        // Verificar tabla planes
        $result = $db->query("SHOW TABLES LIKE 'planes'");
        if ($result->num_rows > 0) {
            echo '<div class="box success">';
            echo '<h2>✅ Tabla planes existe</h2>';
            
            $result = $db->query("SELECT * FROM planes");
            echo '<h3>Planes disponibles:</h3>';
            echo '<pre>';
            while ($plan = $result->fetch_assoc()) {
                print_r($plan);
            }
            echo '</pre>';
            echo '</div>';
        } else {
            echo '<div class="box error">';
            echo '<h2>❌ Tabla planes NO existe</h2>';
            echo '</div>';
        }
        
        // Test de inserción (solo si la tabla existe)
        $result = $db->query("SHOW TABLES LIKE 'usuarios'");
        if ($result->num_rows > 0) {
            echo '<div class="box">';
            echo '<h2>🧪 Test de Modelo Usuario</h2>';
            
            require_once __DIR__ . '/app/models/Usuario.php';
            
            $testData = [
                'id' => 'test_' . time(),
                'email' => 'test_' . time() . '@test.com',
                'name' => 'Usuario de Prueba',
                'picture' => ''
            ];
            
            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->createOrUpdateFromGoogle($testData);
            
            if ($usuario) {
                echo '<p style="color: green;">✅ Test de inserción EXITOSO</p>';
                echo '<pre>';
                print_r($usuario);
                echo '</pre>';
                
                // Eliminar usuario de prueba
                $db->query("DELETE FROM usuarios WHERE google_id LIKE 'test_%'");
                echo '<p>Usuario de prueba eliminado</p>';
            } else {
                echo '<p style="color: red;">❌ Error al insertar usuario de prueba</p>';
            }
            echo '</div>';
        }
        
    } catch (Exception $e) {
        echo '<div class="box error">';
        echo '<h2>❌ Error de Conexión</h2>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        echo '</div>';
    }
    ?>
    
    <div class="box">
        <h2>📝 Próximos pasos:</h2>
        <ol>
            <li>Si no existen las tablas, ejecuta <code>database/schema.sql</code> en phpMyAdmin</li>
            <li>Prueba el login: <a href="<?php echo BASE_URL; ?>auth/google">Iniciar sesión con Google</a></li>
            <li>Verifica que se cree el usuario en la tabla</li>
        </ol>
    </div>
</body>
</html>
