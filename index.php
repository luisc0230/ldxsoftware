<?php
/**
 * LDX Software - Main Entry Point
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define access constant
define('LDX_ACCESS', true);

// Include configuration
require_once __DIR__ . '/config/config.php';

try {
    // Get the requested URI
    $uri = $_SERVER['REQUEST_URI'];
    $uri = parse_url($uri, PHP_URL_PATH);
    
    // Remove base path dynamically
    $basePath = parse_url(BASE_URL, PHP_URL_PATH);
    $uri = str_replace($basePath, '', $uri);
    $uri = trim($uri, '/');
    
    // Handle specific routes
    if ($uri === 'terminos') {
        include 'terminos.php';
        exit;
    } elseif ($uri === 'privacidad') {
        include 'privacidad.php';
        exit;
    } elseif ($uri === 'mis-suscripciones') {
        include 'mis-suscripciones.php';
        exit;
    } elseif ($uri === 'perfil') {
        include 'perfil.php';
        exit;
    } elseif ($uri === 'checkout') {
        include 'checkout.php';
        exit;
    } elseif ($uri === 'test-db') {
        include 'test-db.php';
        exit;
    } elseif ($uri === 'diagnostico-session') {
        include 'diagnostico-session.php';
        exit;
    }
    
    // For other routes, show the main landing page
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LDX SOFTWARE</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
        <link rel="icon" type="image/x-icon" href="<?php echo asset('images/logo.ico'); ?>">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo asset('images/logo.ico'); ?>">
    
        <!-- Swiper CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
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
    </head>
    <body class="bg-gradient-to-b from-black via-gray-900 via-black to-black min-h-screen pt-20">
        <!-- Header -->
        <header role="banner" class="fixed top-0 left-0 right-0 backdrop-blur-md bg-black/20 w-full overflow-visible z-[99999] header-animate border-b border-white/10">
            <div class="grid items-center justify-center md:justify-normal w-full grid-cols-[auto_1fr] mx-auto text-white gap-x-10 md:flex max-w-screen-full py-4">
                <!-- Logo Section -->
                <div class="md:flex-grow md:basis-0 flex justify-start">
                    <a href="#hero" class="ml-4 flex items-center gap-2.5 font-bold transition-transform duration-300 hover:scale-110" title="Ir a la página principal" aria-label="Ir a la página principal">
                        <img src="assets/images/logo.png" alt="LDX Software" class="h-8 w-auto">
                    </a>
                </div>

                <!-- Navigation -->
                <nav aria-label="Navegación principal" id="header-navbar" class="col-span-full overflow-x-auto row-[2/3] grid grid-rows-[0fr] transition-[grid-template-rows] data-[open]:grid-rows-[1fr] md:justify-center md:flex group/nav">
                    <ul data-header-navbar="" class="flex flex-col items-center overflow-x-hidden overflow-y-hidden md:flex-row gap-x-2">
                        <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                            <a href="#hero" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                                <i class="fas fa-home" aria-hidden="true"></i>
                                Inicio
                            </a>
                        </li>
                        <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                            <a href="#servicios" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                                <i class="fas fa-cogs" aria-hidden="true"></i>
                                Servicios
                            </a>
                        </li>
                        <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                            <a href="#trabajos" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                                <i class="fas fa-briefcase" aria-hidden="true"></i>
                                Trabajos
                            </a>
                        </li>
                        <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                            <a href="#suscripciones" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                                <i class="fas fa-credit-card" aria-hidden="true"></i>
                                Suscripciones
                            </a>
                        </li>
                        <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                            <a href="#contacto" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                                <i class="fas fa-envelope" aria-hidden="true"></i>
                                Contacto
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- CTA and Mobile Menu -->
                <div class="flex md:flex-grow md:basis-0 items-center gap-4 mr-4 ml-auto md:ml-0 justify-end">
                    <?php
                    require_once __DIR__ . '/app/controllers/AuthController.php';
                    $isLoggedIn = AuthController::isAuthenticated();
                    $user = AuthController::getCurrentUser();
                    
                    if ($isLoggedIn && $user): ?>
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
                                <i class="fas fa-chevron-down text-white text-xs"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-64 bg-gray-900 border border-gray-700 rounded-xl shadow-2xl z-50">
                                <div class="p-4 border-b border-gray-700">
                                    <p class="text-white font-semibold"><?php echo htmlspecialchars($user['name']); ?></p>
                                    <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($user['email']); ?></p>
                                </div>
                                <div class="p-2">
                                    <a href="<?php echo url('mis-suscripciones'); ?>" class="flex items-center gap-3 px-4 py-2 text-gray-300 hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-credit-card"></i>
                                        <span>Mis Suscripciones</span>
                                    </a>
                                    <a href="<?php echo url('perfil'); ?>" class="flex items-center gap-3 px-4 py-2 text-gray-300 hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-user"></i>
                                        <span>Mi Perfil</span>
                                    </a>
                                    <hr class="my-2 border-gray-700">
                                    <a href="<?php echo url('auth/logout'); ?>" class="flex items-center gap-3 px-4 py-2 text-red-400 hover:bg-red-500/10 rounded-lg transition-all">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Cerrar Sesión</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- CTA Button -->
                        <a href="#contacto" class="hidden md:flex items-center gap-2 px-4 py-2 bg-white text-black hover:bg-gray-200 rounded-2xl transition-all font-semibold">
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

        <!-- Include Hero Section -->
        <?php include __DIR__ . '/app/includes/hero.php'; ?>

        <!-- Include Description Section -->
        <?php include __DIR__ . '/app/includes/descripcion.php'; ?>

        <!-- Include Services Section -->
        <?php include __DIR__ . '/app/includes/services.php'; ?>

        <!-- Include Trabajos Section -->
        <?php include __DIR__ . '/app/includes/trabajos.php'; ?>

        <!-- Include Suscripciones Section -->
        <?php include __DIR__ . '/app/includes/suscripciones.php'; ?>

        <!-- Include Contact Section -->
        <?php include __DIR__ . '/app/includes/contact.php'; ?>

        <!-- Include Footer -->
        <?php include __DIR__ . '/app/includes/footer.php'; ?>

        <!-- JavaScript -->
        <script>
            // Debug: Check session status
            <?php if ($isLoggedIn && $user): ?>
            console.log("✅ Usuario logueado:", <?php echo json_encode($user); ?>);
            <?php else: ?>
            console.log("❌ Usuario NO logueado");
            <?php endif; ?>
            
            // Check for login success parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('login')) {
                console.log("Login status:", urlParams.get('login'));
                if (urlParams.get('login') === 'success') {
                    console.log("✅ Login exitoso detectado!");
                }
            }
            if (urlParams.has('error')) {
                console.error("❌ Error de autenticación:", urlParams.get('error'));
            }
            
            // User menu dropdown toggle
            function toggleUserMenu() {
                const userMenu = document.getElementById('userMenu');
                userMenu.classList.toggle('hidden');
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

            // Smooth scroll behavior for anchor links
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
        </script>
        
        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    </body>
    </html>
    <?php
    
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>Mensaje: " . $e->getMessage() . "</p>";
    echo "<p>Archivo: " . $e->getFile() . "</p>";
    echo "<p>Línea: " . $e->getLine() . "</p>";
}
?>
