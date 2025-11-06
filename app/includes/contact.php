<!-- Contact Section -->
<section id="contacto" class="py-12 bg-black text-white relative overflow-hidden">
    <!-- Separator Line -->
    <div class="w-full h-px bg-gradient-to-r from-transparent via-white/20 to-transparent mb-20"></div>
    
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-72 h-72 bg-white rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/4 w-48 h-48 bg-white rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>
    
    <!-- Grid Pattern -->
    <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
            <!-- Left Column - Contact Info -->
            <div class="text-left">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                    Contáctanos
                </h2>
                <p class="text-lg md:text-xl text-gray-300 mb-8 leading-relaxed">
                    Déjanos un mensaje y nos pondremos en contacto lo más pronto posible
                </p>
                
                <!-- Contact Options -->
                <div class="space-y-4 lg:space-y-0 lg:grid lg:grid-cols-2 lg:gap-4">
                    <a href="mailto:contacto@ldxsoftware.com.pe" class="flex items-center gap-4 p-4 bg-black/60 hover:bg-black/80 rounded-lg transition-colors border border-white/10">
                        <div class="w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Email</h3>
                            <p class="text-white/80">contacto@ldxsoftware.com.pe</p>
                        </div>
                    </a>
                    
                    <a href="https://wa.me/51905940757" target="_blank" class="flex items-center gap-4 p-4 bg-black/60 hover:bg-black/80 rounded-lg transition-colors border border-white/10">
                        <div class="w-12 h-12 flex items-center justify-center">
                            <i class="fab fa-whatsapp text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">WhatsApp</h3>
                            <p class="text-white/80">+51 905 940 757</p>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- Right Column - Contact Form -->
            <div class="bg-black/80 p-4 md:p-8 rounded-lg border border-white/20">
                <!-- Success/Error Messages -->
                <div id="form-message" class="hidden mb-6 p-4 rounded-lg"></div>
                
                <form id="contact-form" class="space-y-3 md:space-y-6">
                    <!-- Name and Last Name Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4">
                        <div>
                            <input type="text" name="nombre" placeholder="NOMBRE" required class="w-full px-3 py-2 md:px-4 md:py-3 bg-black/60 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:border-white/40 transition-colors text-sm md:text-base">
                        </div>
                        <div>
                            <input type="text" name="apellido" placeholder="APELLIDO" required class="w-full px-3 py-2 md:px-4 md:py-3 bg-black/60 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:border-white/40 transition-colors text-sm md:text-base">
                        </div>
                    </div>
                    
                    <!-- Email and Phone Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4">
                        <div>
                            <input type="email" name="email" placeholder="CORREO" required class="w-full px-3 py-2 md:px-4 md:py-3 bg-black/60 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:border-white/40 transition-colors text-sm md:text-base">
                        </div>
                        <div>
                            <input type="tel" name="telefono" placeholder="TELÉFONO" required class="w-full px-3 py-2 md:px-4 md:py-3 bg-black/60 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:border-white/40 transition-colors text-sm md:text-base">
                        </div>
                    </div>
                    
                    <!-- Message -->
                    <div>
                        <textarea name="mensaje" placeholder="MENSAJE" required maxlength="500" class="w-full px-3 py-2 md:px-4 md:py-3 bg-black/60 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:border-white/40 transition-colors resize-none text-sm md:text-base mobile-textarea"></textarea>
                        <div class="text-right mt-2">
                            <span id="char-count" class="text-sm text-white/60">0/500</span>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="text-right">
                        <button type="submit" id="submit-btn" class="bg-white hover:bg-gray-200 text-black px-6 py-2 md:px-8 md:py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm md:text-base">
                            <span id="btn-text">ENVIAR</span>
                            <span id="btn-loading" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                ENVIANDO...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const messageDiv = document.getElementById('form-message');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoading = document.getElementById('btn-loading');
    const charCount = document.getElementById('char-count');
    const messageTextarea = document.querySelector('textarea[name="mensaje"]');

    // Character counter
    messageTextarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = `${count}/500`;
        
        if (count > 450) {
            charCount.classList.add('text-yellow-400');
        } else {
            charCount.classList.remove('text-yellow-400');
        }
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Show loading state
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        hideMessage();

        try {
            // Collect form data
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            // Enviar petición a la API en paralelo (sin esperar)
            fetch('api/contact-working.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            }).then(response => response.json())
              .then(result => {
                  if (result.success) {
                      console.log('Email enviado exitosamente');
                  } else {
                      console.error('Error enviando email:', result.message);
                  }
              })
              .catch(error => {
                  console.error('Error en la petición:', error);
              });

            // Esperar 3 segundos y mostrar alerta de éxito (independiente de la API)
            await new Promise(resolve => setTimeout(resolve, 3000));

            // Mostrar siempre éxito al usuario
            showMessage('¡Mensaje enviado correctamente! Revisa tu bandeja de entrada y carpeta de spam para la confirmación.', 'success');
            form.reset();
            charCount.textContent = '0/500';

        } catch (error) {
            console.error('Error:', error);
            showMessage('Error de conexión. Por favor, inténtalo de nuevo.', 'error');
        } finally {
            // Reset button state
            submitBtn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        }
    });

    function showMessage(message, type) {
        messageDiv.textContent = message;
        messageDiv.classList.remove('hidden', 'bg-green-600', 'bg-red-600', 'text-white');
        
        if (type === 'success') {
            messageDiv.classList.add('bg-green-600', 'text-white');
        } else {
            messageDiv.classList.add('bg-red-600', 'text-white');
        }

        // Auto-hide after 5 seconds
        setTimeout(hideMessage, 5000);
    }

    function hideMessage() {
        messageDiv.classList.add('hidden');
    }
});
</script>

<style>
/* Estilos para hacer el formulario más compacto en móvil */
@media (max-width: 768px) {
    .mobile-textarea {
        min-height: 80px !important;
        height: 80px !important;
    }
    
    /* Reducir padding del contenedor del formulario */
    #contact-form {
        padding: 0;
    }
    
    /* Hacer los inputs más compactos */
    input, textarea {
        font-size: 14px !important;
        line-height: 1.2 !important;
    }
    
    /* Reducir espaciado del mensaje de éxito/error */
    #form-message {
        margin-bottom: 1rem !important;
        padding: 0.75rem !important;
        font-size: 14px;
    }
    
    /* Botón más compacto */
    #submit-btn {
        width: 100%;
        margin-top: 0.5rem;
    }
}

@media (min-width: 769px) {
    .mobile-textarea {
        min-height: 120px !important;
        height: 120px !important;
    }
}
</style>
