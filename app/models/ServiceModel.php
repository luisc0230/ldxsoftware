<?php
/**
 * LDX Software - Service Model
 * 
 * Manages services data and operations
 * 
 * @author LDX Software
 * @version 1.0
 */

// Prevent direct access
if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

class ServiceModel {
    
    private $services;
    
    public function __construct() {
        $this->initializeServices();
    }
    
    /**
     * Initialize services data
     */
    private function initializeServices() {
        $this->services = [
            [
                'id' => 1,
                'title' => 'Desarrollo Web',
                'description' => 'Creamos sitios web modernos, responsivos y optimizados para SEO utilizando las últimas tecnologías.',
                'icon' => 'web-development',
                'category' => 'web',
                'features' => [
                    'Diseño responsive',
                    'Optimización SEO',
                    'Integración CMS',
                    'E-commerce',
                    'Progressive Web Apps'
                ],
                'technologies' => ['HTML5', 'CSS3', 'JavaScript', 'PHP', 'React', 'Vue.js'],
                'price_range' => '$2,000 - $15,000',
                'delivery_time' => '2-8 semanas',
                'featured' => true
            ],
            [
                'id' => 2,
                'title' => 'Aplicaciones Móviles',
                'description' => 'Desarrollamos aplicaciones nativas e híbridas para iOS y Android con excelente UX/UI.',
                'icon' => 'mobile-app',
                'category' => 'mobile',
                'features' => [
                    'Apps nativas iOS/Android',
                    'Aplicaciones híbridas',
                    'Integración APIs',
                    'Push notifications',
                    'Análisis y métricas'
                ],
                'technologies' => ['Swift', 'Kotlin', 'React Native', 'Flutter', 'Ionic'],
                'price_range' => '$5,000 - $25,000',
                'delivery_time' => '3-12 semanas',
                'featured' => true
            ],
            [
                'id' => 3,
                'title' => 'Sistemas Empresariales',
                'description' => 'Soluciones ERP, CRM y sistemas de gestión personalizados para optimizar procesos empresariales.',
                'icon' => 'enterprise-system',
                'category' => 'enterprise',
                'features' => [
                    'Sistemas ERP',
                    'CRM personalizado',
                    'Gestión de inventarios',
                    'Reportes y analytics',
                    'Integración con APIs'
                ],
                'technologies' => ['PHP', 'Laravel', 'Node.js', 'Python', 'MySQL', 'PostgreSQL'],
                'price_range' => '$10,000 - $50,000',
                'delivery_time' => '6-20 semanas',
                'featured' => true
            ],
            [
                'id' => 4,
                'title' => 'E-commerce',
                'description' => 'Tiendas en línea completas con pasarelas de pago, gestión de inventarios y análisis de ventas.',
                'icon' => 'ecommerce',
                'category' => 'web',
                'features' => [
                    'Catálogo de productos',
                    'Carrito de compras',
                    'Pasarelas de pago',
                    'Gestión de pedidos',
                    'Panel administrativo'
                ],
                'technologies' => ['WooCommerce', 'Shopify', 'Magento', 'Laravel', 'Stripe'],
                'price_range' => '$3,000 - $20,000',
                'delivery_time' => '3-10 semanas',
                'featured' => true
            ],
            [
                'id' => 5,
                'title' => 'Consultoría IT',
                'description' => 'Asesoría especializada en arquitectura de software, migración de sistemas y optimización.',
                'icon' => 'consulting',
                'category' => 'consulting',
                'features' => [
                    'Auditoría de sistemas',
                    'Arquitectura de software',
                    'Migración de datos',
                    'Optimización de performance',
                    'Capacitación de equipos'
                ],
                'technologies' => ['Análisis', 'Documentación', 'Metodologías ágiles'],
                'price_range' => '$100 - $200/hora',
                'delivery_time' => 'Variable',
                'featured' => false
            ],
            [
                'id' => 6,
                'title' => 'Mantenimiento y Soporte',
                'description' => 'Servicios de mantenimiento, actualizaciones y soporte técnico para tus aplicaciones.',
                'icon' => 'support',
                'category' => 'support',
                'features' => [
                    'Soporte 24/7',
                    'Actualizaciones de seguridad',
                    'Backup automático',
                    'Monitoreo de performance',
                    'Resolución de incidencias'
                ],
                'technologies' => ['Monitoreo', 'DevOps', 'Cloud Services'],
                'price_range' => '$500 - $2,000/mes',
                'delivery_time' => 'Continuo',
                'featured' => true
            ],
            [
                'id' => 7,
                'title' => 'Automatización de Procesos',
                'description' => 'Automatizamos procesos empresariales mediante bots, APIs y integraciones inteligentes.',
                'icon' => 'automation',
                'category' => 'automation',
                'features' => [
                    'Bots de automatización',
                    'Integración de APIs',
                    'Workflows automáticos',
                    'Procesamiento de datos',
                    'Notificaciones inteligentes'
                ],
                'technologies' => ['Python', 'Zapier', 'Microsoft Power Automate', 'APIs'],
                'price_range' => '$2,000 - $10,000',
                'delivery_time' => '2-6 semanas',
                'featured' => false
            ],
            [
                'id' => 8,
                'title' => 'Análisis de Datos',
                'description' => 'Dashboards interactivos, reportes automáticos y análisis predictivo para toma de decisiones.',
                'icon' => 'data-analysis',
                'category' => 'data',
                'features' => [
                    'Dashboards interactivos',
                    'Reportes automáticos',
                    'Análisis predictivo',
                    'Visualización de datos',
                    'Business Intelligence'
                ],
                'technologies' => ['Power BI', 'Tableau', 'Python', 'R', 'SQL'],
                'price_range' => '$3,000 - $15,000',
                'delivery_time' => '3-8 semanas',
                'featured' => true
            ]
        ];
    }
    
