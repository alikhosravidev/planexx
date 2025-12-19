// Service Worker برای PWA
const CACHE_NAME = 'sapl-pwa-v1';
const urlsToCache = [
  '/app/',
  '/app/index.php',
  '/app/personalized.php',
  '/app/analytics.php',
  '/app/profile.php',
  '/assets/css/variables.css',
  '/assets/css/main.css',
  '/assets/js/utils.js',
  '/assets/js/app.js'
];

// نصب Service Worker
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('Cache opened');
        return cache.addAll(urlsToCache);
      })
  );
});

// فعال‌سازی Service Worker
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// رهگیری درخواست‌ها
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        // اگر در cache بود، از cache بده
        if (response) {
          return response;
        }
        
        // وگرنه از شبکه بگیر
        return fetch(event.request).then(
          (response) => {
            // بررسی که آیا response معتبر است
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }

            // کلون response برای ذخیره در cache
            const responseToCache = response.clone();

            caches.open(CACHE_NAME)
              .then((cache) => {
                cache.put(event.request, responseToCache);
              });

            return response;
          }
        );
      })
      .catch(() => {
        // اگر آفلاین بود، صفحه آفلاین نشان بده
        return caches.match('/app/index.php');
      })
  );
});
