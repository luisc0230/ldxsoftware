<?php
/**
 * Catálogo de Cursos
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/models/Database.php';

// Verificar autenticación (opcional, pero recomendado para ver cursos)
$isLoggedIn = AuthController::isAuthenticated();
$user = $isLoggedIn ? AuthController::getCurrentUser() : null;
$isAdmin = ($user && $user['email'] === 'luisc023030@gmail.com');

// Obtener cursos
$cursos = [];
try {
    $db = Database::getInstance()->getConnection();
    
    // Verificar si la tabla existe
    $checkTable = $db->query("SHOW TABLES LIKE 'cursos'");
    if ($checkTable && $checkTable->num_rows > 0) {
        $result = $db->query("SELECT * FROM cursos WHERE estado = 'activo' ORDER BY orden ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $cursos[] = $row;
            }
        }
    }
} catch (Exception $e) {
    error_log("Error al cargar cursos: " . $e->getMessage());
}

// Si no hay cursos (o tabla no existe), usar datos de ejemplo para visualización
if (empty($cursos)) {
    $cursos = [
        [
            'id' => 1,
            'titulo' => 'Introducción a la Programación',
            'descripcion' => 'Aprende los fundamentos de la programación desde cero con este curso introductorio.',
            'imagen_url' => asset('images/curso-prog.jpg'),
            'es_gratuito' => true,
            'precio' => 0
        ],
        [
            'id' => 2,
            'titulo' => 'Desarrollo Web Full Stack',
            'descripcion' => 'Domina HTML, CSS, JS, PHP y MySQL para crear aplicaciones web completas y escalables.',
            'imagen_url' => asset('images/curso-web.jpg'),
            'es_gratuito' => false,
            'precio' => 199.00
        ],
        [
            'id' => 3,
            'titulo' => 'Base de Datos Avanzada',
            'descripcion' => 'Optimización, diseño y gestión de bases de datos relacionales para alto rendimiento.',
            'imagen_url' => asset('images/curso-db.jpg'),
            'es_gratuito' => false,
            'precio' => 99.00
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos Disponibles - LDX SOFTWARE</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/logo.ico'); ?>">
</head>
<body class="bg-gradient-to-b from-black via-gray-900 to-black min-h-screen flex flex-col">
    
    <!-- Include Navbar -->
    <?php include __DIR__ . '/app/includes/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-24 flex-grow">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Cursos Disponibles</h1>
                    <p class="text-gray-400">Explora nuestro catálogo de formación profesional</p>
                </div>
                
                <?php if ($isAdmin): ?>
                <a href="<?php echo url('admin/cursos/crear'); ?>" 
                   class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-green-500/20 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Agregar Nuevo Curso
                </a>
                <?php endif; ?>
            </div>

            <!-- Grid de Cursos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($cursos as $curso): 
                    // Determinar URL del curso
                    $cursoSlug = !empty($curso['slug']) ? $curso['slug'] : $curso['id'];
                    $cursoUrl = url('curso/' . $cursoSlug);
                    $isNew = false; // Logic for 'New' badge could be added here based on created_at
                ?>
                    <article class="group relative overflow-hidden rounded-xl border border-white/10 bg-[#0D1828] transition hover:contrast-110 before:left-1/2 before:bottom-0 before:-translate-x-1/2 before:w-full before:h-full before:bg-black before:absolute before:translate-y-full hover:before:translate-y-1/2 before:-z-10 before:transition before:duration-200 before:mask-t-from-70% h-full shadow-lg">
                        
                        <!-- Admin Controls -->
                        <?php if ($isAdmin): ?>
                        <div class="absolute top-2 left-2 z-30 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="<?php echo url('admin/cursos/editar/' . $curso['id']); ?>" class="bg-black/70 hover:bg-blue-600 text-white p-1.5 rounded-lg backdrop-blur-sm transition-colors text-xs" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo url('admin/cursos/eliminar'); ?>" method="POST" onsubmit="return confirm('¿Estás seguro?');" class="inline">
                                <input type="hidden" name="id" value="<?php echo $curso['id']; ?>">
                                <button type="submit" class="bg-black/70 hover:bg-red-600 text-white p-1.5 rounded-lg backdrop-blur-sm transition-colors text-xs" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>

                        <a href="<?php echo $cursoUrl; ?>" class="flex aspect-video flex-col h-full p-2 relative z-10">
                            
                            <!-- Badges (Top Right) -->
                            <div class="absolute top-2 right-2 opacity-100 transition inline-flex items-center gap-2 flex-wrap group-hover:opacity-0 group-hover:-translate-y-1">
                                <?php if ($curso['es_gratuito']): ?>
                                <span class="relative isolate inline-flex items-center gap-1 overflow-hidden text-center font-medium whitespace-nowrap transition-all duration-300 rounded-lg shadow-[0_2px_8px_rgba(59,130,246,0.35),0_0_0_1px_theme(colors.white/10%)] bg-gradient-to-br from-blue-500 via-blue-400 to-blue-500 border border-blue-300/50 text-white backdrop-blur-sm px-2.5 py-1 text-xs">
                                    <i class="fas fa-gift text-[10px]"></i> <span class="relative z-10 font-bold">Gratis</span>
                                </span>
                                <?php else: ?>
                                <span class="relative isolate inline-flex items-center gap-1 overflow-hidden text-center font-medium whitespace-nowrap transition-all duration-300 rounded-lg shadow-[0_2px_8px_rgba(59,130,246,0.35),0_0_0_1px_theme(colors.white/10%)] bg-gradient-to-br from-purple-500 via-purple-400 to-purple-500 border border-purple-300/50 text-white backdrop-blur-sm px-2.5 py-1 text-xs">
                                    <i class="fas fa-crown text-[10px]"></i> <span class="relative z-10 font-bold">Premium</span>
                                </span>
                                <?php endif; ?>

                                <?php if ($isNew): ?>
                                <span class="relative isolate inline-flex items-center gap-1 overflow-hidden text-center font-medium whitespace-nowrap transition-all duration-300 rounded-lg shadow-[0_2px_8px_rgba(255,215,0,0.35),0_0_0_1px_theme(colors.white/10%)] bg-gradient-to-br from-yellow-400 via-yellow-300 to-yellow-500 border border-yellow-300/50 text-black backdrop-blur-sm px-2.5 py-1 text-xs">
                                    <i class="fas fa-star text-[10px] animate-pulse"></i> <span class="relative z-10 font-bold">Nuevo</span>
                                </span>
                                <?php endif; ?>
                            </div>

                            <!-- Background Image -->
                            <img src="<?php echo htmlspecialchars($curso['imagen_url']); ?>" 
                                 class="absolute inset-0 -z-20 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                                 alt="<?php echo htmlspecialchars($curso['titulo']); ?>"
                                 onerror="this.src='<?php echo asset('images/logo.png'); ?>'">
                            
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent -z-10"></div>

                            <div class="opacity-100 flex transition flex-col gap-2 flex-1"></div>

                            <!-- Bottom Content (Hover Reveal) -->
                            <div class="flex flex-wrap items-end justify-between mt-8 transition translate-y-1 opacity-0 duration-300 group-hover:opacity-100 group-hover:translate-y-0">
                                <h2 class="mt-auto text-shadow-lg text-white leading-snug font-medium text-balance max-w-[28ch] text-sm mb-2 ml-1">
                                    <?php echo htmlspecialchars($curso['titulo']); ?>
                                </h2>
                                
                                <div class="flex flex-wrap items-center justify-between w-full">
                                    <div>
                                        <div class="flex items-center gap-4 text-sm text-gray-300 flex-wrap">
                                            <?php if (!empty($curso['duracion_total'])): ?>
                                            <p class="flex items-center gap-1 text-xs">
                                                <span class="p-1 aspect-square border border-white/10 bg-white/5 rounded-full flex items-center justify-center w-5 h-5">
                                                    <i class="far fa-clock text-[10px]"></i>
                                                </span>
                                                <span><?php echo htmlspecialchars($curso['duracion_total']); ?></span>
                                            </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="group/btn relative isolate inline-flex items-center justify-center overflow-hidden text-center font-semibold transition-all duration-300 rounded-lg border hover:bg-white/10 shadow-sm h-8 px-3 text-xs w-auto text-white border-white/30 group-hover:scale-105">
                                        <span class="flex items-center gap-1.5">
                                            <i class="fas fa-play text-[10px]"></i> Ir al curso
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <?php if (empty($cursos)): ?>
                <div class="text-center py-20">
                    <div class="inline-block p-6 bg-gray-800 rounded-full mb-4">
                        <i class="fas fa-book-open text-4xl text-gray-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">No hay cursos disponibles</h2>
                    <p class="text-gray-400">Vuelve pronto para ver nuevo contenido.</p>
                </div>
            <?php endif; ?>
            
        </div>
    </div>

    <!-- Include Footer -->
    <?php include __DIR__ . '/app/includes/footer.php'; ?>
</body>
</html>
