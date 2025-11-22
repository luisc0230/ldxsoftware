<?php
/**
 * Mis Suscripciones
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

// Cargar suscripciones del usuario desde la base de datos
require_once __DIR__ . '/app/models/Database.php';
require_once __DIR__ . '/app/models/Suscripcion.php';

$userSubscriptions = [];
try {
    $suscripcionModel = new Suscripcion();
    $suscripciones = $suscripcionModel->obtenerPorUsuario($user['id']);
    
    // Convertir a formato compatible con la vista
    foreach ($suscripciones as $sub) {
        $userSubscriptions[] = [
            'id' => $sub['id'],
            'plan_type' => $sub['plan_nombre'],
            'plan_descripcion' => $sub['plan_descripcion'] ?? 'Acceso completo a todos los beneficios del plan ' . $sub['plan_nombre'],
            'status' => ($sub['estado'] === 'activa') ? 1 : (($sub['estado'] === 'pendiente') ? 0 : 2),
            'estado_texto' => $sub['estado'],
            'amount' => $sub['precio_pagado'],
            'start_date' => $sub['fecha_inicio'],
            'next_billing' => $sub['fecha_fin'],
            'tipo_pago' => $sub['tipo_pago'],
            'metodo_pago' => $sub['metodo_pago'] ?? 'No especificado',
            'transaction_id' => $sub['transaction_id'] ?? 'N/A',
            'created_at' => $sub['created_at'] ?? $sub['fecha_inicio']
        ];
    }
} catch (Exception $e) {
    error_log("Error al cargar suscripciones: " . $e->getMessage());
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
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/logo.ico'); ?>">
</head>
<body class="bg-gradient-to-b from-black via-gray-900 to-black min-h-screen flex flex-col">
    
    <!-- Include Navbar -->
    <?php include __DIR__ . '/app/includes/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-24 flex-grow">
        <div class="max-w-6xl mx-auto">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Mis Suscripciones</h1>
                    <p class="text-gray-400">Gestiona tus planes activos y facturación</p>
                </div>
                <a href="<?php echo url('perfil'); ?>" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-user mr-2"></i>
                    Volver al Perfil
                </a>
            </div>

            <?php if (empty($userSubscriptions)): ?>
                <!-- Sin suscripciones -->
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-12 border border-gray-700 text-center shadow-2xl">
                    <div class="inline-block p-6 bg-purple-500/10 rounded-full mb-6 animate-pulse">
                        <i class="fas fa-crown text-6xl text-purple-400"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-4">Aún no tienes un plan activo</h2>
                    <p class="text-gray-400 mb-8 max-w-md mx-auto text-lg">
                        Desbloquea todo tu potencial con nuestros cursos premium y mentorías exclusivas.
                    </p>
                    <a href="<?php echo BASE_URL; ?>#suscripciones" 
                       class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-4 px-10 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/30">
                        <i class="fas fa-rocket"></i>
                        Ver Planes Disponibles
                    </a>
                </div>
            <?php else: ?>
                <!-- Lista de suscripciones -->
                <div class="grid gap-6">
                    <?php foreach ($userSubscriptions as $subscription): 
                        $statusColors = [
                            0 => ['bg' => 'bg-yellow-500/10', 'text' => 'text-yellow-400', 'border' => 'border-yellow-500/20', 'label' => 'Pendiente', 'icon' => 'fa-clock'],
                            1 => ['bg' => 'bg-green-500/10', 'text' => 'text-green-400', 'border' => 'border-green-500/20', 'label' => 'Activa', 'icon' => 'fa-check-circle'],
                            2 => ['bg' => 'bg-red-500/10', 'text' => 'text-red-400', 'border' => 'border-red-500/20', 'label' => 'Cancelada', 'icon' => 'fa-times-circle'],
                            3 => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-400', 'border' => 'border-blue-500/20', 'label' => 'En Prueba', 'icon' => 'fa-flask']
                        ];
                        $status = $statusColors[$subscription['status']] ?? $statusColors[0];
                    ?>
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 border border-gray-700 hover:border-purple-500/30 transition-all shadow-lg group">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-purple-500/20 to-blue-500/20 flex items-center justify-center border border-white/10">
                                    <i class="fas fa-crown text-2xl text-purple-400"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h3 class="text-2xl font-bold text-white">
                                            Plan <?php echo ucfirst($subscription['plan_type']); ?>
                                        </h3>
                                        <span class="<?php echo $status['bg']; ?> <?php echo $status['text']; ?> <?php echo $status['border']; ?> border px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                                            <i class="fas <?php echo $status['icon']; ?>"></i>
                                            <?php echo $status['label']; ?>
                                        </span>
                                    </div>
                                    <p class="text-gray-400 text-sm font-mono">ID: #<?php echo htmlspecialchars($subscription['id']); ?></p>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-sm text-gray-400 mb-1">Precio pagado</p>
                                <p class="text-2xl font-bold text-white">S/ <?php echo number_format($subscription['amount'], 2); ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 bg-black/20 rounded-xl p-4 border border-white/5">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Inicio</p>
                                <p class="text-white font-semibold"><?php echo date('d/m/Y', strtotime($subscription['created_at'])); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Próximo Cobro</p>
                                <p class="text-white font-semibold"><?php echo $subscription['next_billing'] ? date('d/m/Y', strtotime($subscription['next_billing'])) : 'N/A'; ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Frecuencia</p>
                                <p class="text-white font-semibold capitalize"><?php echo $subscription['tipo_pago']; ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Método</p>
                                <p class="text-white font-semibold capitalize"><?php echo $subscription['metodo_pago']; ?></p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button onclick="openSubscriptionModal(<?php echo htmlspecialchars(json_encode($subscription)); ?>)" 
                                    class="flex-1 bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 border border-blue-500/30 font-semibold py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-eye"></i>
                                Ver Detalles Completos
                            </button>
                            
                            <?php if ($subscription['status'] === 1): ?>
                                <button onclick="downloadInvoice(<?php echo $subscription['id']; ?>)" 
                                        class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-xl transition-all" 
                                        title="Descargar comprobante">
                                    <i class="fas fa-download"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Agregar nueva suscripción -->
                <div class="mt-8 bg-gradient-to-r from-purple-900/20 to-blue-900/20 rounded-2xl p-8 border border-purple-500/20 text-center">
                    <h3 class="text-xl font-bold text-white mb-2">¿Quieres mejorar tu plan?</h3>
                    <p class="text-gray-400 mb-6">Accede a más contenido y beneficios exclusivos actualizando tu suscripción.</p>
                    <a href="<?php echo BASE_URL; ?>#suscripciones" 
                       class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold py-3 px-8 rounded-xl transition-all border border-white/10">
                        <i class="fas fa-arrow-up text-purple-400"></i>
                        Ver Opciones de Upgrade
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal de Detalles de Suscripción -->
    <div id="subscriptionModal" class="hidden fixed inset-0 bg-black/90 backdrop-blur-md z-[100000] flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="bg-gray-900 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-gray-700 shadow-2xl transform transition-all scale-100">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-gray-900/95 backdrop-blur-xl border-b border-gray-800 p-6 flex items-center justify-between z-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-purple-500/20 flex items-center justify-center">
                        <i class="fas fa-crown text-purple-400 text-xl"></i>
                    </div>
                    <div>
                        <h2 id="modalPlanName" class="text-2xl font-bold text-white">Plan Premium</h2>
                        <span id="modalStatus" class="inline-block px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider mt-1">Activa</span>
                    </div>
                </div>
                <button onclick="closeSubscriptionModal()" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-8 space-y-8">
                <!-- Información Principal -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-gray-800/30 rounded-xl p-4 border border-gray-700/50">
                        <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Monto Pagado</p>
                        <p id="modalAmount" class="text-2xl font-bold text-white">S/ 99.00</p>
                    </div>
                    <div class="bg-gray-800/30 rounded-xl p-4 border border-gray-700/50">
                        <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Próximo Vencimiento</p>
                        <p id="modalNextBilling" class="text-2xl font-bold text-white">01/02/2024</p>
                    </div>
                </div>

                <!-- Detalles Técnicos -->
                <div>
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-400"></i>
                        Detalles de la Suscripción
                    </h3>
                    <div class="bg-gray-800/30 rounded-xl border border-gray-700/50 overflow-hidden">
                        <div class="grid grid-cols-1 divide-y divide-gray-700/50">
                            <div class="p-4 flex justify-between items-center hover:bg-gray-800/50 transition-colors">
                                <span class="text-gray-400">ID de Suscripción</span>
                                <span id="modalId" class="text-white font-mono">#12345</span>
                            </div>
                            <div class="p-4 flex justify-between items-center hover:bg-gray-800/50 transition-colors">
                                <span class="text-gray-400">Fecha de Inicio</span>
                                <span id="modalStartDate" class="text-white">01/01/2024</span>
                            </div>
                            <div class="p-4 flex justify-between items-center hover:bg-gray-800/50 transition-colors">
                                <span class="text-gray-400">Tipo de Pago</span>
                                <span id="modalTipoPago" class="text-white capitalize">Mensual</span>
                            </div>
                            <div class="p-4 flex justify-between items-center hover:bg-gray-800/50 transition-colors">
                                <span class="text-gray-400">Método de Pago</span>
                                <span id="modalMetodoPago" class="text-white capitalize">Tarjeta</span>
                            </div>
                            <div class="p-4 flex justify-between items-center hover:bg-gray-800/50 transition-colors">
                                <span class="text-gray-400">ID Transacción</span>
                                <span id="modalTransactionId" class="text-white font-mono text-sm">TXN123</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Beneficios -->
                <div>
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-star text-yellow-400"></i>
                        Beneficios Incluidos
                    </h3>
                    <div class="bg-gradient-to-br from-purple-900/10 to-blue-900/10 rounded-xl p-6 border border-purple-500/10">
                        <p id="modalPlanDescripcion" class="text-gray-300 leading-relaxed">
                            Acceso completo a todos los cursos, mentorías grupales y soporte prioritario.
                        </p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-purple-500/10 text-purple-400 rounded-full text-xs font-bold border border-purple-500/20">Cursos Premium</span>
                            <span class="px-3 py-1 bg-blue-500/10 text-blue-400 rounded-full text-xs font-bold border border-blue-500/20">Soporte 24/7</span>
                            <span class="px-3 py-1 bg-green-500/10 text-green-400 rounded-full text-xs font-bold border border-green-500/20">Certificados</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 bg-gray-900/95 backdrop-blur-xl border-t border-gray-800 p-6 flex gap-4">
                <button onclick="downloadInvoiceFromModal()" class="flex-1 bg-white text-black hover:bg-gray-200 font-bold py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-download"></i>
                    Descargar Recibo
                </button>
                <button onclick="closeSubscriptionModal()" class="px-6 py-3 rounded-xl border border-gray-700 text-gray-300 hover:text-white hover:bg-gray-800 font-semibold transition-all">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include __DIR__ . '/app/includes/footer.php'; ?>

    <script>
        let currentSubscriptionId = null;

        // Abrir modal de suscripción
        function openSubscriptionModal(subscription) {
            currentSubscriptionId = subscription.id;
            
            // Actualizar contenido del modal
            document.getElementById('modalPlanName').textContent = 'Plan ' + subscription.plan_type.charAt(0).toUpperCase() + subscription.plan_type.slice(1);
            document.getElementById('modalId').textContent = '#' + subscription.id;
            document.getElementById('modalAmount').textContent = 'S/ ' + parseFloat(subscription.amount).toFixed(2);
            document.getElementById('modalTipoPago').textContent = subscription.tipo_pago || 'No especificado';
            document.getElementById('modalStartDate').textContent = formatDate(subscription.start_date);
            document.getElementById('modalNextBilling').textContent = subscription.next_billing ? formatDate(subscription.next_billing) : 'N/A';
            document.getElementById('modalMetodoPago').textContent = subscription.metodo_pago || 'No especificado';
            document.getElementById('modalTransactionId').textContent = subscription.transaction_id || 'N/A';
            document.getElementById('modalPlanDescripcion').textContent = subscription.plan_descripcion || 'Disfruta de todos los beneficios de tu suscripción activa.';
            
            // Actualizar badge de estado
            const statusBadge = document.getElementById('modalStatus');
            const statusColors = {
                0: { bg: 'bg-yellow-500/20', text: 'text-yellow-400', border: 'border-yellow-500/30', label: 'Pendiente' },
                1: { bg: 'bg-green-500/20', text: 'text-green-400', border: 'border-green-500/30', label: 'Activa' },
                2: { bg: 'bg-red-500/20', text: 'text-red-400', border: 'border-red-500/30', label: 'Cancelada' }
            };
            const status = statusColors[subscription.status] || statusColors[0];
            statusBadge.className = `inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mt-1 border ${status.bg} ${status.text} ${status.border}`;
            statusBadge.textContent = status.label;
            
            // Mostrar modal con animación
            const modal = document.getElementById('subscriptionModal');
            modal.classList.remove('hidden');
            // Pequeño delay para permitir que la transición CSS funcione si se agrega opacidad
            document.body.style.overflow = 'hidden';
        }

        // Cerrar modal
        function closeSubscriptionModal() {
            document.getElementById('subscriptionModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentSubscriptionId = null;
        }

        // Cerrar modal al hacer click fuera
        document.getElementById('subscriptionModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeSubscriptionModal();
            }
        });

        // Cerrar modal con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('subscriptionModal').classList.contains('hidden')) {
                closeSubscriptionModal();
            }
        });

        // Descargar factura
        function downloadInvoice(subscriptionId) {
            alert('Descargando comprobante de la suscripción #' + subscriptionId + '\n\nEsta funcionalidad se conectará con el sistema de facturación.');
        }

        // Descargar factura desde el modal
        function downloadInvoiceFromModal() {
            if (currentSubscriptionId) {
                downloadInvoice(currentSubscriptionId);
            }
        }

        // Formatear fecha
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }
    </script>
</body>
</html>
