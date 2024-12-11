self.addEventListener('install', (e) => {
    e.waitUntil(
      caches.open('fox-store').then((cache) => cache.addAll([
        '../../index.html',
        '../../login.html',
        '../../admin.html',

        '../../img/favicon.png',
        '../../img/logo-md.png',
        '../../img/logo.png',
        'pwa.js',
      ])),
    );
});

self.addEventListener('fetch', (e) => {
    // console.log('Event fetch: '+e.request.url)
    e.respondWith(
        caches.match(e.request).then((response) => response || fetch(e.request)),
    );
});