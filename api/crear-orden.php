<?php
/**
 * API para crear orden en Culqi
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

// Verificar autenticación
if (!AuthController::isAuthenticated()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}

$user = AuthController::getCurrentUser();

// Obtener datos del plan desde la sesión o POST
$precio = $_SESSION['precioSeleccionado'] ?? $_POST['precio'] ?? null;
$planId = $_SESSION['planSeleccionado'] ?? $_POST['plan_id'] ?? null;
$tipoPago = $_SESSION['tipoPagoSeleccionado'] ?? $_POST['tipo_pago'] ?? 'mensual';

if (!$precio || !$planId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos de plan incompletos']);
    exit;
}

// Calcular monto en centavos y asegurarse que es entero
$amount = (int)($precio * 100);

// Preparar datos para Culqi
$orderData = [
    'amount' => $amount,
    'currency_code' => 'PEN',
    'description' => "Suscripción Plan ID: $planId ($tipoPago)",
    'order_number' => 'LDX-' . uniqid() . '-' . $user['id'], // Usar uniqid para evitar colisiones
    'client_details' => [
        'first_name' => $user['name'],
        'last_name' => 'User', // Google a veces no da apellido separado, usamos placeholder o lo que tengamos
        'email' => $user['email'],
        'phone_number' => '999999999' // Culqi requiere teléfono, usamos dummy si no tenemos
    ],
    'expiration_date' => time() + (24 * 60 * 60) // Expira en 24 horas (Timestamp UNIX futuro)
];

// Llamada a la API de Culqi
$ch = curl_init('https://api.culqi.com/v2/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . CULQI_SECRET_KEY
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($httpCode !== 201) {
    error_log("Error Culqi Crear Orden: " . $response);
    echo json_encode(['success' => false, 'error' => 'Error al crear orden en Culqi: ' . $response]);
    exit;
}

$culqiOrder = json_decode($response, true);

// Devolver ID de la orden
echo json_encode([
    'success' => true,
    'order_id' => $culqiOrder['id'],
    'order_number' => $culqiOrder['order_number']
]);
