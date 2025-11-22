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
                <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="LDX Software" class="h-8 w-auto">
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
                    <a href="<?php echo url('cursos'); ?>" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                        <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                        Cursos
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
                <!-- Login Button -->
                <button onclick="openLoginModal()" class="hidden md:flex items-center gap-2 px-4 py-2 bg-white text-black hover:bg-gray-200 rounded-2xl transition-all font-semibold">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <i class="fas fa-user" aria-hidden="true"></i>
                    </div>
                    <span>Iniciar sesión</span>
                </button>
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

<!-- Login Modal -->
<dialog id="loginModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[99999999] text-white p-8 pt-20 w-full max-w-sm bg-gray-900 border border-gray-700 rounded-xl shadow-xl backdrop:bg-black/50 backdrop:backdrop-blur-sm">
    <form method="dialog">
        <button type="button" onclick="closeLoginModal()" title="Cerrar modal" aria-label="Cerrar modal" class="absolute p-3 rounded-xl bg-black/20 border border-gray-700 top-5 right-5 hover:bg-gray-800 transition-colors text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"></path>
                <path d="m6 6 12 12"></path>
            </svg>
        </button>
    </form>
    
    <div class="px-3 pb-6 w-full">
        <div class="text-center mb-6">
            <div class="size-12 mx-auto mb-3 bg-gradient-to-br from-white to-neutral-200 rounded-lg flex items-center justify-center">
                <img alt="LDX Software logo" class="size-8" src="<?php echo asset('images/logo.png'); ?>">
            </div>
            <h2 class="text-xl font-semibold text-white mb-1">Iniciar sesión</h2>
            <p class="text-sm text-gray-400">Accede a la academia de LDX Software</p>
        </div>
        
        <div class="flex flex-col gap-3">
            <a href="<?php echo url('auth/google'); ?>" class="w-full px-4 py-3 bg-white hover:bg-gray-100 text-gray-900 font-semibold rounded-lg transition-all flex items-center justify-center gap-3 shadow-lg hover:shadow-xl">
                <svg viewBox="0 0 24 24" width="20" height="20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Continuar con Google
            </a>
            
            <div class="text-center pt-2">
                <p class="text-xs text-gray-500">
                    Al continuar, aceptas nuestros 
                    <a href="<?php echo url('terminos'); ?>" class="text-blue-400 hover:text-blue-300 hover:underline">términos</a> y 
                    <a href="<?php echo url('privacidad'); ?>" class="text-blue-400 hover:text-blue-300 hover:underline">política de privacidad</a>
                </p>
            </div>
        </div>
    </div>
</dialog>

<script>
    // Login Modal Functions
    function openLoginModal() {
        const modal = document.getElementById('loginModal');
        if (modal) {
            modal.showModal();
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeLoginModal() {
        const modal = document.getElementById('loginModal');
        if (modal) {
            modal.close();
            document.body.style.overflow = '';
        }
    }
    
    // Close modal on backdrop click
    document.getElementById('loginModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeLoginModal();
        }
    });
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLoginModal();
        }
    });

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
    
    /* Modal animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate(-50%, -48%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }
    
    dialog[open] {
        animation: fadeInUp 0.3s ease-out;
    }
    
    dialog::backdrop {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
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
