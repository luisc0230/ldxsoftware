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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($cursos as $curso): ?>
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl overflow-hidden border border-gray-700 hover:border-blue-500/50 transition-all duration-300 group flex flex-col h-full">
                        <!-- Imagen -->
                        <div class="relative h-48 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent z-10"></div>
                            <img src="<?php echo htmlspecialchars($curso['imagen_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($curso['titulo']); ?>"
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                 onerror="this.src='<?php echo asset('images/logo.png'); ?>'"> <!-- Fallback image -->
                            
                            <?php if ($curso['es_gratuito']): ?>
                                <span class="absolute top-4 right-4 z-20 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                    GRATIS
                                </span>
                            <?php else: ?>
                                <span class="absolute top-4 right-4 z-20 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                    PREMIUM
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Contenido -->
                        <div class="p-6 flex-grow flex flex-col">
                            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors">
                                <?php echo htmlspecialchars($curso['titulo']); ?>
                            </h3>
                            <p class="text-gray-400 text-sm mb-4 flex-grow">
                                <?php echo htmlspecialchars($curso['descripcion']); ?>
                            </p>
                            
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-700">
                                <?php if ($curso['es_gratuito']): ?>
                                    <span class="text-green-400 font-bold text-lg">Gratis</span>
                                <?php else: ?>
                                    <span class="text-white font-bold text-lg">S/ <?php echo number_format($curso['precio'], 2); ?></span>
                                <?php endif; ?>
                                
                                <a href="<?php echo url('curso/' . $curso['id']); ?>" class="text-blue-400 hover:text-white text-sm font-semibold transition-colors">
                                    Ver Detalles <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Controles de Admin -->
                        <?php if ($isAdmin): ?>
                        <div class="bg-gray-900/90 p-3 flex justify-between items-center border-t border-gray-700">
                            <span class="text-xs text-gray-500">ID: <?php echo $curso['id']; ?></span>
                            <div class="flex gap-2">
                                <button class="text-blue-400 hover:text-blue-300 p-2" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-400 hover:text-red-300 p-2" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
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
