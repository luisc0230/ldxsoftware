<!-- Core JavaScript Libraries -->

<!-- AOS (Animate On Scroll) -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- GSAP (GreenSock Animation Platform) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<!-- Swiper.js for carousels -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">

<!-- Lottie for animations -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>

<!-- Main Application JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
    
    // Register GSAP plugins
    gsap.registerPlugin(ScrollTrigger);
    
    // GSAP Animations
    initGSAPAnimations();
    
    // Initialize Swiper carousels
    initSwipers();
    
    // Form enhancements
    initFormEnhancements();
    
    // Lazy loading for images
    initLazyLoading();
    
    // Performance optimizations
    initPerformanceOptimizations();
});

// GSAP Animations
function initGSAPAnimations() {
    // Hero section animations
    if (document.querySelector('.hero-section')) {
        const tl = gsap.timeline();
        
        tl.from('.hero-title', {
            duration: 1,
            y: 50,
            opacity: 0,
            ease: 'power3.out'
        })
        .from('.hero-subtitle', {
            duration: 0.8,
            y: 30,
            opacity: 0,
            ease: 'power3.out'
        }, '-=0.5')
        .from('.hero-cta', {
            duration: 0.6,
            y: 20,
            opacity: 0,
            ease: 'power3.out'
        }, '-=0.3');
    }
    
    // Parallax effects
    gsap.utils.toArray('.parallax').forEach(element => {
        gsap.to(element, {
            yPercent: -50,
            ease: 'none',
            scrollTrigger: {
                trigger: element,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    });
    
    // Counter animations
    gsap.utils.toArray('.counter').forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const obj = { value: 0 };
        
        gsap.to(obj, {
            value: target,
            duration: 2,
            ease: 'power2.out',
            onUpdate: () => {
                counter.textContent = Math.round(obj.value);
            },
            scrollTrigger: {
                trigger: counter,
                start: 'top 80%',
                once: true
            }
        });
    });
    
    // Stagger animations for cards
    gsap.utils.toArray('.stagger-item').forEach((item, index) => {
        gsap.from(item, {
            duration: 0.6,
            y: 50,
            opacity: 0,
            delay: index * 0.1,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: item,
                start: 'top 85%',
                once: true
            }
        });
    });
}

// Swiper Carousels
function initSwipers() {
    // Testimonials carousel
    if (document.querySelector('.testimonials-swiper')) {
        new Swiper('.testimonials-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                }
            }
        });
    }
    
    // Portfolio carousel
    if (document.querySelector('.portfolio-swiper')) {
        new Swiper('.portfolio-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                }
            }
        });
    }
}

// Form Enhancements
function initFormEnhancements() {
    // Real-time form validation
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('blur', validateField);
            input.addEventListener('input', clearErrors);
        });
        
        form.addEventListener('submit', handleFormSubmit);
    });
    
    // File upload enhancements
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', handleFileUpload);
    });
}

// Field validation
function validateField(e) {
    const field = e.target;
    const value = field.value.trim();
    const type = field.type;
    const required = field.hasAttribute('required');
    
    clearFieldError(field);
    
    if (required && !value) {
        showFieldError(field, 'Este campo es requerido');
        return false;
    }
    
    if (value) {
        switch (type) {
            case 'email':
                if (!isValidEmail(value)) {
                    showFieldError(field, 'Ingresa un email válido');
                    return false;
                }
                break;
            case 'tel':
                if (!isValidPhone(value)) {
                    showFieldError(field, 'Ingresa un teléfono válido');
                    return false;
                }
                break;
            case 'url':
                if (!isValidURL(value)) {
                    showFieldError(field, 'Ingresa una URL válida');
                    return false;
                }
                break;
        }
        
        // Custom validation rules
        const minLength = field.getAttribute('data-min-length');
        if (minLength && value.length < parseInt(minLength)) {
            showFieldError(field, `Mínimo ${minLength} caracteres`);
            return false;
        }
        
        const maxLength = field.getAttribute('data-max-length');
        if (maxLength && value.length > parseInt(maxLength)) {
            showFieldError(field, `Máximo ${maxLength} caracteres`);
            return false;
        }
    }
    
    return true;
}

