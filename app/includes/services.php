<!-- Services Section -->
<section id="servicios" class="py-12 bg-black text-white relative overflow-hidden">
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
        <!-- Section Header -->
        <div class="text-center mb-12 md:mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 md:mb-6 leading-tight">
                Mis <span class="text-white">Servicios</span>
            </h2>
            <p class="text-lg md:text-xl lg:text-2xl text-gray-300 mb-8 max-w-3xl mx-auto leading-relaxed">
                Ofrecemos una gama completa de servicios de desarrollo de software para impulsar tu negocio
            </p>
        </div>
        
        <!-- 3D Services Carousel -->
        <div class="relative w-full max-w-full mx-auto overflow-hidden" id="services-carousel">
            <div class="carousel-container relative w-full h-80 md:h-96 lg:h-[28rem]" style="perspective: 1000px;">
                <!-- Service Cards -->
                <div class="service-card center" data-service="0">
                    <div class="bg-transparent border border-white/20 text-white p-4 md:p-6 rounded-lg backdrop-blur-sm h-full flex flex-col">
                        <div class="text-center flex-1 flex flex-col justify-center">
                            <i class="fas fa-laptop-code text-3xl md:text-4xl text-white mb-3 md:mb-4"></i>
                            <h3 class="text-lg md:text-xl font-bold mb-2 md:mb-3 text-white">Diseño Web</h3>
                            <p class="text-xs md:text-sm leading-relaxed text-gray-300 overflow-hidden">
                                Sitios modernos, rápidos y listos para convertir visitas en clientes. Diseño enfocado en resultados y SEO desde el día uno.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="service-card left-1" data-service="1">
                    <div class="bg-transparent border border-white/20 text-white p-4 md:p-6 rounded-lg backdrop-blur-sm h-full flex flex-col">
                        <div class="text-center flex-1 flex flex-col justify-center">
                            <i class="fas fa-shopping-cart text-3xl md:text-4xl text-white mb-3 md:mb-4"></i>
                            <h3 class="text-lg md:text-xl font-bold mb-2 md:mb-3 text-white">E-commerce</h3>
                            <p class="text-xs md:text-sm leading-relaxed text-gray-300 overflow-hidden">
                                Tiendas online que venden más: pagos seguros, gestión de productos sencilla y campañas listas para escalar.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="service-card right-1" data-service="2">
                    <div class="bg-transparent border border-white/20 text-white p-4 md:p-6 rounded-lg backdrop-blur-sm h-full flex flex-col">
                        <div class="text-center flex-1 flex flex-col justify-center">
                            <i class="fas fa-cogs text-3xl md:text-4xl text-white mb-3 md:mb-4"></i>
                            <h3 class="text-lg md:text-xl font-bold mb-2 md:mb-3 text-white">Automatizaciones</h3>
                            <p class="text-xs md:text-sm leading-relaxed text-gray-300 overflow-hidden">
                                Automatizamos tareas repetitivas para que tu equipo gane tiempo y reduzca errores. Más eficiencia, menos costos.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="service-card left-2" data-service="3">
                    <div class="bg-transparent border border-white/20 text-white p-4 md:p-6 rounded-lg backdrop-blur-sm h-full flex flex-col">
                        <div class="text-center flex-1 flex flex-col justify-center">
                            <i class="fas fa-globe text-3xl md:text-4xl text-white mb-3 md:mb-4"></i>
                            <h3 class="text-lg md:text-xl font-bold mb-2 md:mb-3 text-white">Web Scraping</h3>
                            <p class="text-xs md:text-sm leading-relaxed text-gray-300 overflow-hidden">
                                Datos de la web directo a tu sistema. Extracción confiable, a escala y lista para análisis.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="service-card right-2" data-service="4">
                    <div class="bg-transparent border border-white/20 text-white p-4 md:p-6 rounded-lg backdrop-blur-sm h-full flex flex-col">
                        <div class="text-center flex-1 flex flex-col justify-center">
                            <i class="fas fa-code text-3xl md:text-4xl text-white mb-3 md:mb-4"></i>
                            <h3 class="text-lg md:text-xl font-bold mb-2 md:mb-3 text-white">Tecnologías</h3>
                            <p class="text-xs md:text-sm leading-relaxed text-gray-300 overflow-hidden">
                                Elegimos el stack ideal (Python, PHP, JS, frameworks modernos) para entregar rápido, seguro y fácil de mantener.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <button id="prev-btn" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 sm:p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/50 z-30 backdrop-blur-sm">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button id="next-btn" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 sm:p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/50 z-30 backdrop-blur-sm">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
</section>

