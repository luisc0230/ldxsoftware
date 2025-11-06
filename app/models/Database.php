<?php
/**
 * Database Connection Class
 */

if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->conn->connect_error) {
                error_log("Error de conexión a BD: " . $this->conn->connect_error);
                throw new Exception("Error de conexión a la base de datos");
            }
            
            $this->conn->set_charset(DB_CHARSET);
            error_log("Conexión a BD establecida correctamente");
            
        } catch (Exception $e) {
            error_log("Excepción en Database: " . $e->getMessage());
            throw $e;
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function query($sql) {
        $result = $this->conn->query($sql);
        if (!$result) {
            error_log("Error en query: " . $this->conn->error);
            error_log("SQL: " . $sql);
        }
        return $result;
    }
    
    public function prepare($sql) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Error en prepare: " . $this->conn->error);
            error_log("SQL: " . $sql);
        }
        return $stmt;
    }
    
    public function escape($value) {
        return $this->conn->real_escape_string($value);
    }
    
    public function lastInsertId() {
        return $this->conn->insert_id;
    }
    
    public function affectedRows() {
        return $this->conn->affected_rows;
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
