<?php
/**
 * Editar Curso (Admin)
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
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $estado = $_POST['estado'];
        $imagen_url = $_POST['imagen_url'];
        $slug = $_POST['slug'] ?: null;
        $instructor_nombre = $_POST['instructor_nombre'];
        $instructor_bio = $_POST['instructor_bio'];
        $nivel = $_POST['nivel'];
        $duracion_total = $_POST['duracion_total'];

        $stmt = $db->prepare("UPDATE cursos SET 
            titulo = ?, 
            descripcion = ?, 
            precio = ?, 
            estado = ?, 
            imagen_url = ?, 
            slug = ?, 
            instructor_nombre = ?, 
            instructor_bio = ?, 
            nivel = ?, 
            duracion_total = ?,
            updated_at = NOW()
            WHERE id = ?");
            
        $stmt->bind_param("ssdsssssssi", 
            $titulo, 
            $descripcion, 
            $precio, 
            $estado, 
            $imagen_url, 
            $slug, 
            $instructor_nombre, 
            $instructor_bio, 
            $nivel, 
            $duracion_total,
            $id
        );
        
        if ($stmt->execute()) {
            $message = "Curso actualizado correctamente.";
        } else {
            $error = "Error al actualizar: " . $db->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Obtener datos del curso
$stmt = $db->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$curso = $result->fetch_assoc();

if (!$curso) {
    header('Location: ' . url('admin/cursos') . '?error=not_found');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso - Admin</title>
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
                <a href="<?php echo url('admin/cursos'); ?>" class="text-gray-300 hover:text-white">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Cursos
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-white mb-8">Editar Curso: <?php echo htmlspecialchars($curso['titulo']); ?></h1>

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
                    <h2 class="text-xl font-bold text-white mb-4">Información Básica</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Título</label>
                            <input type="text" name="titulo" value="<?php echo htmlspecialchars($curso['titulo']); ?>" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Slug (URL)</label>
                            <input type="text" name="slug" value="<?php echo htmlspecialchars($curso['slug'] ?? ''); ?>"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                            <p class="text-xs text-gray-500 mt-1">Dejar vacío para usar ID</p>
                        </div>
                        <div class="col-span-full">
                            <label class="block text-sm font-medium mb-2">Descripción</label>
                            <textarea name="descripcion" rows="4" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none"><?php echo htmlspecialchars($curso['descripcion']); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Precio (S/)</label>
                            <input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($curso['precio']); ?>" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Estado</label>
                            <select name="estado" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                                <option value="activo" <?php echo $curso['estado'] === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                <option value="inactivo" <?php echo $curso['estado'] === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                                <option value="borrador" <?php echo $curso['estado'] === 'borrador' ? 'selected' : ''; ?>>Borrador</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <h2 class="text-xl font-bold text-white mb-4">Media & Detalles</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">URL Imagen</label>
                            <input type="text" name="imagen_url" value="<?php echo htmlspecialchars($curso['imagen_url']); ?>"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Nivel</label>
                            <select name="nivel" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                                <option value="Principiante" <?php echo $curso['nivel'] === 'Principiante' ? 'selected' : ''; ?>>Principiante</option>
                                <option value="Intermedio" <?php echo $curso['nivel'] === 'Intermedio' ? 'selected' : ''; ?>>Intermedio</option>
                                <option value="Avanzado" <?php echo $curso['nivel'] === 'Avanzado' ? 'selected' : ''; ?>>Avanzado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Duración Total</label>
                            <input type="text" name="duracion_total" value="<?php echo htmlspecialchars($curso['duracion_total']); ?>" placeholder="Ej: 2h 30m"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <h2 class="text-xl font-bold text-white mb-4">Instructor</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nombre</label>
                            <input type="text" name="instructor_nombre" value="<?php echo htmlspecialchars($curso['instructor_nombre']); ?>"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        <div class="col-span-full">
                            <label class="block text-sm font-medium mb-2">Biografía</label>
                            <textarea name="instructor_bio" rows="3"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none"><?php echo htmlspecialchars($curso['instructor_bio']); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="<?php echo url('admin/cursos'); ?>" class="px-6 py-3 rounded-lg bg-gray-700 hover:bg-gray-600 transition-colors">
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