<!-- Custom CSS for Responsive 3D Carousel -->
<style>
    .service-card {
        position: absolute;
        width: 240px;
        height: 300px;
        transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        transform-style: preserve-3d;
        cursor: pointer;
    }

    .service-card.center {
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%) scale(1) translateZ(0px);
        z-index: 10;
    }

    .service-card.left-1 {
        left: 20%;
        top: 50%;
        transform: translate(-50%, -50%) scale(1) rotateY(15deg) translateZ(-100px);
        z-index: 5;
        opacity: 0.4;
        pointer-events: none;
    }

    .service-card.right-1 {
        right: 20%;
        top: 50%;
        transform: translate(50%, -50%) scale(1) rotateY(-15deg) translateZ(-100px);
        z-index: 5;
        opacity: 0.4;
        pointer-events: none;
    }

    .service-card.left-2 {
        left: 5%;
        top: 50%;
        transform: translate(-50%, -50%) scale(1) rotateY(25deg) translateZ(-200px);
        z-index: 2;
        opacity: 0.2;
        pointer-events: none;
    }

    .service-card.right-2 {
        right: 5%;
        top: 50%;
        transform: translate(50%, -50%) scale(1) rotateY(-25deg) translateZ(-200px);
        z-index: 2;
        opacity: 0.2;
        pointer-events: none;
    }

    .service-card.hidden {
        opacity: 0;
        pointer-events: none;
        transform: translate(-50%, -50%) scale(0.5) translateZ(-300px);
    }

    /* Hide paragraph text on side cards; show only when centered */
    .service-card p {
        transition: opacity 0.3s ease, max-height 0.3s ease, margin 0.3s ease;
    }
    .service-card:not(.center) p {
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        margin-top: 0;
    }
    .service-card.center p {
        opacity: 1;
        max-height: 300px; /* enough to reveal text */
    }

    /* Disable hover-grow to prevent card size change on hover/click */
    .service-card.center:hover {
        transform: translate(-50%, -50%) translateZ(0);
    }

    /* Responsive adjustments */
    @media (min-width: 768px) {
        .service-card {
            width: 280px;
            height: 350px;
        }
    }

    @media (min-width: 1024px) {
        .service-card {
            width: 320px;
            height: 400px;
        }
    }

    @media (max-width: 767px) and (min-width: 640px) {
        /* Mobile L: make cards larger and raise container height */
        .service-card {
            width: 260px;
            height: 360px;
        }
        .carousel-container { height: 26rem; }
        .service-card.left-1 { left: 18%; }
        .service-card.right-1 { right: 18%; }
        .service-card.left-2,
        .service-card.right-2 { display: none; }
    }

    @media (max-width: 639px) and (min-width: 480px) {
        .service-card {
            width: 230px;
            height: 320px;
        }
        .carousel-container { height: 24rem; }
    }

    @media (max-width: 480px) {
        .service-card {
            width: 180px;
            height: 250px;
        }
        
        .service-card.left-1,
        .service-card.right-1 {
            display: none;
        }
    }
</style>

<!-- JavaScript for 3D Carousel -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.service-card');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const carousel = document.getElementById('services-carousel');
    let currentIndex = 0;
    const totalCards = cards.length;
    let autoRotateInterval;
    let isUserInteracting = false;

    const positions = ['center', 'right-1', 'right-2', 'left-2', 'left-1'];

    function updateCarousel() {
        cards.forEach((card, index) => {
            const positionIndex = (index - currentIndex + totalCards) % totalCards;
            const position = positions[positionIndex] || 'hidden';
            
            card.className = `service-card ${position}`;
            card.setAttribute('data-service', index);
        });
    }

    function startAutoRotate() {
        if (autoRotateInterval) clearInterval(autoRotateInterval);
        autoRotateInterval = setInterval(() => {
            if (!isUserInteracting) {
                currentIndex = (currentIndex + 1) % totalCards;
                updateCarousel();
            }
        }, 6000);
    }

    function stopAutoRotate() {
        if (autoRotateInterval) {
            clearInterval(autoRotateInterval);
            autoRotateInterval = null;
        }
    }

    function handleUserInteraction() {
        isUserInteracting = true;
        stopAutoRotate();
    }

    function handleUserStopInteraction() {
        isUserInteracting = false;
        // Restart auto-rotate after a short delay
        setTimeout(() => {
            if (!isUserInteracting) {
                startAutoRotate();
            }
        }, 2000);
    }

    // Navigation buttons
    nextBtn.addEventListener('click', () => {
        handleUserInteraction();
        currentIndex = (currentIndex + 1) % totalCards;
        updateCarousel();
        setTimeout(handleUserStopInteraction, 100);
    });

    prevBtn.addEventListener('click', () => {
        handleUserInteraction();
        currentIndex = (currentIndex - 1 + totalCards) % totalCards;
        updateCarousel();
        setTimeout(handleUserStopInteraction, 100);
    });

    // Mouse events for desktop
    carousel.addEventListener('mouseenter', handleUserInteraction);
    carousel.addEventListener('mouseleave', handleUserStopInteraction);

    // Touch events for mobile
    carousel.addEventListener('touchstart', handleUserInteraction);
    carousel.addEventListener('touchend', handleUserStopInteraction);

    // Swipe functionality for mobile
    let startX = 0;
    let startY = 0;
    let isDragging = false;

    carousel.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        isDragging = true;
        handleUserInteraction();
    });

    carousel.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        e.preventDefault(); // Prevent scrolling
    });

    carousel.addEventListener('touchend', (e) => {
        if (!isDragging) return;
        isDragging = false;

        const endX = e.changedTouches[0].clientX;
        const endY = e.changedTouches[0].clientY;
        const diffX = startX - endX;
        const diffY = startY - endY;

        // Check if it's a horizontal swipe (not vertical scroll)
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
            if (diffX > 0) {
                // Swipe left - next card
                currentIndex = (currentIndex + 1) % totalCards;
            } else {
                // Swipe right - previous card
                currentIndex = (currentIndex - 1 + totalCards) % totalCards;
            }
            updateCarousel();
        }
        
        setTimeout(handleUserStopInteraction, 100);
    });

    // Initialize
    updateCarousel();
    startAutoRotate();
});
</script>
