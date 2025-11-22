<?php
/**
 * Editar/Crear Plan (Admin)
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/models/Database.php';

// Verificar autenticación
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
$id = $_GET['id'] ?? null;
$isEditing = !empty($id);
$message = '';
$error = '';

// Datos por defecto
$plan = [
    'nombre' => '',
    'descripcion' => '',
    'precio_mensual' => null,
    'precio_trimestral' => null,
    'precio_anual' => null,
    'precio_lifetime' => null,
    'descuento_porcentaje' => 0,
    'es_recomendado' => 0,
    'orden' => 0,
    'caracteristicas' => '',
    'estado' => 'activo'
];

// Cargar datos si es edición
if ($isEditing) {
    $stmt = $db->prepare("SELECT * FROM planes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $plan = $row;
    } else {
        header('Location: ' . url('admin/planes') . '?error=not_found');
        exit;
    }
}

// Procesar Formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_mensual = !empty($_POST['precio_mensual']) ? $_POST['precio_mensual'] : null;
    $precio_trimestral = !empty($_POST['precio_trimestral']) ? $_POST['precio_trimestral'] : null;
    $precio_anual = !empty($_POST['precio_anual']) ? $_POST['precio_anual'] : null;
    $precio_lifetime = !empty($_POST['precio_lifetime']) ? $_POST['precio_lifetime'] : null;
    $descuento = $_POST['descuento_porcentaje'] ?? 0;
    $es_recomendado = isset($_POST['es_recomendado']) ? 1 : 0;
    $orden = $_POST['orden'] ?? 0;
    $caracteristicas = $_POST['caracteristicas']; // Pipe separated
    $estado = $_POST['estado'];

    if ($isEditing) {
        $stmt = $db->prepare("UPDATE planes SET nombre=?, descripcion=?, precio_mensual=?, precio_trimestral=?, precio_anual=?, precio_lifetime=?, descuento_porcentaje=?, es_recomendado=?, orden=?, caracteristicas=?, estado=? WHERE id=?");
        $stmt->bind_param("ssddddiiissi", $nombre, $descripcion, $precio_mensual, $precio_trimestral, $precio_anual, $precio_lifetime, $descuento, $es_recomendado, $orden, $caracteristicas, $estado, $id);
    } else {
        $stmt = $db->prepare("INSERT INTO planes (nombre, descripcion, precio_mensual, precio_trimestral, precio_anual, precio_lifetime, descuento_porcentaje, es_recomendado, orden, caracteristicas, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssddddiiiss", $nombre, $descripcion, $precio_mensual, $precio_trimestral, $precio_anual, $precio_lifetime, $descuento, $es_recomendado, $orden, $caracteristicas, $estado);
    }

    if ($stmt->execute()) {
        header('Location: ' . url('admin/planes') . '?msg=saved');
        exit;
    } else {
        $error = "Error al guardar: " . $db->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEditing ? 'Editar' : 'Crear'; ?> Plan - Admin</title>
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
            <a href="<?php echo url('admin/planes'); ?>" class="text-gray-300 hover:text-white">
                <i class="fas fa-arrow-left mr-2"></i> Volver a Planes
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold text-white mb-8"><?php echo $isEditing ? 'Editar Plan' : 'Nuevo Plan'; ?></h1>

            <?php if ($error): ?>
                <div class="bg-red-500/20 text-red-400 p-4 rounded-lg mb-6 border border-red-500/30">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <h2 class="text-xl font-bold text-white mb-4">Información General</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-full">
                            <label class="block text-sm font-medium mb-2">Nombre del Plan</label>
                            <input type="text" name="nombre" value="<?php echo htmlspecialchars($plan['nombre']); ?>" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        
                        <div class="col-span-full">
                            <label class="block text-sm font-medium mb-2">Descripción Corta</label>
                            <input type="text" name="descripcion" value="<?php echo htmlspecialchars($plan['descripcion']); ?>" required
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Orden de Visualización</label>
                            <input type="number" name="orden" value="<?php echo htmlspecialchars($plan['orden']); ?>"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Estado</label>
                            <select name="estado" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                                <option value="activo" <?php echo $plan['estado'] === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                <option value="inactivo" <?php echo $plan['estado'] === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>

                        <div class="col-span-full flex items-center gap-3">
                            <input type="checkbox" id="es_recomendado" name="es_recomendado" value="1" <?php echo $plan['es_recomendado'] ? 'checked' : ''; ?> class="w-5 h-5 rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500">
                            <label for="es_recomendado" class="text-sm font-medium text-gray-300">Marcar como "Recomendado"</label>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <h2 class="text-xl font-bold text-white mb-4">Precios (S/)</h2>
                    <p class="text-xs text-gray-500 mb-4">Llena solo el campo correspondiente al tipo de plan. Deja los demás vacíos.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Precio Mensual</label>
                            <input type="number" step="0.01" name="precio_mensual" value="<?php echo $plan['precio_mensual']; ?>"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Precio Trimestral</label>
                            <input type="number" step="0.01" name="precio_trimestral" value="<?php echo $plan['precio_trimestral']; ?>"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Precio Anual</label>
                            <input type="number" step="0.01" name="precio_anual" value="<?php echo $plan['precio_anual']; ?>"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Precio Lifetime</label>
                            <input type="number" step="0.01" name="precio_lifetime" value="<?php echo $plan['precio_lifetime']; ?>"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Descuento (%)</label>
                            <input type="number" name="descuento_porcentaje" value="<?php echo $plan['descuento_porcentaje']; ?>"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none">
                            <p class="text-xs text-gray-500 mt-1">Solo visual (Badge de descuento)</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <h2 class="text-xl font-bold text-white mb-4">Características</h2>
                    
                    <div class="col-span-full">
                        <label class="block text-sm font-medium mb-2">Lista de beneficios</label>
                        <p class="text-xs text-gray-500 mb-2">Separa cada característica con una barra vertical ( | ). Ejemplo: Acceso total | Certificado incluido | Soporte 24/7</p>
                        <textarea name="caracteristicas" rows="5" required
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none"><?php echo htmlspecialchars($plan['caracteristicas']); ?></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="<?php echo url('admin/planes'); ?>" class="px-6 py-3 rounded-lg bg-gray-700 hover:bg-gray-600 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold transition-colors">
                        <?php echo $isEditing ? 'Guardar Cambios' : 'Crear Plan'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
