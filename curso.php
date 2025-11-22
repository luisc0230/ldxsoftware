<?php
define('LDX_ACCESS', true);

// Configuración primero para asegurar que las constantes estén disponibles
require_once __DIR__ . '/config/config.php';

// Luego los modelos y controladores
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/models/Curso.php';
require_once __DIR__ . '/app/models/Suscripcion.php';

// Habilitar reporte de errores si estamos en modo debug o si hay problemas
if (isset($_GET['debug']) || (defined('DEBUG_MODE') && DEBUG_MODE)) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header("Location: " . BASE_URL);
    exit;
}

$cursoModel = new Curso();
$curso = $cursoModel->getCursoBySlug($slug);

if (!$curso) {
    http_response_code(404);
    echo "Curso no encontrado";
    exit;
}

$contenido = $cursoModel->getContenidoCurso($curso['id']);

// Verificar suscripción
$isLoggedIn = AuthController::isAuthenticated();
$user = AuthController::getCurrentUser();
$hasActiveSubscription = false;

if ($isLoggedIn) {
    $suscripcionModel = new Suscripcion();
    $suscripciones = $suscripcionModel->getSuscripcionesActivas($user['id']);
    if (!empty($suscripciones)) {
        $hasActiveSubscription = true;
    }
}

// Determinar URL del botón principal
$firstLessonUrl = '#';
$courseSlug = !empty($curso['slug']) ? $curso['slug'] : $curso['id'];

if (!empty($contenido) && !empty($contenido[0]['clases'])) {
    $firstLessonUrl = url("curso/{$courseSlug}/{$contenido[0]['slug']}/{$contenido[0]['clases'][0]['slug']}");
}

$ctaLink = $hasActiveSubscription ? $firstLessonUrl : url('suscripciones');
$ctaText = $hasActiveSubscription ? 'Empezar curso' : 'Suscribirse para acceder';

// Parse JSON fields
$stack = json_decode($curso['stack'] ?? '[]', true);
if (!is_array($stack)) $stack = [];

$learningGoals = json_decode($curso['lo_que_aprenderas'] ?? '[]', true);
if (!is_array($learningGoals)) $learningGoals = [];

