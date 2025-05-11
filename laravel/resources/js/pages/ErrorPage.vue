<template>
  <div class="error-page">
    <div class="error-container">
      <div class="skull">💀</div>
      <h1>{{ title || 'Oups ! Page introuvable' }}</h1>
      <p>{{ message || 'La page que vous recherchez n\'existe pas ou a été déplacée.' }}</p>
      <div class="error-actions">
        <button @click="goBack" class="back-button">Retour</button>
        <button @click="goHome" class="home-button">Accueil</button>
      </div>
      <div v-if="showErrorDetails" class="error-details">
        <p>Code d'erreur: {{ errorCode || 404 }}</p>
        <p>Path: {{ path }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import HomePage from './HomePage.vue';

const props = defineProps({
  errorCode: {
    type: Number,
    default: 404
  },
  title: {
    type: String,
    default: ''
  },
  message: {
    type: String,
    default: ''
  },
  showErrorDetails: {
    type: Boolean,
    default: false
  }
});

const router = useRouter();
const route = useRoute();
const path = ref(route.path);

function goBack() {
  router.go(-1);
}

function goHome() {
  router.push('/home');
}
</script>

<style scoped>
.error-page {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  padding: 20px;
}

.error-container {
  max-width: 600px;
  padding: 40px;
  background-color: #2a2a2a;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
  text-align: center;
}

h1 {
  font-size: 2.5rem;
  margin-bottom: 20px;
  color: #ff6b6b;
}

p {
  font-size: 1.2rem;
  margin-bottom: 30px;
  line-height: 1.6;
}

.error-actions {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-bottom: 30px;
}

.back-button, .home-button {
  background-color: #5a1414;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 4px;
  font-weight: bold;
  transition: background-color 0.3s;
  cursor: pointer;
}

.back-button:hover, .home-button:hover {
  background-color: #7a1c1c;
}

.skull {
  font-size: 8rem;
  margin-bottom: 20px;
  animation: float 3s ease-in-out infinite;
}

.error-details {
  margin-top: 20px;
  padding: 15px;
  background-color: rgba(0, 0, 0, 0.3);
  border-radius: 8px;
  font-size: 0.9rem;
}

.error-details p {
  margin: 5px 0;
  font-size: 0.9rem;
}

@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
  100% { transform: translateY(0px); }
}
</style>
