// sw.js - Service Worker minimal pour permettre l'installation PWA
self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    // Stratégie "Network First" ou simple passage pour le SEO/PWA
    event.respondWith(fetch(event.request));
});
