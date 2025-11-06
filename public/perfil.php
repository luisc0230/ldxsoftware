<?php
/**
 * Perfil de Usuario
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - LDX SOFTWARE</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gradient-to-b from-black via-gray-900 to-black min-h-screen">
    
    <div class="container mx-auto px-4 py-20">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-4xl font-bold text-white">Mi Perfil</h1>
                <a href="<?php echo BASE_URL; ?>" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al inicio
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                
                <!-- Sidebar -->
                <div class="md:col-span-1">
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 border border-gray-700">
                        <div class="text-center mb-6">
                            <?php if (!empty($user['picture'])): ?>
                                <img src="<?php echo htmlspecialchars($user['picture']); ?>" 
                                     alt="<?php echo htmlspecialchars($user['name']); ?>"
                                     class="w-24 h-24 rounded-full mx-auto border-4 border-blue-500 mb-4">
                            <?php else: ?>
                                <div class="w-24 h-24 rounded-full bg-blue-500 flex items-center justify-center text-white text-4xl font-bold mx-auto mb-4">
                                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <h2 class="text-xl font-bold text-white mb-1"><?php echo htmlspecialchars($user['name']); ?></h2>
                            <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        
                        <div class="space-y-2">
                            <a href="<?php echo url('perfil'); ?>" class="flex items-center gap-3 px-4 py-3 bg-blue-500/20 text-blue-400 rounded-lg">
                                <i class="fas fa-user"></i>
                                <span>Información Personal</span>
                            </a>
                            <a href="<?php echo url('mis-suscripciones'); ?>" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 rounded-lg transition-all">
                                <i class="fas fa-credit-card"></i>
                                <span>Mis Suscripciones</span>
                            </a>
                            <hr class="my-2 border-gray-700">
                            <a href="<?php echo url('auth/logout'); ?>" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-lg transition-all">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Cerrar Sesión</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="md:col-span-2">
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 border border-gray-700">
                        <h2 class="text-2xl font-bold text-white mb-6">Información Personal</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">Nombre Completo</label>
                                <div class="bg-gray-800/50 rounded-lg px-4 py-3 text-white">
                                    <?php echo htmlspecialchars($user['name']); ?>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">Correo Electrónico</label>
                                <div class="bg-gray-800/50 rounded-lg px-4 py-3 text-white flex items-center gap-2">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                    <i class="fas fa-check-circle text-green-400 ml-auto" title="Verificado con Google"></i>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">ID de Usuario</label>
                                <div class="bg-gray-800/50 rounded-lg px-4 py-3 text-gray-400 font-mono text-sm">
                                    <?php echo htmlspecialchars($user['id']); ?>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">Fecha de Registro</label>
                                <div class="bg-gray-800/50 rounded-lg px-4 py-3 text-white">
                                    <?php echo date('d/m/Y H:i', $user['login_time']); ?>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-400 mt-1"></i>
                                <div>
                                    <p class="text-blue-400 font-semibold mb-1">Cuenta verificada con Google</p>
                                    <p class="text-gray-400 text-sm">
                                        Tu cuenta está protegida por la autenticación de Google. 
                                        No necesitas gestionar contraseñas adicionales.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="bg-gradient-to-br from-purple-900/30 to-blue-900/30 rounded-xl p-6 border border-purple-500/30">
                            <div class="flex items-center justify-between mb-2">
                                <i class="fas fa-credit-card text-purple-400 text-2xl"></i>
                                <span class="text-3xl font-bold text-white">0</span>
                            </div>
                            <p class="text-gray-400 text-sm">Suscripciones Activas</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-900/30 to-emerald-900/30 rounded-xl p-6 border border-green-500/30">
                            <div class="flex items-center justify-between mb-2">
                                <i class="fas fa-calendar-check text-green-400 text-2xl"></i>
                                <span class="text-3xl font-bold text-white">0</span>
                            </div>
                            <p class="text-gray-400 text-sm">Días de Servicio</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
