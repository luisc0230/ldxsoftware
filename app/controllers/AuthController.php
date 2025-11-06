<?php
/**
 * Auth Controller - Maneja autenticación con Google OAuth
 */

if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

class AuthController {
    
    /**
     * Redirige al usuario a Google OAuth
     */
    public function googleLogin() {
        $params = [
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'prompt' => 'select_account'
        ];
        
        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        header('Location: ' . $authUrl);
        exit;
    }
    
    /**
     * Callback de Google OAuth
     */
    public function googleCallback() {
        error_log("googleCallback() iniciado");
        
        if (!isset($_GET['code'])) {
            error_log("ERROR: No se recibió código de autorización");
            header('Location: ' . BASE_URL . '?error=auth_failed');
            exit;
        }
        
        $code = $_GET['code'];
        error_log("Código recibido: " . substr($code, 0, 20) . "...");
        
        // Intercambiar código por token
        error_log("Intentando obtener token...");
        $tokenData = $this->getGoogleToken($code);
        
        if (!$tokenData) {
            error_log("ERROR: No se pudo obtener token de Google");
            header('Location: ' . BASE_URL . '?error=token_failed');
            exit;
        }
        error_log("Token obtenido exitosamente");
        
        // Obtener información del usuario
        error_log("Obteniendo información del usuario...");
        $userInfo = $this->getGoogleUserInfo($tokenData['access_token']);
        
        if (!$userInfo) {
            error_log("ERROR: No se pudo obtener información del usuario");
            header('Location: ' . BASE_URL . '?error=user_info_failed');
            exit;
        }
        error_log("Usuario obtenido: " . ($userInfo['email'] ?? 'sin email'));
        
        // Guardar usuario en sesión
        error_log("Guardando usuario en sesión...");
        $_SESSION['user'] = [
            'id' => $userInfo['id'],
            'email' => $userInfo['email'],
            'name' => $userInfo['name'],
            'picture' => $userInfo['picture'] ?? '',
            'logged_in' => true,
            'login_time' => time()
        ];
        error_log("Usuario guardado en sesión. Session ID: " . session_id());
        
        // Guardar usuario en archivo JSON
        error_log("Guardando usuario en archivo JSON...");
        $this->saveUser($userInfo);
        
        // Verificar si hay un plan seleccionado en la sesión
        if (isset($_SESSION['planSeleccionado']) && isset($_SESSION['precioSeleccionado'])) {
            // Si hay plan seleccionado, ir a checkout
            error_log("Redirigiendo a checkout...");
            header('Location: ' . BASE_URL . 'checkout');
        } else {
            // Si no hay plan, volver al inicio
            error_log("Redirigiendo al inicio con login=success");
            header('Location: ' . BASE_URL . '?login=success');
        }
        exit;
    }
    
    /**
     * Obtener token de Google
     */
    private function getGoogleToken($code) {
        $params = [
            'code' => $code,
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code'
        ];
        
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            error_log("ERROR getGoogleToken: HTTP " . $httpCode);
            error_log("Response: " . $response);
            if ($curlError) {
                error_log("cURL Error: " . $curlError);
            }
            return false;
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Obtener información del usuario de Google
     */
    private function getGoogleUserInfo($accessToken) {
        $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            error_log("ERROR getGoogleUserInfo: HTTP " . $httpCode);
            error_log("Response: " . $response);
            if ($curlError) {
                error_log("cURL Error: " . $curlError);
            }
            return false;
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Guardar usuario en archivo JSON
     */
    private function saveUser($userInfo) {
        $usersFile = APP_PATH . 'data/users.json';
        
        // Crear directorio si no existe
        if (!file_exists(dirname($usersFile))) {
            mkdir(dirname($usersFile), 0755, true);
        }
        
        // Leer usuarios existentes
        $users = [];
        if (file_exists($usersFile)) {
            $users = json_decode(file_get_contents($usersFile), true) ?? [];
        }
        
        // Buscar si el usuario ya existe
        $userExists = false;
        foreach ($users as &$user) {
            if ($user['id'] === $userInfo['id']) {
                $user['last_login'] = date('Y-m-d H:i:s');
                $userExists = true;
                break;
            }
        }
        
        // Si no existe, agregarlo
        if (!$userExists) {
            $users[] = [
                'id' => $userInfo['id'],
                'email' => $userInfo['email'],
                'name' => $userInfo['name'],
                'picture' => $userInfo['picture'] ?? '',
                'created_at' => date('Y-m-d H:i:s'),
                'last_login' => date('Y-m-d H:i:s')
            ];
        }
        
        // Guardar usuarios
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }
    
    /**
     * Verificar si el usuario está autenticado
     */
    public static function isAuthenticated() {
        return isset($_SESSION['user']) && $_SESSION['user']['logged_in'] === true;
    }
    
    /**
     * Obtener usuario actual
     */
    public static function getCurrentUser() {
        return $_SESSION['user'] ?? null;
    }
}
