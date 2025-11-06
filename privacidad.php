<?php
// Política de Privacidad - LDX Software
$page_title = 'Política de Privacidad - LDX Software';
$page_description = 'Política de privacidad y protección de datos de LDX Software';

// Define access constant and include config
define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/logo.ico'); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo asset('images/logo.ico'); ?>">
    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-black text-white">

<!-- Header -->
<header role="banner" class="fixed top-0 left-0 right-0 backdrop-blur-md bg-black/20 w-full overflow-visible z-[99999] header-animate border-b border-white/10">
    <div class="grid items-center justify-center md:justify-normal w-full grid-cols-[auto_1fr] mx-auto text-white gap-x-10 md:flex max-w-screen-full py-4">
        <!-- Logo Section -->
        <div class="md:flex-grow md:basis-0 flex justify-start">
            <a href="<?php echo url(); ?>" class="ml-4 flex items-center gap-2.5 font-bold transition-transform duration-300 hover:scale-110" title="Ir a la página principal" aria-label="Ir a la página principal">
                <img src="<?php echo asset('images/logo.png'); ?>" alt="LDX Software" class="h-8 w-auto">
            </a>
        </div>

        <!-- Navigation -->
        <nav aria-label="Navegación principal" id="header-navbar" class="col-span-full overflow-x-auto row-[2/3] grid grid-rows-[0fr] transition-[grid-template-rows] data-[open]:grid-rows-[1fr] md:justify-center md:flex group/nav">
            <ul data-header-navbar="" class="flex flex-col items-center overflow-x-hidden overflow-y-hidden md:flex-row gap-x-2">
                <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                    <a href="<?php echo url(); ?>#hero" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                        <i class="fas fa-home" aria-hidden="true"></i>
                        Inicio
                    </a>
                </li>
                <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                    <a href="<?php echo url(); ?>#servicios" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                        <i class="fas fa-cogs" aria-hidden="true"></i>
                        Servicios
                    </a>
                </li>
                <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                    <a href="<?php echo url(); ?>#trabajos" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                        <i class="fas fa-briefcase" aria-hidden="true"></i>
                        Trabajos
                    </a>
                </li>
                <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                    <a href="<?php echo url(); ?>#contacto" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        Contacto
                    </a>
                </li>
            </ul>
        </nav>

        <!-- CTA and Mobile Menu -->
        <div class="flex md:flex-grow md:basis-0 items-center gap-4 mr-4 ml-auto md:ml-0 justify-end">
            <!-- CTA Button -->
            <a href="<?php echo url(); ?>#contacto" class="hidden md:flex items-center gap-2 px-4 py-2 bg-white text-black hover:bg-gray-200 rounded-2xl transition-all font-semibold">
                <i class="fas fa-rocket" aria-hidden="true"></i>
                Cotizar Proyecto
            </a>
            
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

