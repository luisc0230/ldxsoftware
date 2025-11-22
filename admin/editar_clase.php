<?php
/**
 * Editar Clase (Admin)
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

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: ' . url('admin/cursos') . '?error=missing_id');
    exit;
}

$db = Database::getInstance()->getConnection();
$message = '';
$error = '';

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion']; // Contenido HTML/Markdown
        $video_url = $_POST['video_url'];
        $duracion = $_POST['duracion'];
        $orden = $_POST['orden'];
        $es_gratuita = isset($_POST['es_gratuita']) ? 1 : 0;

        $stmt = $db->prepare("UPDATE clases SET 
            titulo = ?, 
            descripcion = ?, 
            video_url = ?, 
            duracion = ?, 
            orden = ?, 
            es_gratuita = ?
            WHERE id = ?");
            
        $stmt->bind_param("ssssiii", 
            $titulo, 
            $descripcion, 
            $video_url, 
            $duracion, 
            $orden, 
            $es_gratuita,
            $id
        );
        
        if ($stmt->execute()) {
            $message = "Clase actualizada correctamente.";
        } else {
            $error = "Error al actualizar: " . $db->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Obtener datos de la clase
$stmt = $db->prepare("SELECT c.*, m.curso_id FROM clases c JOIN modulos m ON c.modulo_id = m.id WHERE c.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$clase = $result->fetch_assoc();

if (!$clase) {
    header('Location: ' . url('admin/cursos') . '?error=not_found');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Clase - Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-900 min-h-screen flex flex-col text-gray-300">
    
    <!-- Navbar -->
    <nav class="bg-gray-800 border-b border-gray-700 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>" class="flex items-center gap-2">
                <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="LDX" class="h-8">
                <span class="text-white font-bold">Admin Panel</span>
            </a>
            <div class="flex gap-4">
                <a href="<?php echo url('admin/temario?curso_id=' . $clase['curso_id']); ?>" class="text-gray-300 hover:text-white">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Temario
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-white mb-8">Editar Clase</h1>

            <?php if ($message): ?>
                <div class="bg-green-500/20 text-green-400 p-4 rounded-lg mb-6 border border-green-500/30">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-500/20 text-red-400 p-4 rounded-lg mb-6 border border-red-500/30">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <h2 class="text-xl font-bold text-white mb-4">Información de la Clase</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-full">
                            <label class="block text-sm font-medium mb-2">Título</label>
                            <input type="text" name="titulo" value="<?php echo htmlspecialchars($clase['titulo']); ?>" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        
                        <div class="col-span-full">
                            <label class="block text-sm font-medium mb-2">Contenido (Descripción HTML)</label>
                            <p class="text-xs text-gray-500 mb-2">Puedes pegar aquí el HTML del contenido de la clase.</p>
                            <textarea name="descripcion" rows="15" 
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white font-mono text-sm focus:border-blue-500 focus:outline-none"><?php echo htmlspecialchars($clase['descripcion']); ?></textarea>
                        </div>

                        <div class="col-span-full">
                            <label class="block text-sm font-medium mb-2">URL del Video</label>
                            <input type="text" name="video_url" value="<?php echo htmlspecialchars($clase['video_url']); ?>" placeholder="https://..."
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                            <p class="text-xs text-gray-500 mt-1">Soporta M3U8 (HLS), YouTube, MP4 directo, etc.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Duración</label>
                            <input type="text" name="duracion" value="<?php echo htmlspecialchars($clase['duracion']); ?>" placeholder="Ej: 10m 30s"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Orden</label>
                            <input type="number" name="orden" value="<?php echo htmlspecialchars($clase['orden']); ?>"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        
                        <div class="col-span-full flex items-center gap-3">
                            <input type="checkbox" id="es_gratuita" name="es_gratuita" value="1" <?php echo $clase['es_gratuita'] ? 'checked' : ''; ?> class="w-5 h-5 rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500">
                            <label for="es_gratuita" class="text-sm font-medium text-gray-300">Esta clase es gratuita (vista previa)</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="<?php echo url('admin/temario?curso_id=' . $clase['curso_id']); ?>" class="px-6 py-3 rounded-lg bg-gray-700 hover:bg-gray-600 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold transition-colors">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
