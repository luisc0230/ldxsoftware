<!-- Suscripciones Section -->
<section id="suscripciones" class="py-20 bg-gradient-to-b from-black via-gray-900 to-black">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Planes de <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-600">Suscripción</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                Elige el plan perfecto para tu negocio y comienza a transformar tus ideas en realidad
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            
            <!-- Plan Básico -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 border border-gray-700 hover:border-blue-500 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/20">
                <div class="text-center mb-6">
                    <div class="inline-block p-3 bg-blue-500/10 rounded-full mb-4">
                        <i class="fas fa-rocket text-4xl text-blue-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Plan Básico</h3>
                    <p class="text-gray-400">Perfecto para empezar</p>
                </div>
                
                <div class="text-center mb-8">
                    <div class="flex items-baseline justify-center">
                        <span class="text-gray-400 text-xl">S/</span>
                        <span class="text-5xl font-bold text-white">99</span>
                        <span class="text-gray-400 ml-2">/mes</span>
                    </div>
                </div>

                <ul class="space-y-4 mb-8">
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Sitio web responsive</span>
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Hasta 5 páginas</span>
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Hosting incluido</span>
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Soporte por email</span>
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>SSL gratuito</span>
                    </li>
                </ul>

                <button onclick="iniciarSuscripcion('basico', 99)" 
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105">
                    Suscribirse Ahora
                </button>
            </div>

            <!-- Plan Profesional (Destacado) -->
            <div class="bg-gradient-to-br from-purple-900/50 to-blue-900/50 rounded-2xl p-8 border-2 border-purple-500 relative hover:scale-105 transition-all duration-300 hover:shadow-2xl hover:shadow-purple-500/30">
                <!-- Badge "Más Popular" -->
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-gradient-to-r from-purple-500 to-pink-500 text-white text-sm font-bold px-4 py-1 rounded-full">
                        MÁS POPULAR
                    </span>
                </div>

                <div class="text-center mb-6 mt-4">
                    <div class="inline-block p-3 bg-purple-500/10 rounded-full mb-4">
                        <i class="fas fa-star text-4xl text-purple-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Plan Profesional</h3>
                    <p class="text-gray-300">Para negocios en crecimiento</p>
                </div>
                
                <div class="text-center mb-8">
                    <div class="flex items-baseline justify-center">
                        <span class="text-gray-300 text-xl">S/</span>
                        <span class="text-5xl font-bold text-white">199</span>
                        <span class="text-gray-300 ml-2">/mes</span>
                    </div>
                </div>

                <ul class="space-y-4 mb-8">
                    <li class="flex items-center text-gray-200">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Todo del Plan Básico</span>
                    </li>
                    <li class="flex items-center text-gray-200">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Hasta 15 páginas</span>
                    </li>
                    <li class="flex items-center text-gray-200">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Sistema de blog</span>
                    </li>
                    <li class="flex items-center text-gray-200">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>SEO optimizado</span>
                    </li>
                    <li class="flex items-center text-gray-200">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Soporte prioritario</span>
                    </li>
                    <li class="flex items-center text-gray-200">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Analytics incluido</span>
                    </li>
                </ul>

                <button onclick="iniciarSuscripcion('profesional', 199)" 
                        class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/50">
                    Suscribirse Ahora
                </button>
            </div>

            <!-- Plan Empresarial -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 border border-gray-700 hover:border-yellow-500 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-yellow-500/20">
                <div class="text-center mb-6">
                    <div class="inline-block p-3 bg-yellow-500/10 rounded-full mb-4">
                        <i class="fas fa-crown text-4xl text-yellow-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Plan Empresarial</h3>
                    <p class="text-gray-400">Solución completa</p>
                </div>
                
                <div class="text-center mb-8">
                    <div class="flex items-baseline justify-center">
                        <span class="text-gray-400 text-xl">S/</span>
                        <span class="text-5xl font-bold text-white">399</span>
                        <span class="text-gray-400 ml-2">/mes</span>
                    </div>
                </div>

                <ul class="space-y-4 mb-8">
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Todo del Plan Profesional</span>
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Páginas ilimitadas</span>
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>E-commerce completo</span>
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>App móvil incluida</span>
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Soporte 24/7</span>
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Mantenimiento incluido</span>
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-3"></i>
                        <span>Consultoría mensual</span>
                    </li>
                </ul>

                <button onclick="iniciarSuscripcion('empresarial', 399)" 
                        class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105">
                    Suscribirse Ahora
                </button>
            </div>
        </div>

        <!-- Garantía -->
        <div class="text-center mt-12">
            <p class="text-gray-400 flex items-center justify-center gap-2">
                <i class="fas fa-shield-alt text-green-400"></i>
                Garantía de 30 días. Cancela cuando quieras sin compromiso.
            </p>
        </div>
    </div>
</section>

<!-- Modal de Login con Google -->
<div id="loginModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 max-w-md w-full mx-4 border border-gray-700 relative">
        <button onclick="cerrarModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white">
            <i class="fas fa-times text-2xl"></i>
        </button>
        
        <div class="text-center mb-6">
            <div class="inline-block p-3 bg-blue-500/10 rounded-full mb-4">
                <i class="fas fa-lock text-4xl text-blue-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Iniciar Sesión</h3>
            <p class="text-gray-400">Inicia sesión para continuar con tu suscripción</p>
        </div>

        <div id="planSeleccionado" class="bg-gray-800/50 rounded-lg p-4 mb-6 hidden">
            <p class="text-gray-300 text-sm mb-1">Plan seleccionado:</p>
            <p class="text-white font-bold text-lg" id="planNombre"></p>
            <p class="text-gray-400 text-sm">S/ <span id="planPrecio"></span>/mes</p>
        </div>

        <button onclick="loginConGoogle()" 
                class="w-full bg-white hover:bg-gray-100 text-gray-800 font-semibold py-3 px-6 rounded-xl transition-all duration-300 flex items-center justify-center gap-3 mb-4">
            <svg class="w-6 h-6" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Continuar con Google
        </button>

        <p class="text-gray-500 text-xs text-center">
            Al continuar, aceptas nuestros <a href="<?php echo url('terminos'); ?>" class="text-blue-400 hover:underline">Términos y Condiciones</a>
        </p>
    </div>
</div>

<script>
let planSeleccionado = null;
let precioSeleccionado = null;

function iniciarSuscripcion(plan, precio) {
    planSeleccionado = plan;
    precioSeleccionado = precio;
    
    // Actualizar información del modal
    document.getElementById('planNombre').textContent = plan.charAt(0).toUpperCase() + plan.slice(1);
    document.getElementById('planPrecio').textContent = precio;
    document.getElementById('planSeleccionado').classList.remove('hidden');
    
    // Mostrar modal
    document.getElementById('loginModal').classList.remove('hidden');
    document.getElementById('loginModal').classList.add('flex');
}

function cerrarModal() {
    document.getElementById('loginModal').classList.add('hidden');
    document.getElementById('loginModal').classList.remove('flex');
}

function loginConGoogle() {
    // Guardar plan seleccionado en sessionStorage
    sessionStorage.setItem('planSeleccionado', planSeleccionado);
    sessionStorage.setItem('precioSeleccionado', precioSeleccionado);
    
    // Redirigir a Google OAuth
    window.location.href = '<?php echo url('auth/google'); ?>';
}

// Cerrar modal al hacer clic fuera
document.getElementById('loginModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModal();
    }
});
</script>
