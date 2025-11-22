<?php
// Debug: Activar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener planes de la base de datos
require_once __DIR__ . '/../../app/models/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Verificar si existen las nuevas columnas
    $checkColumns = $db->query("SHOW COLUMNS FROM planes LIKE 'precio_trimestral'");
    $hasNewColumns = $checkColumns->rowCount() > 0;
    
    if ($hasNewColumns) {
        // Solo mostrar los planes nuevos de la academia (IDs 4-7)
        $stmt = $db->query("SELECT * FROM planes WHERE estado = 'activo' AND id >= 4 ORDER BY orden ASC");
    } else {
        $stmt = $db->query("SELECT * FROM planes WHERE estado = 'activo' ORDER BY id ASC");
    }
    
    $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Debug: Verificar cuántos planes se obtuvieron
    error_log("Planes obtenidos: " . count($planes));
    
    // Si no hay nuevas columnas, agregar valores por defecto
    if (!$hasNewColumns && !empty($planes)) {
        foreach ($planes as &$plan) {
            $plan['precio_trimestral'] = 0;
            $plan['precio_lifetime'] = 0;
            $plan['descuento_porcentaje'] = 50; // Descuento por defecto
            $plan['es_recomendado'] = ($plan['id'] == 2) ? 1 : 0; // El segundo plan es recomendado
            $plan['orden'] = $plan['id'];
        }
    }
} catch (Exception $e) {
    error_log("Error al cargar planes: " . $e->getMessage());
    $planes = [];
}
?>

