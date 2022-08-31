// (A) FILES TO CACHE
const cName = "cb-pwa",
cFiles = [
  "assets/favicon.png",
  "assets/ico-512.png",
  "assets/login.jpg",
  "assets/maticon.woff2",
  "assets/bootstrap.bundle.min.js",
  "assets/bootstrap.bundle.min.js.map",
  "assets/bootstrap.min.css",
  "assets/bootstrap.min.css.map",
  "assets/PAGE-cb.js"
  // @TODO - ADD MORE OF YOUR OWN TO CACHE
];

// (B) CREATE/INSTALL CACHE
self.addEventListener("install", (evt) => {
  evt.waitUntil(
    caches.open(cName)
    .then((cache) => { return cache.addAll(cFiles); })
    .catch((err) => { console.error(err) })
  );
});

// (C) LOAD FROM CACHE FIRST, FALLBACK TO NETWORK IF NOT FOUND
self.addEventListener("fetch", (evt) => {
  evt.respondWith(
    caches.match(evt.request)
    .then((res) => { return res || fetch(evt.request); })
  );
});