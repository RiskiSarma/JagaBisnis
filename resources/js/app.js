import './bootstrap';

import Alpine from 'alpinejs';

// Pastikan Alpine tersedia secara global
window.Alpine = Alpine;

// Start Alpine
Alpine.start();

// Debug: Tampilkan pesan bahwa JS sudah load
console.log('Alpine.js started successfully');