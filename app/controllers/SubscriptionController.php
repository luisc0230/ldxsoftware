<?php
/**
 * Subscription Controller - Maneja suscripciones con Culqi
 */

if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

class SubscriptionController {
    
    private $apiUrl;
    private $secretKey;
    
    public function __construct() {
        $this->apiUrl = CULQI_API_URL;
        $this->secretKey = CULQI_SECRET_KEY;
    }
    
    /**
     * Crear un plan en Culqi
     */
    public function createPlan($planData) {
        $url = $this->apiUrl . 'plans';
        
        $data = [
            'name' => $planData['name'],
            'amount' => $planData['amount'] * 100, // Convertir a centavos
            'currency_code' => 'PEN',
            'interval' => 'months',
            'interval_count' => 1,
            'limit' => 0, // Sin límite de cobros
            'trial_days' => 0
        ];
        
        return $this->makeRequest('POST', $url, $data);
    }
    
    /**
     * Crear un cliente en Culqi
     */
    public function createCustomer($userData) {
        $url = $this->apiUrl . 'customers';
        
        $data = [
            'email' => $userData['email'],
            'first_name' => $userData['first_name'] ?? '',
            'last_name' => $userData['last_name'] ?? '',
            'phone_number' => $userData['phone'] ?? ''
        ];
        
        return $this->makeRequest('POST', $url, $data);
    }
    
    /**
     * Crear una tarjeta (asociar token con cliente)
     */
    public function createCard($customerId, $tokenId) {
        $url = $this->apiUrl . 'cards';
        
        $data = [
            'customer_id' => $customerId,
            'token_id' => $tokenId
        ];
        
        return $this->makeRequest('POST', $url, $data);
    }
    
    /**
     * Crear una suscripción
     */
    public function createSubscription($subscriptionData) {
        $url = $this->apiUrl . 'subscriptions';
        
        $data = [
            'card_id' => $subscriptionData['card_id'],
            'plan_id' => $subscriptionData['plan_id'],
            'tyc' => true,
            'metadata' => $subscriptionData['metadata'] ?? []
        ];
        
        return $this->makeRequest('POST', $url, $data);
    }
    
    /**
     * Consultar una suscripción
     */
    public function getSubscription($subscriptionId) {
        $url = $this->apiUrl . 'subscriptions/' . $subscriptionId;
        return $this->makeRequest('GET', $url);
    }
    
    /**
     * Listar suscripciones
     */
    public function listSubscriptions($filters = []) {
        $url = $this->apiUrl . 'subscriptions';
        if (!empty($filters)) {
            $url .= '?' . http_build_query($filters);
        }
        return $this->makeRequest('GET', $url);
    }
    
    /**
     * Cancelar una suscripción
     */
    public function cancelSubscription($subscriptionId) {
        $url = $this->apiUrl . 'subscriptions/' . $subscriptionId;
        return $this->makeRequest('DELETE', $url);
    }
    
