<?php
/**
 * Gestión de Suscripciones (Admin)
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

// Obtener todas las suscripciones con info de usuario
$suscripciones = [];
try {
    $db = Database::getInstance()->getConnection();
    $query = "
        SELECT s.*, u.name as usuario_nombre, u.email as usuario_email, p.nombre as plan_nombre 
        FROM suscripciones s
        JOIN usuarios u ON s.usuario_id = u.id
        JOIN planes p ON s.plan_id = p.id
        ORDER BY s.created_at DESC
        LIMIT 50
    ";
    $result = $db->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $suscripciones[] = $row;
        }
    }
} catch (Exception $e) {
    error_log("Error al cargar suscripciones admin: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Suscripciones - Admin</title>
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
                <a href="<?php echo url('admin/cursos'); ?>" class="text-gray-300 hover:text-white">Cursos</a>
                <a href="<?php echo url('perfil'); ?>" class="text-gray-300 hover:text-white">Volver al Perfil</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 flex-grow">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Gestión de Suscripciones</h1>
                <div class="text-gray-400 text-sm">Historial de pagos y accesos</div>
            </div>
            <a href="<?php echo url('admin/planes'); ?>" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors shadow-lg shadow-purple-500/20">
                <i class="fas fa-tags mr-2"></i> Gestionar Planes
            </a>
        </div>

        <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-xl overflow-x-auto">
            <table class="w-full text-left text-gray-300 whitespace-nowrap">
                <thead class="bg-gray-700/50 text-gray-400 uppercase text-sm">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">Usuario</th>
                        <th class="p-4">Plan</th>
                        <th class="p-4">Monto</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4">Fecha Inicio</th>
                        <th class="p-4">Próximo Cobro</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php if (empty($suscripciones)): ?>
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                No hay suscripciones registradas.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($suscripciones as $sub): ?>
                        <tr class="hover:bg-gray-700/30 transition-colors">
                            <td class="p-4 font-mono text-sm text-gray-500">#<?php echo $sub['id']; ?></td>
                            <td class="p-4">
                                <div class="font-bold text-white"><?php echo htmlspecialchars($sub['usuario_nombre']); ?></div>
                                <div class="text-xs text-gray-500"><?php echo htmlspecialchars($sub['usuario_email']); ?></div>
                            </td>
                            <td class="p-4 text-purple-400 font-semibold">
                                <?php echo htmlspecialchars($sub['plan_nombre']); ?>
                            </td>
                            <td class="p-4 font-mono">
                                S/ <?php echo number_format($sub['precio_pagado'], 2); ?>
                            </td>
                            <td class="p-4">
                                <?php 
                                $estadoClass = match($sub['estado']) {
                                    'activa' => 'bg-green-500/20 text-green-400',
                                    'pendiente' => 'bg-yellow-500/20 text-yellow-400',
                                    'cancelada' => 'bg-red-500/20 text-red-400',
                                    default => 'bg-gray-500/20 text-gray-400'
                                };
                                ?>
                                <span class="px-2 py-1 rounded text-xs font-bold <?php echo $estadoClass; ?>">
                                    <?php echo ucfirst($sub['estado']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-sm text-gray-400">
                                <?php echo date('d/m/Y', strtotime($sub['fecha_inicio'])); ?>
                            </td>
                            <td class="p-4 text-sm text-gray-400">
                                <?php echo $sub['fecha_fin'] ? date('d/m/Y', strtotime($sub['fecha_fin'])) : '-'; ?>
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
