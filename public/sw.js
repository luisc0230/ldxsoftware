/**
 * LDX Software - Service Worker
 * 
 * Basic service worker for caching static assets and improving performance
 */

const CACHE_NAME = 'ldx-software-v1.0.0';
const urlsToCache = [
    '/',
    '/public/assets/css/custom.css',
    '/public/assets/js/app.js',
    '/public/assets/images/logo.png',
    '/public/assets/images/logo-white.png',
    'https://cdn.tailwindcss.com',
    'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
    'https://unpkg.com/aos@2.3.1/dist/aos.css',
    'https://unpkg.com/aos@2.3.1/dist/aos.js',
    'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js'
];

// Install event
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
            .catch(error => {
                console.log('Cache install failed:', error);
            })
    );
});

// Fetch event
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Return cached version or fetch from network
                if (response) {
                    return response;
                }
                
                return fetch(event.request).then(response => {
                    // Check if valid response
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }
                    
                    // Clone the response
                    const responseToCache = response.clone();
                    
                    caches.open(CACHE_NAME)
                        .then(cache => {
                            cache.put(event.request, responseToCache);
                        });
                    
                    return response;
                });
            })
            .catch(() => {
                // Return offline page if available
                if (event.request.destination === 'document') {
                    return caches.match('/offline.html');
                }
            })
    );
});

// Activate event
self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Background sync for form submissions
self.addEventListener('sync', event => {
    if (event.tag === 'contact-form-sync') {
        event.waitUntil(syncContactForm());
    }
});

async function syncContactForm() {
    // Handle offline form submissions
    const formData = await getStoredFormData();
    
    if (formData.length > 0) {
        for (const data of formData) {
            try {
                await fetch('/api/contact', {
                    method: 'POST',
                    body: data.formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                // Remove from storage after successful submission
                await removeStoredFormData(data.id);
            } catch (error) {
                console.log('Failed to sync form data:', error);
            }
        }
    }
}

async function getStoredFormData() {
    // Implementation would depend on IndexedDB or similar storage
    return [];
}

async function removeStoredFormData(id) {
    // Implementation would depend on IndexedDB or similar storage
}
