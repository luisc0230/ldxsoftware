<?php
/**
 * Suscripcion Controller - Maneja el flujo de suscripciones
 */

if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Suscripcion.php';

class SuscripcionController {
    
    /**
     * Iniciar proceso de suscripción
     * Guarda el plan seleccionado en la sesión
     */
    public function iniciarSuscripcion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            exit;
        }
        
        $planId = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : 0;
        $tipoPago = isset($_POST['tipo_pago']) ? $_POST['tipo_pago'] : 'mensual';
        $precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
        
        if ($planId <= 0 || $precio <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos inválidos']);
            exit;
        }
        
        // Guardar en sesión
        $_SESSION['planSeleccionado'] = $planId;
        $_SESSION['tipoPagoSeleccionado'] = $tipoPago;
        $_SESSION['precioSeleccionado'] = $precio;
        
        error_log("Plan seleccionado: Plan ID $planId, Tipo: $tipoPago, Precio: $precio");
        
        // Verificar si el usuario está logueado
        if (isset($_SESSION['user']) && $_SESSION['user']['logged_in']) {
            // Usuario logueado, ir directo a checkout
            echo json_encode([
                'success' => true,
                'redirect' => BASE_URL . 'checkout',
                'logged_in' => true
            ]);
        } else {
            // Usuario no logueado, debe iniciar sesión
            echo json_encode([
                'success' => true,
                'redirect' => BASE_URL . 'auth/google',
                'logged_in' => false
            ]);
        }
        exit;
    }
    
    /**
     * Procesar pago con Culqi
     */
    public function procesarPago() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            exit;
        }
        
        // Verificar que el usuario esté logueado
        if (!isset($_SESSION['user']) || !$_SESSION['user']['logged_in']) {
            http_response_code(401);
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }
        
        $usuarioId = $_SESSION['user']['id'];
        $token = isset($_POST['token']) ? $_POST['token'] : '';
        
        if (empty($token)) {
            http_response_code(400);
            echo json_encode(['error' => 'Token de pago requerido']);
            exit;
        }
        
        // Obtener datos de la sesión
        $planId = $_SESSION['planSeleccionado'] ?? 0;
        $tipoPago = $_SESSION['tipoPagoSeleccionado'] ?? 'mensual';
        $precio = $_SESSION['precioSeleccionado'] ?? 0;
        
        if ($planId <= 0 || $precio <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'No hay plan seleccionado']);
            exit;
        }
        
        try {
            // Crear suscripción en estado pendiente
            $suscripcionModel = new Suscripcion();
            $suscripcionId = $suscripcionModel->crear($usuarioId, $planId, $tipoPago, $precio);
            
            if (!$suscripcionId) {
                throw new Exception('Error al crear suscripción');
            }
            
            // Procesar pago con Culqi
            $charge = $this->crearCargoCulqi($token, $precio);
            
            if ($charge && isset($charge->id)) {
                // Pago exitoso, activar suscripción
                $suscripcionModel->activar($suscripcionId);
                
                // Guardar registro de pago
                $this->guardarPago($suscripcionId, $usuarioId, $precio, $charge);
                
                // Limpiar sesión
                unset($_SESSION['planSeleccionado']);
                unset($_SESSION['tipoPagoSeleccionado']);
                unset($_SESSION['precioSeleccionado']);
                
                echo json_encode([
                    'success' => true,
                    'message' => '¡Suscripción activada exitosamente!',
                    'suscripcion_id' => $suscripcionId,
                    'redirect' => BASE_URL . 'mis-suscripciones'
                ]);
            } else {
                throw new Exception('Error al procesar el pago');
            }
            
        } catch (Exception $e) {
            error_log("Error en procesarPago: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
    
    /**
     * Crear cargo en Culqi
     */
    private function crearCargoCulqi($token, $monto) {
        $url = CULQI_API_URL . 'charges';
        
        $data = [
            'amount' => intval($monto * 100), // Culqi usa centavos
            'currency_code' => 'PEN',
            'email' => $_SESSION['user']['email'],
            'source_id' => $token
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . CULQI_SECRET_KEY,
            'Content-Type: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 201 || $httpCode === 200) {
            return json_decode($response);
        }
        
        error_log("Error Culqi: HTTP $httpCode - $response");
        return false;
    }
    
    /**
     * Guardar registro de pago en la base de datos
     */
    private function guardarPago($suscripcionId, $usuarioId, $monto, $charge) {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            INSERT INTO pagos (suscripcion_id, usuario_id, monto, moneda, metodo_pago, culqi_charge_id, estado, datos_adicionales)
            VALUES (?, ?, ?, 'PEN', 'culqi', ?, 'completado', ?)
        ");
        
        $chargeId = $charge->id;
        $datosAdicionales = json_encode($charge);
        
        $stmt->bind_param("iidss", $suscripcionId, $usuarioId, $monto, $chargeId, $datosAdicionales);
        $stmt->execute();
        
        error_log("Pago guardado: Suscripción ID $suscripcionId, Charge ID $chargeId");
    }
}
