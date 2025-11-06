<?php
/**
 * API - Procesar Libro de Reclamaciones
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validar datos requeridos
$required = ['tipo_solicitud', 'nombre_completo', 'tipo_documento', 'numero_documento', 'telefono', 'email', 'servicio', 'descripcion', 'pedido'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => 'Campo requerido: ' . $field]);
        exit;
    }
}

try {
    // Generar número de registro único
    $numeroRegistro = 'LR-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    // Guardar en base de datos
    require_once __DIR__ . '/../app/models/Database.php';
    $db = Database::getInstance()->getConnection();
    
    // Crear tabla si no existe
    $db->query("CREATE TABLE IF NOT EXISTS libro_reclamaciones (
        id INT AUTO_INCREMENT PRIMARY KEY,
        numero_registro VARCHAR(50) UNIQUE NOT NULL,
        tipo_solicitud ENUM('reclamo', 'queja') NOT NULL,
        fecha DATE NOT NULL,
        nombre_completo VARCHAR(255) NOT NULL,
        tipo_documento VARCHAR(20) NOT NULL,
        numero_documento VARCHAR(20) NOT NULL,
        telefono VARCHAR(20) NOT NULL,
        email VARCHAR(255) NOT NULL,
        direccion VARCHAR(500),
        servicio VARCHAR(255) NOT NULL,
        monto DECIMAL(10,2),
        descripcion TEXT NOT NULL,
        pedido TEXT NOT NULL,
        estado ENUM('pendiente', 'en_proceso', 'resuelto') DEFAULT 'pendiente',
        fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
        fecha_respuesta DATETIME NULL,
        respuesta TEXT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    
    // Insertar reclamación
    $stmt = $db->prepare("
        INSERT INTO libro_reclamaciones 
        (numero_registro, tipo_solicitud, fecha, nombre_completo, tipo_documento, numero_documento, 
         telefono, email, direccion, servicio, monto, descripcion, pedido)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param("ssssssssssdss",
        $numeroRegistro,
        $data['tipo_solicitud'],
        $data['fecha'],
        $data['nombre_completo'],
        $data['tipo_documento'],
        $data['numero_documento'],
        $data['telefono'],
        $data['email'],
        $data['direccion'] ?? '',
        $data['servicio'],
        $data['monto'] ?? 0,
        $data['descripcion'],
        $data['pedido']
    );
    
    if ($stmt->execute()) {
        // Enviar email de confirmación (opcional)
        // mail($data['email'], "Reclamación Registrada - $numeroRegistro", ...);
        
        echo json_encode([
            'success' => true,
            'numero_registro' => $numeroRegistro,
            'message' => 'Reclamación registrada exitosamente'
        ]);
    } else {
        throw new Exception('Error al guardar la reclamación');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
