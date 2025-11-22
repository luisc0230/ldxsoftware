<?php
/**
 * Mi Perfil
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

// Verificar autenticación
if (!AuthController::isAuthenticated()) {
    header('Location: ' . BASE_URL . '?error=not_authenticated');
    exit;
}

$user = AuthController::getCurrentUser();

// Cargar información adicional del usuario desde la base de datos
require_once __DIR__ . '/app/models/Database.php';

$userInfo = null;
$suscripcionesActivas = 0;

try {
    $db = Database::getInstance()->getConnection();
    
    // Obtener información completa del usuario
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $userInfo = $result->fetch_assoc();
    
    // Contar suscripciones activas
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM suscripciones WHERE usuario_id = ? AND estado = 'activa'");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $suscripcionesActivas = $row['total'];
    
} catch (Exception $e) {
    error_log("Error al cargar información del usuario: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - LDX SOFTWARE</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/logo.ico'); ?>">
</head>
<body class="bg-gradient-to-b from-black via-gray-900 to-black min-h-screen">
    
    <!-- Include Navbar -->
    <?php include __DIR__ . '/app/includes/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-24">
        <div class="max-w-6xl mx-auto">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Mi Perfil</h1>
                    <p class="text-gray-400">Gestiona tu información personal</p>
                </div>
                <a href="<?php echo BASE_URL; ?>" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al inicio
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Sidebar con foto de perfil -->
                <div class="md:col-span-1">
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 border border-gray-700 sticky top-24">
                        <div class="text-center">
                            <?php if (!empty($user['picture'])): ?>
                                <img src="<?php echo htmlspecialchars($user['picture']); ?>" 
                                     alt="<?php echo htmlspecialchars($user['name']); ?>"
                                     class="w-32 h-32 rounded-full border-4 border-blue-500 mx-auto mb-4">
                            <?php else: ?>
                                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold text-5xl mx-auto mb-4">
                                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            
                            <h2 class="text-2xl font-bold text-white mb-1"><?php echo htmlspecialchars($user['name']); ?></h2>
                            <p class="text-gray-400 text-sm mb-4"><?php echo htmlspecialchars($user['email']); ?></p>
                            
                            <div class="bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-500/30 rounded-lg p-4 mb-4">
                                <p class="text-gray-400 text-sm mb-1">Suscripciones Activas</p>
                                <p class="text-3xl font-bold text-white"><?php echo $suscripcionesActivas; ?></p>
                            </div>
                            
                            <a href="<?php echo url('mis-suscripciones'); ?>" 
                               class="block w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 mb-3">
                                <i class="fas fa-credit-card mr-2"></i>
                                Mis Suscripciones
                            </a>
                            
                            <a href="<?php echo url('auth/logout'); ?>" 
                               class="block w-full bg-gray-700 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="md:col-span-2">
                    <!-- Información Personal -->
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 border border-gray-700 mb-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-white">Información Personal</h3>
                            <button class="text-blue-400 hover:text-blue-300 transition-colors">
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </button>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-gray-800/50 rounded-lg p-4">
                                <p class="text-gray-400 text-sm mb-1">Nombre Completo</p>
                                <p class="text-white font-semibold"><?php echo htmlspecialchars($user['name']); ?></p>
                            </div>
                            
                            <div class="bg-gray-800/50 rounded-lg p-4">
                                <p class="text-gray-400 text-sm mb-1">Correo Electrónico</p>
                                <p class="text-white font-semibold"><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                            
                            <div class="bg-gray-800/50 rounded-lg p-4">
                                <p class="text-gray-400 text-sm mb-1">ID de Usuario</p>
                                <p class="text-white font-semibold">#<?php echo htmlspecialchars($user['id']); ?></p>
                            </div>
                            
                            <div class="bg-gray-800/50 rounded-lg p-4">
                                <p class="text-gray-400 text-sm mb-1">Fecha de Registro</p>
                                <p class="text-white font-semibold">
                                    <?php 
                                    if ($userInfo && isset($userInfo['fecha_registro'])) {
                                        echo date('d/m/Y', strtotime($userInfo['fecha_registro']));
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gradient-to-br from-blue-900/30 to-blue-800/30 rounded-xl p-6 border border-blue-500/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-400 text-sm mb-1">Total Suscripciones</p>
                                    <p class="text-3xl font-bold text-white"><?php echo $suscripcionesActivas; ?></p>
                                </div>
                                <div class="bg-blue-500/20 p-4 rounded-full">
                                    <i class="fas fa-crown text-blue-400 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-900/30 to-green-800/30 rounded-xl p-6 border border-green-500/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-400 text-sm mb-1">Estado de Cuenta</p>
                                    <p class="text-xl font-bold text-green-400">Activa</p>
                                </div>
                                <div class="bg-green-500/20 p-4 rounded-full">
                                    <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel de Administración (Solo para Admin) -->
                    <?php if ($user['email'] === 'luisc023030@gmail.com'): ?>
                    <div class="bg-gradient-to-br from-red-900/20 to-orange-900/20 rounded-2xl p-6 border border-red-500/30 mb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-red-500/20 p-3 rounded-lg">
                                <i class="fas fa-user-shield text-red-400 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">Panel de Administración</h3>
                                <p class="text-gray-400 text-sm">Acceso exclusivo de administrador</p>
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <a href="<?php echo url('admin/cursos'); ?>" 
                               class="flex items-center gap-4 bg-gray-800/50 hover:bg-red-900/20 rounded-lg p-4 transition-all group border border-gray-700 hover:border-red-500/30">
                                <div class="bg-red-500/20 p-3 rounded-lg group-hover:bg-red-500/30 transition-all">
                                    <i class="fas fa-graduation-cap text-red-400 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-white font-semibold">Gestionar Cursos</p>
                                    <p class="text-gray-400 text-sm">Agregar, editar o eliminar cursos</p>
                                </div>
                            </a>
                            
                            <a href="<?php echo url('admin/suscripciones'); ?>" 
                               class="flex items-center gap-4 bg-gray-800/50 hover:bg-red-900/20 rounded-lg p-4 transition-all group border border-gray-700 hover:border-red-500/30">
                                <div class="bg-orange-500/20 p-3 rounded-lg group-hover:bg-orange-500/30 transition-all">
                                    <i class="fas fa-users-cog text-orange-400 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-white font-semibold">Gestionar Suscripciones</p>
                                    <p class="text-gray-400 text-sm">Ver y administrar usuarios</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Acciones Rápidas -->
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 border border-gray-700">
                        <h3 class="text-2xl font-bold text-white mb-4">Acciones Rápidas</h3>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <a href="<?php echo url('mis-suscripciones'); ?>" 
                               class="flex items-center gap-4 bg-gray-800/50 hover:bg-gray-700/50 rounded-lg p-4 transition-all group">
                                <div class="bg-purple-500/20 p-3 rounded-lg group-hover:bg-purple-500/30 transition-all">
                                    <i class="fas fa-credit-card text-purple-400 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-white font-semibold">Ver Suscripciones</p>
                                    <p class="text-gray-400 text-sm">Gestiona tus planes</p>
                                </div>
                            </a>
                            
                            <a href="<?php echo url('cursos'); ?>" 
                               class="flex items-center gap-4 bg-gray-800/50 hover:bg-gray-700/50 rounded-lg p-4 transition-all group">
                                <div class="bg-blue-500/20 p-3 rounded-lg group-hover:bg-blue-500/30 transition-all">
                                    <i class="fas fa-play-circle text-blue-400 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-white font-semibold">Ver Cursos</p>
                                    <p class="text-gray-400 text-sm">Explora el contenido</p>
                                </div>
                            </a>
                            
                            <a href="<?php echo BASE_URL; ?>#contacto" 
                               class="flex items-center gap-4 bg-gray-800/50 hover:bg-gray-700/50 rounded-lg p-4 transition-all group">
                                <div class="bg-green-500/20 p-3 rounded-lg group-hover:bg-green-500/30 transition-all">
                                    <i class="fas fa-headset text-green-400 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-white font-semibold">Soporte</p>
                                    <p class="text-gray-400 text-sm">Contacta con nosotros</p>
                                </div>
                            </a>
                            
                            <a href="<?php echo BASE_URL; ?>" 
                               class="flex items-center gap-4 bg-gray-800/50 hover:bg-gray-700/50 rounded-lg p-4 transition-all group">
                                <div class="bg-yellow-500/20 p-3 rounded-lg group-hover:bg-yellow-500/30 transition-all">
                                    <i class="fas fa-home text-yellow-400 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-white font-semibold">Inicio</p>
                                    <p class="text-gray-400 text-sm">Volver a la página principal</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include __DIR__ . '/app/includes/footer.php'; ?>
</body>
</html>
