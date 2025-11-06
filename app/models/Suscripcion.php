<?php
/**
 * Suscripcion Model
 */

if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

require_once __DIR__ . '/Database.php';

class Suscripcion {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear nueva suscripción
     */
    public function crear($usuarioId, $planId, $tipoPago, $precio) {
        $stmt = $this->db->prepare("
            INSERT INTO suscripciones (usuario_id, plan_id, tipo_pago, precio_pagado, estado, fecha_creacion)
            VALUES (?, ?, ?, ?, 'pendiente', NOW())
        ");
        $stmt->bind_param("iisd", $usuarioId, $planId, $tipoPago, $precio);
        
        if ($stmt->execute()) {
            $suscripcionId = $this->db->insert_id;
            error_log("Suscripción creada: ID $suscripcionId para usuario $usuarioId");
            return $suscripcionId;
        }
        
        error_log("Error al crear suscripción: " . $this->db->error);
        return false;
    }
    
    /**
     * Activar suscripción después del pago
     */
    public function activar($suscripcionId) {
        $stmt = $this->db->prepare("SELECT tipo_pago FROM suscripciones WHERE id = ?");
        $stmt->bind_param("i", $suscripcionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $suscripcion = $result->fetch_assoc();
        
        if (!$suscripcion) {
            return false;
        }
        
        $intervalo = $suscripcion['tipo_pago'] === 'anual' ? '1 YEAR' : '1 MONTH';
        
        $updateStmt = $this->db->prepare("
            UPDATE suscripciones 
            SET estado = 'activa', 
                fecha_inicio = NOW(), 
                fecha_fin = DATE_ADD(NOW(), INTERVAL $intervalo),
                fecha_actualizacion = NOW()
            WHERE id = ?
        ");
        $updateStmt->bind_param("i", $suscripcionId);
        
        if ($updateStmt->execute()) {
            error_log("Suscripción activada: ID $suscripcionId");
            return true;
        }
        
        error_log("Error al activar suscripción: " . $this->db->error);
        return false;
    }
    
    /**
     * Obtener suscripciones activas del usuario
     */
    public function getSuscripcionesActivas($usuarioId) {
        $stmt = $this->db->prepare("
            SELECT s.*, p.nombre as plan_nombre 
            FROM suscripciones s
            INNER JOIN planes p ON s.plan_id = p.id
            WHERE s.usuario_id = ? AND s.estado = 'activa'
            AND (s.fecha_fin IS NULL OR s.fecha_fin > NOW())
        ");
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