    /**
     * Get all services
     */
    public function getAllServices() {
        return $this->services;
    }
    
    /**
     * Get featured services
     */
    public function getFeaturedServices($limit = null) {
        $featured = array_filter($this->services, function($service) {
            return $service['featured'];
        });
        
        if ($limit) {
            return array_slice($featured, 0, $limit);
        }
        
        return $featured;
    }
    
    /**
     * Get service by ID
     */
    public function getServiceById($id) {
        foreach ($this->services as $service) {
            if ($service['id'] == $id) {
                return $service;
            }
        }
        return null;
    }
    
    /**
     * Get services by category
     */
    public function getServicesByCategory($category) {
        return array_filter($this->services, function($service) use ($category) {
            return $service['category'] === $category;
        });
    }
    
    /**
     * Get service categories
     */
    public function getServiceCategories() {
        $categories = [];
        foreach ($this->services as $service) {
            $category = $service['category'];
            if (!isset($categories[$category])) {
                $categories[$category] = [
                    'id' => $category,
                    'name' => $this->getCategoryName($category),
                    'count' => 0
                ];
            }
            $categories[$category]['count']++;
        }
        return array_values($categories);
    }
    
    /**
     * Get category display name
     */
    private function getCategoryName($category) {
        $names = [
            'web' => 'Desarrollo Web',
            'mobile' => 'Aplicaciones Móviles',
            'enterprise' => 'Sistemas Empresariales',
            'consulting' => 'Consultoría',
            'support' => 'Soporte',
            'automation' => 'Automatización',
            'data' => 'Análisis de Datos'
        ];
        
        return $names[$category] ?? ucfirst($category);
    }
    
    /**
     * Search services
     */
    public function searchServices($query) {
        $query = strtolower($query);
        return array_filter($this->services, function($service) use ($query) {
            return strpos(strtolower($service['title']), $query) !== false ||
                   strpos(strtolower($service['description']), $query) !== false ||
                   in_array($query, array_map('strtolower', $service['technologies']));
        });
    }
}
