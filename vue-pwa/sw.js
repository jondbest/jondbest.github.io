importScripts('workbox-sw.prod.js')

const workboxSW = new WorkboxSW({ clientsClaim: true })

workboxSW.precache([
  {
    "url": "https://jondbest.github.io/vue-pwa/012cf6a10129e2275d79d6adac7f3b02.woff",
    "revision": "012cf6a10129e2275d79d6adac7f3b02"
  },
  {
    "url": "https://jondbest.github.io/vue-pwa/0f964a72f8fbdf9c8d4e0122b8effb40.woff",
    "revision": "0f964a72f8fbdf9c8d4e0122b8effb40"
  },
  {
    "url": "https://jondbest.github.io/vue-pwa/29f80b00a7b4641183f96f08be374697.ttf",
    "revision": "29f80b00a7b4641183f96f08be374697"
  },
  {
    "url": "https://jondbest.github.io/vue-pwa/681fa39a79c22f0035a0720e2b2bda3b.eot",
    "revision": "681fa39a79c22f0035a0720e2b2bda3b"
  },
  {
    "url": "https://jondbest.github.io/vue-pwa/74e6e6c3dcfca56767adabe83a510f2e.svg",
    "revision": "74e6e6c3dcfca56767adabe83a510f2e"
  },
  {
    "url": "https://jondbest.github.io/vue-pwa/a37b0c01c0baf1888ca812cc0508f6e2.ttf",
    "revision": "a37b0c01c0baf1888ca812cc0508f6e2"
  },
  {
    "url": "https://jondbest.github.io/vue-pwa/app.145b4a6ab8abb40835bf.js",
    "revision": "41f6081b559eb07a5d9994078b894743"
  },
  {
    "url": "https://jondbest.github.io/vue-pwa/e79bfd88537def476913f3ed52f4f4b3.eot",
    "revision": "e79bfd88537def476913f3ed52f4f4b3"
  },
  {
    "url": "https://jondbest.github.io/vue-pwa/index.html",
    "revision": "6cd6ebf17d978adc854cc78352fc10a1"
  },
  {
    "url": "https://jondbest.github.io/vue-pwa/vendor.b4565a500a60de89393f.js",
    "revision": "323bc66288412ce62d6523eea4a023d2"
  },
  {
    "url": "https://jondbest.github.io/vue-pwa/workbox-sw.prod.js",
    "revision": "e5f207838d7fd9c81835d5705a73cfa2"
  }
])

workboxSW.router.registerRoute(
  'https://jondbest.github.io/vue-pwa/(.*)',
  workboxSW.strategies.networkFirst({networkTimeoutSeconds: 3})
)

workboxSW.router.registerNavigationRoute('index.html', {
  whitelist: [/./]
})

importScripts('https://unpkg.com/workbox-routing@0.0.2/build/importScripts/workbox-routing.dev.v0.0.2.js');

const router = new workbox.routing.Router()
const crossOriginRedditAPI = new workbox.routing.ExpressRoute({
  path: 'https://www.reddit.com/r/(.*)',
  handler: ({ event }) => {
    return fetch(event.request)
  }
})

router.registerRoutes({
  routes: [crossOriginRedditAPI]
})

self.addEventListener('install', () => self.skipWaiting())
self.addEventListener('activate', () => self.clients.claim())
