<?php
/**
 * LDX Software - Error Controller
 * 
 * Handles error pages and exceptions
 * 
 * @author LDX Software
 * @version 1.0
 */

// Prevent direct access
if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

require_once 'BaseController.php';

class ErrorController extends BaseController {
    
    /**
     * Display 404 error page
     */
    public function notFound() {
        $this->setTitle('Página no encontrada');
        $this->setDescription('La página que buscas no existe o ha sido movida.');
        
        // Set 404 HTTP status code
        http_response_code(404);
        
        $data = [
            'error_code' => '404',
            'error_title' => 'Página no encontrada',
            'error_message' => 'Lo sentimos, la página que buscas no existe o ha sido movida.',
            'suggestions' => [
                'Verifica que la URL esté escrita correctamente',
                'Regresa a la página de inicio',
                'Usa el menú de navegación para encontrar lo que buscas',
                'Contáctanos si necesitas ayuda'
            ]
        ];
        
        $this->render('errors/404', $data);
    }
    
    /**
     * Display 500 error page
     */
    public function serverError() {
        $this->setTitle('Error del servidor');
        $this->setDescription('Ha ocurrido un error interno del servidor.');
        
        // Set 500 HTTP status code
        http_response_code(500);
        
        $data = [
            'error_code' => '500',
            'error_title' => 'Error interno del servidor',
            'error_message' => 'Ha ocurrido un error interno. Nuestro equipo ha sido notificado y está trabajando para solucionarlo.',
            'suggestions' => [
                'Intenta recargar la página en unos minutos',
                'Regresa a la página de inicio',
                'Contáctanos si el problema persiste'
            ]
        ];
        
        $this->render('errors/500', $data);
    }
    
    /**
     * Display 403 error page
     */
    public function forbidden() {
        $this->setTitle('Acceso denegado');
        $this->setDescription('No tienes permisos para acceder a esta página.');
        
        // Set 403 HTTP status code
        http_response_code(403);
        
        $data = [
            'error_code' => '403',
            'error_title' => 'Acceso denegado',
            'error_message' => 'No tienes permisos para acceder a esta página o recurso.',
            'suggestions' => [
                'Verifica que tengas los permisos necesarios',
                'Inicia sesión si es requerido',
                'Regresa a la página de inicio',
                'Contáctanos si crees que esto es un error'
            ]
        ];
        
        $this->render('errors/403', $data);
    }
    
    /**
     * Display maintenance page
     */
    public function maintenance() {
        $this->setTitle('Sitio en mantenimiento');
        $this->setDescription('El sitio está temporalmente en mantenimiento.');
        
        // Set 503 HTTP status code
        http_response_code(503);
        
        $data = [
            'error_code' => '503',
            'error_title' => 'Sitio en mantenimiento',
            'error_message' => 'Estamos realizando mejoras en nuestro sitio. Regresa pronto.',
            'estimated_time' => 'Tiempo estimado: 2 horas',
            'contact_email' => SMTP_FROM_EMAIL
        ];
        
        $this->render('errors/maintenance', $data);
    }
}
