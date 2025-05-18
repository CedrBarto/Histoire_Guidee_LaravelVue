//import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { setDefaultBaseUrl, setDefaultHeaders } from './utils/fetchJson';

// Configuration de l'API
setDefaultBaseUrl('/api');

// Configure le CSRF token pour Laravel
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
  setDefaultHeaders({
    'X-CSRF-TOKEN': token
  });
}

// Crée une seule instance d'application
const app = createApp(App);

// Utilise le routeur
app.use(router);

// Monte l'application
app.mount('#app');

