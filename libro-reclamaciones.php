<?php
/**
 * Libro de Reclamaciones - Cumple con requisitos de INDECOPI
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libro de Reclamaciones - LDX SOFTWARE</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gradient-to-b from-black via-gray-900 to-black min-h-screen">
    
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="<?php echo BASE_URL; ?>" class="inline-block mb-6">
                    <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="LDX Software" class="h-12 mx-auto" onerror="this.style.display='none'">
                </a>
                <h1 class="text-4xl font-bold text-white mb-2">
                    <i class="fas fa-book text-blue-400 mr-3"></i>
                    Libro de Reclamaciones
                </h1>
                <p class="text-gray-400">Conforme a la Ley N° 29571 - Código de Protección y Defensa del Consumidor</p>
            </div>

            <!-- Información de la empresa -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 border border-gray-700 mb-8">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-building text-blue-400"></i>
                    Datos de la Empresa
                </h2>
                <div class="grid md:grid-cols-2 gap-4 text-gray-300">
                    <div>
                        <p class="font-semibold text-white mb-1">Razón Social:</p>
                        <p>LDX SOFTWARE</p>
                    </div>
                    <div>
                        <p class="font-semibold text-white mb-1">RUC:</p>
                        <p>20XXXXXXXXX</p>
                    </div>
                    <div>
                        <p class="font-semibold text-white mb-1">Dirección:</p>
                        <p>Lima, Perú</p>
                    </div>
                    <div>
                        <p class="font-semibold text-white mb-1">Teléfono:</p>
                        <p>+51 XXX XXX XXX</p>
                    </div>
                    <div>
                        <p class="font-semibold text-white mb-1">Email:</p>
                        <p>contacto@ldxsoftware.com.pe</p>
                    </div>
                    <div>
                        <p class="font-semibold text-white mb-1">Sitio Web:</p>
                        <p>www.ldxsoftware.com.pe</p>
                    </div>
                </div>
            </div>

            <!-- Formulario de Reclamación -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 border border-gray-700">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-edit text-purple-400"></i>
                    Formulario de Reclamación
                </h2>

                <form id="formReclamacion" class="space-y-6">
                    
                    <!-- Tipo de documento -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white font-semibold mb-2">
                                Tipo de Solicitud <span class="text-red-400">*</span>
                            </label>
                            <select name="tipo_solicitud" required class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                                <option value="">Seleccione...</option>
                                <option value="reclamo">Reclamo</option>
                                <option value="queja">Queja</option>
                            </select>
                            <p class="text-gray-400 text-xs mt-1">
                                <strong>Reclamo:</strong> Disconformidad relacionada al servicio<br>
                                <strong>Queja:</strong> Disconformidad no relacionada al servicio
                            </p>
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">
                                Fecha <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="fecha" required 
                                   value="<?php echo date('Y-m-d'); ?>"
                                   class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- Datos del consumidor -->
                    <div class="border-t border-gray-700 pt-6">
                        <h3 class="text-xl font-bold text-white mb-4">Datos del Consumidor</h3>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-white font-semibold mb-2">
                                    Nombres y Apellidos <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="nombre_completo" required 
                                       class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">
                                    Tipo de Documento <span class="text-red-400">*</span>
                                </label>
                                <select name="tipo_documento" required class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                                    <option value="">Seleccione...</option>
                                    <option value="DNI">DNI</option>
                                    <option value="CE">Carnet de Extranjería</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                    <option value="RUC">RUC</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">
                                    Número de Documento <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="numero_documento" required 
                                       class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">
                                    Teléfono <span class="text-red-400">*</span>
                                </label>
                                <input type="tel" name="telefono" required 
                                       class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">
                                    Email <span class="text-red-400">*</span>
                                </label>
                                <input type="email" name="email" required 
                                       class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">
                                    Dirección
                                </label>
                                <input type="text" name="direccion" 
                                       class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Detalle de la reclamación -->
                    <div class="border-t border-gray-700 pt-6">
                        <h3 class="text-xl font-bold text-white mb-4">Detalle de la Reclamación/Queja</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-white font-semibold mb-2">
                                    Servicio Contratado <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="servicio" required 
                                       placeholder="Ej: Plan Profesional - Desarrollo Web"
                                       class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">
                                    Monto Reclamado (S/)
                                </label>
                                <input type="number" name="monto" step="0.01" 
                                       class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">
                                    Descripción del Reclamo/Queja <span class="text-red-400">*</span>
                                </label>
                                <textarea name="descripcion" required rows="6"
                                          placeholder="Describa detalladamente su reclamo o queja..."
                                          class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">
                                    Pedido del Consumidor <span class="text-red-400">*</span>
                                </label>
                                <textarea name="pedido" required rows="4"
                                          placeholder="¿Qué solicita como solución?"
                                          class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4 pt-6">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar Reclamación
                        </button>
                        <a href="<?php echo BASE_URL; ?>" 
                           class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 text-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Información adicional -->
            <div class="mt-8 bg-blue-500/10 border border-blue-500/30 rounded-xl p-6">
                <h3 class="text-lg font-bold text-white mb-3">
                    <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                    Información Importante
                </h3>
                <ul class="text-gray-300 space-y-2 text-sm">
                    <li>• La empresa debe dar respuesta en un plazo no mayor a 30 días calendario.</li>
                    <li>• Recibirá una copia de su reclamo al email proporcionado.</li>
                    <li>• Puede hacer seguimiento de su reclamo contactándonos al email: contacto@ldxsoftware.com.pe</li>
                    <li>• Si no está conforme con la respuesta, puede acudir a INDECOPI.</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('formReclamacion').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch('<?php echo BASE_URL; ?>api/libro-reclamaciones.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('✅ Su reclamación ha sido registrada exitosamente.\n\nNúmero de registro: ' + result.numero_registro + '\n\nRecibirá una copia en su email.');
                    this.reset();
                    window.location.href = '<?php echo BASE_URL; ?>';
                } else {
                    alert('❌ Error al enviar la reclamación: ' + result.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ Error al enviar la reclamación. Por favor, intente nuevamente.');
            }
        });
    </script>
</body>
</html>
