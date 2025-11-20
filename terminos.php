<?php
// Términos y Condiciones - LDX Software
$page_title = 'Términos y Condiciones - LDX Software';
$page_description = 'Términos y condiciones de uso de los servicios de LDX Software';

// Define access constant and include config
// Define access constant and include config
define('LDX_ACCESS', true);
require_once __DIR__ . '/config/config.php';

// Include header
require_once __DIR__ . '/app/includes/header.php';
?>
<body class="bg-black text-white">

<?php
// Include navbar
require_once __DIR__ . '/app/includes/navbar.php';
?>

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
                Términos y <span class="text-white">Condiciones</span>
            </h1>
            <p class="text-xl text-gray-300 mb-8">
                Condiciones de uso de nuestros servicios
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
                        Bienvenido a LDX Software. Estos términos y condiciones describen las reglas y regulaciones para el uso del sitio web de LDX Software, ubicado en https://ldxsoftware.com.pe.
                    </p>
                </div>

                <div class="space-y-12">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">1. Aceptación de los Términos</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Al acceder y utilizar este sitio web, aceptas cumplir con estos términos y condiciones de uso. Si no estás de acuerdo con alguna parte de estos términos, no debes utilizar nuestro sitio web.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">2. Servicios Ofrecidos</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            LDX Software ofrece servicios de desarrollo de software que incluyen, pero no se limitan a:
                        </p>
                        <ul class="list-disc list-inside text-gray-300 space-y-2 ml-4">
                            <li>Diseño y desarrollo de sitios web</li>
                            <li>Desarrollo de aplicaciones móviles</li>
                            <li>Sistemas de comercio electrónico</li>
                            <li>Automatización de procesos</li>
                            <li>Web scraping y extracción de datos</li>
                            <li>Consultoría tecnológica</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">3. Uso del Sitio Web</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Al utilizar nuestro sitio web, te comprometes a:
                        </p>
                        <ul class="list-disc list-inside text-gray-300 space-y-2 ml-4">
                            <li>Proporcionar información veraz y actualizada</li>
                            <li>No utilizar el sitio para actividades ilegales o no autorizadas</li>
                            <li>No intentar acceder a áreas restringidas del sitio</li>
                            <li>Respetar los derechos de propiedad intelectual</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">4. Propiedad Intelectual</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Todo el contenido de este sitio web, incluyendo textos, gráficos, logotipos, imágenes, y software, es propiedad de LDX Software y está protegido por las leyes de derechos de autor y propiedad intelectual.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">5. Limitación de Responsabilidad</h2>
                        <p class="text-gray-300 leading-relaxed">
                            LDX Software no será responsable por daños directos, indirectos, incidentales, especiales o consecuentes que resulten del uso o la imposibilidad de usar nuestros servicios o sitio web.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">6. Modificaciones</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Nos reservamos el derecho de modificar estos términos y condiciones en cualquier momento. Las modificaciones entrarán en vigor inmediatamente después de su publicación en el sitio web.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">7. Ley Aplicable</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Estos términos y condiciones se rigen por las leyes de la República del Perú. Cualquier disputa será resuelta en los tribunales competentes de Lima, Perú.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">8. Contacto</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Si tienes preguntas sobre estos términos y condiciones, puedes contactarnos:
                        </p>
                        <div class="bg-gray-900 p-6 rounded-lg">
                            <p class="text-gray-300 mb-2"><strong>Email:</strong> contacto@ldxsoftware.com.pe</p>
                            <p class="text-gray-300 mb-2"><strong>WhatsApp:</strong> +51 905 940 757</p>
                            <p class="text-gray-300"><strong>Dirección:</strong> Lima, Perú</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
require_once __DIR__ . '/app/includes/footer.php';
?>

</body>
</html>
