<!-- Footer -->
<footer class="text-white py-16 relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        
        <!-- Libro de Reclamaciones - DESTACADO -->
        <div class="mb-12 max-w-md mx-auto">
            <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 border-2 border-yellow-500 rounded-2xl p-6 shadow-2xl shadow-yellow-500/20 hover:shadow-yellow-500/40 transition-all duration-300">
                <div class="text-center mb-4">
                    <i class="fas fa-book text-yellow-400 text-5xl mb-3 animate-pulse"></i>
                    <h3 class="font-bold text-white text-2xl mb-2">Libro de Reclamaciones</h3>
                    <p class="text-gray-300 text-sm">
                        Conforme a la Ley N° 29571 - Código de Protección del Consumidor
                    </p>
                </div>
                <a href="<?php echo url('libro-reclamaciones'); ?>" 
                   class="block w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-edit mr-2"></i>
                    Presentar Reclamo o Queja
                </a>
            </div>
        </div>
        
        <!-- Logo -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-3 mb-2">
                <img src="<?php echo asset('images/logo.png'); ?>" alt="LDX Software" class="h-12 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <span class="font-bold text-2xl text-white" style="display: none;">LDX Software</span>
            </div>
        </div>
        
        <!-- Social Icons -->
        <div class="flex justify-center space-x-8 mb-8">
            <a href="https://instagram.com/ldxsoftware" 
               target="_blank" 
               rel="noopener noreferrer"
               class="text-gray-400 hover:text-pink-400 transition-colors duration-300"
               title="Síguenos en Instagram">
                <i class="fab fa-instagram text-3xl"></i>
            </a>
            <a href="https://facebook.com/ldxsoftware" 
               target="_blank" 
               rel="noopener noreferrer"
               class="text-gray-400 hover:text-blue-400 transition-colors duration-300"
               title="Síguenos en Facebook">
                <i class="fab fa-facebook text-3xl"></i>
            </a>
            <a href="https://linkedin.com/company/ldxsoftware" 
               target="_blank" 
               rel="noopener noreferrer"
               class="text-gray-400 hover:text-blue-500 transition-colors duration-300"
               title="Síguenos en LinkedIn">
                <i class="fab fa-linkedin text-3xl"></i>
            </a>
        </div>
        
        <!-- Links -->
        <div class="flex flex-wrap justify-center gap-6 mb-8 text-gray-400">
            <a href="<?php echo url('terminos'); ?>" class="hover:text-white transition-colors duration-300">
                Términos y Condiciones
            </a>
            <a href="<?php echo url('privacidad'); ?>" class="hover:text-white transition-colors duration-300">
                Política de Privacidad
            </a>
            <a href="<?php echo url('politicas-devolucion'); ?>" class="hover:text-white transition-colors duration-300">
                Políticas de Devolución
            </a>
        </div>
        
        <!-- Copyright -->
        <div class="text-gray-500 text-sm">
            Copyright © <?php echo date('Y'); ?> LDX Software - Todos los derechos reservados
        </div>
    </div>
</footer>
