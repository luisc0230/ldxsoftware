<?php
/**
 * Página de Pago Exitoso
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

// Verificar autenticación
if (!AuthController::isAuthenticated()) {
    header('Location: ' . BASE_URL);
    exit;
}

$user = AuthController::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Pago Exitoso! - LDX SOFTWARE</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gradient-to-b from-black via-gray-900 to-black min-h-screen flex items-center justify-center">
    
    <div class="container mx-auto px-4 py-20">
        <div class="max-w-2xl mx-auto text-center">
            
            <!-- Icono de éxito -->
            <div class="mb-8 animate-bounce">
                <div class="inline-block p-6 bg-green-500/20 rounded-full border-4 border-green-500">
                    <i class="fas fa-check text-6xl text-green-400"></i>
                </div>
            </div>
            
            <!-- Mensaje principal -->
            <h1 class="text-5xl font-bold text-white mb-4">
                ¡Pago Exitoso!
            </h1>
            <p class="text-2xl text-gray-300 mb-8">
                Tu suscripción ha sido activada correctamente
            </p>
            
            <!-- Información -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 border border-green-500/30 mb-8">
                <div class="flex items-center justify-center gap-4 mb-6">
                    <?php if (!empty($user['picture'])): ?>
                        <img src="<?php echo htmlspecialchars($user['picture']); ?>" 
                             alt="<?php echo htmlspecialchars($user['name']); ?>"
                             class="w-16 h-16 rounded-full border-2 border-green-500">
                    <?php endif; ?>
                    <div class="text-left">
                        <p class="text-white font-semibold text-lg"><?php echo htmlspecialchars($user['name']); ?></p>
                        <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
                
                <div class="space-y-3 text-left">
                    <div class="flex items-center gap-3 text-gray-300">
                        <i class="fas fa-check-circle text-green-400"></i>
                        <span>Pago procesado exitosamente</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-300">
                        <i class="fas fa-crown text-yellow-400"></i>
                        <span>Suscripción activada</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-300">
                        <i class="fas fa-envelope text-blue-400"></i>
                        <span>Recibirás un email de confirmación</span>
                    </div>
                </div>
            </div>
            
            <!-- Botones de acción -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo BASE_URL; ?>mis-suscripciones" 
                   class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-green-500/50">
                    <i class="fas fa-list"></i>
                    Ver Mis Suscripciones
                </a>
                <a href="<?php echo BASE_URL; ?>" 
                   class="inline-flex items-center justify-center gap-2 bg-gray-700 hover:bg-gray-600 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300">
                    <i class="fas fa-home"></i>
                    Ir al Inicio
                </a>
            </div>
            
            <!-- Mensaje adicional -->
            <div class="mt-12 p-6 bg-blue-500/10 border border-blue-500/30 rounded-xl">
                <p class="text-blue-300 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Si tienes alguna pregunta, no dudes en <a href="<?php echo BASE_URL; ?>#contacto" class="underline hover:text-blue-200">contactarnos</a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Confetti animation (opcional) -->
    <script>
        // Redirigir automáticamente después de 5 segundos
        setTimeout(() => {
            window.location.href = '<?php echo BASE_URL; ?>mis-suscripciones';
        }, 5000);
    </script>
</body>
</html>
