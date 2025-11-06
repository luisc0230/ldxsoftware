<?php
/**
 * LDX Software - Testimonial Model
 * 
 * Manages client testimonials and reviews
 * 
 * @author LDX Software
 * @version 1.0
 */

// Prevent direct access
if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

class TestimonialModel {
    
    private $testimonials;
    
    public function __construct() {
        $this->initializeTestimonials();
    }
    
    /**
     * Initialize testimonials data
     */
    private function initializeTestimonials() {
        $this->testimonials = [
            [
                'id' => 1,
                'name' => 'María González',
                'position' => 'CEO',
                'company' => 'MexiTech Solutions',
                'project' => 'E-commerce MexiTech',
                'rating' => 5,
                'testimonial' => 'LDX Software superó nuestras expectativas. El e-commerce que desarrollaron no solo es visualmente impresionante, sino que también ha aumentado nuestras ventas en un 150%. Su equipo es profesional, creativo y siempre dispuesto a ir más allá.',
                'image' => 'testimonials/maria-gonzalez.jpg',
                'date' => '2024-01-15',
                'featured' => true,
                'verified' => true
            ],
            [
                'id' => 2,
                'name' => 'Carlos Rodríguez',
                'position' => 'Director de Operaciones',
                'company' => 'FastFood Chain',
                'project' => 'App Delivery FastFood',
                'rating' => 5,
                'testimonial' => 'La aplicación móvil que desarrolló LDX Software revolucionó nuestro negocio. El sistema de tracking en tiempo real y la interfaz intuitiva han mejorado significativamente la experiencia de nuestros clientes. Altamente recomendados.',
                'image' => 'testimonials/carlos-rodriguez.jpg',
                'date' => '2024-02-20',
                'featured' => true,
                'verified' => true
            ],
            [
                'id' => 3,
                'name' => 'Ana Martínez',
                'position' => 'Gerente General',
                'company' => 'ManufacturaPro SA',
                'project' => 'Sistema ERP ManufacturaPro',
                'rating' => 5,
                'testimonial' => 'El sistema ERP que implementó LDX Software transformó completamente nuestros procesos. La integración fue perfecta y el soporte post-implementación ha sido excepcional. Nuestros procesos son ahora un 40% más eficientes.',
                'image' => 'testimonials/ana-martinez.jpg',
                'date' => '2023-11-10',
                'featured' => true,
                'verified' => true
            ],
            [
                'id' => 4,
                'name' => 'Roberto Silva',
                'position' => 'Director Académico',
                'company' => 'EduLearn Institute',
                'project' => 'Portal Educativo EduLearn',
                'rating' => 5,
                'testimonial' => 'La plataforma educativa que desarrollaron es increíble. Nuestros estudiantes están más comprometidos que nunca y hemos visto un aumento del 200% en la finalización de cursos. La calidad del trabajo de LDX Software es incomparable.',
                'image' => 'testimonials/roberto-silva.jpg',
                'date' => '2024-03-05',
                'featured' => true,
                'verified' => true
            ],
            [
                'id' => 5,
                'name' => 'Patricia López',
                'position' => 'CFO',
                'company' => 'BizIntel Corp',
                'project' => 'Dashboard Analytics BizIntel',
                'rating' => 4,
                'testimonial' => 'El dashboard de analytics que crearon nos ha dado insights valiosos sobre nuestro negocio. Las visualizaciones son claras y los reportes automáticos nos ahorran horas de trabajo cada semana. Excelente trabajo.',
                'image' => 'testimonials/patricia-lopez.jpg',
                'date' => '2023-12-18',
                'featured' => false,
                'verified' => true
            ],
            [
                'id' => 6,
                'name' => 'Miguel Hernández',
                'position' => 'Propietario',
                'company' => 'FitLife Gym',
                'project' => 'App Fitness TrackFit',
                'rating' => 5,
                'testimonial' => 'La app de fitness que están desarrollando para nosotros ya muestra un gran potencial. El equipo de LDX Software entiende perfectamente nuestras necesidades y la experiencia de usuario es fantástica.',
                'image' => 'testimonials/miguel-hernandez.jpg',
                'date' => '2024-01-30',
                'featured' => false,
                'verified' => true
            ],
            [
                'id' => 7,
                'name' => 'Sofía Ramírez',
                'position' => 'Directora de Ventas',
                'company' => 'VentasPro Solutions',
                'project' => 'Sistema CRM VentasPro',
                'rating' => 5,
                'testimonial' => 'El CRM personalizado ha transformado nuestro proceso de ventas. La automatización y los reportes detallados nos han ayudado a aumentar nuestras conversiones en un 35%. LDX Software entregó exactamente lo que necesitábamos.',
                'image' => 'testimonials/sofia-ramirez.jpg',
                'date' => '2023-10-25',
                'featured' => true,
                'verified' => true
            ],
            [
                'id' => 8,
                'name' => 'David Chen',
                'position' => 'Marketing Director',
                'company' => 'TechCorp International',
                'project' => 'Sitio Web Corporativo TechCorp',
                'rating' => 5,
                'testimonial' => 'Nuestro nuevo sitio web corporativo es simplemente espectacular. El diseño es moderno, la navegación es intuitiva y el SEO ha mejorado dramáticamente nuestro posicionamiento. LDX Software hizo un trabajo excepcional.',
                'image' => 'testimonials/david-chen.jpg',
                'date' => '2024-02-14',
                'featured' => false,
                'verified' => true
            ],
            [
                'id' => 9,
                'name' => 'Laura Jiménez',
                'position' => 'Fundadora',
                'company' => 'StartupTech',
                'project' => 'Consultoría IT',
                'rating' => 5,
                'testimonial' => 'La consultoría que recibimos de LDX Software fue invaluable para nuestra startup. Su experiencia y conocimiento nos ayudaron a tomar decisiones técnicas acertadas que nos ahorraron tiempo y dinero.',
                'image' => 'testimonials/laura-jimenez.jpg',
                'date' => '2024-01-08',
                'featured' => false,
                'verified' => true
            ],
            [
                'id' => 10,
                'name' => 'Fernando Morales',
                'position' => 'CTO',
                'company' => 'InnovaCorp',
                'project' => 'Automatización de Procesos',
                'rating' => 4,
                'testimonial' => 'La automatización de procesos que implementaron ha optimizado significativamente nuestras operaciones. Los bots y workflows automáticos nos han permitido enfocar nuestro tiempo en tareas más estratégicas.',
                'image' => 'testimonials/fernando-morales.jpg',
                'date' => '2023-09-12',
                'featured' => false,
                'verified' => true
            ]
        ];
    }
    
