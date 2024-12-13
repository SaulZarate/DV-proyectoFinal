self.addEventListener('install', (e) => {
    e.waitUntil(
      caches.open('fox-store').then((cache) => cache.addAll([
        '../../detalleSalida.html',
        '../../login.html',
        '../../admin.html',

        '../../img/favicon.png',
        '../../img/logo-md.png',
        '../../img/logo.png',

        '../css/style.css',
        'main.css',
        '../../../assets/vendor/bootstrap/css/bootstrap.min.css',
        '../../../assets/vendor/bootstrap-icons/bootstrap-icons.css',
        '../../../assets/vendor/quill/quill.snow.css',
        '../../../assets/vendor/quill/quill.bubble.css',

        '../../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
        '../../../assets/vendor/quill/quill.js',
        '../../../assets/vendor/sortable/main.js',

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