    /**
     * Procesar suscripción completa
     */
    public function processSubscription($tokenId, $planType, $userEmail) {
        try {
            // 1. Obtener datos del plan
            $planData = $this->getPlanData($planType);
            
            // 2. Crear o buscar plan en Culqi
            $plan = $this->getOrCreatePlan($planData);
            if (!$plan || !isset($plan['id'])) {
                throw new Exception('Error al crear el plan');
            }
            
            // 3. Crear o buscar cliente
            $customer = $this->getOrCreateCustomer($userEmail);
            if (!$customer || !isset($customer['id'])) {
                throw new Exception('Error al crear el cliente');
            }
            
            // 4. Crear tarjeta
            $card = $this->createCard($customer['id'], $tokenId);
            if (!$card || !isset($card['id'])) {
                throw new Exception('Error al registrar la tarjeta');
            }
            
            // 5. Crear suscripción
            $subscription = $this->createSubscription([
                'card_id' => $card['id'],
                'plan_id' => $plan['id'],
                'metadata' => [
                    'plan_type' => $planType,
                    'user_email' => $userEmail
                ]
            ]);
            
            if (!$subscription || !isset($subscription['id'])) {
                throw new Exception('Error al crear la suscripción');
            }
            
            // 6. Guardar suscripción localmente
            $this->saveSubscriptionLocal($subscription, $userEmail, $planType);
            
            return [
                'success' => true,
                'subscription' => $subscription
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtener datos del plan según el tipo
     */
    private function getPlanData($planType) {
        $plans = [
            'basico' => [
                'name' => 'Plan Básico',
                'amount' => 99,
                'description' => 'Sitio web responsive con hasta 5 páginas'
            ],
            'profesional' => [
                'name' => 'Plan Profesional',
                'amount' => 199,
                'description' => 'Sitio web completo con hasta 15 páginas y blog'
            ],
            'empresarial' => [
                'name' => 'Plan Empresarial',
                'amount' => 399,
                'description' => 'Solución completa con e-commerce y app móvil'
            ]
        ];
        
        return $plans[$planType] ?? $plans['basico'];
    }
    
    /**
     * Obtener o crear plan
     */
    private function getOrCreatePlan($planData) {
        // Buscar plan existente en archivo local
        $plansFile = APP_PATH . 'data/culqi_plans.json';
        
        if (file_exists($plansFile)) {
            $plans = json_decode(file_get_contents($plansFile), true) ?? [];
            foreach ($plans as $plan) {
                if ($plan['name'] === $planData['name']) {
                    return $plan;
                }
            }
        }
        
        // Si no existe, crear nuevo plan
        $newPlan = $this->createPlan($planData);
        
        if ($newPlan && isset($newPlan['id'])) {
            // Guardar plan localmente
            $plans[] = $newPlan;
            file_put_contents($plansFile, json_encode($plans, JSON_PRETTY_PRINT));
        }
        
        return $newPlan;
    }
    
    /**
     * Obtener o crear cliente
     */
    private function getOrCreateCustomer($email) {
        // Buscar cliente existente
        $customersFile = APP_PATH . 'data/culqi_customers.json';
        
        if (file_exists($customersFile)) {
            $customers = json_decode(file_get_contents($customersFile), true) ?? [];
            foreach ($customers as $customer) {
                if ($customer['email'] === $email) {
                    return $customer;
                }
            }
        }
        
        // Si no existe, crear nuevo cliente
        $user = $_SESSION['user'] ?? [];
        $nameParts = explode(' ', $user['name'] ?? 'Usuario');
        
        $newCustomer = $this->createCustomer([
            'email' => $email,
            'first_name' => $nameParts[0] ?? 'Usuario',
            'last_name' => $nameParts[1] ?? 'LDX'
        ]);
        
        if ($newCustomer && isset($newCustomer['id'])) {
            // Guardar cliente localmente
            $customers = $customers ?? [];
            $customers[] = $newCustomer;
            file_put_contents($customersFile, json_encode($customers, JSON_PRETTY_PRINT));
        }
        
        return $newCustomer;
    }
    
    /**
     * Guardar suscripción localmente
     */
    private function saveSubscriptionLocal($subscription, $userEmail, $planType) {
        $subscriptionsFile = APP_PATH . 'data/subscriptions.json';
        
        $subscriptions = [];
        if (file_exists($subscriptionsFile)) {
            $subscriptions = json_decode(file_get_contents($subscriptionsFile), true) ?? [];
        }
        
        $subscriptions[] = [
            'id' => $subscription['id'],
            'user_email' => $userEmail,
            'plan_type' => $planType,
            'plan_id' => $subscription['plan']['plan_id'] ?? '',
            'status' => $subscription['status'] ?? 0,
            'created_at' => date('Y-m-d H:i:s'),
            'next_billing_date' => date('Y-m-d H:i:s', $subscription['next_billing_date'] ?? time())
        ];
        
        file_put_contents($subscriptionsFile, json_encode($subscriptions, JSON_PRETTY_PRINT));
    }
    
    /**
     * Hacer petición a la API de Culqi
     */
    private function makeRequest($method, $url, $data = null) {
        $ch = curl_init($url);
        
        $headers = [
            'Authorization: Bearer ' . $this->secretKey,
            'Content-Type: application/json'
        ];
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true);
        }
        
        return false;
    }
}
