<?php
/**
 * Gestión del Temario (Módulos y Clases)
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/models/Database.php';
require_once __DIR__ . '/../app/models/Curso.php';

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

$cursoId = $_GET['curso_id'] ?? null;
if (!$cursoId) {
    header('Location: ' . url('admin/cursos') . '?error=missing_id');
    exit;
}

$db = Database::getInstance()->getConnection();
$cursoModel = new Curso();
$curso = $cursoModel->getCursoBySlug($cursoId); // Funciona con ID también

if (!$curso) {
    header('Location: ' . url('admin/cursos') . '?error=not_found');
    exit;
}

$contenido = $cursoModel->getContenidoCurso($curso['id']);

// Procesar formulario de nuevo módulo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_module') {
        $titulo = $_POST['titulo'];
        $orden = $_POST['orden'] ?? 0;
        
        $stmt = $db->prepare("INSERT INTO modulos (curso_id, titulo, orden) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $curso['id'], $titulo, $orden);
        $stmt->execute();
        header("Location: " . url("admin/temario.php?curso_id={$curso['id']}"));
        exit;
    }
    
    if ($_POST['action'] === 'delete_module') {
        $moduloId = $_POST['modulo_id'];
        $stmt = $db->prepare("DELETE FROM modulos WHERE id = ? AND curso_id = ?");
        $stmt->bind_param("ii", $moduloId, $curso['id']);
        $stmt->execute();
        header("Location: " . url("admin/temario.php?curso_id={$curso['id']}"));
        exit;
    }

    if ($_POST['action'] === 'add_lesson') {
        $moduloId = $_POST['modulo_id'];
        $titulo = $_POST['titulo'];
        $orden = $_POST['orden'] ?? 0;
        
        $stmt = $db->prepare("INSERT INTO clases (modulo_id, titulo, orden) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $moduloId, $titulo, $orden);
        $stmt->execute();
        header("Location: " . url("admin/temario.php?curso_id={$curso['id']}"));
        exit;
    }

    if ($_POST['action'] === 'delete_lesson') {
        $claseId = $_POST['clase_id'];
        // Verificar que la clase pertenece a un módulo de este curso (seguridad básica)
        $stmt = $db->prepare("DELETE FROM clases WHERE id = ?");
        $stmt->bind_param("i", $claseId);
        $stmt->execute();
        header("Location: " . url("admin/temario.php?curso_id={$curso['id']}"));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Temario - <?php echo htmlspecialchars($curso['titulo']); ?></title>
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
                <a href="<?php echo url('admin/cursos/editar/' . $curso['id']); ?>" class="text-gray-300 hover:text-white">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Curso
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">Temario del Curso</h1>
                    <p class="text-gray-400"><?php echo htmlspecialchars($curso['titulo']); ?></p>
                </div>
                <button onclick="document.getElementById('modal-new-module').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i> Nuevo Módulo
                </button>
            </div>

            <div class="space-y-6">
                <?php if (empty($contenido)): ?>
                    <div class="bg-gray-800 rounded-xl p-8 text-center border border-gray-700">
                        <p class="text-gray-500">No hay módulos creados para este curso.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($contenido as $modulo): ?>
                    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                        <div class="bg-gray-700/50 p-4 flex justify-between items-center border-b border-gray-700">
                            <h3 class="font-bold text-white text-lg"><?php echo htmlspecialchars($modulo['titulo']); ?></h3>
                            <div class="flex gap-2">
                                <button onclick="openNewLessonModal(<?php echo $modulo['id']; ?>)" class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition-colors">
                                    <i class="fas fa-plus mr-1"></i> Clase
                                </button>
                                <form method="POST" onsubmit="return confirm('¿Eliminar módulo y todas sus clases?');" class="inline">
                                    <input type="hidden" name="action" value="delete_module">
                                    <input type="hidden" name="modulo_id" value="<?php echo $modulo['id']; ?>">
                                    <button type="submit" class="text-sm bg-red-600/20 hover:bg-red-600/40 text-red-400 px-3 py-1 rounded transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <?php if (empty($modulo['clases'])): ?>
                                <p class="text-sm text-gray-500 italic">Sin clases registradas.</p>
                            <?php else: ?>
                                <ul class="space-y-2">
                                    <?php foreach ($modulo['clases'] as $clase): ?>
                                    <li class="flex justify-between items-center bg-gray-900/50 p-3 rounded-lg border border-gray-700 hover:border-gray-600 transition-colors group">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-full bg-gray-800 text-xs flex items-center justify-center text-gray-500">
                                                <?php echo $clase['orden']; ?>
                                            </span>
                                            <span class="text-gray-300 font-medium"><?php echo htmlspecialchars($clase['titulo']); ?></span>
                                            <?php if ($clase['es_gratuita']): ?>
                                                <span class="text-xs bg-green-500/20 text-green-400 px-2 py-0.5 rounded">Gratis</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="<?php echo url('admin/clases/editar/' . $clase['id']); ?>" class="text-blue-400 hover:text-blue-300">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <form method="POST" onsubmit="return confirm('¿Eliminar esta clase?');" class="inline">
                                                <input type="hidden" name="action" value="delete_lesson">
                                                <input type="hidden" name="clase_id" value="<?php echo $clase['id']; ?>">
                                                <button type="submit" class="text-red-400 hover:text-red-300">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Módulo -->
    <div id="modal-new-module" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-xl p-6 w-full max-w-md border border-gray-700">
            <h3 class="text-xl font-bold text-white mb-4">Nuevo Módulo</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_module">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Título del Módulo</label>
                    <input type="text" name="titulo" required class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Orden</label>
                    <input type="number" name="orden" value="0" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-new-module').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Crear Módulo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Nueva Clase -->
    <div id="modal-new-lesson" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-xl p-6 w-full max-w-md border border-gray-700">
            <h3 class="text-xl font-bold text-white mb-4">Nueva Clase</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_lesson">
                <input type="hidden" name="modulo_id" id="new-lesson-modulo-id">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Título de la Clase</label>
                    <input type="text" name="titulo" required class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Orden</label>
                    <input type="number" name="orden" value="0" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-new-lesson').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">Crear Clase</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openNewLessonModal(moduloId) {
            document.getElementById('new-lesson-modulo-id').value = moduloId;
            document.getElementById('modal-new-lesson').classList.remove('hidden');
        }
    </script>
</body>
</html>