<!-- Sección de Suscripciones -->
<section id="suscripciones" class="py-20 bg-gradient-to-b from-black via-gray-900 to-black relative overflow-hidden">
    <div class="container mx-auto px-4">
        
        <!-- Banner de Descuento -->
        <div class="max-w-4xl mx-auto mb-12">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <span class="inline-block bg-yellow-400 text-black text-sm font-bold px-4 py-1 rounded-full mb-4">
                        EXCLUSIVO
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">
                        Disfruta de un 50% de descuento
                    </h2>
                    <p class="text-white/90 text-lg">
                        por tiempo limitado en tu primera suscripción
                    </p>
                </div>
            </div>
        </div>

        <!-- Planes de Suscripción -->
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <?php foreach ($planes as $plan): ?>
                    <?php
                    // Determinar el precio y tipo
                    $precioOriginal = 0;
                    $precioFinal = 0;
                    $tipoPlan = '';
                    $periodo = '';
                    $ahorro = 0;
                    
                    if ($plan['precio_mensual'] > 0) {
                        $precioOriginal = $plan['precio_mensual'];
                        $tipoPlan = 'mensual';
                        $periodo = '/mes';
                    } elseif ($plan['precio_trimestral'] > 0) {
                        $precioOriginal = 30; // Precio original antes del descuento
                        $precioFinal = $plan['precio_trimestral'];
                        $tipoPlan = 'trimestral';
                        $periodo = '/trimestre';
                        $ahorro = $precioOriginal - $precioFinal;
                    } elseif ($plan['precio_anual'] > 0) {
                        $precioOriginal = 120; // Precio original antes del descuento
                        $precioFinal = $plan['precio_anual'];
                        $tipoPlan = 'anual';
                        $periodo = '/año';
                        $ahorro = $precioOriginal - $precioFinal;
                    } elseif ($plan['precio_lifetime'] > 0) {
                        $precioFinal = $plan['precio_lifetime'];
                        $tipoPlan = 'lifetime';
                        $periodo = '';
                    }
                    
                    // Aplicar descuento si existe
                    if ($plan['descuento_porcentaje'] > 0 && $precioOriginal > 0) {
                        $precioFinal = $precioOriginal * (1 - $plan['descuento_porcentaje'] / 100);
                    } elseif ($precioFinal == 0) {
                        $precioFinal = $precioOriginal;
                    }
                    
                    // Obtener características
                    $caracteristicas = explode('|', $plan['caracteristicas'] ?? '');
                    ?>
                    
                    <div class="relative <?php echo $plan['es_recomendado'] ? 'lg:-mt-4' : ''; ?>">
                        <!-- Badge de Recomendado -->
                        <?php if ($plan['es_recomendado']): ?>
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                <span class="bg-blue-500 text-white text-xs font-bold px-4 py-1 rounded-full">
                                    Recomendado
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Card del Plan -->
                        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 border <?php echo $plan['es_recomendado'] ? 'border-blue-500 shadow-lg shadow-blue-500/20' : 'border-gray-700'; ?> h-full flex flex-col relative overflow-hidden">
                            
                            <!-- Badge de Descuento -->
                            <?php if ($plan['descuento_porcentaje'] > 0): ?>
                                <div class="absolute top-4 right-4">
                                    <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        -<?php echo $plan['descuento_porcentaje']; ?>% descuento
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Nombre del Plan -->
                            <h3 class="text-xl font-bold text-white mb-2 mt-2">
                                <?php echo htmlspecialchars($plan['nombre']); ?>
                            </h3>
                            
                            <!-- Precio -->
                            <div class="mb-4">
                                <?php if ($ahorro > 0): ?>
                                    <p class="text-gray-400 text-sm line-through">
                                        Antes S/<?php echo number_format($precioOriginal, 2); ?>
                                    </p>
                                <?php endif; ?>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-bold text-white">
                                        S/<?php echo number_format($precioFinal, 2); ?>
                                    </span>
                                    <span class="text-gray-400">
                                        <?php echo $periodo; ?>
                                    </span>
                                </div>
                                <?php if ($ahorro > 0): ?>
                                    <p class="text-green-400 text-sm font-semibold mt-1">
                                        ¡Ahorras S/<?php echo number_format($ahorro, 2); ?>!
                                    </p>
                                <?php endif; ?>
                                <?php if ($tipoPlan === 'mensual'): ?>
                                    <p class="text-gray-400 text-sm mt-1">
                                        Cobro mensual recurrente
                                    </p>
                                <?php elseif ($tipoPlan === 'lifetime'): ?>
                                    <p class="text-gray-400 text-sm mt-1">
                                        <?php echo $plan['descripcion']; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Características -->
                            <ul class="space-y-3 mb-6 flex-grow">
                                <?php foreach ($caracteristicas as $caracteristica): ?>
                                    <?php if (trim($caracteristica)): ?>
                                        <li class="flex items-start gap-2 text-gray-300">
                                            <i class="fas fa-check text-green-400 mt-1 flex-shrink-0"></i>
                                            <span class="text-sm"><?php echo htmlspecialchars(trim($caracteristica)); ?></span>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                            
                            <!-- Botón -->
                            <button 
                                onclick="iniciarSuscripcion(<?php echo $plan['id']; ?>, <?php echo $precioFinal; ?>, '<?php echo $tipoPlan; ?>')"
                                class="w-full <?php echo $plan['es_recomendado'] ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-700 hover:bg-gray-600'; ?> text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                                Comenzar ahora
                            </button>
                            
                            <p class="text-xs text-gray-500 text-center mt-3">
                                Puedes cancelar tu suscripción en cualquier momento
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
                
            </div>
        </div>

        <!-- Tabla Comparativa -->
        <div class="max-w-6xl mx-auto mt-20">
            <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">
                Compara los planes
            </h2>
            <p class="text-gray-400 text-center mb-8">
                Descubre el plan que mejor se adapte a tus necesidades
            </p>
            
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left p-4 text-white font-semibold">Características</th>
                                <?php foreach ($planes as $plan): ?>
                                    <th class="p-4 text-center">
                                        <div class="text-white font-semibold"><?php echo htmlspecialchars($plan['nombre']); ?></div>
                                        <div class="text-2xl font-bold text-blue-400 mt-1">
                                            S/<?php 
                                                if ($plan['precio_mensual'] > 0) {
                                                    echo number_format($plan['precio_mensual'] * 0.5, 2);
                                                } elseif ($plan['precio_trimestral'] > 0) {
                                                    echo number_format($plan['precio_trimestral'], 2);
                                                } elseif ($plan['precio_anual'] > 0) {
                                                    echo number_format($plan['precio_anual'], 2);
                                                } else {
                                                    echo number_format($plan['precio_lifetime'], 2);
                                                }
                                            ?>
                                        </div>
                                        <?php if ($plan['es_recomendado']): ?>
                                            <span class="inline-block bg-blue-500 text-white text-xs px-2 py-1 rounded-full mt-2">
                                                Recomendado
                                            </span>
                                        <?php endif; ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $todasCaracteristicas = [
                                'Acceso a cursos actuales',
                                'Proyectos prácticos',
                                'Soporte en WhatsApp',
                                'Soporte prioritario',
                                'Sorteos exclusivos',
                                'Mentorías grupales',
                                'Acceso prioritario a nuevos cursos',
                                'Comunidad exclusiva',
                                'Un pago para toda la vida'
                            ];
                            
                            foreach ($todasCaracteristicas as $caracteristica):
                            ?>
                                <tr class="border-b border-gray-700/50 hover:bg-gray-800/50 transition">
                                    <td class="p-4 text-gray-300"><?php echo $caracteristica; ?></td>
                                    <?php foreach ($planes as $index => $plan): ?>
                                        <td class="p-4 text-center">
                                            <?php
                                            $tieneCaracteristica = false;
                                            $caracteristicasPlan = explode('|', $plan['caracteristicas'] ?? '');
                                            
                                            // Lógica para determinar qué características tiene cada plan
                                            if ($index == 0) { // Mensual
                                                $tieneCaracteristica = in_array($caracteristica, ['Acceso a cursos actuales', 'Proyectos prácticos', 'Soporte en WhatsApp']);
                                            } elseif ($index == 1) { // Trimestral
                                                $tieneCaracteristica = in_array($caracteristica, ['Acceso a cursos actuales', 'Proyectos prácticos', 'Soporte en WhatsApp', 'Soporte prioritario', 'Sorteos exclusivos']);
                                            } elseif ($index == 2) { // Anual
                                                $tieneCaracteristica = !in_array($caracteristica, ['Comunidad exclusiva', 'Un pago para toda la vida']);
                                            } else { // Lifetime
                                                $tieneCaracteristica = true;
                                            }
                                            ?>
                                            <?php if ($tieneCaracteristica): ?>
                                                <i class="fas fa-check text-green-400 text-xl"></i>
                                            <?php else: ?>
                                                <span class="text-gray-600">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ventajas de la Academia -->
        <div class="max-w-6xl mx-auto mt-20">
            <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">
                Disfruta de las ventajas de formar parte de la academia
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Academia -->
                <div class="bg-gradient-to-br from-blue-900/50 to-blue-800/30 rounded-2xl p-8 border border-blue-700/50">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Academia</h3>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Acceso a cursos, retos, proyectos, certificados y mucho más.
                    </p>
                    <div class="bg-gray-900/50 rounded-lg p-4 space-y-2">
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <i class="fas fa-play text-blue-400"></i>
                            <span>Duración: Contenido actualizado constantemente</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <i class="fas fa-language text-blue-400"></i>
                            <span>Lenguaje: Español</span>
                        </div>
                    </div>
                </div>
                
                <!-- WhatsApp -->
                <div class="bg-gradient-to-br from-green-900/50 to-green-800/30 rounded-2xl p-8 border border-green-700/50">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fab fa-whatsapp text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">WhatsApp</h3>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Espacio exclusivo con soporte personalizado y contenido valioso.
                    </p>
                    <div class="bg-gray-900/50 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                            <span class="text-sm text-gray-400">estudiantes-academia</span>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                            <span class="text-sm text-gray-400">prioritario</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-purple-400 rounded-full"></div>
                            <span class="text-sm text-gray-400">mentoria-grupal-mensual</span>
                        </div>
                    </div>
                </div>
                
                <!-- Comunidad -->
                <div class="bg-gradient-to-br from-purple-900/50 to-purple-800/30 rounded-2xl p-8 border border-purple-700/50">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Comunidad</h3>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Ventajas exclusivas, networking y oportunidades de crecimiento.
                    </p>
                    <div class="aspect-video bg-gray-900/50 rounded-lg overflow-hidden">
                        <img src="<?php echo asset('images/community-preview.jpg'); ?>" 
                             alt="Comunidad LDX" 
                             class="w-full h-full object-cover"
                             onerror="this.src='<?php echo asset('images/logo.png'); ?>'">
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<script>
function iniciarSuscripcion(planId, precio, tipo) {
    // Verificar si el usuario está autenticado
    <?php if (!isset($_SESSION['user_id'])): ?>
        alert('Debes iniciar sesión para suscribirte');
        window.location.href = '<?php echo url('login'); ?>';
        return;
    <?php endif; ?>
    
    // Redirigir al checkout
    window.location.href = `<?php echo url('checkout'); ?>?plan=${planId}&tipo=${tipo}&precio=${precio}`;
}
</script>
