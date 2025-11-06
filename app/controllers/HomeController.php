<?php
/**
 * LDX Software - Home Controller
 * 
 * Handles the main sections of the landing page including
 * hero, about, services, and home page display.
 * 
 * @author LDX Software
 * @version 1.0
 */

// Prevent direct access
if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

require_once 'BaseController.php';

class HomeController extends BaseController {
    
    private $serviceModel;
    private $testimonialModel;
    private $portfolioModel;
    
    public function __construct() {
        parent::__construct();
        
        // Initialize models
        $this->serviceModel = new ServiceModel();
        $this->testimonialModel = new TestimonialModel();
        $this->portfolioModel = new PortfolioModel();
    }
    
    /**
     * Display home page with all sections
     */
    public function index() {
        $this->setTitle('Inicio');
        $this->setDescription('LDX Software - Soluciones de software innovadoras y personalizadas. Desarrollo web, aplicaciones móviles y sistemas empresariales.');
        $this->setKeywords('software, desarrollo web, aplicaciones móviles, LDX Software, sistemas empresariales');
        
        // Get data for home page sections
        $data = [
            'services' => $this->serviceModel->getFeaturedServices(6),
            'testimonials' => $this->testimonialModel->getTestimonials(),
            'featured_projects' => $this->portfolioModel->getFeaturedProjects(6),
            'stats' => [
                'projects_completed' => 150,
                'happy_clients' => 89,
                'years_experience' => 8,
                'team_members' => 12
            ]
        ];
        
        $this->render('home', $data);
    }
    
    /**
     * Display about section
     */
    public function about() {
        $this->setTitle('Acerca de Nosotros');
        $this->setDescription('Conoce más sobre LDX Software, nuestro equipo, misión y visión en el desarrollo de software.');
        
        $data = [
            'team_members' => [
                [
                    'name' => 'Luis Rodríguez',
                    'position' => 'CEO & Fundador',
                    'image' => 'team/luis.jpg',
                    'bio' => 'Más de 10 años de experiencia en desarrollo de software y liderazgo de equipos.',
                    'social' => [
                        'linkedin' => '#',
                        'twitter' => '#',
                        'github' => '#'
                    ]
                ],
                [
                    'name' => 'Diana Martínez',
                    'position' => 'CTO',
                    'image' => 'team/diana.jpg',
                    'bio' => 'Experta en arquitectura de software y tecnologías emergentes.',
                    'social' => [
                        'linkedin' => '#',
                        'twitter' => '#',
                        'github' => '#'
                    ]
                ],
                [
                    'name' => 'Xavier López',
                    'position' => 'Lead Developer',
                    'image' => 'team/xavier.jpg',
                    'bio' => 'Especialista en desarrollo full-stack y metodologías ágiles.',
                    'social' => [
                        'linkedin' => '#',
                        'twitter' => '#',
                        'github' => '#'
                    ]
                ]
            ],
            'company_values' => [
                [
                    'title' => 'Innovación',
                    'description' => 'Utilizamos las últimas tecnologías para crear soluciones vanguardistas.',
                    'icon' => 'innovation'
                ],
                [
                    'title' => 'Calidad',
                    'description' => 'Nos comprometemos a entregar software de la más alta calidad.',
                    'icon' => 'quality'
                ],
                [
                    'title' => 'Colaboración',
                    'description' => 'Trabajamos de cerca con nuestros clientes para entender sus necesidades.',
                    'icon' => 'collaboration'
                ],
                [
                    'title' => 'Transparencia',
                    'description' => 'Mantenemos comunicación clara y honesta en todos nuestros proyectos.',
                    'icon' => 'transparency'
                ]
            ]
        ];
        
        $this->render('about', $data);
    }
    
    /**
     * Display services section
     */
    public function services() {
        $this->setTitle('Servicios');
        $this->setDescription('Descubre todos los servicios que LDX Software ofrece: desarrollo web, aplicaciones móviles, sistemas empresariales y más.');
        
        $data = [
            'services' => $this->serviceModel->getAllServices(),
            'service_categories' => $this->serviceModel->getServiceCategories()
        ];
        
        $this->render('services', $data);
    }
}
