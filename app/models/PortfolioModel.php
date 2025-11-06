<?php
/**
 * LDX Software - Portfolio Model
 * 
 * Manages portfolio projects data and operations
 * 
 * @author LDX Software
 * @version 1.0
 */

// Prevent direct access
if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

class PortfolioModel {
    
    private $projects;
    
    public function __construct() {
        $this->initializeProjects();
    }
    
    /**
     * Initialize projects data
     */
    private function initializeProjects() {
        $this->projects = [
            [
                'id' => 1,
                'title' => 'E-commerce MexiTech',
                'description' => 'Plataforma de comercio electrónico completa para empresa de tecnología con más de 10,000 productos.',
                'short_description' => 'Tienda en línea con gestión avanzada de inventarios',
                'category' => 'ecommerce',
                'client' => 'MexiTech Solutions',
                'year' => 2024,
                'duration' => '3 meses',
                'team_size' => 4,
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Stripe', 'AWS'],
                'features' => [
                    'Catálogo de productos con filtros avanzados',
                    'Sistema de pagos múltiples (tarjetas, OXXO, transferencias)',
                    'Panel administrativo completo',
                    'Integración con sistemas de inventario',
                    'Análisis de ventas y reportes',
                    'App móvil complementaria'
                ],
                'images' => [
                    'portfolio/mexitech-1.jpg',
                    'portfolio/mexitech-2.jpg',
                    'portfolio/mexitech-3.jpg'
                ],
                'thumbnail' => 'portfolio/thumbs/mexitech.jpg',
                'url' => 'https://mexitech.com',
                'featured' => true,
                'status' => 'completed',
                'tags' => 'ecommerce, laravel, vue, mysql, stripe'
            ],
            [
                'id' => 2,
                'title' => 'App Delivery FastFood',
                'description' => 'Aplicación móvil para pedidos de comida rápida con geolocalización y seguimiento en tiempo real.',
                'short_description' => 'App de delivery con tracking en tiempo real',
                'category' => 'mobile',
                'client' => 'FastFood Chain',
                'year' => 2024,
                'duration' => '4 meses',
                'team_size' => 5,
                'technologies' => ['React Native', 'Node.js', 'MongoDB', 'Socket.io', 'Google Maps API'],
                'features' => [
                    'Registro y autenticación de usuarios',
                    'Catálogo de restaurantes y menús',
                    'Carrito de compras y pagos integrados',
                    'Geolocalización y mapas',
                    'Seguimiento de pedidos en tiempo real',
                    'Sistema de calificaciones y reseñas',
                    'Notificaciones push',
                    'Panel para restaurantes'
                ],
                'images' => [
                    'portfolio/fastfood-1.jpg',
                    'portfolio/fastfood-2.jpg',
                    'portfolio/fastfood-3.jpg'
                ],
                'thumbnail' => 'portfolio/thumbs/fastfood.jpg',
                'url' => null,
                'featured' => true,
                'status' => 'completed',
                'tags' => 'mobile, react native, nodejs, mongodb, delivery'
            ],
            [
                'id' => 3,
                'title' => 'Sistema ERP ManufacturaPro',
                'description' => 'Sistema integral de planificación de recursos empresariales para empresa manufacturera.',
                'short_description' => 'ERP completo para gestión manufacturera',
                'category' => 'enterprise',
                'client' => 'ManufacturaPro SA',
                'year' => 2023,
                'duration' => '8 meses',
                'team_size' => 6,
                'technologies' => ['PHP', 'Laravel', 'PostgreSQL', 'Redis', 'Docker'],
                'features' => [
                    'Gestión de inventarios y almacenes',
                    'Control de producción y calidad',
                    'Módulo de ventas y facturación',
                    'Gestión de recursos humanos',
                    'Contabilidad y finanzas',
                    'Reportes ejecutivos y KPIs',
                    'Integración con sistemas externos',
                    'Módulo de mantenimiento'
                ],
                'images' => [
                    'portfolio/manufacturapro-1.jpg',
                    'portfolio/manufacturapro-2.jpg',
                    'portfolio/manufacturapro-3.jpg'
                ],
                'thumbnail' => 'portfolio/thumbs/manufacturapro.jpg',
                'url' => null,
                'featured' => true,
                'status' => 'completed',
                'tags' => 'erp, laravel, postgresql, enterprise, manufacturing'
            ],
            [
                'id' => 4,
                'title' => 'Portal Educativo EduLearn',
                'description' => 'Plataforma de aprendizaje en línea con cursos interactivos, evaluaciones y certificaciones.',
                'short_description' => 'LMS con cursos interactivos y certificaciones',
                'category' => 'web',
                'client' => 'EduLearn Institute',
                'year' => 2024,
                'duration' => '5 meses',
                'team_size' => 4,
                'technologies' => ['React', 'Node.js', 'Express', 'MongoDB', 'WebRTC'],
                'features' => [
                    'Gestión de cursos y contenido',
                    'Videos interactivos y streaming',
                    'Sistema de evaluaciones',
                    'Certificaciones digitales',
                    'Foros de discusión',
                    'Videoconferencias integradas',
                    'Progreso y analytics',
                    'Pagos y suscripciones'
                ],
                'images' => [
                    'portfolio/edulearn-1.jpg',
                    'portfolio/edulearn-2.jpg',
                    'portfolio/edulearn-3.jpg'
                ],
                'thumbnail' => 'portfolio/thumbs/edulearn.jpg',
                'url' => 'https://edulearn.mx',
                'featured' => true,
                'status' => 'completed',
                'tags' => 'education, react, nodejs, mongodb, lms'
            ],
            [
                'id' => 5,
                'title' => 'Dashboard Analytics BizIntel',
                'description' => 'Dashboard de inteligencia de negocios con visualizaciones interactivas y reportes automáticos.',
                'short_description' => 'BI Dashboard con analytics avanzados',
                'category' => 'data',
                'client' => 'BizIntel Corp',
                'year' => 2023,
                'duration' => '3 meses',
                'team_size' => 3,
                'technologies' => ['Python', 'Django', 'D3.js', 'PostgreSQL', 'Celery'],
                'features' => [
                    'Dashboards interactivos',
                    'Visualizaciones personalizadas',
                    'Reportes automáticos',
                    'Análisis predictivo',
                    'Integración con múltiples fuentes',
                    'Alertas y notificaciones',
                    'Exportación de datos',
                    'API para integraciones'
                ],
                'images' => [
                    'portfolio/bizintel-1.jpg',
                    'portfolio/bizintel-2.jpg',
                    'portfolio/bizintel-3.jpg'
                ],
                'thumbnail' => 'portfolio/thumbs/bizintel.jpg',
                'url' => null,
                'featured' => false,
                'status' => 'completed',
                'tags' => 'analytics, python, django, d3js, business intelligence'
            ],
            [
                'id' => 6,
                'title' => 'App Fitness TrackFit',
                'description' => 'Aplicación móvil para seguimiento de ejercicios, nutrición y progreso físico.',
                'short_description' => 'App de fitness con tracking completo',
                'category' => 'mobile',
                'client' => 'FitLife Gym',
                'year' => 2024,
                'duration' => '3 meses',
                'team_size' => 3,
                'technologies' => ['Flutter', 'Firebase', 'Dart', 'Cloud Functions'],
                'features' => [
                    'Rutinas de ejercicio personalizadas',
                    'Seguimiento de progreso',
                    'Contador de calorías',
                    'Planes nutricionales',
                    'Integración con wearables',
                    'Retos y gamificación',
                    'Comunidad de usuarios',
                    'Entrenadores virtuales'
                ],
                'images' => [
                    'portfolio/trackfit-1.jpg',
                    'portfolio/trackfit-2.jpg',
                    'portfolio/trackfit-3.jpg'
                ],
                'thumbnail' => 'portfolio/thumbs/trackfit.jpg',
                'url' => null,
                'featured' => true,
                'status' => 'in_progress',
                'tags' => 'fitness, flutter, firebase, mobile, health'
            ],
            [
                'id' => 7,
                'title' => 'Sistema CRM VentasPro',
                'description' => 'CRM personalizado para gestión de clientes, ventas y seguimiento de oportunidades.',
                'short_description' => 'CRM con automatización de ventas',
                'category' => 'enterprise',
                'client' => 'VentasPro Solutions',
                'year' => 2023,
                'duration' => '4 meses',
                'team_size' => 4,
                'technologies' => ['Vue.js', 'Laravel', 'MySQL', 'Redis', 'Elasticsearch'],
                'features' => [
                    'Gestión de contactos y leads',
                    'Pipeline de ventas visual',
                    'Automatización de marketing',
                    'Seguimiento de oportunidades',
                    'Reportes de ventas',
                    'Integración con email',
                    'Calendario y tareas',
                    'API para integraciones'
                ],
                'images' => [
                    'portfolio/ventaspro-1.jpg',
                    'portfolio/ventaspro-2.jpg',
                    'portfolio/ventaspro-3.jpg'
                ],
                'thumbnail' => 'portfolio/thumbs/ventaspro.jpg',
                'url' => null,
                'featured' => false,
                'status' => 'completed',
                'tags' => 'crm, vuejs, laravel, mysql, sales'
            ],
            [
                'id' => 8,
                'title' => 'Sitio Web Corporativo TechCorp',
                'description' => 'Sitio web corporativo moderno con CMS personalizado y optimización SEO avanzada.',
                'short_description' => 'Website corporativo con CMS custom',
                'category' => 'web',
                'client' => 'TechCorp International',
                'year' => 2024,
                'duration' => '2 meses',
                'team_size' => 3,
                'technologies' => ['Next.js', 'Strapi', 'PostgreSQL', 'Vercel'],
                'features' => [
                    'Diseño responsive moderno',
                    'CMS headless personalizado',
                    'Optimización SEO avanzada',
                    'Blog integrado',
                    'Formularios de contacto',
                    'Galería de proyectos',
                    'Múltiples idiomas',
                    'Analytics integrado'
                ],
                'images' => [
                    'portfolio/techcorp-1.jpg',
                    'portfolio/techcorp-2.jpg',
                    'portfolio/techcorp-3.jpg'
                ],
                'thumbnail' => 'portfolio/thumbs/techcorp.jpg',
                'url' => 'https://techcorp.com',
                'featured' => true,
                'status' => 'completed',
                'tags' => 'website, nextjs, strapi, seo, corporate'
            ]
        ];
    }
    
