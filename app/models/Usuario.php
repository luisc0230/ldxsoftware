<?php
/**
 * Usuario Model
 */

if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

require_once __DIR__ . '/Database.php';

class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear o actualizar usuario desde Google OAuth
     */
    public function createOrUpdateFromGoogle($googleData) {
        try {
            $googleId = $this->db->real_escape_string($googleData['id']);
            $email = $this->db->real_escape_string($googleData['email']);
            $nombre = $this->db->real_escape_string($googleData['name']);
            $foto = isset($googleData['picture']) ? $this->db->real_escape_string($googleData['picture']) : null;
            
            error_log("createOrUpdateFromGoogle - Google ID: $googleId, Email: $email");
            
            // Verificar si el usuario ya existe
            $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE google_id = ? OR email = ?");
            if (!$stmt) {
                error_log("ERROR prepare: " . $this->db->error);
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("ss", $googleId, $email);
            $stmt->execute();
            $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Usuario existe, actualizar último login
            $usuario = $result->fetch_assoc();
            $usuarioId = $usuario['id'];
            
            $updateStmt = $this->db->prepare("UPDATE usuarios SET nombre = ?, foto = ?, ultimo_login = NOW() WHERE id = ?");
            $updateStmt->bind_param("ssi", $nombre, $foto, $usuarioId);
            $updateStmt->execute();
            
            error_log("Usuario actualizado: ID $usuarioId - $email");
            
        } else {
            // Usuario nuevo, insertar
            $insertStmt = $this->db->prepare("INSERT INTO usuarios (google_id, email, nombre, foto, fecha_registro, ultimo_login) VALUES (?, ?, ?, ?, NOW(), NOW())");
            if (!$insertStmt) {
                error_log("ERROR prepare insert: " . $this->db->error);
                throw new Exception("Error al preparar inserción: " . $this->db->error);
            }
            
            $insertStmt->bind_param("ssss", $googleId, $email, $nombre, $foto);
            if (!$insertStmt->execute()) {
                error_log("ERROR execute insert: " . $insertStmt->error);
                throw new Exception("Error al ejecutar inserción: " . $insertStmt->error);
            }
            
            $usuarioId = $this->db->insert_id;
            error_log("Usuario nuevo creado: ID $usuarioId - $email");
        }
        
        $usuarioData = $this->getById($usuarioId);
        error_log("Usuario retornado: " . json_encode($usuarioData));
        return $usuarioData;
        
        } catch (Exception $e) {
            error_log("EXCEPCIÓN en createOrUpdateFromGoogle: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * Obtener usuario por ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Obtener usuario por email
     */
    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Obtener usuario por Google ID
     */
    public function getByGoogleId($googleId) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE google_id = ?");
        $stmt->bind_param("s", $googleId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Verificar si el usuario tiene suscripción activa
     */
    public function tieneSuscripcionActiva($usuarioId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM suscripciones 
            WHERE usuario_id = ? 
            AND estado = 'activa' 
            AND (fecha_fin IS NULL OR fecha_fin > NOW())
        ");
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    }
    
    /**
     * Obtener suscripciones del usuario
     */
    public function getSuscripciones($usuarioId) {
        $stmt = $this->db->prepare("
            SELECT s.*, p.nombre as plan_nombre, p.descripcion as plan_descripcion
            FROM suscripciones s
            INNER JOIN planes p ON s.plan_id = p.id
            WHERE s.usuario_id = ?
            ORDER BY s.fecha_creacion DESC
        ");
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $suscripciones = [];
        while ($row = $result->fetch_assoc()) {
            $suscripciones[] = $row;
        }
        
        return $suscripciones;
    }
}
