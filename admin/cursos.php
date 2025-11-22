<?php
/**
 * Gestión de Cursos (Admin)
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/models/Database.php';

// Verificar autenticación y rol de admin
if (!AuthController::isAuthenticated()) {
    header('Location: ' . BASE_URL . '?error=not_authenticated');
    exit;
}

$user = AuthController::getCurrentUser();
if ($user['email'] !== 'luisc023030@gmail.com') {
    header('Location: ' . BASE_URL . '?error=unauthorized');
    exit;
}

// Lógica para eliminar curso (si se envía ID)
if (isset($_GET['delete_id'])) {
    try {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM cursos WHERE id = ?");
        $stmt->bind_param("i", $_GET['delete_id']);
        $stmt->execute();
        header('Location: ' . url('admin/cursos') . '?msg=deleted');
        exit;
    } catch (Exception $e) {
        $error = "Error al eliminar: " . $e->getMessage();
    }
}

// Obtener cursos
$cursos = [];
try {
    $db = Database::getInstance()->getConnection();
    $result = $db->query("SELECT * FROM cursos ORDER BY orden ASC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $cursos[] = $row;
        }
    }
} catch (Exception $e) {
    error_log("Error al cargar cursos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos - Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/logo.ico'); ?>">
</head>
<body class="bg-gray-900 min-h-screen flex flex-col">
    
    <!-- Navbar simplificado para admin -->
    <nav class="bg-gray-800 border-b border-gray-700 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>" class="flex items-center gap-2">
                <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="LDX" class="h-8">
                <span class="text-white font-bold">Admin Panel</span>
            </a>
            <div class="flex gap-4">
                <a href="<?php echo url('admin/suscripciones'); ?>" class="text-gray-300 hover:text-white">Suscripciones</a>
                <a href="<?php echo url('perfil'); ?>" class="text-gray-300 hover:text-white">Volver al Perfil</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 flex-grow">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white">Gestión de Cursos</h1>
            <a href="#" onclick="alert('Funcionalidad de agregar en desarrollo')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i> Nuevo Curso
            </a>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="bg-green-500/20 text-green-400 p-4 rounded-lg mb-6 border border-green-500/30">
                Curso eliminado correctamente.
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($cursos)): ?>
                <div class="col-span-full p-8 text-center text-gray-500 bg-gray-800 rounded-xl border border-gray-700">
                    No hay cursos registrados o no se pudo conectar a la base de datos.
                </div>
            <?php else: ?>
                <?php foreach ($cursos as $curso): ?>
                    <article class="group relative overflow-hidden rounded-xl border border-white/10 bg-[#0D1828] transition hover:contrast-110 before:left-1/2 before:bottom-0 before:-translate-x-1/2 before:w-full before:h-full before:bg-black before:absolute before:translate-y-full hover:before:translate-y-1/2 before:-z-10 before:transition before:duration-200 before:mask-t-from-70% h-full shadow-lg min-h-[250px]">
                        
                        <!-- Admin Controls -->
                        <div class="absolute top-2 left-2 z-30 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="<?php echo url('admin/cursos/editar/' . $curso['id']); ?>" class="bg-black/70 hover:bg-blue-600 text-white p-1.5 rounded-lg backdrop-blur-sm transition-colors text-xs flex items-center justify-center w-8 h-8" title="Editar">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                            </a>
                            <a href="?delete_id=<?php echo $curso['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este curso?')" class="bg-black/70 hover:bg-red-600 text-white p-1.5 rounded-lg backdrop-blur-sm transition-colors text-xs flex items-center justify-center w-8 h-8" title="Eliminar">
                                <i class="fas fa-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                        
                        <a href="<?php echo url('curso/' . ($curso['slug'] ?? $curso['id'])); ?>" class="flex aspect-video flex-col h-full p-2 relative z-10">
                            
                            <!-- Badges (Top Right) -->
                            <div class="absolute top-2 right-2 opacity-100 transition inline-flex items-center gap-2 flex-wrap group-hover:opacity-0 group-hover:-translate-y-1">
                                <?php if ($curso['es_gratuito']): ?>
                                    <span class="relative isolate inline-flex items-center gap-1 overflow-hidden text-center font-medium whitespace-nowrap transition-all duration-300 rounded-lg shadow-[0_2px_8px_rgba(59,130,246,0.35),0_0_0_1px_theme(colors.white/10%)] bg-gradient-to-br from-blue-500 via-blue-400 to-blue-500 border border-blue-300/50 text-white backdrop-blur-sm px-2.5 py-1 text-xs">
                                        <i class="fas fa-gift text-[10px]" aria-hidden="true"></i> <span class="relative z-10 font-bold">Gratis</span>
                                    </span>
                                <?php else: ?>
                                    <span class="relative isolate inline-flex items-center gap-1 overflow-hidden text-center font-medium whitespace-nowrap transition-all duration-300 rounded-lg shadow-[0_2px_8px_rgba(59,130,246,0.35),0_0_0_1px_theme(colors.white/10%)] bg-gradient-to-br from-purple-500 via-purple-400 to-purple-500 border border-purple-300/50 text-white backdrop-blur-sm px-2.5 py-1 text-xs">
                                        <i class="fas fa-crown text-[10px]" aria-hidden="true"></i> <span class="relative z-10 font-bold">Premium</span>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Background Image -->
                            <img src="<?php echo htmlspecialchars($curso['imagen_url'] ?? 'assets/images/logo.png'); ?>" class="absolute inset-0 -z-20 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="<?php echo htmlspecialchars($curso['titulo']); ?>" onerror="this.src='<?php echo asset('assets/images/logo.png'); ?>'">
                            
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
                                            <p class="flex items-center gap-1 text-xs">
                                                <span class="p-1 aspect-square border border-white/10 bg-white/5 rounded-full flex items-center justify-center w-5 h-5">
                                                    <i class="far fa-clock text-[10px]" aria-hidden="true"></i>
                                                </span>
                                                <span><?php echo htmlspecialchars($curso['duracion'] ?? '0h 0m'); ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="group/btn relative isolate inline-flex items-center justify-center overflow-hidden text-center font-semibold transition-all duration-300 rounded-lg border hover:bg-white/10 shadow-sm h-8 px-3 text-xs w-auto text-white border-white/30 group-hover:scale-105">
                                        <span class="flex items-center gap-1.5">
                                            <i class="fas fa-play text-[10px]" aria-hidden="true"></i> Ir al curso
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
