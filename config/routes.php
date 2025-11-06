<?php
/**
 * LDX Software - Routes Configuration
 * 
 * This file defines all the routes for the application
 * 
 * @author LDX Software
 * @version 1.0
 */

// Prevent direct access
if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

/**
 * Routes Configuration
 * 
 * Format: 'route' => ['controller' => 'ControllerName', 'action' => 'methodName']
 */
$routes = [
    // Home routes
    '' => ['controller' => 'HomeController', 'action' => 'index'],
    'home' => ['controller' => 'HomeController', 'action' => 'index'],
    'about' => ['controller' => 'HomeController', 'action' => 'about'],
    'services' => ['controller' => 'HomeController', 'action' => 'services'],
    
    // Portfolio routes
    'portfolio' => ['controller' => 'PortfolioController', 'action' => 'index'],
    'portfolio/project' => ['controller' => 'PortfolioController', 'action' => 'project'],
    
    // Contact routes
    'contact' => ['controller' => 'ContactController', 'action' => 'index'],
    'contact/send' => ['controller' => 'ContactController', 'action' => 'send'],
    
    // Legal pages
    'terminos' => ['controller' => 'LegalController', 'action' => 'terminos'],
    'privacidad' => ['controller' => 'LegalController', 'action' => 'privacidad'],
    
    // API routes (for AJAX requests)
    'api/contact' => ['controller' => 'ContactController', 'action' => 'apiSend'],
    'api/newsletter' => ['controller' => 'ContactController', 'action' => 'newsletter'],
];

/**
 * Get route information
 */
function getRoute($uri) {
    global $routes;
    
    // Remove query string and clean URI
    $uri = strtok($uri, '?');
    $uri = trim($uri, '/');
    
    // Check if route exists
    if (isset($routes[$uri])) {
        return $routes[$uri];
    }
    
    // Check for dynamic routes (like portfolio/project/1)
    $segments = explode('/', $uri);
    
    // Portfolio project detail
    if (count($segments) >= 2 && $segments[0] === 'portfolio' && $segments[1] === 'project') {
        return ['controller' => 'PortfolioController', 'action' => 'project'];
    }
    
    // Default to 404
    return ['controller' => 'ErrorController', 'action' => 'notFound'];
}

/**
 * Generate URL for a route
 */
function route($routeName, $params = []) {
    $url = url($routeName);
    
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    
    return $url;
}

/**
 * Redirect to a route
 */
function redirect($route, $params = []) {
    $url = route($route, $params);
    header("Location: $url");
    exit;
}

/**
 * Check if current route matches
 */
function isCurrentRoute($route) {
    $currentUri = trim($_SERVER['REQUEST_URI'], '/');
    $currentUri = str_replace(trim(parse_url(BASE_URL, PHP_URL_PATH), '/'), '', $currentUri);
    $currentUri = trim($currentUri, '/');
    
    return $currentUri === $route || ($route === '' && $currentUri === '');
}
