<?php
/**
 * Checkout Page - Procesar pago de suscripción
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
                    <img src="https://ldxsoftware.com.pe/assets/images/logo.png" alt="LDX Software" class="h-12 w-auto">
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
                    <a href="<?php echo url('terminos'); ?>" class="text-blue-400 hover:underline" target="_blank">Términos y Condiciones</a> y 
                    <a href="<?php echo url('privacidad'); ?>" class="text-blue-400 hover:underline" target="_blank">Política de Privacidad</a>
                </p>
                <div class="mt-4">
                    <a href="<?php echo BASE_URL; ?>" class="text-red-400 hover:text-red-300 text-sm transition-colors">
                        <i class="fas fa-times mr-1"></i> Cancelar y volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Recuperar plan seleccionado desde PHP session
        // Recuperar plan seleccionado desde GET o Session y guardar en Session
        <?php
        $planId = $_GET['plan'] ?? $_SESSION['planSeleccionado'] ?? 4;
        $tipoPago = $_GET['tipo'] ?? $_SESSION['tipoPagoSeleccionado'] ?? 'mensual';
        $precio = $_GET['precio'] ?? $_SESSION['precioSeleccionado'] ?? 5;
        
        // Guardar en sesión para que el controlador de pago los tenga disponibles
        $_SESSION['planSeleccionado'] = $planId;
        $_SESSION['tipoPagoSeleccionado'] = $tipoPago;
        $_SESSION['precioSeleccionado'] = $precio;
        
        // Nombres de planes (Actualizado con nuevos planes)
        $planesNombres = [
            1 => 'Básico',
            2 => 'Profesional',
            3 => 'Empresarial',
            4 => 'Mensual',
            5 => 'Trimestral',
            6 => 'Anual',
            7 => 'Lifetime'
        ];
        $planNombre = $planesNombres[$planId] ?? 'Plan Desconocido';
        ?>
        
        const planId = <?php echo $planId; ?>;
        const planNombre = '<?php echo $planNombre; ?>';
        const precio = <?php echo $precio; ?>;
        
        // Actualizar UI
        document.getElementById('planNombre').textContent = planNombre;
        document.getElementById('planPrecio').textContent = precio;
        
        // Configurar Culqi
        Culqi.publicKey = '<?php echo CULQI_PUBLIC_KEY; ?>';
        
        Culqi.settings({
            title: 'LDX Software',
            currency: 'PEN',
            amount: parseInt(precio) * 100, // Convertir a centavos
            order: 'ord_live_' + Date.now()
        });
        
        Culqi.options({
            lang: 'es',
            installments: false,
            paymentMethods: {
                tarjeta: true,
                yape: true,
                billetera: true,
                bancaMovil: true,
                agente: true,
                cuotealo: true
            }
        });
        
        // Abrir Culqi Checkout
        document.getElementById('btnPagar').addEventListener('click', function() {
            const btn = document.getElementById('btnPagar');
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creando Orden...';

            // 1. Crear Orden en el Backend
            fetch('<?php echo BASE_URL; ?>api/crear-orden.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 2. Configurar Culqi con la orden real
                    Culqi.settings({
                        title: 'LDX Software',
                        currency: 'PEN',
                        amount: parseInt(precio) * 100,
                        order: data.order_id // ID de orden real generado por Culqi
                    });
                    
                    // 3. Abrir Checkout
                    Culqi.open();
                    
                    // Restaurar botón
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                } else {
                    alert('Error al iniciar pago: ' + (data.error || 'Error desconocido'));
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión al crear la orden.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
        
        // Función que se ejecuta cuando Culqi retorna el token
        function culqi() {
            if (Culqi.token) {
                const token = Culqi.token.id;
                
                // Mostrar loading
                document.getElementById('btnPagar').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';
                document.getElementById('btnPagar').disabled = true;
                
                // Enviar token al servidor para procesar suscripción
                fetch('<?php echo BASE_URL; ?>api/procesar-pago.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        token: token
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirigir a página de éxito
                        window.location.href = data.redirect || '<?php echo BASE_URL; ?>mis-suscripciones';
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
