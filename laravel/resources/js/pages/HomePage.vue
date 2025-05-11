<!-- Crée l'affichage de la page d'accueil -->
<template>
  <div class="home-page">
    <div class="home-content">
      <h1>Histoires Interactives</h1>
      <div class="home-description">
        <p>Bienvenue dans le monde des histoires interactives où vos choix comptent.</p>
        <p>Explorez nos histoires et plongez dans des aventures immersives.</p>
      </div>
      
      <div class="home-actions">
        <button @click="goToStories">Explorer les histoires</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

function goToStories() {
  router.push('/stories');
}
</script>

<style scoped>
.home-page {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  text-align: center;
}

.home-content {
  max-width: 800px;
  padding: 20px;
}

button {
  background-color: #5a1414;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  margin-top: 20px;
}
</style>