?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($curso['titulo']); ?> - LDX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom styles to match the provided design */
        .bg-box { background-color: #0D1828; }
        .border-line { border-color: #1E293B; }
        .text-brand-gray { color: #94A3B8; }
        .text-brand-blue { color: #38BDF8; }
        .bg-interactive-primary { background-color: #0EA5E9; }
        .bg-interactive-primary:hover { background-color: #0284C7; }
        .text-text-inverse { color: #FFFFFF; }
        
        /* Accordion transition */
        .accordion-content {
            transition: max-height 0.3s ease-in-out;
            max-height: 0;
        }
        .accordion-content.open {
            max-height: 1000px; /* Arbitrary large value */
        }
        .rotate-90 {
            transform: rotate(90deg);
        }
    </style>
</head>
<body class="bg-[#020617] text-white min-h-screen font-sans">

    <?php include __DIR__ . '/app/includes/navbar.php'; ?>

    <main class="pt-24 pb-12 px-4">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-[1fr_332px] gap-8">
            
            <!-- Breadcrumb -->
            <nav class="text-brand-gray gap-2 flex px-4 lg:px-0 mb-6 col-span-full row-start-1">
                <a href="<?php echo BASE_URL; ?>" class="transition-colors hover:underline">Inicio</a>
                <span aria-hidden="true">/</span>
                <a href="<?php echo url('cursos'); ?>" class="transition-colors hover:underline">Cursos</a>
                <span aria-hidden="true">/</span>
                <span class="text-brand-blue text-ellipsis overflow-hidden whitespace-nowrap">
                    <?php echo htmlspecialchars($curso['titulo']); ?>
                </span>
            </nav>

            <!-- Hero Image & CTA (Mobile/Desktop) -->
            <div class="relative col-span-1 row-start-2 lg:row-start-2">
                <div class="relative rounded-[30px] overflow-hidden border border-[#111C2D] bg-[#0D1828]">
                    <img src="<?php echo htmlspecialchars(BASE_URL . $curso['imagen_url']); ?>" 
                         class="w-full aspect-video object-cover" 
                         alt="Portada del curso <?php echo htmlspecialchars($curso['titulo']); ?>">
                    
                    <!-- Play Button Overlay -->
                    <a href="<?php echo $ctaLink; ?>" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#0D1828]/90 border border-brand-gray py-3 px-6 rounded-xl flex items-center gap-2 hover:scale-110 transition-transform backdrop-blur-sm group">
                        <i class="fas fa-play text-white group-hover:text-brand-blue transition-colors"></i>
                        <span class="font-semibold"><?php echo $ctaText; ?></span>
                    </a>

                    <!-- Instructor Overlay -->
                    <div class="absolute bottom-5 left-5 flex items-center gap-3 bg-black/40 p-2 rounded-xl backdrop-blur-sm">
                        <img class="w-12 h-12 rounded-xl object-cover" src="<?php echo htmlspecialchars(BASE_URL . $curso['instructor_avatar']); ?>" alt="<?php echo htmlspecialchars($curso['instructor_nombre']); ?>">
                        <div>
                            <p class="text-white/60 text-xs">Un curso de</p>
                            <p class="text-white font-semibold text-sm"><?php echo htmlspecialchars($curso['instructor_nombre']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Title & Description -->
            <div class="col-span-1 row-start-3 lg:row-start-3">
                <h1 class="text-3xl md:text-4xl font-bold text-balance mb-6"><?php echo htmlspecialchars($curso['titulo']); ?></h1>
                <div class="text-brand-gray leading-relaxed space-y-4">
                    <p><?php echo nl2br(htmlspecialchars($curso['descripcion'])); ?></p>
                </div>
            </div>

            <!-- Stack -->
            <div class="col-span-1 row-start-4 lg:row-start-4 mt-8">
                <h2 class="text-xl font-bold mb-4">Stack</h2>
                <ul class="flex flex-wrap gap-3">
                    <?php foreach ($stack as $tech): ?>
                    <li class="bg-box px-4 py-2 flex items-center gap-2 justify-center rounded-lg border border-line text-sm font-medium">
                        <span><?php echo htmlspecialchars($tech); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- What you will learn -->
            <div class="col-span-1 row-start-5 lg:row-start-5 mt-8">
                <h2 class="text-xl font-bold mb-4">Lo que aprenderás</h2>
                <ul class="grid md:grid-cols-2 gap-3">
                    <?php foreach ($learningGoals as $goal): ?>
                    <li class="bg-box px-4 py-3 flex items-start gap-3 rounded-lg border border-line">
                        <i class="fas fa-check text-green-400 mt-1"></i>
                        <span class="text-sm text-gray-300"><?php echo htmlspecialchars($goal); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Course Content (Accordion) -->
            <div class="col-span-1 row-start-6 lg:row-start-6 mt-10">
                <h2 class="text-xl font-bold mb-2">Contenido del curso</h2>
                <div class="flex items-center gap-2 text-brand-gray text-sm mb-6">
                    <span><?php echo count($contenido); ?> secciones</span>
                    <span>•</span>
                    <span><?php echo $curso['total_clases']; ?> clases</span>
                    <span>•</span>
                    <span><?php echo htmlspecialchars($curso['duracion_total']); ?> de duración total</span>
                </div>

                <div class="flex flex-col gap-4">
                    <?php foreach ($contenido as $index => $modulo): ?>
                    <div class="rounded-2xl bg-box border border-line overflow-hidden">
                        <button class="w-full p-4 flex items-center gap-4 text-left hover:bg-white/5 transition-colors" onclick="toggleAccordion('modulo-<?php echo $index; ?>')">
                            <i id="icon-modulo-<?php echo $index; ?>" class="fas fa-chevron-right text-white transition-transform duration-300"></i>
                            <div>
                                <h3 class="text-sm text-brand-blue font-medium">Capítulo <?php echo $index + 1; ?></h3>
                                <p class="text-white font-semibold"><?php echo htmlspecialchars($modulo['titulo']); ?></p>
                            </div>
                        </button>
                        <div id="modulo-<?php echo $index; ?>" class="accordion-content bg-[#0B1221]">
                            <ul class="py-2">
                                <?php foreach ($modulo['clases'] as $clase): 
                                    $claseUrl = $hasActiveSubscription 
                                        ? url("curso/{$courseSlug}/{$modulo['slug']}/{$clase['slug']}")
                                        : url('suscripciones');
                                ?>
                                <li>
                                    <a href="<?php echo $claseUrl; ?>" class="flex items-center gap-4 px-6 py-3 hover:bg-white/5 transition-colors group">
                                        <div class="w-6 h-6 rounded-full border border-gray-600 flex items-center justify-center text-xs text-gray-400 group-hover:border-brand-blue group-hover:text-brand-blue">
                                            <?php if ($hasActiveSubscription): ?>
                                                <i class="fas fa-play text-[10px]"></i>
                                            <?php else: ?>
                                                <i class="fas fa-lock text-[10px]"></i>
                                            <?php endif; ?>
                                        </div>
                                        <p class="flex-grow text-sm text-gray-300 group-hover:text-white transition-colors">
                                            <?php echo htmlspecialchars($clase['titulo']); ?>
                                        </p>
                                        <span class="text-xs text-brand-gray"><?php echo htmlspecialchars($clase['duracion']); ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Sidebar (Desktop) -->
            <div class="hidden lg:block col-span-1 row-start-2 row-end-7">
                <div class="sticky top-24 space-y-6">
                    <!-- Course Info Card -->
                    <div class="bg-box border border-line rounded-3xl p-6 flex flex-col gap-4">
                        <h3 class="text-xl font-bold">El curso incluye:</h3>
                        <ul class="flex flex-col gap-3 text-brand-gray text-sm">
                            <li class="flex justify-between items-center">
                                <span class="flex items-center gap-2"><i class="far fa-clock"></i> Duración:</span>
                                <span class="text-white"><?php echo htmlspecialchars($curso['duracion_total']); ?></span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="flex items-center gap-2"><i class="fas fa-globe"></i> Lenguaje:</span>
                                <span class="text-white">Español</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="flex items-center gap-2"><i class="fas fa-layer-group"></i> Nivel:</span>
                                <span class="text-white"><?php echo htmlspecialchars($curso['nivel']); ?></span>
                            </li>
                        </ul>

                        <div class="mt-4 flex rounded-lg border border-line bg-[#0F172A] overflow-hidden">
                            <div class="flex-1 p-3 text-center border-r border-line">
                                <span class="block text-xs text-brand-gray uppercase tracking-wider">Clases</span>
                                <span class="block text-lg font-bold text-white mt-1"><?php echo $curso['total_clases']; ?></span>
                            </div>
                            <div class="flex-1 p-3 text-center">
                                <span class="block text-xs text-brand-gray uppercase tracking-wider">Recursos</span>
                                <span class="block text-lg font-bold text-white mt-1">Incluidos</span>
                            </div>
                        </div>

                        <a href="<?php echo $ctaLink; ?>" class="mt-2 w-full py-3 rounded-xl font-bold bg-interactive-primary hover:bg-[#0284C7] text-white text-center transition-all hover:scale-105 flex items-center justify-center gap-2">
                            <?php if ($hasActiveSubscription): ?>
                                <i class="fas fa-play"></i> Empezar curso
                            <?php else: ?>
                                <i class="fas fa-star"></i> Suscribirse
                            <?php endif; ?>
                        </a>
                        <p class="text-xs text-brand-gray text-center">Acceso al curso mientras dura la suscripción</p>
                    </div>

                    <!-- Instructor Card -->
                    <div class="bg-box border border-line rounded-3xl p-6">
                        <h3 class="text-xl font-bold mb-4">Docente</h3>
                        <div class="flex items-center gap-4 mb-4">
                            <img src="<?php echo htmlspecialchars(BASE_URL . $curso['instructor_avatar']); ?>" class="w-14 h-14 rounded-xl object-cover" alt="Instructor">
                            <div>
                                <h4 class="text-brand-blue font-semibold"><?php echo htmlspecialchars($curso['instructor_nombre']); ?></h4>
                                <p class="text-xs text-brand-gray">Instructor LDX</p>
                            </div>
                        </div>
                        <p class="text-sm text-brand-gray mb-4">
                            <?php echo htmlspecialchars($curso['instructor_bio']); ?>
                        </p>
                        <div class="flex gap-2">
                            <a href="#" class="p-2 bg-[#0F172A] rounded-lg text-brand-gray hover:text-white hover:scale-110 transition-all"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="p-2 bg-[#0F172A] rounded-lg text-brand-gray hover:text-white hover:scale-110 transition-all"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="p-2 bg-[#0F172A] rounded-lg text-brand-gray hover:text-white hover:scale-110 transition-all"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php include __DIR__ . '/app/includes/footer.php'; ?>

    <script>
        function toggleAccordion(id) {
            const content = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            
            if (content.classList.contains('open')) {
                content.classList.remove('open');
                icon.classList.remove('rotate-90');
            } else {
                content.classList.add('open');
                icon.classList.add('rotate-90');
            }
        }
        
        // Open first module by default
        document.addEventListener('DOMContentLoaded', () => {
            toggleAccordion('modulo-0');
        });
    </script>
</body>
</html>