<main class="bg-black text-white min-h-screen pt-20 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-72 h-72 bg-white rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/4 w-48 h-48 bg-white rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>
    
    <!-- Grid Pattern -->
    <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 50px 50px;"></div>

    <!-- Hero Section -->
    <section class="py-20 relative z-10">
        <!-- Separator Line -->
        
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                Política de <span class="text-white">Privacidad</span>
            </h1>
            <p class="text-xl text-gray-300 mb-8">
                Protección y tratamiento de tus datos personales
            </p>
            <div class="w-24 h-1 bg-white mx-auto"></div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-16 relative z-10">
        <div class="max-w-4xl mx-auto px-4">
            <div class="prose prose-invert prose-lg max-w-none">
                
                <div class="mb-12">
                    <p class="text-gray-300 mb-8">
                        <strong>Última actualización:</strong> <?php echo date('d/m/Y'); ?>
                    </p>
                    
                    <p class="text-gray-300 leading-relaxed mb-8">
                        En LDX Software, respetamos tu privacidad y nos comprometemos a proteger tus datos personales. Esta política de privacidad explica cómo recopilamos, utilizamos y protegemos tu información.
                    </p>
                </div>

                <div class="space-y-12">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">1. Información que Recopilamos</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Recopilamos información que nos proporcionas directamente, incluyendo:
                        </p>
                        <ul class="list-disc list-inside text-gray-300 space-y-2 ml-4">
                            <li><strong>Información de contacto:</strong> nombre, apellido, correo electrónico, número de teléfono</li>
                            <li><strong>Información de comunicación:</strong> mensajes que nos envías a través de formularios de contacto</li>
                            <li><strong>Información técnica:</strong> dirección IP, tipo de navegador, páginas visitadas (solo para análisis estadísticos)</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">2. Cómo Utilizamos tu Información</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Utilizamos tu información personal para:
                        </p>
                        <ul class="list-disc list-inside text-gray-300 space-y-2 ml-4">
                            <li>Responder a tus consultas y solicitudes de servicios</li>
                            <li>Proporcionar cotizaciones y propuestas comerciales</li>
                            <li>Comunicarnos contigo sobre nuestros servicios</li>
                            <li>Mejorar nuestro sitio web y servicios</li>
                            <li>Cumplir con obligaciones legales</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">3. Base Legal para el Tratamiento</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Procesamos tus datos personales basándonos en:
                        </p>
                        <ul class="list-disc list-inside text-gray-300 space-y-2 ml-4">
                            <li><strong>Consentimiento:</strong> cuando nos contactas voluntariamente</li>
                            <li><strong>Interés legítimo:</strong> para responder a tus consultas y mejorar nuestros servicios</li>
                            <li><strong>Ejecución contractual:</strong> para proporcionar los servicios solicitados</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">4. Compartir Información</h2>
                        <p class="text-gray-300 leading-relaxed">
                            No vendemos, alquilamos ni compartimos tu información personal con terceros, excepto en los siguientes casos:
                        </p>
                        <ul class="list-disc list-inside text-gray-300 space-y-2 ml-4 mt-4">
                            <li>Cuando sea requerido por ley</li>
                            <li>Con proveedores de servicios que nos ayudan a operar nuestro sitio web (bajo acuerdos de confidencialidad)</li>
                            <li>Para proteger nuestros derechos legales</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">5. Seguridad de los Datos</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Implementamos medidas de seguridad técnicas y organizativas apropiadas para proteger tu información personal contra acceso no autorizado, alteración, divulgación o destrucción.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">6. Retención de Datos</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Conservamos tu información personal solo durante el tiempo necesario para cumplir con los propósitos para los cuales fue recopilada, o según lo requiera la ley aplicable.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">7. Tus Derechos</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Tienes derecho a:
                        </p>
                        <ul class="list-disc list-inside text-gray-300 space-y-2 ml-4">
                            <li><strong>Acceso:</strong> solicitar información sobre los datos que tenemos sobre ti</li>
                            <li><strong>Rectificación:</strong> corregir datos inexactos o incompletos</li>
                            <li><strong>Eliminación:</strong> solicitar la eliminación de tus datos personales</li>
                            <li><strong>Portabilidad:</strong> recibir tus datos en un formato estructurado</li>
                            <li><strong>Oposición:</strong> oponerte al procesamiento de tus datos</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">8. Cookies</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Nuestro sitio web utiliza cookies para mejorar tu experiencia de navegación. Puedes configurar tu navegador para rechazar cookies, aunque esto puede afectar la funcionalidad del sitio.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">9. Cambios en esta Política</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Podemos actualizar esta política de privacidad ocasionalmente. Te notificaremos sobre cambios significativos publicando la nueva política en nuestro sitio web.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">10. Contacto</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Si tienes preguntas sobre esta política de privacidad o quieres ejercer tus derechos, contáctanos:
                        </p>
                        <div class="bg-gray-900 p-6 rounded-lg">
                            <p class="text-gray-300 mb-2"><strong>Email:</strong> contacto@ldxsoftware.com.pe</p>
                            <p class="text-gray-300 mb-2"><strong>WhatsApp:</strong> +51 905 940 757</p>
                            <p class="text-gray-300 mb-2"><strong>Asunto:</strong> "Consulta sobre Privacidad"</p>
                            <p class="text-gray-300"><strong>Dirección:</strong> Lima, Perú</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

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
            <a href="https://instagram.com/ldxsoftware" target="_blank" class="text-gray-400 hover:text-white transition-colors duration-300">
                <i class="fab fa-instagram text-3xl" aria-hidden="true"></i>
            </a>
            <a href="https://facebook.com/ldxsoftware" target="_blank" class="text-gray-400 hover:text-white transition-colors duration-300">
                <i class="fab fa-facebook text-3xl" aria-hidden="true"></i>
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
            Copyright © 2025 LDX Software - Todos los derechos reservados
        </div>
    </div>
</footer>

</body>
</html>
