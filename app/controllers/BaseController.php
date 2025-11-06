<?php
/**
 * LDX Software - Base Controller
 * 
 * Base controller class that provides common functionality
 * for all controllers in the application.
 * 
 * @author LDX Software
 * @version 1.0
 */

// Prevent direct access
if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

class BaseController {
    
    protected $data = [];
    protected $layout = 'layout';
    
    /**
     * Constructor
     */
    public function __construct() {
        // Set default data available to all views
        $this->data['site_name'] = SITE_NAME;
        $this->data['site_description'] = SITE_DESCRIPTION;
        $this->data['base_url'] = BASE_URL;
        $this->data['csrf_token'] = generateCSRFToken();
        $this->data['current_year'] = date('Y');
    }
    
    /**
     * Render a view
     */
    protected function render($view, $data = []) {
        // Merge controller data with view data
        $this->data = array_merge($this->data, $data);
        
        // Extract data to variables
        extract($this->data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = VIEWS_PATH . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new Exception("View file not found: $view");
        }
        
        // Get the view content
        $content = ob_get_clean();
        
        // If layout is set, render within layout
        if ($this->layout) {
            $layoutFile = VIEWS_PATH . 'layouts/' . $this->layout . '.php';
            if (file_exists($layoutFile)) {
                include $layoutFile;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }
    
    /**
     * Render JSON response
     */
    protected function renderJson($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Set page title
     */
    protected function setTitle($title) {
        $this->data['page_title'] = $title . ' - ' . SITE_NAME;
    }
    
    /**
     * Set page description
     */
    protected function setDescription($description) {
        $this->data['page_description'] = $description;
    }
    
    /**
     * Set page keywords
     */
    protected function setKeywords($keywords) {
        $this->data['page_keywords'] = $keywords;
    }
    
    /**
     * Add CSS file
     */
    protected function addCSS($file) {
        if (!isset($this->data['css_files'])) {
            $this->data['css_files'] = [];
        }
        $this->data['css_files'][] = $file;
    }
    
    /**
     * Add JS file
     */
    protected function addJS($file) {
        if (!isset($this->data['js_files'])) {
            $this->data['js_files'] = [];
        }
        $this->data['js_files'][] = $file;
    }
    
    /**
     * Check if request is AJAX
     */
    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCSRF() {
        $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
        if (!verifyCSRFToken($token)) {
            if ($this->isAjax()) {
                $this->renderJson(['error' => 'Invalid CSRF token'], 403);
            } else {
                throw new Exception('Invalid CSRF token');
            }
        }
    }
    
    /**
     * Sanitize input data
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate email
     */
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
}