// Clear field errors
function clearErrors(e) {
    clearFieldError(e.target);
}

function clearFieldError(field) {
    field.classList.remove('border-red-500', 'focus:border-red-500');
    field.classList.add('border-gray-300', 'focus:border-primary-500');
    
    const errorElement = field.parentNode.querySelector('.field-error');
    if (errorElement) {
        errorElement.remove();
    }
}

function showFieldError(field, message) {
    field.classList.remove('border-gray-300', 'focus:border-primary-500');
    field.classList.add('border-red-500', 'focus:border-red-500');
    
    const errorElement = document.createElement('div');
    errorElement.className = 'field-error text-red-500 text-sm mt-1';
    errorElement.textContent = message;
    
    field.parentNode.appendChild(errorElement);
}

// Form submission handler
async function handleFormSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    // Validate all fields
    let isValid = true;
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        if (!validateField({ target: input })) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        return;
    }
    
    // Show loading state
    submitBtn.textContent = 'Enviando...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch(form.action || window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('¡Mensaje enviado correctamente!', 'success');
            form.reset();
        } else {
            showNotification(data.message || 'Error al enviar el mensaje', 'error');
            
            // Show field-specific errors
            if (data.errors) {
                Object.keys(data.errors).forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        showFieldError(field, data.errors[fieldName]);
                    }
                });
            }
        }
    } catch (error) {
        showNotification('Error de conexión. Intenta nuevamente.', 'error');
    }
    
    // Reset button
    submitBtn.textContent = originalText;
    submitBtn.disabled = false;
}

// File upload handler
function handleFileUpload(e) {
    const input = e.target;
    const files = input.files;
    const maxSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    
    Array.from(files).forEach(file => {
        if (file.size > maxSize) {
            showNotification('El archivo es muy grande. Máximo 5MB.', 'error');
            input.value = '';
            return;
        }
        
        if (!allowedTypes.includes(file.type)) {
            showNotification('Tipo de archivo no permitido.', 'error');
            input.value = '';
            return;
        }
    });
}

// Utility functions
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function isValidPhone(phone) {
    const regex = /^[\+]?[1-9][\d]{0,15}$/;
    return regex.test(phone.replace(/\s/g, ''));
}

function isValidURL(url) {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-4 z-50 max-w-md p-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ${
        type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
        type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
        'bg-blue-100 border border-blue-400 text-blue-700'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-1">${message}</div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-current hover:opacity-75">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

// Lazy loading for images
function initLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

// Performance optimizations
function initPerformanceOptimizations() {
    // Debounce scroll events
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }
        scrollTimeout = setTimeout(() => {
            // Custom scroll handlers here
        }, 10);
    });
    
    // Preload critical resources
    const criticalImages = [
        '<?php echo asset("images/hero-bg.jpg"); ?>',
        '<?php echo asset("images/logo.png"); ?>'
    ];
    
    criticalImages.forEach(src => {
        const link = document.createElement('link');
        link.rel = 'preload';
        link.as = 'image';
        link.href = src;
        document.head.appendChild(link);
    });
}

// Service Worker registration (if available)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('<?php echo url("sw.js"); ?>')
            .then(registration => {
                console.log('SW registered: ', registration);
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
</script>

<!-- Additional JavaScript files -->
<?php if (isset($js_files) && is_array($js_files)): ?>
    <?php foreach ($js_files as $js_file): ?>
        <script src="<?php echo asset('js/' . $js_file); ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Google Analytics (replace with your tracking ID) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GA_TRACKING_ID"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'GA_TRACKING_ID');
</script>

<!-- Facebook Pixel (replace with your pixel ID) -->
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', 'YOUR_PIXEL_ID');
    fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=YOUR_PIXEL_ID&ev=PageView&noscript=1"/></noscript>
