<!-- Gère la logique de la liste des histoires -->
<template>
  <div class="stories-container">
    <div 
      v-for="story in stories" 
      :key="story.id" 
      class="story-card"
    >
      <div class="story-image" v-if="story.cover_image">
        <img :src="`/images/covers/${story.cover_image}`" :alt="story.title">
      </div>
      <div class="story-info">
        <h2>{{ story.title }}</h2>
        <p>{{ story.description }}</p>
        <div v-if="hasSavedProgress(story.id) && !isDeathScene(story.id)" class="story-actions">
          <button @click="continueStory(story.id)" class="btn-continue">
            Reprendre l'histoire
          </button>
          <button @click="startNewStory(story.id)" class="btn-new">
            Recommencer l'histoire
          </button>
        </div>
        <button v-else @click="startStory(story.id)" class="btn-start">
          Commencer l'histoire
        </button>
      </div>
    </div>
    <div v-if="loading" class="loading">Chargement des histoires...</div>
    <div v-else-if="error" class="error">
      <p>Une erreur s'est produite lors du chargement des histoires.</p>
      <button @click="loadStories">Réessayer</button>
      <p class="error-details">{{ error }}</p>
    </div>
  </div>
</template>
  
<script setup>
import { ref, onMounted, watch } from 'vue';
import { useFetchJson } from '../composables/useFetchJson';
import { useRouter } from 'vue-router';

const router = useRouter();
const stories = ref([]);
const loading = ref(true);
const error = ref(null);

// Chargement des histoires depuis l'API
function loadStories() {
  loading.value = true;
  error.value = null;
  
  // Appel à l'API pour récupérer la liste des histoires
  const { data, error: fetchError, loading: fetchLoading } = useFetchJson('/stories');
  
  watch(data, (newData) => {
    if (newData && newData.stories) {
      stories.value = newData.stories;
    } else if (newData) {
      // Gère les cas où la structure de la réponse est différente
      stories.value = Array.isArray(newData) ? newData : [newData];
    }
  });
  
  watch(fetchError, (newError) => {
    if (newError) {
      error.value = newError.toString();
    }
  });
  
  watch(fetchLoading, (isLoading) => {
    loading.value = isLoading;
  });
}

// Vérifie si une sauvegarde existe pour une histoire donnée
function hasSavedProgress(storyId) {
  const savedProgress = localStorage.getItem('story_progress');
  if (!savedProgress) return false;
  
  try {
    const progress = JSON.parse(savedProgress);
    return progress.storyId == storyId;
  } catch (e) {
    return false;
  }
}

// Vérifie si la dernière scène sauvegardée est une scène de mort ou de fin
function isDeathScene(storyId) {
  const savedProgress = localStorage.getItem('story_progress');
  if (!savedProgress) return false;
  
  try {
    const progress = JSON.parse(savedProgress);
    // Vérifie que c'est la bonne histoire et que le flag de fin ou de mort est activé
    return (
      progress.storyId == storyId && 
      (progress.isDeathScene === true || progress.isEnding === true)
    );
  } catch (e) {
    return false;
  }
}

// Permet de reprendre une histoire là où elle a été laissée
function continueStory(storyId) {
  const savedProgress = localStorage.getItem('story_progress');
  if (!savedProgress) return startStory(storyId);
  
  try {
    const progress = JSON.parse(savedProgress);
    if (progress.storyId == storyId && progress.sceneId) {
      router.push(`/story/${storyId}/scene/${progress.sceneId}`);
    } else {
      // Si la sauvegarde est incomplète, recommence l'histoire
      startStory(storyId);
    }
  } catch (e) {
    startStory(storyId);
  }
}

// Démarre une nouvelle partie pour une histoire (efface la sauvegarde existante)
function startNewStory(storyId) {
  localStorage.removeItem('story_progress');
  startStory(storyId);
}

// Démarre une histoire depuis la première scène
async function startStory(storyId) {
  loading.value = true;
  try {
    // Appel à l'API pour obtenir la première scène publique
    const response = await fetch('/api/public/first-scene');
    const data = await response.json();
    
    if (data.scene && data.scene.id) {
      // Enregistre la progression initiale dans le localStorage
      const initialProgress = {
        storyId: storyId,
        sceneId: data.scene.id,
        timestamp: Date.now(),
        isDeathScene: false, // Ce n'est pas une scène de mort au début
        isEnding: false      // Ce n'est pas une scène de fin au début
      };
      localStorage.setItem('story_progress', JSON.stringify(initialProgress));
      
      // Redirige vers la première scène de l'histoire
      router.push(`/story/${storyId}/scene/${data.scene.id}`);
    } else {
      // Si l'API ne retourne pas de scène, redirige vers la scène 1 par défaut
      router.push(`/story/${storyId}/scene/1`);
    }
  } catch (error) {
    // En cas d'erreur, redirige vers la scène 1 par défaut
    router.push(`/story/${storyId}/scene/1`);
  } finally {
    loading.value = false;
  }
}

// Charge les histoires au montage du composant
onMounted(loadStories);
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

.stories-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.story-card {
  background-color: #222;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid #333;
}

.story-image img {
  width: 100%;
  height: 180px;
  object-fit: cover;
}

.story-info {
  padding: 15px;
}

.story-actions {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 10px;
}

.btn-continue {
  background-color: #2c8a3e;  /* Green for continue */
}

.btn-new {
  background-color: #5c636a;  /* Gray for new game */
}

.btn-start, .btn-continue, .btn-new {
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  color: white;
  transition: all 0.2s ease;
}

.btn-start {
  background-color: #5a1414;
}

.btn-start:hover, .btn-continue:hover, .btn-new:hover {
  opacity: 0.9;
  transform: translateY(-2px);
}

.loading, .error {
  text-align: center;
  padding: 30px;
}

.error button {
  background-color: #5a1414;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  margin-top: 10px;
  cursor: pointer;
}

.error-details {
  margin-top: 10px;
  color: #ff6b6b;
  font-size: 14px;
}
</style>