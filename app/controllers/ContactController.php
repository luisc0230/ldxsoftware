<?php
/**
 * LDX Software - Contact Controller
 * 
 * Handles contact form submission and validation
 * 
 * @author LDX Software
 * @version 1.0
 */

// Prevent direct access
if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

require_once 'BaseController.php';

class ContactController extends BaseController {
    
    private $contactModel;
    
    public function __construct() {
        parent::__construct();
        $this->contactModel = new ContactModel();
    }
    
    /**
     * Display contact page
     */
    public function index() {
        $this->setTitle('Contacto');
        $this->setDescription('Contáctanos para discutir tu próximo proyecto de software. LDX Software está aquí para ayudarte.');
        $this->setKeywords('contacto, LDX Software, desarrollo software, cotización, proyecto');
        
        $data = [
            'contact_info' => [
                'address' => 'Av. Tecnológico 123, Col. Innovación, Ciudad de México, CP 01234',
                'phone' => '+52 (55) 1234-5678',
                'email' => 'contacto@ldxsoftware.com.pe',
                'hours' => 'Lunes a Viernes: 9:00 AM - 6:00 PM'
            ],
            'social_links' => [
                'facebook' => 'https://facebook.com/ldxsoftware',
                'twitter' => 'https://twitter.com/ldxsoftware',
                'linkedin' => 'https://linkedin.com/company/ldxsoftware',
                'github' => 'https://github.com/ldxsoftware'
            ]
        ];
        
        $this->render('contact', $data);
    }
    
    /**
     * Handle contact form submission
     */
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(url('contact'));
            return;
        }
        
        // Validate CSRF token
        $this->validateCSRF();
        
        // Sanitize input data
        $data = [
            'name' => $this->sanitize($_POST['name'] ?? ''),
            'email' => $this->sanitize($_POST['email'] ?? ''),
            'phone' => $this->sanitize($_POST['phone'] ?? ''),
            'company' => $this->sanitize($_POST['company'] ?? ''),
            'service' => $this->sanitize($_POST['service'] ?? ''),
            'budget' => $this->sanitize($_POST['budget'] ?? ''),
            'message' => $this->sanitize($_POST['message'] ?? ''),
            'newsletter' => isset($_POST['newsletter'])
        ];
        
        // Validate required fields
        $errors = $this->validateContactForm($data);
        
        if (empty($errors)) {
            // Save contact to database/file
            $contactId = $this->contactModel->saveContact($data);
            
            if ($contactId) {
                // Send email notification
                $emailSent = $this->sendContactEmail($data);
                
                if ($emailSent) {
                    $this->setFlashMessage('success', '¡Gracias por contactarnos! Te responderemos pronto.');
                } else {
                    $this->setFlashMessage('warning', 'Tu mensaje fue recibido, pero hubo un problema enviando la confirmación por email.');
                }
            } else {
                $this->setFlashMessage('error', 'Hubo un problema procesando tu mensaje. Por favor intenta nuevamente.');
            }
        } else {
            $this->setFlashMessage('error', 'Por favor corrige los errores en el formulario.');
            $this->data['form_errors'] = $errors;
            $this->data['form_data'] = $data;
        }
        
        $this->redirect(url('contact'));
    }
    
    /**
     * AJAX endpoint for contact form
     */
    public function apiSend() {
        if (!$this->isAjax() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->renderJson(['error' => 'Invalid request'], 400);
            return;
        }
        
        // Validate CSRF token
        try {
            $this->validateCSRF();
        } catch (Exception $e) {
            $this->renderJson(['error' => 'Invalid CSRF token'], 403);
            return;
        }
        
        // Sanitize input data
        $data = [
            'name' => $this->sanitize($_POST['name'] ?? ''),
            'email' => $this->sanitize($_POST['email'] ?? ''),
            'phone' => $this->sanitize($_POST['phone'] ?? ''),
            'company' => $this->sanitize($_POST['company'] ?? ''),
            'service' => $this->sanitize($_POST['service'] ?? ''),
            'budget' => $this->sanitize($_POST['budget'] ?? ''),
            'message' => $this->sanitize($_POST['message'] ?? ''),
            'newsletter' => isset($_POST['newsletter'])
        ];
        
        // Validate required fields
        $errors = $this->validateContactForm($data);
        
        if (!empty($errors)) {
            $this->renderJson([
                'success' => false,
                'errors' => $errors
            ], 400);
            return;
        }
        
        // Save contact
        $contactId = $this->contactModel->saveContact($data);
        
        if (!$contactId) {
            $this->renderJson([
                'success' => false,
                'message' => 'Error al procesar el mensaje'
            ], 500);
            return;
        }
        
        // Send email
        $emailSent = $this->sendContactEmail($data);
        
        $this->renderJson([
            'success' => true,
            'message' => '¡Gracias por contactarnos! Te responderemos pronto.',
            'email_sent' => $emailSent
        ]);
    }
    
    /**
     * Newsletter subscription
     */
    public function newsletter() {
        if (!$this->isAjax() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->renderJson(['error' => 'Invalid request'], 400);
            return;
        }
        
        $email = $this->sanitize($_POST['email'] ?? '');
        
        if (!$this->validateEmail($email)) {
            $this->renderJson([
                'success' => false,
                'message' => 'Email inválido'
            ], 400);
            return;
        }
        
        // Save to newsletter list
        $saved = $this->contactModel->saveNewsletter($email);
        
        if ($saved) {
            $this->renderJson([
                'success' => true,
                'message' => '¡Te has suscrito exitosamente al newsletter!'
            ]);
        } else {
            $this->renderJson([
                'success' => false,
                'message' => 'Error al suscribirse. Intenta nuevamente.'
            ], 500);
        }
    }
    
    /**
     * Validate contact form data
     */
    private function validateContactForm($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'El nombre es requerido';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'El email es requerido';
        } elseif (!$this->validateEmail($data['email'])) {
            $errors['email'] = 'El email no es válido';
        }
        
        if (empty($data['message'])) {
            $errors['message'] = 'El mensaje es requerido';
        } elseif (strlen($data['message']) < 10) {
            $errors['message'] = 'El mensaje debe tener al menos 10 caracteres';
        }
        
        return $errors;
    }
    
    /**
     * Send contact email notification
     */
    private function sendContactEmail($data) {
        // This would integrate with PHPMailer or similar
        // For now, we'll simulate email sending
        
        $to = SMTP_FROM_EMAIL;
        $subject = "Nuevo contacto desde " . SITE_NAME;
        $message = "
        Nuevo mensaje de contacto:
        
        Nombre: {$data['name']}
        Email: {$data['email']}
        Teléfono: {$data['phone']}
        Empresa: {$data['company']}
        Servicio: {$data['service']}
        Presupuesto: {$data['budget']}
        
        Mensaje:
        {$data['message']}
        
        Newsletter: " . ($data['newsletter'] ? 'Sí' : 'No') . "
        
        Enviado desde: " . $_SERVER['HTTP_HOST'] . "
        IP: " . $_SERVER['REMOTE_ADDR'] . "
        Fecha: " . date('Y-m-d H:i:s');
        
        $headers = "From: " . $data['email'] . "\r\n";
        $headers .= "Reply-To: " . $data['email'] . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        return mail($to, $subject, $message, $headers);
    }
    
    /**
     * Set flash message
     */
    private function setFlashMessage($type, $message) {
        $_SESSION['flash_message'] = [
            'type' => $type,
            'message' => $message
        ];
    }
}
