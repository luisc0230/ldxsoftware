<!-- 404 Error Page -->
<section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        
        <!-- Error Illustration -->
        <div class="mb-12" data-aos="fade-down">
            <div class="relative">
                <!-- Large 404 Text -->
                <div class="text-[200px] md:text-[300px] font-bold text-white/10 leading-none select-none">
                    404
                </div>
                
                <!-- Floating Elements -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <div class="w-32 h-32 bg-accent-500/20 rounded-full blur-xl animate-pulse"></div>
                </div>
                
                <!-- Icon -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <div class="w-24 h-24 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-white text-4xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Error Content -->
        <div class="max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
            <h1 class="font-heading text-4xl md:text-5xl font-bold text-white mb-6">
                <?php echo escape($error_title); ?>
            </h1>
            
            <p class="text-xl text-gray-200 mb-8 leading-relaxed">
                <?php echo escape($error_message); ?>
            </p>
            
            <!-- Suggestions -->
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 mb-8 text-left">
                <h3 class="font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-lightbulb text-accent-400 mr-2"></i>
                    Sugerencias:
                </h3>
                <ul class="space-y-2 text-gray-200">
                    <?php foreach ($suggestions as $suggestion): ?>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent-400 mr-3 mt-1 text-sm"></i>
                            <?php echo escape($suggestion); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="<?php echo url(); ?>" 
                   class="btn-primary text-white px-8 py-4 rounded-xl text-lg font-semibold hover-lift inline-flex items-center space-x-2">
                    <i class="fas fa-home"></i>
                    <span>Ir al Inicio</span>
                </a>
                
                <button onclick="history.back()" 
                        class="bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition-all duration-300 inline-flex items-center space-x-2 border border-white/20">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver Atrás</span>
                </button>
            </div>
            
            <!-- Search Box -->
            <div class="mt-12">
                <div class="max-w-md mx-auto">
                    <label class="block text-white mb-3 font-medium">
                        ¿Buscas algo específico?
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="search-input"
                               placeholder="Buscar en el sitio..."
                               class="w-full px-4 py-3 pr-12 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-gray-300 focus:outline-none focus:border-accent-400 focus:ring-2 focus:ring-accent-400/20">
                        <button onclick="performSearch()" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Popular Links -->
        <div class="mt-16 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="400">
            <h3 class="text-white font-semibold mb-6">Enlaces Populares</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="<?php echo url('services'); ?>" 
                   class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-lg p-4 text-center text-white hover:bg-white/10 transition-all duration-200 group">
                    <i class="fas fa-cogs text-2xl mb-2 text-accent-400 group-hover:scale-110 transition-transform duration-200"></i>
                    <div class="text-sm font-medium">Servicios</div>
                </a>
                
                <a href="<?php echo url('portfolio'); ?>" 
                   class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-lg p-4 text-center text-white hover:bg-white/10 transition-all duration-200 group">
                    <i class="fas fa-briefcase text-2xl mb-2 text-accent-400 group-hover:scale-110 transition-transform duration-200"></i>
                    <div class="text-sm font-medium">Portfolio</div>
                </a>
                
                <a href="<?php echo url('about'); ?>" 
                   class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-lg p-4 text-center text-white hover:bg-white/10 transition-all duration-200 group">
                    <i class="fas fa-users text-2xl mb-2 text-accent-400 group-hover:scale-110 transition-transform duration-200"></i>
                    <div class="text-sm font-medium">Nosotros</div>
                </a>
                
                <a href="<?php echo url('contact'); ?>" 
                   class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-lg p-4 text-center text-white hover:bg-white/10 transition-all duration-200 group">
                    <i class="fas fa-envelope text-2xl mb-2 text-accent-400 group-hover:scale-110 transition-transform duration-200"></i>
                    <div class="text-sm font-medium">Contacto</div>
                </a>
            </div>
        </div>
        
        <!-- Contact Support -->
        <div class="mt-12 text-center" data-aos="fade-up" data-aos-delay="600">
            <p class="text-gray-300 mb-4">
                ¿Sigues teniendo problemas? Nuestro equipo está aquí para ayudarte.
            </p>
            <a href="<?php echo url('contact'); ?>" 
               class="text-accent-400 hover:text-accent-300 font-medium inline-flex items-center space-x-2">
                <i class="fas fa-life-ring"></i>
                <span>Contactar Soporte</span>
            </a>
        </div>
    </div>
    
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-primary-500/5 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-accent-500/5 rounded-full blur-3xl animate-float" style="animation-delay: -2s;"></div>
        <div class="absolute top-1/2 left-1/4 w-48 h-48 bg-primary-400/5 rounded-full blur-2xl animate-float" style="animation-delay: -1s;"></div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('search-input');
    
    // Handle Enter key in search
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    // Auto-focus search input after page load
    setTimeout(() => {
        searchInput.focus();
    }, 1000);
});

function performSearch() {
    const query = document.getElementById('search-input').value.trim();
    
    if (query) {
        // Redirect to search results or homepage with query
        window.location.href = `<?php echo url(); ?>?search=${encodeURIComponent(query)}`;
    }
}

// Add some interactive effects
document.addEventListener('mousemove', function(e) {
    const cursor = document.querySelector('.cursor-effect');
    if (!cursor) {
        const cursorDiv = document.createElement('div');
        cursorDiv.className = 'cursor-effect fixed w-4 h-4 bg-accent-400/30 rounded-full pointer-events-none z-50 transition-transform duration-100';
        document.body.appendChild(cursorDiv);
    }
    
    const cursorElement = document.querySelector('.cursor-effect');
    if (cursorElement) {
        cursorElement.style.left = e.clientX - 8 + 'px';
        cursorElement.style.top = e.clientY - 8 + 'px';
    }
});

// Easter egg: Konami code
let konamiCode = [];
const konamiSequence = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'KeyB', 'KeyA'];

document.addEventListener('keydown', function(e) {
    konamiCode.push(e.code);
    
    if (konamiCode.length > konamiSequence.length) {
        konamiCode.shift();
    }
    
    if (JSON.stringify(konamiCode) === JSON.stringify(konamiSequence)) {
        // Easter egg activated
        showNotification('🎉 ¡Código Konami activado! Has desbloqueado el modo desarrollador.', 'success');
        document.body.classList.add('konami-mode');
        
        // Add some fun effects
        const style = document.createElement('style');
        style.textContent = `
            .konami-mode * {
                animation: rainbow 2s linear infinite !important;
            }
            @keyframes rainbow {
                0% { filter: hue-rotate(0deg); }
                100% { filter: hue-rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
        
        setTimeout(() => {
            document.body.classList.remove('konami-mode');
            style.remove();
        }, 10000);
        
        konamiCode = [];
    }
});
</script>