    /**
     * Get all projects with pagination
     */
    public function getProjects($category = 'all', $page = 1, $perPage = 12) {
        $projects = $this->projects;
        
        // Filter by category
        if ($category !== 'all') {
            $projects = array_filter($projects, function($project) use ($category) {
                return $project['category'] === $category;
            });
        }
        
        // Sort by year and featured status
        usort($projects, function($a, $b) {
            if ($a['featured'] !== $b['featured']) {
                return $b['featured'] - $a['featured'];
            }
            return $b['year'] - $a['year'];
        });
        
        // Pagination
        $offset = ($page - 1) * $perPage;
        return array_slice($projects, $offset, $perPage);
    }
    
    /**
     * Get total projects count
     */
    public function getTotalProjects($category = 'all') {
        if ($category === 'all') {
            return count($this->projects);
        }
        
        return count(array_filter($this->projects, function($project) use ($category) {
            return $project['category'] === $category;
        }));
    }
    
    /**
     * Get featured projects
     */
    public function getFeaturedProjects($limit = null) {
        $featured = array_filter($this->projects, function($project) {
            return $project['featured'];
        });
        
        if ($limit) {
            return array_slice($featured, 0, $limit);
        }
        
        return $featured;
    }
    
    /**
     * Get project by ID
     */
    public function getProjectById($id) {
        foreach ($this->projects as $project) {
            if ($project['id'] == $id) {
                return $project;
            }
        }
        return null;
    }
    
    /**
     * Get related projects
     */
    public function getRelatedProjects($projectId, $category, $limit = 4) {
        $related = array_filter($this->projects, function($project) use ($projectId, $category) {
            return $project['id'] != $projectId && $project['category'] === $category;
        });
        
        return array_slice($related, 0, $limit);
    }
    
    /**
     * Get project categories
     */
    public function getCategories() {
        $categories = [];
        foreach ($this->projects as $project) {
            $category = $project['category'];
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
            'mobile' => 'Apps Móviles',
            'enterprise' => 'Sistemas Empresariales',
            'ecommerce' => 'E-commerce',
            'data' => 'Análisis de Datos'
        ];
        
        return $names[$category] ?? ucfirst($category);
    }
    
    /**
     * Search projects
     */
    public function searchProjects($query) {
        $query = strtolower($query);
        return array_filter($this->projects, function($project) use ($query) {
            return strpos(strtolower($project['title']), $query) !== false ||
                   strpos(strtolower($project['description']), $query) !== false ||
                   strpos(strtolower($project['tags']), $query) !== false;
        });
    }
}
