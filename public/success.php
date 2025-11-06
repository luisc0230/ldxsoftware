<?php
/**
 * Success Page - Suscripción exitosa
 */

define('LDX_ACCESS', true);
require_once '../config/config.php';
require_once '../app/controllers/AuthController.php';

// Verificar autenticación
if (!AuthController::isAuthenticated()) {
    header('Location: ' . BASE_URL);
    exit;
}

$user = AuthController::getCurrentUser();
$subscriptionId = $_GET['subscription'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Suscripción Exitosa! - LDX SOFTWARE</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gradient-to-b from-black via-gray-900 to-black min-h-screen flex items-center justify-center">
    
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            
            <!-- Icono de éxito -->
            <div class="mb-8 animate-bounce">
                <div class="inline-block p-6 bg-green-500/20 rounded-full">
                    <i class="fas fa-check-circle text-8xl text-green-400"></i>
                </div>
            </div>

            <!-- Mensaje -->
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                ¡Suscripción Exitosa!
            </h1>
            <p class="text-xl text-gray-300 mb-8">
                Gracias por confiar en LDX Software. Tu suscripción ha sido activada correctamente.
            </p>

            <!-- Información -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 border border-gray-700 mb-8">
                <div class="space-y-4 text-left">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Usuario:</span>
                        <span class="text-white font-semibold"><?php echo htmlspecialchars($user['name']); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Email:</span>
                        <span class="text-white"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <?php if ($subscriptionId): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">ID de Suscripción:</span>
                        <span class="text-white font-mono text-sm"><?php echo htmlspecialchars($subscriptionId); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="border-t border-gray-700 pt-4 mt-4">
                        <p class="text-gray-300 text-sm">
                            <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                            Recibirás un email de confirmación con todos los detalles de tu suscripción.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Próximos pasos -->
            <div class="bg-gradient-to-br from-purple-900/30 to-blue-900/30 rounded-2xl p-6 border border-purple-500/30 mb-8">
                <h2 class="text-2xl font-bold text-white mb-4">
                    <i class="fas fa-rocket text-purple-400 mr-2"></i>
                    ¿Qué sigue?
                </h2>
                <ul class="text-left space-y-3 text-gray-300">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-green-400 mt-1"></i>
                        <span>Nuestro equipo se pondrá en contacto contigo en las próximas 24 horas</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-green-400 mt-1"></i>
                        <span>Programaremos una reunión para conocer tus necesidades específicas</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-green-400 mt-1"></i>
                        <span>Comenzaremos el desarrollo de tu proyecto inmediatamente</span>
                    </li>
                </ul>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo BASE_URL; ?>" 
                   class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-home mr-2"></i>
                    Volver al Inicio
                </a>
                <a href="<?php echo BASE_URL; ?>#contacto" 
                   class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300">
                    <i class="fas fa-envelope mr-2"></i>
                    Contactar Soporte
                </a>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-gray-500 text-sm">
                <p>
                    <i class="fas fa-shield-alt text-green-400 mr-1"></i>
                    Tu pago está protegido y puedes cancelar tu suscripción en cualquier momento
                </p>
            </div>
        </div>
    </div>

    <!-- Confetti Animation -->
    <script>
        // Simple confetti effect
        function createConfetti() {
            const colors = ['#3B82F6', '#8B5CF6', '#EC4899', '#10B981', '#F59E0B'];
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.width = '10px';
                    confetti.style.height = '10px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.left = Math.random() * window.innerWidth + 'px';
                    confetti.style.top = '-10px';
                    confetti.style.opacity = '1';
                    confetti.style.borderRadius = '50%';
                    confetti.style.pointerEvents = 'none';
                    confetti.style.zIndex = '9999';
                    document.body.appendChild(confetti);
                    
                    let pos = -10;
                    let opacity = 1;
                    const fall = setInterval(() => {
                        if (pos > window.innerHeight) {
                            clearInterval(fall);
                            confetti.remove();
                        } else {
                            pos += 5;
                            opacity -= 0.01;
                            confetti.style.top = pos + 'px';
                            confetti.style.opacity = opacity;
                        }
                    }, 20);
                }, i * 30);
            }
        }
        
        // Ejecutar confetti al cargar
        window.addEventListener('load', createConfetti);
    </script>
</body>
</html>
