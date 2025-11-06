<?php
/**
 * Checkout Page - Procesar pago de suscripción
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
    <title>Checkout - LDX SOFTWARE</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
    
    <!-- Culqi Checkout -->
    <script src="https://checkout.culqi.com/js/v4"></script>
</head>
<body class="bg-gradient-to-b from-black via-gray-900 to-black min-h-screen">
    
    <div class="container mx-auto px-4 py-20">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-12">
                <a href="<?php echo BASE_URL; ?>" class="inline-block mb-6">
                    <img src="<?php echo asset('images/logo.png'); ?>" alt="LDX Software" class="h-12 mx-auto">
                </a>
                <h1 class="text-4xl font-bold text-white mb-2">Finalizar Suscripción</h1>
                <p class="text-gray-400">Estás a un paso de comenzar tu transformación digital</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                
                <!-- Información del Usuario -->
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 border border-gray-700">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-user text-blue-400"></i>
                        Tu Información
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <?php if (!empty($user['picture'])): ?>
                                <img src="<?php echo htmlspecialchars($user['picture']); ?>" 
                                     alt="<?php echo htmlspecialchars($user['name']); ?>"
                                     class="w-16 h-16 rounded-full border-2 border-blue-500">
                            <?php else: ?>
                                <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold">
                                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <p class="text-white font-semibold"><?php echo htmlspecialchars($user['name']); ?></p>
                                <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen del Plan -->
                <div class="bg-gradient-to-br from-purple-900/50 to-blue-900/50 rounded-2xl p-8 border-2 border-purple-500">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-purple-400"></i>
                        Resumen del Pedido
                    </h2>
                    
                    <div id="planResumen" class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Plan:</span>
                            <span class="text-white font-bold" id="planNombre">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Frecuencia:</span>
                            <span class="text-white">Mensual</span>
                        </div>
                        <div class="border-t border-gray-600 pt-4 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xl text-white font-bold">Total:</span>
                                <span class="text-3xl text-white font-bold">S/ <span id="planPrecio">-</span></span>
                            </div>
                            <p class="text-gray-400 text-sm mt-2">Se cobrará mensualmente</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón de Pago -->
            <div class="mt-8 text-center">
                <button id="btnPagar" 
                        class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-4 px-12 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/50 text-lg">
                    <i class="fas fa-credit-card mr-2"></i>
                    Proceder al Pago
                </button>
                <p class="text-gray-500 text-sm mt-4">
                    <i class="fas fa-lock mr-1"></i>
                    Pago seguro procesado por Culqi
                </p>
            </div>

            <!-- Términos y Condiciones -->
            <div class="mt-8 text-center text-gray-400 text-sm">
                <p>Al continuar, aceptas nuestros 
                    <a href="<?php echo url('terminos'); ?>" class="text-blue-400 hover:underline">Términos y Condiciones</a> y 
                    <a href="<?php echo url('privacidad'); ?>" class="text-blue-400 hover:underline">Política de Privacidad</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Recuperar plan seleccionado
        const planSeleccionado = sessionStorage.getItem('planSeleccionado') || 'basico';
        const precioSeleccionado = sessionStorage.getItem('precioSeleccionado') || '99';
        
        // Actualizar UI
        document.getElementById('planNombre').textContent = planSeleccionado.charAt(0).toUpperCase() + planSeleccionado.slice(1);
        document.getElementById('planPrecio').textContent = precioSeleccionado;
        
        // Configurar Culqi
        Culqi.publicKey = '<?php echo CULQI_PUBLIC_KEY; ?>';
        
        Culqi.settings({
            title: 'LDX Software',
            currency: 'PEN',
            amount: parseInt(precioSeleccionado) * 100, // Convertir a centavos
            order: 'ord_live_' + Date.now()
        });
        
        Culqi.options({
            lang: 'es',
            installments: false,
            paymentMethods: {
                tarjeta: true,
                yape: false,
                billetera: false,
                bancaMovil: false,
                agente: false,
                cuotealo: false
            }
        });
        
        // Abrir Culqi Checkout
        document.getElementById('btnPagar').addEventListener('click', function() {
            Culqi.open();
        });
        
        // Función que se ejecuta cuando Culqi retorna el token
        function culqi() {
            if (Culqi.token) {
                const token = Culqi.token.id;
                
                // Mostrar loading
                document.getElementById('btnPagar').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';
                document.getElementById('btnPagar').disabled = true;
                
                // Enviar token al servidor para procesar suscripción
                fetch('<?php echo BASE_URL; ?>api/subscription/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        token: token,
                        plan: planSeleccionado,
                        email: '<?php echo $user['email']; ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Limpiar sessionStorage
                        sessionStorage.removeItem('planSeleccionado');
                        sessionStorage.removeItem('precioSeleccionado');
                        
                        // Redirigir a página de éxito
                        window.location.href = '<?php echo BASE_URL; ?>success?subscription=' + data.subscription.id;
                    } else {
                        alert('Error al procesar la suscripción: ' + (data.error || 'Error desconocido'));
                        document.getElementById('btnPagar').innerHTML = '<i class="fas fa-credit-card mr-2"></i>Proceder al Pago';
                        document.getElementById('btnPagar').disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la suscripción. Por favor, intenta nuevamente.');
                    document.getElementById('btnPagar').innerHTML = '<i class="fas fa-credit-card mr-2"></i>Proceder al Pago';
                    document.getElementById('btnPagar').disabled = false;
                });
                
            } else if (Culqi.error) {
                console.error('Error de Culqi:', Culqi.error);
                alert('Error: ' + Culqi.error.user_message);
            }
        }
    </script>
</body>
</html>
