<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-gray-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="<?php echo url(); ?>" class="flex items-center space-x-2">
                    <img src="<?php echo asset('images/logo.png'); ?>" alt="<?php echo escape($site_name); ?>" class="h-8 w-auto">
                    <span class="font-heading font-bold text-xl gradient-text">LDX Software</span>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8">
                    <a href="<?php echo url(); ?>" 
                       class="nav-link <?php echo isCurrentRoute('') ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700 hover:text-primary-600'; ?> px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Inicio
                    </a>
                    <a href="<?php echo url('about'); ?>" 
                       class="nav-link <?php echo isCurrentRoute('about') ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700 hover:text-primary-600'; ?> px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Nosotros
                    </a>
                    <a href="<?php echo url('services'); ?>" 
                       class="nav-link <?php echo isCurrentRoute('services') ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700 hover:text-primary-600'; ?> px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Servicios
                    </a>
                    <a href="<?php echo url('portfolio'); ?>" 
                       class="nav-link <?php echo isCurrentRoute('portfolio') ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700 hover:text-primary-600'; ?> px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Portfolio
                    </a>
                    <a href="<?php echo url('contact'); ?>" 
                       class="nav-link <?php echo isCurrentRoute('contact') ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700 hover:text-primary-600'; ?> px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Contacto
                    </a>
                </div>
            </div>
            
            <!-- CTA Button -->
            <div class="hidden md:block">
                <a href="<?php echo url('contact'); ?>" 
                   class="btn-primary text-white px-6 py-2 rounded-lg text-sm font-medium hover-lift">
                    Cotizar Proyecto
                </a>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" 
                        type="button" 
                        class="text-gray-700 hover:text-primary-600 focus:outline-none focus:text-primary-600 transition-colors duration-200"
                        aria-controls="mobile-menu" 
                        aria-expanded="false">
                    <span class="sr-only">Abrir menú principal</span>
                    <svg id="menu-icon" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="close-icon" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
            <a href="<?php echo url(); ?>" 
               class="<?php echo isCurrentRoute('') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-900'; ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200">
                Inicio
            </a>
            <a href="<?php echo url('about'); ?>" 
               class="<?php echo isCurrentRoute('about') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-900'; ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200">
                Nosotros
            </a>
            <a href="<?php echo url('services'); ?>" 
               class="<?php echo isCurrentRoute('services') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-900'; ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200">
                Servicios
            </a>
            <a href="<?php echo url('portfolio'); ?>" 
               class="<?php echo isCurrentRoute('portfolio') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-900'; ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200">
                Portfolio
            </a>
            <a href="<?php echo url('contact'); ?>" 
               class="<?php echo isCurrentRoute('contact') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-900'; ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200">
                Contacto
            </a>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <a href="<?php echo url('contact'); ?>" 
                   class="btn-primary text-white block mx-3 px-4 py-2 rounded-lg text-center font-medium">
                    Cotizar Proyecto
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('navbar');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');
    
    // Mobile menu toggle
    mobileMenuButton.addEventListener('click', function() {
        const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
        
        mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
        mobileMenu.classList.toggle('hidden');
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    });
    
    // Close mobile menu when clicking on links
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
        });
    });
    
    // Navbar scroll effect
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            navbar.classList.add('bg-white/95', 'shadow-lg');
            navbar.classList.remove('bg-white/90');
        } else {
            navbar.classList.add('bg-white/90');
            navbar.classList.remove('bg-white/95', 'shadow-lg');
        }
        
        // Hide/show navbar on scroll
        if (scrollTop > lastScrollTop && scrollTop > 200) {
            // Scrolling down
            navbar.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up
            navbar.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = scrollTop;
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
