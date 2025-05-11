<!-- Crée l'affichage de la page des histoires -->
<template>
  <div class="stories-page">
    <h1>Histoires disponibles</h1>
    <StoryList />
  </div>
</template>

<script setup>
import StoryList from '../components/StoryList.vue'
</script>

<style scoped>
.stories-page {
  max-width: 1200px;
  margin-top: 50px;
  padding: 20px;
}
h1 {
  margin-bottom: 30px;
  font-size: larger;
}
</style>