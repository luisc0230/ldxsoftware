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
        
        // Manejo de imagen
        $imagen_url = $_POST['imagen_url_actual']; // Default to existing
        
        if (isset($_FILES['imagen_nueva']) && $_FILES['imagen_nueva']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../assets/images/cursos/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExtension = pathinfo($_FILES['imagen_nueva']['name'], PATHINFO_EXTENSION);
            $fileName = 'curso_' . $id . '_' . time() . '.' . $fileExtension;
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['imagen_nueva']['tmp_name'], $targetPath)) {
                // Delete old image if it exists and is local
                if (!empty($imagen_url) && strpos($imagen_url, 'assets/images/cursos/') !== false) {
                    $oldPath = __DIR__ . '/../../' . $imagen_url;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $imagen_url = 'assets/images/cursos/' . $fileName;
            }
        } else if (!empty($_POST['imagen_url_manual'])) {
             // Allow manual URL override if provided
             $imagen_url = $_POST['imagen_url_manual'];
        }

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
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-white">Editar Curso: <?php echo htmlspecialchars($curso['titulo']); ?></h1>
                <a href="<?php echo url('admin/temario?curso_id=' . $curso['id']); ?>" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-list-ul mr-2"></i> Gestionar Temario
                </a>
            </div>

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

            <form method="POST" class="space-y-6" enctype="multipart/form-data">
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
                        <div class="col-span-full md:col-span-1">
                            <label class="block text-sm font-medium mb-2">Imagen del Curso</label>
                            
                            <!-- Imagen Actual Preview -->
                            <div class="mb-4 relative group w-full aspect-video bg-gray-900 rounded-lg overflow-hidden border border-gray-700">
                                <img src="<?php echo htmlspecialchars(BASE_URL . $curso['imagen_url']); ?>" alt="Portada actual" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-sm font-medium">Imagen Actual</span>
                                </div>
                            </div>

                            <input type="hidden" name="imagen_url_actual" value="<?php echo htmlspecialchars($curso['imagen_url']); ?>">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1">Subir Nueva Imagen (Reemplaza la anterior)</label>
                                    <input type="file" name="imagen_nueva" accept="image/*"
                                        class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                </div>
                                
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1">O usar URL externa (Opcional)</label>
                                    <input type="text" name="imagen_url_manual" placeholder="https://..."
                                        class="w-full bg-gray-700 border border-gray-600 rounded-lg p-2 text-sm text-white focus:border-blue-500 focus:outline-none">
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-6">
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
