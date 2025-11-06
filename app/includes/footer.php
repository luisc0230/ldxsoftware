<!-- Footer -->
<footer class="text-white py-16 relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
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
               class="text-gray-400 hover:text-white transition-colors duration-300">
                <i class="fab fa-instagram text-3xl"></i>
            </a>
            <a href="https://facebook.com/ldxsoftware" 
               target="_blank" 
               class="text-gray-400 hover:text-white transition-colors duration-300">
                <i class="fab fa-facebook text-3xl"></i>
            </a>
        </div>
        
        <!-- Links -->
        <div class="flex justify-center space-x-12 mb-8 text-gray-400">
            <a href="<?php echo url('terminos'); ?>" class="hover:text-white transition-colors duration-300">
                Términos y Condiciones
            </a>
            <a href="<?php echo url('privacidad'); ?>" class="hover:text-white transition-colors duration-300">
                Política de Privacidad
            </a>
        </div>
        
        <!-- Copyright -->
        <div class="text-gray-500 text-sm">
            Copyright © <?php echo date('Y'); ?> LDX Software - Todos los derechos reservados
        </div>
    </div>
</footer>
