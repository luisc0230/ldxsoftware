<?php
/**
 * Políticas de Cambios y Devoluciones
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Políticas de Cambios y Devoluciones - LDX SOFTWARE</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gradient-to-b from-black via-gray-900 to-black min-h-screen">
    
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="<?php echo BASE_URL; ?>" class="inline-block mb-6">
                    <i class="fas fa-arrow-left text-gray-400 hover:text-white mr-2"></i>
                    <span class="text-gray-400 hover:text-white">Volver al inicio</span>
                </a>
                <h1 class="text-4xl font-bold text-white mb-2">
                    Políticas de Cambios y Devoluciones
                </h1>
                <p class="text-gray-400">Última actualización: <?php echo date('d/m/Y'); ?></p>
            </div>

            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 border border-gray-700 space-y-8">
                
                <!-- Sección 1 -->
                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-undo text-blue-400"></i>
                        1. Política de Devoluciones
                    </h2>
                    <div class="text-gray-300 space-y-3">
                        <p>En LDX SOFTWARE, nos comprometemos a ofrecer servicios de calidad. Si no está satisfecho con nuestro servicio, puede solicitar una devolución bajo las siguientes condiciones:</p>
                        
                        <h3 class="text-lg font-semibold text-white mt-4">1.1 Plazo para Solicitar Devolución</h3>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Tiene <strong>7 días calendario</strong> desde la fecha de contratación del servicio para solicitar una devolución.</li>
                            <li>Para suscripciones mensuales: devolución completa si no se ha iniciado el desarrollo.</li>
                            <li>Para suscripciones anuales: devolución proporcional según el tiempo no utilizado.</li>
                        </ul>

                        <h3 class="text-lg font-semibold text-white mt-4">1.2 Condiciones para Devolución</h3>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>El servicio no debe haber sido iniciado o debe estar en fase inicial (menos del 20% de avance).</li>
                            <li>No aplica devolución si el proyecto está en fase de desarrollo activo.</li>
                            <li>Los servicios personalizados ya entregados no son reembolsables.</li>
                        </ul>

                        <h3 class="text-lg font-semibold text-white mt-4">1.3 Proceso de Devolución</h3>
                        <ol class="list-decimal list-inside space-y-2 ml-4">
                            <li>Envíe un correo a <strong>contacto@ldxsoftware.com.pe</strong> con el asunto "Solicitud de Devolución".</li>
                            <li>Incluya su número de orden, motivo de la devolución y datos de contacto.</li>
                            <li>Nuestro equipo evaluará su solicitud en un plazo de 3 días hábiles.</li>
                            <li>Si es aprobada, el reembolso se procesará en 15 días hábiles.</li>
                        </ol>
                    </div>
                </div>

                <!-- Sección 2 -->
                <div class="border-t border-gray-700 pt-8">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-exchange-alt text-purple-400"></i>
                        2. Política de Cambios
                    </h2>
                    <div class="text-gray-300 space-y-3">
                        <h3 class="text-lg font-semibold text-white">2.1 Cambio de Plan</h3>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Puede cambiar de plan en cualquier momento.</li>
                            <li><strong>Upgrade:</strong> Paga la diferencia prorrateada del mes actual.</li>
                            <li><strong>Downgrade:</strong> El cambio se aplica en el siguiente ciclo de facturación.</li>
                        </ul>

                        <h3 class="text-lg font-semibold text-white mt-4">2.2 Modificaciones al Proyecto</h3>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Cambios menores: Incluidos en el plan (hasta 2 revisiones).</li>
                            <li>Cambios mayores: Pueden generar costos adicionales.</li>
                            <li>Cambios de alcance: Requieren nueva cotización.</li>
                        </ul>
                    </div>
                </div>

                <!-- Sección 3 -->
                <div class="border-t border-gray-700 pt-8">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-ban text-red-400"></i>
                        3. Cancelación de Suscripción
                    </h2>
                    <div class="text-gray-300 space-y-3">
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Puede cancelar su suscripción en cualquier momento desde su panel de usuario.</li>
                            <li>La cancelación será efectiva al final del período de facturación actual.</li>
                            <li>No se realizan reembolsos por cancelaciones anticipadas de suscripciones anuales.</li>
                            <li>Conservará acceso a los servicios hasta el final del período pagado.</li>
                        </ul>
                    </div>
                </div>

                <!-- Sección 4 -->
                <div class="border-t border-gray-700 pt-8">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-green-400"></i>
                        4. Métodos de Reembolso
                    </h2>
                    <div class="text-gray-300 space-y-3">
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Los reembolsos se procesan al mismo método de pago utilizado en la compra.</li>
                            <li>Tarjeta de crédito/débito: 10-15 días hábiles.</li>
                            <li>Transferencia bancaria: 5-7 días hábiles (requiere datos bancarios).</li>
                        </ul>
                    </div>
                </div>

                <!-- Sección 5 -->
                <div class="border-t border-gray-700 pt-8">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        5. Excepciones
                    </h2>
                    <div class="text-gray-300 space-y-3">
                        <p>No se aceptan devoluciones en los siguientes casos:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Servicios completamente entregados y aceptados por el cliente.</li>
                            <li>Proyectos personalizados con más del 50% de avance.</li>
                            <li>Servicios de consultoría ya prestados.</li>
                            <li>Dominios y hosting contratados (sujetos a políticas del proveedor).</li>
                        </ul>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="border-t border-gray-700 pt-8">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-envelope text-blue-400"></i>
                        6. Contacto
                    </h2>
                    <div class="text-gray-300 space-y-3">
                        <p>Para solicitudes de devolución o cambios, contáctenos:</p>
                        <ul class="space-y-2">
                            <li><strong>Email:</strong> contacto@ldxsoftware.com.pe</li>
                            <li><strong>Teléfono:</strong> +51 XXX XXX XXX</li>
                            <li><strong>Horario:</strong> Lunes a Viernes, 9:00 AM - 6:00 PM</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Botón volver -->
            <div class="mt-8 text-center">
                <a href="<?php echo BASE_URL; ?>" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300">
                    <i class="fas fa-home"></i>
                    Volver al Inicio
                </a>
            </div>
        </div>
    </div>
</body>
</html>