    /**
     * Get all testimonials
     */
    public function getTestimonials() {
        // Sort by date (newest first) and featured status
        $testimonials = $this->testimonials;
        usort($testimonials, function($a, $b) {
            if ($a['featured'] !== $b['featured']) {
                return $b['featured'] - $a['featured'];
            }
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return $testimonials;
    }
    
    /**
     * Get featured testimonials
     */
    public function getFeaturedTestimonials($limit = null) {
        $featured = array_filter($this->testimonials, function($testimonial) {
            return $testimonial['featured'];
        });
        
        // Sort by date
        usort($featured, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        if ($limit) {
            return array_slice($featured, 0, $limit);
        }
        
        return $featured;
    }
    
    /**
     * Get testimonial by ID
     */
    public function getTestimonialById($id) {
        foreach ($this->testimonials as $testimonial) {
            if ($testimonial['id'] == $id) {
                return $testimonial;
            }
        }
        return null;
    }
    
    /**
     * Get testimonials by rating
     */
    public function getTestimonialsByRating($rating) {
        return array_filter($this->testimonials, function($testimonial) use ($rating) {
            return $testimonial['rating'] >= $rating;
        });
    }
    
    /**
     * Get testimonials by project
     */
    public function getTestimonialsByProject($project) {
        return array_filter($this->testimonials, function($testimonial) use ($project) {
            return stripos($testimonial['project'], $project) !== false;
        });
    }
    
    /**
     * Get average rating
     */
    public function getAverageRating() {
        if (empty($this->testimonials)) {
            return 0;
        }
        
        $totalRating = array_sum(array_column($this->testimonials, 'rating'));
        return round($totalRating / count($this->testimonials), 1);
    }
    
    /**
     * Get rating distribution
     */
    public function getRatingDistribution() {
        $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        
        foreach ($this->testimonials as $testimonial) {
            $rating = $testimonial['rating'];
            if (isset($distribution[$rating])) {
                $distribution[$rating]++;
            }
        }
        
        return $distribution;
    }
    
    /**
     * Get testimonials count
     */
    public function getTestimonialsCount() {
        return count($this->testimonials);
    }
    
    /**
     * Get recent testimonials
     */
    public function getRecentTestimonials($limit = 5) {
        $testimonials = $this->testimonials;
        
        // Sort by date (newest first)
        usort($testimonials, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return array_slice($testimonials, 0, $limit);
    }
    
    /**
     * Search testimonials
     */
    public function searchTestimonials($query) {
        $query = strtolower($query);
        return array_filter($this->testimonials, function($testimonial) use ($query) {
            return strpos(strtolower($testimonial['name']), $query) !== false ||
                   strpos(strtolower($testimonial['company']), $query) !== false ||
                   strpos(strtolower($testimonial['testimonial']), $query) !== false ||
                   strpos(strtolower($testimonial['project']), $query) !== false;
        });
    }
}
