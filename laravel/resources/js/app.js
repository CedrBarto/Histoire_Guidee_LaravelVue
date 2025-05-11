//import './bootstrap';

//import Alpine from 'alpinejs';

//window.Alpine = Alpine;
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { setDefaultBaseUrl, setDefaultHeaders } from './utils/fetchJson';

// Configuration de l'API
setDefaultBaseUrl('/api');

// Configurer le CSRF token pour Laravel
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
  setDefaultHeaders({
    'X-CSRF-TOKEN': token
  });
}

// Créer une seule instance d'application
const app = createApp(App);

// Utiliser le routeur
app.use(router);

// Monter l'application
app.mount('#app');

