<?php
/**
 * Mis Suscripciones
 */

define('LDX_ACCESS', true);
require_once '../config/config.php';
require_once '../app/controllers/AuthController.php';

// Verificar autenticación
if (!AuthController::isAuthenticated()) {
    header('Location: ' . BASE_URL . '?error=not_authenticated');
    exit;
}

$user = AuthController::getCurrentUser();

// Cargar suscripciones del usuario
$subscriptionsFile = APP_PATH . 'data/subscriptions.json';
$userSubscriptions = [];

if (file_exists($subscriptionsFile)) {
    $allSubscriptions = json_decode(file_get_contents($subscriptionsFile), true) ?? [];
    $userSubscriptions = array_filter($allSubscriptions, function($sub) use ($user) {
        return $sub['user_email'] === $user['email'];
    });
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Suscripciones - LDX SOFTWARE</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gradient-to-b from-black via-gray-900 to-black min-h-screen">
    
    <div class="container mx-auto px-4 py-20">
        <div class="max-w-6xl mx-auto">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Mis Suscripciones</h1>
                    <p class="text-gray-400">Gestiona tus planes activos</p>
                </div>
                <a href="<?php echo BASE_URL; ?>" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al inicio
                </a>
            </div>

            <?php if (empty($userSubscriptions)): ?>
                <!-- Sin suscripciones -->
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-12 border border-gray-700 text-center">
                    <div class="inline-block p-6 bg-purple-500/10 rounded-full mb-6">
                        <i class="fas fa-credit-card text-6xl text-purple-400"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-4">No tienes suscripciones activas</h2>
                    <p class="text-gray-400 mb-8 max-w-md mx-auto">
                        Explora nuestros planes y elige el que mejor se adapte a tus necesidades
                    </p>
                    <a href="<?php echo BASE_URL; ?>#suscripciones" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-plus"></i>
                        Ver Planes Disponibles
                    </a>
                </div>
            <?php else: ?>
                <!-- Lista de suscripciones -->
                <div class="grid gap-6">
                    <?php foreach ($userSubscriptions as $subscription): 
                        $statusColors = [
                            0 => ['bg' => 'bg-yellow-500/20', 'text' => 'text-yellow-400', 'border' => 'border-yellow-500/30', 'label' => 'Pendiente'],
                            1 => ['bg' => 'bg-green-500/20', 'text' => 'text-green-400', 'border' => 'border-green-500/30', 'label' => 'Activa'],
                            2 => ['bg' => 'bg-red-500/20', 'text' => 'text-red-400', 'border' => 'border-red-500/30', 'label' => 'Cancelada'],
                            3 => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/30', 'label' => 'En Prueba']
                        ];
                        $status = $statusColors[$subscription['status']] ?? $statusColors[0];
                    ?>
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 border border-gray-700 hover:border-purple-500/50 transition-all">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-2xl font-bold text-white">
                                        Plan <?php echo ucfirst($subscription['plan_type']); ?>
                                    </h3>
                                    <span class="<?php echo $status['bg']; ?> <?php echo $status['text']; ?> <?php echo $status['border']; ?> border px-3 py-1 rounded-full text-xs font-semibold">
                                        <?php echo $status['label']; ?>
                                    </span>
                                </div>
                                <p class="text-gray-400 text-sm">ID: <?php echo htmlspecialchars($subscription['id']); ?></p>
                            </div>
                            <i class="fas fa-crown text-yellow-400 text-2xl"></i>
                        </div>

                        <div class="grid md:grid-cols-3 gap-4 mb-4">
                            <div class="bg-gray-800/50 rounded-lg p-4">
                                <p class="text-gray-400 text-sm mb-1">Fecha de inicio</p>
                                <p class="text-white font-semibold"><?php echo date('d/m/Y', strtotime($subscription['created_at'])); ?></p>
                            </div>
                            <div class="bg-gray-800/50 rounded-lg p-4">
                                <p class="text-gray-400 text-sm mb-1">Próximo cobro</p>
                                <p class="text-white font-semibold"><?php echo date('d/m/Y', strtotime($subscription['next_billing_date'])); ?></p>
                            </div>
                            <div class="bg-gray-800/50 rounded-lg p-4">
                                <p class="text-gray-400 text-sm mb-1">Estado</p>
                                <p class="<?php echo $status['text']; ?> font-semibold"><?php echo $status['label']; ?></p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition-all">
                                <i class="fas fa-eye mr-2"></i>
                                Ver Detalles
                            </button>
                            <button class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-all">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Agregar nueva suscripción -->
                <div class="mt-8 bg-gradient-to-br from-purple-900/30 to-blue-900/30 rounded-2xl p-6 border border-purple-500/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">¿Necesitas más servicios?</h3>
                            <p class="text-gray-400">Explora nuestros otros planes disponibles</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>#suscripciones" 
                           class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>
                            Ver Planes
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sidebar con info del usuario -->
            <div class="mt-8 grid md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-6 border border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <?php if (!empty($user['picture'])): ?>
                            <img src="<?php echo htmlspecialchars($user['picture']); ?>" 
                                 alt="<?php echo htmlspecialchars($user['name']); ?>"
                                 class="w-12 h-12 rounded-full border-2 border-blue-500">
                        <?php else: ?>
                            <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <p class="text-white font-semibold"><?php echo htmlspecialchars($user['name']); ?></p>
                            <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo url('perfil'); ?>" class="block text-center bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-lg transition-all">
                        Ver Perfil
                    </a>
                </div>

                <div class="bg-gradient-to-br from-green-900/30 to-emerald-900/30 rounded-xl p-6 border border-green-500/30">
                    <i class="fas fa-headset text-green-400 text-3xl mb-3"></i>
                    <h4 class="text-white font-bold mb-2">Soporte 24/7</h4>
                    <p class="text-gray-400 text-sm mb-3">¿Necesitas ayuda con tu suscripción?</p>
                    <a href="<?php echo BASE_URL; ?>#contacto" class="text-green-400 hover:underline text-sm">
                        Contactar Soporte →
                    </a>
                </div>

                <div class="bg-gradient-to-br from-blue-900/30 to-purple-900/30 rounded-xl p-6 border border-blue-500/30">
                    <i class="fas fa-gift text-blue-400 text-3xl mb-3"></i>
                    <h4 class="text-white font-bold mb-2">Programa de Referidos</h4>
                    <p class="text-gray-400 text-sm mb-3">Gana recompensas invitando amigos</p>
                    <button class="text-blue-400 hover:underline text-sm">
                        Próximamente →
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
