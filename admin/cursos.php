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

        <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
            <table class="w-full text-left text-gray-300">
                <thead class="bg-gray-700/50 text-gray-400 uppercase text-sm">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">Título</th>
                        <th class="p-4">Precio</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php if (empty($cursos)): ?>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">
                                No hay cursos registrados o no se pudo conectar a la base de datos.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cursos as $curso): ?>
                        <tr class="hover:bg-gray-700/30 transition-colors">
                            <td class="p-4 font-mono text-sm text-gray-500">#<?php echo $curso['id']; ?></td>
                            <td class="p-4 font-semibold text-white">
                                <div class="flex items-center gap-3">
                                    <?php if ($curso['imagen_url']): ?>
                                        <?php 
                                        // Si ya empieza con http, usar directamente. Si empieza con assets/, concatenar con BASE_URL. Si no, usar asset()
                                        if (str_starts_with($curso['imagen_url'], 'http')) {
                                            $imageSrc = $curso['imagen_url'];
                                        } elseif (str_starts_with($curso['imagen_url'], 'assets/')) {
                                            $imageSrc = BASE_URL . $curso['imagen_url'];
                                        } else {
                                            $imageSrc = asset($curso['imagen_url']);
                                        }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($imageSrc); ?>" 
                                             class="w-10 h-10 rounded object-cover bg-gray-700" 
                                             alt=""
                                             onerror="this.src='<?php echo BASE_URL; ?>assets/images/logo.png'">
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($curso['titulo']); ?>
                                </div>
                            </td>
                            <td class="p-4">
                                <?php echo $curso['es_gratuito'] ? '<span class="text-green-400 font-bold">Gratis</span>' : 'S/ ' . number_format($curso['precio'], 2); ?>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs font-bold <?php echo $curso['estado'] === 'activo' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'; ?>">
                                    <?php echo strtoupper($curso['estado']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <a href="<?php echo url('admin/cursos/editar/' . $curso['id']); ?>" class="text-blue-400 hover:text-blue-300 mr-3" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete_id=<?php echo $curso['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este curso?')" class="text-red-400 hover:text-red-300" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
