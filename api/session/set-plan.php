<?php
/**
 * API Endpoint - Guardar plan en sesión
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido'
    ]);
    exit;
}

// Obtener datos del request
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['plan']) || !isset($input['precio'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Datos incompletos'
    ]);
    exit;
}

// Guardar en sesión
$_SESSION['planSeleccionado'] = $input['plan'];
$_SESSION['precioSeleccionado'] = $input['precio'];

echo json_encode([
    'success' => true,
    'message' => 'Plan guardado en sesión'
]);
