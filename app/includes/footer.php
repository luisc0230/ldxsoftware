<!-- Footer -->
<footer class="text-white py-16 relative overflow-hidden bg-gradient-to-b from-gray-900 to-black">
    <div class="max-w-6xl mx-auto px-4 relative z-10">
        
        <!-- Main Footer Content -->
        <div class="grid md:grid-cols-4 gap-8 mb-12">
            
            <!-- Columna 1: Logo y Descripción -->
            <div class="md:col-span-1">
                <div class="flex items-center space-x-3 mb-4">
                    <img src="<?php echo asset('images/logo.png'); ?>" alt="LDX Software" class="h-10 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <span class="font-bold text-xl text-white" style="display: none;">LDX Software</span>
                </div>
                <p class="text-gray-400 text-sm mb-4">
                    Soluciones de software innovadoras y personalizadas para tu negocio.
                </p>
                <!-- Redes Sociales -->
                <div class="flex space-x-4">
                    <a href="https://instagram.com/ldxsoftware" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="text-gray-400 hover:text-pink-400 transition-colors duration-300"
                       title="Síguenos en Instagram">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                    <a href="https://facebook.com/ldxsoftware" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="text-gray-400 hover:text-blue-400 transition-colors duration-300"
                       title="Síguenos en Facebook">
                        <i class="fab fa-facebook text-2xl"></i>
                    </a>
                    <a href="https://linkedin.com/company/ldxsoftware" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="text-gray-400 hover:text-blue-500 transition-colors duration-300"
                       title="Síguenos en LinkedIn">
                        <i class="fab fa-linkedin text-2xl"></i>
                    </a>
                </div>
            </div>
            
            <!-- Columna 2: Enlaces Legales -->
            <div>
                <h3 class="font-bold text-white mb-4 text-lg">Información Legal</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li>
                        <a href="<?php echo url('terminos'); ?>" class="hover:text-white transition-colors duration-300 flex items-center gap-2">
                            <i class="fas fa-file-contract text-blue-400"></i>
                            Términos y Condiciones
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo url('privacidad'); ?>" class="hover:text-white transition-colors duration-300 flex items-center gap-2">
                            <i class="fas fa-shield-alt text-green-400"></i>
                            Política de Privacidad
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo url('politicas-devolucion'); ?>" class="hover:text-white transition-colors duration-300 flex items-center gap-2">
                            <i class="fas fa-undo text-purple-400"></i>
                            Políticas de Cambios y Devoluciones
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo url('libro-reclamaciones'); ?>" class="hover:text-white transition-colors duration-300 flex items-center gap-2">
                            <i class="fas fa-book text-yellow-400"></i>
                            Libro de Reclamaciones
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Columna 3: Contacto -->
            <div>
                <h3 class="font-bold text-white mb-4 text-lg">Contacto</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-envelope text-blue-400 mt-1"></i>
                        <a href="mailto:contacto@ldxsoftware.com.pe" class="hover:text-white transition-colors">
                            contacto@ldxsoftware.com.pe
                        </a>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-phone text-green-400 mt-1"></i>
                        <span>+51 XXX XXX XXX</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-red-400 mt-1"></i>
                        <span>Lima, Perú</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-clock text-yellow-400 mt-1"></i>
                        <span>Lun - Vie: 9:00 AM - 6:00 PM</span>
                    </li>
                </ul>
            </div>
            
            <!-- Columna 4: Libro de Reclamaciones Destacado -->
            <div>
                <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 border-2 border-yellow-500/50 rounded-xl p-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-book text-yellow-400 text-4xl mb-2"></i>
                        <h3 class="font-bold text-white text-lg">Libro de Reclamaciones</h3>
                    </div>
                    <p class="text-gray-300 text-xs mb-3 text-center">
                        Conforme a la Ley N° 29571
                    </p>
                    <a href="<?php echo url('libro-reclamaciones'); ?>" 
                       class="block w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded-lg transition-all duration-300 text-center text-sm">
                        <i class="fas fa-edit mr-1"></i>
                        Presentar Reclamo
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Divider -->
        <div class="border-t border-gray-800 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-gray-500 text-sm">
                <div>
                    Copyright © <?php echo date('Y'); ?> LDX Software - Todos los derechos reservados
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-shield-alt text-green-400"></i>
                    <span>Pagos seguros procesados por</span>
                    <img src="https://culqi.com/LogoCulqi.png" alt="Culqi" class="h-4" onerror="this.style.display='none'">
                    <span class="font-semibold">Culqi</span>
                </div>
            </div>
        </div>
    </div>
</footer>
