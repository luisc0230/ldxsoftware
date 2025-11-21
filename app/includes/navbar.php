<?php
// Ensure we have access to auth functions
if (!defined('LDX_ACCESS')) {
    define('LDX_ACCESS', true);
}
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$isLoggedIn = AuthController::isAuthenticated();
$user = AuthController::getCurrentUser();
?>
<header role="banner" class="fixed top-0 left-0 right-0 backdrop-blur-md bg-black/20 w-full overflow-visible z-[99999] header-animate border-b border-white/10">
    <div class="grid items-center justify-center md:justify-normal w-full grid-cols-[auto_1fr] mx-auto text-white gap-x-10 md:flex max-w-screen-full py-4">
        <!-- Logo Section -->
        <div class="md:flex-grow md:basis-0 flex justify-start">
            <a href="<?php echo BASE_URL; ?>" class="ml-4 flex items-center gap-2.5 font-bold transition-transform duration-300 hover:scale-110" title="Ir a la página principal" aria-label="Ir a la página principal">
                <img src="<?php echo asset('images/logo.png'); ?>" alt="LDX Software" class="h-8 w-auto">
            </a>
        </div>

        <!-- Navigation -->
        <nav aria-label="Navegación principal" id="header-navbar" class="col-span-full overflow-x-auto row-[2/3] grid grid-rows-[0fr] transition-[grid-template-rows] data-[open]:grid-rows-[1fr] md:justify-center md:flex group/nav">
            <ul data-header-navbar="" class="flex flex-col items-center overflow-x-hidden overflow-y-hidden md:flex-row gap-x-2">
                <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                    <a href="<?php echo BASE_URL; ?>#hero" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                        <i class="fas fa-home" aria-hidden="true"></i>
                        Inicio
                    </a>
                </li>
                <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                    <a href="<?php echo BASE_URL; ?>#acerca" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                        Acerca de
                    </a>
                </li>
                <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                    <a href="<?php echo BASE_URL; ?>#servicios" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                        <i class="fas fa-cogs" aria-hidden="true"></i>
                        Servicios
                    </a>
                </li>
                <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                    <a href="<?php echo BASE_URL; ?>#trabajos" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                        <i class="fas fa-briefcase" aria-hidden="true"></i>
                        Trabajos
                    </a>
                </li>
                <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                    <a href="<?php echo BASE_URL; ?>#suscripciones" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                        <i class="fas fa-credit-card" aria-hidden="true"></i>
                        Suscripciones
                    </a>
                </li>
                <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                    <a href="<?php echo BASE_URL; ?>#contacto" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        Contacto
                    </a>
                </li>
            </ul>
        </nav>

        <!-- CTA and Mobile Menu -->
        <div class="flex md:flex-grow md:basis-0 items-center gap-4 mr-4 ml-auto md:ml-0 justify-end">
            <?php if ($isLoggedIn && $user): ?>
                <!-- User Profile Dropdown -->
                <div class="relative" id="userDropdown">
                    <button onclick="toggleUserMenu()" class="flex items-center gap-2 hover:bg-white/5 rounded-2xl px-3 py-2 transition-all">
                        <?php if (!empty($user['picture'])): ?>
                            <img src="<?php echo htmlspecialchars($user['picture']); ?>" 
                                 alt="<?php echo htmlspecialchars($user['name']); ?>"
                                 class="w-8 h-8 rounded-full border-2 border-blue-500">
                        <?php else: ?>
                            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <span class="text-white hidden md:block"><?php echo htmlspecialchars(explode(' ', $user['name'])[0]); ?></span>
                        <i class="fas fa-chevron-down text-white text-xs" aria-hidden="true"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-64 bg-gray-900 border border-gray-700 rounded-xl shadow-2xl z-50">
                        <div class="p-4 border-b border-gray-700">
                            <p class="text-white font-semibold"><?php echo htmlspecialchars($user['name']); ?></p>
                            <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        <div class="p-2">
                            <a href="<?php echo url('mis-suscripciones'); ?>" class="flex items-center gap-3 px-4 py-2 text-gray-300 hover:bg-white/5 rounded-lg transition-all">
                                <i class="fas fa-credit-card" aria-hidden="true"></i>
                                <span>Mis Suscripciones</span>
                            </a>
                            <a href="<?php echo url('perfil'); ?>" class="flex items-center gap-3 px-4 py-2 text-gray-300 hover:bg-white/5 rounded-lg transition-all">
                                <i class="fas fa-user" aria-hidden="true"></i>
                                <span>Mi Perfil</span>
                            </a>
                            <hr class="my-2 border-gray-700">
                            <a href="<?php echo url('auth/logout'); ?>" class="flex items-center gap-3 px-4 py-2 text-red-400 hover:bg-red-500/10 rounded-lg transition-all">
                                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                                <span>Cerrar Sesión</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- CTA Button -->
                <a href="<?php echo BASE_URL; ?>#contacto" class="hidden md:flex items-center gap-2 px-4 py-2 bg-white text-black hover:bg-gray-200 rounded-2xl transition-all font-semibold">
                    <i class="fas fa-rocket" aria-hidden="true"></i>
                    Cotizar Proyecto
                </a>
            <?php endif; ?>
            
            <!-- Mobile Menu Toggle -->
            <button class="flex items-center justify-center py-2 md:hidden group" id="header-navbar-toggle" aria-controls="header-navbar" title="Mostrar Menú" aria-label="Mostrar menú" aria-expanded="false">
                <div class="flex items-center justify-center p-2 cursor-pointer">
                    <div class="flex flex-col gap-2">
                        <span class="hamburger-line block h-0.5 w-8 origin-center rounded-full bg-white/80 transition-transform ease-in-out duration-300"></span>
                        <span class="hamburger-line block h-0.5 w-8 origin-center rounded-full bg-white/80 transition-transform ease-in-out duration-300"></span>
                    </div>
                </div>
            </button>
        </div>
    </div>
