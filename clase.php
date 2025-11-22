<?php
define('LDX_ACCESS', true);
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/models/Curso.php';
require_once __DIR__ . '/app/models/Suscripcion.php';
require_once __DIR__ . '/config/config.php';

// 1. Verificar autenticación y suscripción
if (!AuthController::isAuthenticated()) {
    header("Location: " . url('login') . "?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$user = AuthController::getCurrentUser();
$suscripcionModel = new Suscripcion();
$suscripciones = $suscripcionModel->getSuscripcionesActivas($user['id']);

if (empty($suscripciones)) {
    header("Location: " . url('suscripciones'));
    exit;
}

// 2. Obtener parámetros de la URL
$courseSlug = $_GET['course_slug'] ?? '';
$moduleSlug = $_GET['module_slug'] ?? '';
$lessonSlug = $_GET['lesson_slug'] ?? '';

if (empty($courseSlug) || empty($moduleSlug) || empty($lessonSlug)) {
    header("Location: " . url('cursos'));
    exit;
}

// 3. Obtener datos de la clase
$cursoModel = new Curso();
$data = $cursoModel->getClaseBySlugs($courseSlug, $moduleSlug, $lessonSlug);

if (!$data) {
    http_response_code(404);
    echo "Clase no encontrada";
    exit;
}

$curso = $data['curso'];
$clase = $data['clase'];
$moduloActual = $data['modulo'];
$prevClase = $data['prev'];
$nextClase = $data['next'];
$temario = $data['temario'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($clase['titulo']); ?> - <?php echo htmlspecialchars($curso['titulo']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Plyr CSS -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <style>
        .bg-box { background-color: #0D1828; }
        .border-line { border-color: #1E293B; }
        .text-brand-gray { color: #94A3B8; }
        .text-brand-blue { color: #38BDF8; }
        
        /* Scrollbar custom */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1E293B; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }

        .accordion-content {
            transition: max-height 0.3s ease-in-out;
            max-height: 0;
            overflow: hidden;
        }
        .accordion-content.open {
            max-height: 1000px;
        }
        .rotate-90 { transform: rotate(90deg); }
    </style>
</head>
<body class="bg-[#020617] text-white min-h-screen font-sans flex flex-col">

    <!-- Navbar Simplificado -->
    <nav class="border-b border-line bg-[#020617]/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4 overflow-hidden">
                <a href="<?php echo url('curso/' . $curso['slug']); ?>" class="flex-shrink-0 hover:opacity-80 transition-opacity">
                    <i class="fas fa-arrow-left text-brand-gray mr-2"></i>
                    <span class="font-bold hidden md:inline">Volver al curso</span>
                </a>
                <div class="h-6 w-px bg-line mx-2 hidden md:block"></div>
                <h1 class="text-sm md:text-base font-medium truncate text-brand-gray">
                    <span class="text-white"><?php echo htmlspecialchars($curso['titulo']); ?></span>
                    <span class="mx-2">/</span>
                    <span class="text-brand-blue"><?php echo htmlspecialchars($clase['titulo']); ?></span>
                </h1>
            </div>
            
            <!-- User Menu (Simplified) -->
            <div class="flex items-center gap-4">
                <a href="<?php echo url('mis-suscripciones'); ?>" class="text-sm text-brand-gray hover:text-white transition-colors">Mi Cuenta</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex flex-col lg:flex-row max-w-[1600px] mx-auto w-full">
        
        <!-- Video Content Area -->
        <div class="flex-grow p-4 lg:p-6 lg:pr-8 min-w-0">
            
            <!-- Video Player Container -->
            <div class="aspect-video bg-black rounded-xl overflow-hidden shadow-2xl border border-line relative group">
                <?php if (!empty($clase['video_url'])): ?>
                    <!-- Plyr Video -->
                    <video id="player" playsinline controls data-poster="<?php echo htmlspecialchars(BASE_URL . $curso['imagen_url']); ?>">
                        <source src="<?php echo htmlspecialchars($clase['video_url']); ?>" type="application/x-mpegURL" />
                        <source src="<?php echo htmlspecialchars($clase['video_url']); ?>" type="video/mp4" />
                    </video>
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center bg-[#0D1828]">
                        <div class="text-center">
                            <i class="fas fa-video-slash text-4xl text-brand-gray mb-4"></i>
                            <p class="text-brand-gray">Video no disponible</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-6">
                <?php if ($prevClase): ?>
                    <a href="<?php echo url("curso/{$curso['slug']}/{$prevClase['modulo_slug']}/{$prevClase['slug']}"); ?>" class="px-4 py-2 rounded-lg border border-line bg-box hover:bg-white/5 text-sm flex items-center gap-2 transition-colors">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </a>
                <?php else: ?>
                    <div></div>
                <?php endif; ?>

                <?php if ($nextClase): ?>
                    <a href="<?php echo url("curso/{$curso['slug']}/{$nextClase['modulo_slug']}/{$nextClase['slug']}"); ?>" class="px-4 py-2 rounded-lg bg-interactive-primary hover:bg-[#0284C7] text-white text-sm flex items-center gap-2 transition-colors font-semibold">
                        Siguiente clase <i class="fas fa-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo url("curso/{$curso['slug']}"); ?>" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm flex items-center gap-2 transition-colors font-semibold">
                        Finalizar Curso <i class="fas fa-check"></i>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Class Details -->
            <div class="mt-8 max-w-3xl">
                <h2 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($clase['titulo']); ?></h2>
                <div class="flex items-center gap-4 mb-6">
                    <img src="<?php echo htmlspecialchars(BASE_URL . $curso['instructor_avatar']); ?>" class="w-10 h-10 rounded-full object-cover" alt="Instructor">
                    <div>
                        <p class="text-white font-medium"><?php echo htmlspecialchars($curso['instructor_nombre']); ?></p>
                        <p class="text-xs text-brand-gray">Instructor</p>
                    </div>
                </div>
                
                <div class="prose prose-invert prose-blue max-w-none text-brand-gray">
                    <?php echo $clase['descripcion'] ?? '<p>Sin descripción disponible para esta clase.</p>'; ?>
                </div>
            </div>

        </div>

        <!-- Sidebar (Curriculum) -->
        <aside class="w-full lg:w-[400px] border-l border-line bg-[#020617] lg:h-[calc(100vh-64px)] lg:sticky lg:top-16 overflow-y-auto">
            <div class="p-4 border-b border-line flex justify-between items-center sticky top-0 bg-[#020617] z-10">
                <h3 class="font-bold text-lg">Contenido</h3>
                <div class="text-xs text-brand-gray">
                    <?php echo $curso['total_clases']; ?> clases
                </div>
            </div>

            <div class="p-4 flex flex-col gap-4">
                <?php foreach ($temario as $index => $mod): ?>
                <div class="rounded-xl border border-line bg-box overflow-hidden">
                    <button class="w-full p-3 flex items-center justify-between text-left hover:bg-white/5 transition-colors" onclick="toggleAccordion('sidebar-mod-<?php echo $index; ?>')">
                        <span class="font-medium text-sm text-white">
                            <?php echo htmlspecialchars($mod['titulo']); ?>
                        </span>
                        <i id="icon-sidebar-mod-<?php echo $index; ?>" class="fas fa-chevron-down text-xs text-brand-gray transition-transform duration-300 <?php echo ($mod['slug'] === $moduleSlug) ? 'rotate-180' : ''; ?>"></i>
                    </button>
                    
                    <div id="sidebar-mod-<?php echo $index; ?>" class="accordion-content <?php echo ($mod['slug'] === $moduleSlug) ? 'open' : ''; ?>">
                        <ul class="border-t border-line bg-[#0B1221]">
                            <?php foreach ($mod['clases'] as $c): 
                                $isActive = ($c['slug'] === $lessonSlug && $mod['slug'] === $moduleSlug);
                            ?>
                            <li>
                                <a href="<?php echo url("curso/{$curso['slug']}/{$mod['slug']}/{$c['slug']}"); ?>" 
                                   class="flex items-start gap-3 px-4 py-3 hover:bg-white/5 transition-colors <?php echo $isActive ? 'bg-blue-500/10 border-l-2 border-brand-blue' : 'border-l-2 border-transparent'; ?>">
                                    
                                    <div class="mt-1">
                                        <?php if ($isActive): ?>
                                            <i class="fas fa-play-circle text-brand-blue text-sm"></i>
                                        <?php else: ?>
                                            <i class="far fa-circle text-brand-gray text-xs"></i>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="flex-grow">
                                        <p class="text-sm <?php echo $isActive ? 'text-brand-blue font-medium' : 'text-gray-400'; ?>">
                                            <?php echo htmlspecialchars($c['titulo']); ?>
                                        </p>
                                        <span class="text-xs text-brand-gray block mt-1"><?php echo htmlspecialchars($c['duracion']); ?></span>
                                    </div>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </aside>

    </main>

    <!-- Plyr JS -->
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    <script>
        // Initialize Plyr
        const player = new Plyr('#player', {
            controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'pip', 'airplay', 'fullscreen'],
            settings: ['captions', 'quality', 'speed', 'loop']
        });

        // Accordion Logic
        function toggleAccordion(id) {
            const content = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            
            if (content.classList.contains('open')) {
                content.classList.remove('open');
                if(icon) icon.classList.remove('rotate-180');
            } else {
                content.classList.add('open');
                if(icon) icon.classList.add('rotate-180');
            }
        }
    </script>
</body>
</html>
