<?php
/**
 * Acerca de LDX Software
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="es" itemscope itemtype="https://schema.org/AboutPage">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>Acerca de LDX Software - Empresa de Desarrollo Web en Perú</title>
    <meta name="title" content="Acerca de LDX Software - Empresa de Desarrollo Web en Perú">
    <meta name="description" content="LDX Software es una empresa peruana especializada en desarrollo de páginas web, aplicaciones móviles, sistemas de automatización y e-commerce. Conoce más sobre nosotros.">
    <meta name="keywords" content="ldx software, acerca de ldx, empresa peruana desarrollo web, quién es ldx software, desarrollo software perú">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo url('acerca'); ?>">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo url('acerca'); ?>">
    <meta property="og:title" content="Acerca de LDX Software">
    <meta property="og:description" content="Empresa peruana especializada en desarrollo web, apps móviles y automatizaciones.">
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/6d85ddc2e8.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/logo.ico'); ?>">
</head>
<body class="bg-gradient-to-b from-black via-gray-900 to-black min-h-screen">
    
    <!-- Include Navbar -->
    <?php include __DIR__ . '/app/includes/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-24">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    ¿Quién es <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-600">LDX Software</span>?
                </h1>
                <p class="text-gray-400 text-lg">
                    Conoce más sobre nuestra empresa peruana de desarrollo de software
                </p>
            </div>

            <!-- Contenido Principal -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 md:p-12 border border-gray-700 mb-8">
                <div class="prose prose-invert max-w-none">
                    <h2 class="text-2xl font-bold text-white mb-4">Sobre LDX Software</h2>
                    <p class="text-gray-300 mb-6 leading-relaxed">
                        <strong>LDX Software</strong> es una empresa peruana especializada en desarrollo de software, 
                        fundada en 2024 con el objetivo de transformar ideas en realidad digital. Nos ubicamos en 
                        <strong>Perú</strong> y ofrecemos servicios de desarrollo web, aplicaciones móviles, 
                        automatizaciones y soluciones digitales personalizadas para empresas de todos los tamaños.
                    </p>

                    <h3 class="text-xl font-bold text-white mb-3">¿Qué hace LDX Software?</h3>
                    <p class="text-gray-300 mb-4">
                        En LDX Software nos dedicamos a:
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-start text-gray-300">
                            <i class="fas fa-check text-green-400 mr-3 mt-1"></i>
                            <span><strong>Desarrollo de Páginas Web</strong>: Creamos sitios web profesionales, responsivos y optimizados para SEO</span>
                        </li>
                        <li class="flex items-start text-gray-300">
                            <i class="fas fa-check text-green-400 mr-3 mt-1"></i>
                            <span><strong>Aplicaciones Móviles</strong>: Desarrollamos apps nativas y multiplataforma para Android e iOS</span>
                        </li>
                        <li class="flex items-start text-gray-300">
                            <i class="fas fa-check text-green-400 mr-3 mt-1"></i>
                            <span><strong>Automatizaciones</strong>: Implementamos sistemas de automatización de procesos empresariales</span>
                        </li>
                        <li class="flex items-start text-gray-300">
                            <i class="fas fa-check text-green-400 mr-3 mt-1"></i>
                            <span><strong>E-commerce</strong>: Creamos tiendas online completas con pasarelas de pago integradas</span>
                        </li>
                        <li class="flex items-start text-gray-300">
                            <i class="fas fa-check text-green-400 mr-3 mt-1"></i>
                            <span><strong>Sistemas Web Personalizados</strong>: Desarrollamos soluciones a medida según necesidades específicas</span>
                        </li>
                    </ul>

                    <h3 class="text-xl font-bold text-white mb-3">Nuestra Misión</h3>
                    <p class="text-gray-300 mb-6">
                        Transformar ideas en realidad digital, brindando soluciones tecnológicas innovadoras 
                        que impulsen el crecimiento de nuestros clientes en Perú y Latinoamérica.
                    </p>

                    <h3 class="text-xl font-bold text-white mb-3">¿Por qué elegir LDX Software?</h3>
                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-800/50 rounded-lg p-4">
                            <i class="fas fa-users text-blue-400 text-2xl mb-2"></i>
                            <h4 class="text-white font-semibold mb-2">Equipo Experto</h4>
                            <p class="text-gray-400 text-sm">Desarrolladores con experiencia en las últimas tecnologías</p>
                        </div>
                        <div class="bg-gray-800/50 rounded-lg p-4">
                            <i class="fas fa-rocket text-purple-400 text-2xl mb-2"></i>
                            <h4 class="text-white font-semibold mb-2">Soluciones Innovadoras</h4>
                            <p class="text-gray-400 text-sm">Implementamos las mejores prácticas y tecnologías modernas</p>
                        </div>
                        <div class="bg-gray-800/50 rounded-lg p-4">
                            <i class="fas fa-headset text-green-400 text-2xl mb-2"></i>
                            <h4 class="text-white font-semibold mb-2">Soporte Continuo</h4>
                            <p class="text-gray-400 text-sm">Acompañamiento durante y después del desarrollo</p>
                        </div>
                        <div class="bg-gray-800/50 rounded-lg p-4">
                            <i class="fas fa-map-marker-alt text-yellow-400 text-2xl mb-2"></i>
                            <h4 class="text-white font-semibold mb-2">Empresa Peruana</h4>
                            <p class="text-gray-400 text-sm">Conocemos el mercado local y sus necesidades</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-white mb-3">Contacto</h3>
                    <p class="text-gray-300 mb-4">
                        <strong>Sitio web</strong>: <a href="https://ldxsoftware.com.pe" class="text-blue-400 hover:underline">https://ldxsoftware.com.pe</a><br>
                        <strong>Ubicación</strong>: Perú<br>
                        <strong>Servicios</strong>: Desarrollo Web, Apps Móviles, Automatizaciones, E-commerce
                    </p>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center">
                <a href="<?php echo BASE_URL; ?>#contacto" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-envelope"></i>
                    Contáctanos Ahora
                </a>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include __DIR__ . '/app/includes/footer.php'; ?>
</body>
</html>
