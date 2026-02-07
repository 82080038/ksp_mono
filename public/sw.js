// Simple no-op service worker to avoid 404 and enable future offline features
const VERSION = 'ksp_mono-sw-v1';

self.addEventListener('install', (event) => {
  // Activate immediately
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  // Claim clients so updates take effect without reload
  event.waitUntil(self.clients.claim());
});

// Passthrough fetch; can be extended for caching if needed
self.addEventListener('fetch', () => {
  // No custom handling for now
});
