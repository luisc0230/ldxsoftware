<?php
/**
 * Gestión de Planes (Admin)
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

$db = Database::getInstance()->getConnection();
$message = '';
$error = '';

// Procesar eliminación
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    try {
        $stmt = $db->prepare("DELETE FROM planes WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Plan eliminado correctamente.";
        } else {
            $error = "No se puede eliminar el plan porque tiene suscripciones asociadas.";
        }
    } catch (Exception $e) {
        $error = "Error al eliminar: " . $e->getMessage();
    }
}

// Obtener planes
$planes = [];
$result = $db->query("SELECT * FROM planes ORDER BY orden ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $planes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Planes - Admin</title>
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
                <a href="<?php echo url('admin/suscripciones'); ?>" class="text-gray-300 hover:text-white">
                   <i class="fas fa-users mr-2"></i> Ver Suscripciones
                </a>
                <a href="<?php echo url('admin/cursos'); ?>" class="text-gray-300 hover:text-white">
                    <i class="fas fa-book mr-2"></i> Cursos
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white">Planes de Suscripción</h1>
            <a href="<?php echo url('admin/planes/crear'); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i> Nuevo Plan
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($planes as $plan): ?>
            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden flex flex-col">
                <div class="p-6 flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-white"><?php echo htmlspecialchars($plan['nombre']); ?></h3>
                            <span class="text-xs text-gray-500">Orden: <?php echo $plan['orden']; ?></span>
                        </div>
                        <?php if ($plan['es_recomendado']): ?>
                            <span class="bg-purple-600 text-white text-xs px-2 py-1 rounded-full">Recomendado</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <div class="text-2xl font-bold text-white">
                            <?php 
                            if ($plan['precio_mensual'] > 0) echo "S/ " . $plan['precio_mensual'] . " /mes";
                            elseif ($plan['precio_trimestral'] > 0) echo "S/ " . $plan['precio_trimestral'] . " /trimestre";
                            elseif ($plan['precio_anual'] > 0) echo "S/ " . $plan['precio_anual'] . " /año";
                            elseif ($plan['precio_lifetime'] > 0) echo "S/ " . $plan['precio_lifetime'] . " /vida";
                            ?>
                        </div>
                        <p class="text-sm text-gray-400 mt-2"><?php echo htmlspecialchars($plan['descripcion']); ?></p>
                    </div>

                    <div class="space-y-2 mb-4">
                        <?php 
                        $features = explode('|', $plan['caracteristicas']);
                        foreach(array_slice($features, 0, 3) as $feature): 
                        ?>
                            <div class="flex items-center text-sm text-gray-400">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <?php echo htmlspecialchars(trim($feature)); ?>
                            </div>
                        <?php endforeach; ?>
                        <?php if(count($features) > 3): ?>
                            <div class="text-xs text-gray-500 italic">+ <?php echo count($features) - 3; ?> más...</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="bg-gray-700/50 p-4 border-t border-gray-700 flex justify-between items-center">
                    <span class="text-sm px-2 py-1 rounded <?php echo $plan['estado'] === 'activo' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'; ?>">
                        <?php echo ucfirst($plan['estado']); ?>
                    </span>
                    <div class="flex gap-3">
                        <a href="<?php echo url('admin/planes/editar/' . $plan['id']); ?>" class="text-blue-400 hover:text-blue-300">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este plan?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $plan['id']; ?>">
                            <button type="submit" class="text-red-400 hover:text-red-300">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