</header>

<script>
    // User menu dropdown toggle
    function toggleUserMenu() {
        const userMenu = document.getElementById('userMenu');
        if (userMenu) {
            userMenu.classList.toggle('hidden');
        }
    }

    // Close user menu when clicking outside
    document.addEventListener('click', function(event) {
        const userDropdown = document.getElementById('userDropdown');
        const userMenu = document.getElementById('userMenu');
        
        if (userDropdown && userMenu && !userDropdown.contains(event.target)) {
            userMenu.classList.add('hidden');
        }
    });

    // Modern mobile menu toggle
    const mobileMenuToggle = document.getElementById('header-navbar-toggle');
    const headerNavbar = document.getElementById('header-navbar');

    if (mobileMenuToggle && headerNavbar) {
        mobileMenuToggle.addEventListener('click', () => {
            const isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
            
            // Toggle aria-expanded
            mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
            
            // Toggle data-open attribute for CSS transitions
            if (isExpanded) {
                headerNavbar.removeAttribute('data-open');
                document.body.classList.remove('overflow-hidden');
            } else {
                headerNavbar.setAttribute('data-open', '');
                document.body.classList.add('overflow-hidden');
            }
        });

        // Close menu when clicking on navigation links
        const navLinks = headerNavbar.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                headerNavbar.removeAttribute('data-open');
                document.body.classList.remove('overflow-hidden');
            });
        });

        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenuToggle.getAttribute('aria-expanded') === 'true') {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                headerNavbar.removeAttribute('data-open');
                document.body.classList.remove('overflow-hidden');
            }
        });
    }
</script>

<style>
    html { scroll-behavior: smooth; }
    
    /* Hamburger Animation */
    .hamburger-line {
        transform-origin: center;
    }
    
    /* When menu is closed (hamburger) */
    button[aria-expanded="false"] .hamburger-line:first-child {
        transform: translateY(0) rotate(0deg);
    }
    
    button[aria-expanded="false"] .hamburger-line:last-child {
        transform: translateY(0) rotate(0deg);
    }
    
    /* When menu is open (X) */
    button[aria-expanded="true"] .hamburger-line:first-child {
        transform: translateY(5px) rotate(45deg);
    }
    
    button[aria-expanded="true"] .hamburger-line:last-child {
        transform: translateY(-5px) rotate(-45deg);
    }
</style>
