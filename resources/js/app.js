import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';

// Import semua Vue components yang bisa di-mount dari Blade
import OrderTracker from './components/OrderTracker.vue';

// Map nama komponen -> class komponen (string key diambil dari data-vue-component di HTML)
const components = {
    'OrderTracker': OrderTracker,
};

// Multi-mount: Setiap <div data-vue-component="NamaKomponen"> di halaman
// akan di-mount sebagai Vue app terpisah berisi Pinia store masing-masing
document.querySelectorAll('[data-vue-component]').forEach(el => {
    const name = el.dataset.vueComponent;
    const component = components[name];
    if (!component) return;

    const app = createApp(component, {
        // Teruskan semua data-* attribute sebagai props ke komponen
        ...el.dataset
    });
    app.use(createPinia());
    app.mount(el);
});
