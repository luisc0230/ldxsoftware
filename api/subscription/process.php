<?php
/**
 * API Endpoint - Procesar Suscripción
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/SubscriptionController.php';

header('Content-Type: application/json');

// Verificar autenticación
if (!AuthController::isAuthenticated()) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'No autenticado'
    ]);
    exit;
}

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

if (!isset($input['token']) || !isset($input['plan']) || !isset($input['email'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Datos incompletos'
    ]);
    exit;
}

$token = $input['token'];
$plan = $input['plan'];
$email = $input['email'];

// Validar plan
$planesValidos = ['basico', 'profesional', 'empresarial'];
if (!in_array($plan, $planesValidos)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Plan inválido'
    ]);
    exit;
}

try {
    // Procesar suscripción
    $subscriptionController = new SubscriptionController();
    $result = $subscriptionController->processSubscription($token, $plan, $email);
    
    if ($result['success']) {
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(400);
        echo json_encode($result);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}
