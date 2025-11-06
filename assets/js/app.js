/**
 * LDX Software - Main Application JavaScript
 * 
 * This file contains the core JavaScript functionality for the LDX Software website.
 * It handles interactions, animations, and various UI enhancements.
 * 
 * @author LDX Software
 * @version 1.0
 */

class LDXApp {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeComponents();
        this.setupPerformanceOptimizations();
    }

    bindEvents() {
        document.addEventListener('DOMContentLoaded', () => {
            this.onDOMContentLoaded();
        });

        window.addEventListener('load', () => {
            this.onWindowLoad();
        });

        window.addEventListener('resize', this.debounce(() => {
            this.onWindowResize();
        }, 250));

        window.addEventListener('scroll', this.throttle(() => {
            this.onWindowScroll();
        }, 16));
    }

    onDOMContentLoaded() {
        console.log('LDX Software - Website Loaded');
        
        // Initialize components
        this.initSmoothScrolling();
        this.initLazyLoading();
        this.initFormValidation();
        this.initTooltips();
        this.initModals();
        this.initCounters();
        this.initProgressBars();
        
        // Performance monitoring
        this.trackPerformance();
    }

    onWindowLoad() {
        // Hide loading screen if exists
        const loader = document.querySelector('.page-loader');
        if (loader) {
            loader.classList.add('fade-out');
            setTimeout(() => loader.remove(), 500);
        }

        // Initialize heavy components after load
        this.initParticleEffects();
        this.preloadCriticalImages();
    }

    onWindowResize() {
        // Handle responsive adjustments
        this.adjustMobileNavigation();
        this.recalculateAnimations();
    }

    onWindowScroll() {
        // Handle scroll-based animations and effects
        this.updateScrollProgress();
        this.handleParallaxEffects();
        this.updateActiveNavigation();
    }

    // Smooth Scrolling
    initSmoothScrolling() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                const targetId = link.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    const headerOffset = 80;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Lazy Loading
    initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            img.classList.add('loaded');
                        }
                        
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                        }
                        
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    // Form Validation
    initFormValidation() {
        const forms = document.querySelectorAll('form[data-validate]');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, textarea, select');
            
            inputs.forEach(input => {
                input.addEventListener('blur', (e) => this.validateField(e.target));
                input.addEventListener('input', (e) => this.clearFieldErrors(e.target));
            });
            
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        const required = field.hasAttribute('required');
        
        this.clearFieldErrors(field);
        
        if (required && !value) {
            this.showFieldError(field, 'Este campo es requerido');
            return false;
        }
        
        if (value) {
            switch (type) {
                case 'email':
                    if (!this.isValidEmail(value)) {
                        this.showFieldError(field, 'Ingresa un email válido');
                        return false;
                    }
                    break;
                case 'tel':
                    if (!this.isValidPhone(value)) {
                        this.showFieldError(field, 'Ingresa un teléfono válido');
                        return false;
                    }
                    break;
                case 'url':
                    if (!this.isValidURL(value)) {
                        this.showFieldError(field, 'Ingresa una URL válida');
                        return false;
                    }
                    break;
            }
            
            const minLength = field.getAttribute('data-min-length');
            if (minLength && value.length < parseInt(minLength)) {
                this.showFieldError(field, `Mínimo ${minLength} caracteres`);
                return false;
            }
        }
        
        return true;
    }

    clearFieldErrors(field) {
        field.classList.remove('border-red-500', 'focus:border-red-500');
        field.classList.add('border-gray-300', 'focus:border-primary-500');
        
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    showFieldError(field, message) {
        field.classList.remove('border-gray-300', 'focus:border-primary-500');
        field.classList.add('border-red-500', 'focus:border-red-500');
        
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error text-red-500 text-sm mt-1';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }

    async handleFormSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Validate all fields
        let isValid = true;
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            this.showNotification('Por favor corrige los errores en el formulario', 'error');
            return;
        }
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
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
                this.showNotification(data.message || '¡Mensaje enviado correctamente!', 'success');
                form.reset();
                
                // Track conversion
                this.trackConversion('form_submission', {
                    form_name: form.getAttribute('name') || 'contact_form'
                });
            } else {
                this.showNotification(data.message || 'Error al enviar el mensaje', 'error');
                
                if (data.errors) {
                    Object.keys(data.errors).forEach(fieldName => {
                        const field = form.querySelector(`[name="${fieldName}"]`);
                        if (field) {
                            this.showFieldError(field, data.errors[fieldName]);
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Form submission error:', error);
            this.showNotification('Error de conexión. Intenta nuevamente.', 'error');
        }
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }

    // Tooltips
    initTooltips() {
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        
        tooltipElements.forEach(element => {
            const tooltipText = element.getAttribute('data-tooltip');
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip-text';
            tooltip.textContent = tooltipText;
            
            element.classList.add('tooltip');
            element.appendChild(tooltip);
        });
    }

    // Modals
    initModals() {
        const modalTriggers = document.querySelectorAll('[data-modal]');
        
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modalId = trigger.getAttribute('data-modal');
                this.openModal(modalId);
            });
        });
        
        // Close modal on backdrop click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-backdrop')) {
                this.closeModal();
            }
        });
        
        // Close modal on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('modal-enter');
            
            setTimeout(() => {
                modal.classList.remove('modal-enter');
                modal.classList.add('modal-enter-active');
            }, 10);
            
            document.body.style.overflow = 'hidden';
        }
    }

    closeModal() {
        const activeModal = document.querySelector('.modal-enter-active');
        if (activeModal) {
            activeModal.classList.remove('modal-enter-active');
            activeModal.classList.add('modal-exit-active');
            
            setTimeout(() => {
                activeModal.classList.add('hidden');
                activeModal.classList.remove('modal-exit-active');
                document.body.style.overflow = '';
            }, 300);
        }
    }

    // Counters
    initCounters() {
        const counters = document.querySelectorAll('.counter');
        
        if ('IntersectionObserver' in window) {
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animateCounter(entry.target);
                        counterObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            counters.forEach(counter => {
                counterObserver.observe(counter);
            });
        }
    }

    animateCounter(element) {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = parseInt(element.getAttribute('data-duration')) || 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString();
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString();
            }
        }, 16);
    }

    // Progress Bars
    initProgressBars() {
        const progressBars = document.querySelectorAll('.progress-bar');
        
        if ('IntersectionObserver' in window) {
            const progressObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const progressFill = entry.target.querySelector('.progress-fill');
                        const percentage = entry.target.getAttribute('data-percentage');
                        
                        if (progressFill && percentage) {
                            setTimeout(() => {
                                progressFill.style.width = percentage + '%';
                            }, 200);
                        }
                        
                        progressObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            progressBars.forEach(bar => {
                progressObserver.observe(bar);
            });
        }
    }

    // Notifications
    showNotification(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 z-50 max-w-md p-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ${
            type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
            type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
            type === 'warning' ? 'bg-yellow-100 border border-yellow-400 text-yellow-700' :
            'bg-blue-100 border border-blue-400 text-blue-700'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0 mr-3">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                </div>
                <div class="flex-1">${message}</div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-current hover:opacity-75">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto-hide
        if (duration > 0) {
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }, duration);
        }
        
        return notification;
    }

    // Utility Functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    isValidPhone(phone) {
        const regex = /^[\+]?[1-9][\d]{0,15}$/;
        return regex.test(phone.replace(/\s/g, ''));
    }

    isValidURL(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }

    // Performance Tracking
    trackPerformance() {
        if ('performance' in window) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    
                    console.log('Performance Metrics:', {
                        'DNS Lookup': perfData.domainLookupEnd - perfData.domainLookupStart,
                        'TCP Connection': perfData.connectEnd - perfData.connectStart,
                        'Request': perfData.responseStart - perfData.requestStart,
                        'Response': perfData.responseEnd - perfData.responseStart,
                        'DOM Processing': perfData.domContentLoadedEventStart - perfData.responseEnd,
                        'Load Complete': perfData.loadEventEnd - perfData.navigationStart
                    });
                }, 0);
            });
        }
    }

    // Analytics Tracking
    trackConversion(eventName, data = {}) {
        // Google Analytics 4
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, data);
        }
        
        // Facebook Pixel
        if (typeof fbq !== 'undefined') {
            fbq('track', eventName, data);
        }
        
        console.log('Conversion tracked:', eventName, data);
    }

    // Additional helper methods would go here...
    updateScrollProgress() {
        const scrollProgress = document.querySelector('.scroll-progress');
        if (scrollProgress) {
            const scrollTop = window.pageYOffset;
            const docHeight = document.body.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            scrollProgress.style.width = scrollPercent + '%';
        }
    }

    handleParallaxEffects() {
        const parallaxElements = document.querySelectorAll('.parallax');
        const scrollTop = window.pageYOffset;
        
        parallaxElements.forEach(element => {
            const speed = element.getAttribute('data-speed') || 0.5;
            const yPos = -(scrollTop * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    }

    updateActiveNavigation() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            if (sectionTop <= 100) {
                currentSection = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${currentSection}`) {
                link.classList.add('active');
            }
        });
    }

    initializeComponents() {
        // Initialize any additional components here
        this.initCustomSelects();
        this.initImageGalleries();
        this.initCookieConsent();
    }

    initCustomSelects() {
        // Custom select dropdown implementation
        const selects = document.querySelectorAll('.custom-select');
        selects.forEach(select => {
            // Implementation for custom select styling
        });
    }

    initImageGalleries() {
        // Image gallery/lightbox implementation
        const galleries = document.querySelectorAll('.image-gallery');
        galleries.forEach(gallery => {
            // Implementation for image gallery
        });
    }

    initCookieConsent() {
        // Cookie consent implementation
        if (!localStorage.getItem('cookieConsent')) {
            // Show cookie consent banner
        }
    }

    setupPerformanceOptimizations() {
        // Preload critical resources
        this.preloadCriticalResources();
        
        // Setup service worker if available
        this.registerServiceWorker();
    }

    preloadCriticalResources() {
        const criticalImages = [
            '/public/assets/images/logo.png',
            '/public/assets/images/hero-bg.jpg'
        ];
        
        criticalImages.forEach(src => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = src;
            document.head.appendChild(link);
        });
    }

    registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('SW registered: ', registration);
                    })
                    .catch(registrationError => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    }
}

// Initialize the application
const ldxApp = new LDXApp();
