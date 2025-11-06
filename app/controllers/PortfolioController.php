<?php
/**
 * LDX Software - Portfolio Controller
 * 
 * Handles portfolio display and project details
 * 
 * @author LDX Software
 * @version 1.0
 */

// Prevent direct access
if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

require_once 'BaseController.php';

class PortfolioController extends BaseController {
    
    private $portfolioModel;
    
    public function __construct() {
        parent::__construct();
        $this->portfolioModel = new PortfolioModel();
    }
    
    /**
     * Display portfolio gallery
     */
    public function index() {
        $this->setTitle('Portfolio');
        $this->setDescription('Explora nuestro portfolio de proyectos exitosos en LDX Software. Desarrollo web, aplicaciones móviles y sistemas empresariales.');
        $this->setKeywords('portfolio, proyectos, desarrollo web, aplicaciones, LDX Software');
        
        // Get filter from query string
        $filter = $_GET['category'] ?? 'all';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 12;
        
        $data = [
            'projects' => $this->portfolioModel->getProjects($filter, $page, $perPage),
            'categories' => $this->portfolioModel->getCategories(),
            'current_filter' => $filter,
            'current_page' => $page,
            'total_projects' => $this->portfolioModel->getTotalProjects($filter),
            'per_page' => $perPage
        ];
        
        // Calculate pagination
        $data['total_pages'] = ceil($data['total_projects'] / $perPage);
        $data['has_prev'] = $page > 1;
        $data['has_next'] = $page < $data['total_pages'];
        
        $this->render('portfolio/index', $data);
    }
    
    /**
     * Display project details
     */
    public function project() {
        // Get project ID from URL
        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($uri, '/'));
        $projectId = end($segments);
        
        if (!$projectId || !is_numeric($projectId)) {
            $this->redirect(url('portfolio'));
            return;
        }
        
        $project = $this->portfolioModel->getProjectById($projectId);
        
        if (!$project) {
            $this->redirect(url('portfolio'));
            return;
        }
        
        $this->setTitle($project['title']);
        $this->setDescription($project['description']);
        $this->setKeywords($project['tags']);
        
        // Get related projects
        $relatedProjects = $this->portfolioModel->getRelatedProjects($projectId, $project['category'], 4);
        
        $data = [
            'project' => $project,
            'related_projects' => $relatedProjects
        ];
        
        $this->render('portfolio/project', $data);
    }
    
    /**
     * AJAX endpoint for loading more projects
     */
    public function loadMore() {
        if (!$this->isAjax()) {
            $this->renderJson(['error' => 'Invalid request'], 400);
            return;
        }
        
        $filter = $_POST['category'] ?? 'all';
        $page = (int)($_POST['page'] ?? 1);
        $perPage = 6;
        
        $projects = $this->portfolioModel->getProjects($filter, $page, $perPage);
        $totalProjects = $this->portfolioModel->getTotalProjects($filter);
        $totalPages = ceil($totalProjects / $perPage);
        
        $this->renderJson([
            'success' => true,
            'projects' => $projects,
            'has_more' => $page < $totalPages,
            'next_page' => $page + 1
        ]);
    }
}
