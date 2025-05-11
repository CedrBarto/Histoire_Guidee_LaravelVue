<template>
    <div class="scene-page">
      <div v-if="loading" class="loading">Chargement...</div>
      
      <div v-else-if="error" class="error">
        <p>Erreur lors du chargement de la scène</p>
        <button @click="loadScene">Réessayer</button>
      </div>
      
      <div v-else-if="scene" class="scene-content">
        <div class="scene-header">
          <h1 class="scene-title">{{ scene.title }}</h1>
        </div>
        
        <div class="main-content">
          <div class="scene-main">
            <div v-if="scene.image" class="scene-image">
              <img :src="`/images/scenes/${scene.image}`" :alt="scene.title">
            </div>
            
            <div class="scene-description" v-html="scene.content"></div>
            
            <!-- Si c'est une fin, afficher le bouton retour -->
            <div v-if="scene.is_ending" class="ending">
              <p class="ending-text" :class="{'good-ending': scene.id === 17, 'bad-ending': scene.id !== 17}">

                {{ scene.id === 17 ? 'Le Cauchemar est terminé' : 'VOUS ÊTES MORT' }}
              </p>
              <button @click="goToStories" class="btn-back">Retourner aux histoires</button>
            </div>
            
            <!-- Sinon, afficher les choix -->
            <div v-else class="choices">
              <div v-for="choice in scene.choices" :key="choice.id" class="choice">
                <!-- Choix standard -->
                <button 
                  v-if="!choice.type || choice.type === 'normal'" 
                  @click="makeChoice(choice)"
                  :disabled="choice.required_item && !hasItem(choice.required_item)"
                  :class="{'choice-disabled': choice.required_item && !hasItem(choice.required_item)}"
                  class="choice-btn"
                >
                  {{ choice.text }}
                  <span v-if="choice.required_item && !hasItem(choice.required_item)" class="item-required">
                    (Objet requis)
                  </span>
                </button>
                
                <!-- Énigme (riddle) -->
                <div v-else-if="choice.type === 'riddle'" class="riddle-choice">
                  <p>{{ choice.text }}</p>
                  <div class="riddle-attempts" v-if="riddleAttempts[choice.id]">
                    <p class="attempts-warning">Réfléchis bien ! Tu as plus que {{ 3 - riddleAttempts[choice.id] }} tentative(s)...</p>
                  </div>
                  <div class="riddle-input">
                    <input 
                      type="text" 
                      v-model="riddleAnswers[choice.id]" 
                      placeholder="Votre réponse..." 
                      @keyup.enter="submitRiddle(choice)"
                    />
                    <button @click="submitRiddle(choice)">Tenter sa chance</button>
                  </div>
                  <p v-if="riddleErrors[choice.id]" class="riddle-error">
                    Mauvaise réponse. Essaie encore.
                  </p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Inventaire placé à droite -->
          <div class="inventory-container">
            <Inventory :items="items" />
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import { useRouter, useRoute } from 'vue-router';
  import { useFetchJson } from '../composables/useFetchJson';
  import Inventory from '../components/Inventory.vue'; // Import du composant d'inventaire
  import { useInventory } from '../composables/useInventory';
  
  // Débogage global pour voir les valeurs dans la console du navigateur
  window.debugInventory = function() {
    const progress = JSON.parse(localStorage.getItem('story_progress') || '{}');
    console.log("Progression stockée:", progress);
    console.log("Inventaire stocké:", progress.inventory);
    return progress;
  };
  
  const router = useRouter();
  const route = useRoute();
  const scene = ref(null);
  const loading = ref(true);
  const error = ref(null);
  
  // Pour les énigmes
  const riddleAnswers = ref({});
  const riddleErrors = ref({});
  const riddleAttempts = ref({});
  
  // Remplacer les variables et fonctions d'inventaire par l'utilisation du composable
  const { 
    inventory,
    items,
    itemObtained,
    itemObtainedName,
    loadInventory,
    resetInventory,
    addToInventory,
    removeFromInventory,
    hasItem
  } = useInventory();
  
  // Fonctions de gestion de la sauvegarde
  function saveProgress(storyId, sceneId) {
    // Récupérer la sauvegarde existante pour garder itemsData
    let itemsData = {};
    const existingProgress = localStorage.getItem('story_progress');
    
    if (existingProgress) {
      try {
        const progress = JSON.parse(existingProgress);
        itemsData = progress.itemsData || {};
      } catch (e) {
        console.error('Erreur lors de la récupération des données d\'items:', e);
      }
    }
    
    const progress = {
      storyId,
      sceneId,
      timestamp: Date.now(),
      isDeathScene: scene.value && scene.value.is_ending && scene.value.id != 17,
      isEnding: scene.value && scene.value.is_ending,
      riddleAttempts: riddleAttempts.value,
      inventory: inventory.value,
      itemsData: itemsData
    };
    localStorage.setItem('story_progress', JSON.stringify(progress));
    console.log('Progression sauvegardée:', progress);
  }
  
  function loadProgress() {
    const savedProgress = localStorage.getItem('story_progress');
    return savedProgress ? JSON.parse(savedProgress) : null;
  }
  
  function clearProgress() {
    localStorage.removeItem('story_progress');
    console.log('Progression effacée');
  }
  
  function loadScene() {
    loading.value = true;
    error.value = null;
    
    const sceneId = route.params.sceneId;
    const { data, error: fetchError, loading: fetchLoading } = useFetchJson(`/scenes/${sceneId}`);
    
    watch(data, (newData) => {
      if (newData && newData.scene) {
        scene.value = newData.scene;
        console.log("Scène chargée:", newData.scene);
        
        // Si c'est la première scène (ID 1), réinitialiser l'inventaire
        if (scene.value.id === 1) {
          resetInventory();
        }
        
        // Sauvegarder automatiquement la progression à chaque nouvelle scène
        if (route.params.storyId) {
          saveProgress(route.params.storyId, sceneId);
        }
      }
    });
    
    watch(fetchError, (newError) => {
      if (newError) {
        console.error("Erreur lors du chargement de la scène:", newError);
        error.value = newError;
      }
    });
    
    watch(fetchLoading, (isLoading) => {
      loading.value = isLoading;
    });
  }
  
  function makeChoice(choice) {
    console.log("Choix complet:", JSON.stringify(choice));
    
    if (choice.required_item && !hasItem(choice.required_item)) {
        console.log("Objet requis non présent dans l'inventaire:", choice.required_item);
        return;
    }
    
    if (choice.consumes_item) {
        console.log("Consommation d'objet:", choice.consumes_item);
        removeFromInventory(parseInt(choice.consumes_item), route.params.storyId, scene.value.id);
    }
    
    if (choice.item_reward) {
        console.log("Récompense principale:", choice.item_reward);
        addToInventory(parseInt(choice.item_reward), route.params.storyId, scene.value.id);
    }
    
    if (choice.additional_reward) {
        console.log("Type de additional_reward:", typeof choice.additional_reward);
        console.log("Valeur de additional_reward:", choice.additional_reward);
        
        if (typeof choice.additional_reward === 'number') {
            addToInventory(choice.additional_reward, route.params.storyId, scene.value.id);
        }
        else if (Array.isArray(choice.additional_reward)) {
            choice.additional_reward.forEach(itemId => {
                addToInventory(parseInt(itemId), route.params.storyId, scene.value.id);
            });
        } 
        else if (typeof choice.additional_reward === 'string') {
            try {
                const parsed = JSON.parse(choice.additional_reward);
                if (Array.isArray(parsed)) {
                    parsed.forEach(itemId => addToInventory(parseInt(itemId), route.params.storyId, scene.value.id));
                } else {
                    addToInventory(parseInt(parsed), route.params.storyId, scene.value.id);
                }
            } catch (e) {
                if (choice.additional_reward.startsWith('[') && choice.additional_reward.endsWith(']')) {
                    const content = choice.additional_reward.substring(1, choice.additional_reward.length - 1);
                    const ids = content.split(',').map(id => parseInt(id.trim()));
                    ids.forEach(id => {
                        if (!isNaN(id)) {
                            addToInventory(id, route.params.storyId, scene.value.id);
                        }
                    });
                } else {
                    const id = parseInt(choice.additional_reward);
                    if (!isNaN(id)) {
                        addToInventory(id, route.params.storyId, scene.value.id);
                    }

                }
            }
        }
    }
    
    router.push(`/story/${route.params.storyId}/scene/${choice.next_scene_id}`);
  }
  
  async function submitRiddle(choice) {
    const answer = riddleAnswers.value[choice.id];
    
    if (!answer || answer.trim() === '') {
      riddleErrors.value[choice.id] = true;
      return;
    }
    
    loading.value = true;
    
    try {
      // Validation côté client au lieu d'un appel API
      const isCorrect = checkRiddleAnswer(choice, answer.trim());
      
      if (isCorrect) {
        console.log("Réponse correcte !");
        // Réinitialiser les erreurs
        riddleErrors.value[choice.id] = false;
        
        // Charger la scène suivante
        if (choice.next_scene_id) {
          // Navigation vers la scène suivante
          router.push(`/story/${route.params.storyId}/scene/${choice.next_scene_id}`);
        }
      } else {
        console.log("Réponse incorrecte");
        riddleErrors.value[choice.id] = true;
        
        // Incrémenter le compteur d'essais
        if (!riddleAttempts.value[choice.id]) {
          riddleAttempts.value[choice.id] = 1;
        } else {
          riddleAttempts.value[choice.id]++;
        }
        
        // Sauvegarder les tentatives d'énigmes
        saveProgress(route.params.storyId, route.params.sceneId);
        
        // Vérifier si l'utilisateur a épuisé ses essais
        if (riddleAttempts.value[choice.id] >= 3) {
          // Trouver le choix de mort s'il existe
          const deathChoice = scene.value.choices.find(c => c.type === 'normal' && c !== choice);
          
          if (deathChoice) {
            // Navigation vers la scène de mort
            router.push(`/story/${route.params.storyId}/scene/${deathChoice.next_scene_id}`);
          } else {
            // S'il n'y a pas de choix alternatif, rediriger vers les histoires
            goToStories();
          }
        }
      }
      
      loading.value = false;
    } catch (err) {
      console.error("Erreur lors de la validation de l'énigme:", err);
      riddleErrors.value[choice.id] = true;
      loading.value = false;
    }
  }
  
  // Fonction pour vérifier la réponse d'une énigme
  function checkRiddleAnswer(choice, response) {
    // Normalisation de la réponse (trim et lowercase)
    const normalizedResponse = response.trim().toLowerCase();
    
    // Si la réponse est vide, c'est incorrect
    if (!normalizedResponse) {
      return false;
    }
    
    // Vérifier si la réponse correspond à une des réponses acceptables
    if (!choice.answer) {
      console.error("Erreur: l'énigme n'a pas de réponse définie");
      return false;
    }
    
    // Diviser les réponses acceptables (séparées par des virgules)
    const acceptableAnswers = choice.answer.split(',');
    
    // Vérifier si la réponse fournie correspond à une des réponses acceptables
    for (const acceptableAnswer of acceptableAnswers) {
      if (acceptableAnswer.trim().toLowerCase() === normalizedResponse) {
        return true;
      }
    }
    
    return false;
  }
  
  function goToStories() {
    resetInventory();
    router.push('/stories');
  }
  
  // Charger la scène et l'inventaire au démarrage
  onMounted(() => {
    console.log("Montage du composant ScenePage");
    loadInventory();
    loadScene();
    
    // Restaurer les tentatives d'énigmes si elles existent
    const savedProgress = loadProgress();
    if (savedProgress && savedProgress.riddleAttempts) {
      riddleAttempts.value = savedProgress.riddleAttempts;
    }
  });
  
  // Surveiller les changements d'ID de scène pour recharger
  watch(() => route.params.sceneId, (newId, oldId) => {
    console.log(`ID de scène changé: ${oldId} -> ${newId}`);
    if (newId !== oldId) {
      loadScene();
    }
  }, { immediate: true });
  </script>
  
  <style scoped>
  .scene-page {
    max-width: 100%;
    margin: 0 auto;
    min-height: calc(100vh - 100px);
    display: flex;
    flex-direction: column;
  }
  
  .scene-content {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    height: calc(100vh - 70px);
    margin-top: 50px;
  }
  
  .scene-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
  }
  
  .main-content {
    display: flex;
    gap: 20px;
    height: 100%;
    overflow: hidden;
  }
  
  .scene-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    padding-right: 10px;
  }
  
  .inventory-container {
    width: 300px;
    flex-shrink: 0;
  }
  
  .scene-title {
    font-size: 2rem;
    margin: 0;
    text-align: center;
  }
  
  .scene-image {
    margin: 1rem 0;
    height: 40vh;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    border-radius: 8px;
    background-color: rgba(0, 0, 0, 0.3);
  }
  
  .scene-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 8px;
  }
  
  .scene-description {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
  }
  
  .choices {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    margin-top: auto;
  }
  
  .choice button {
    background-color: #5a1414;
    color: white;
    border: none;
    padding: 0.8rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    text-align: left;
    width: 100%;
    transition: background-color 0.2s;
  }
  
  .choice button:hover:not(.choice-disabled) {
    background-color: #7a1c1c;
  }
  
  .choice-disabled {
    background-color: #555 !important;
    cursor: not-allowed !important;
    opacity: 0.7;
  }
  
  .item-required {
    color: #ff6b6b;
    font-style: italic;
    margin-left: 0.5rem;
    font-size: 0.9rem;
  }
  
  .ending {
    text-align: center;
    margin-top: auto;
    padding: 1rem 0;
  }
  
  .ending button {
    background-color: #333;
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 1rem;
  }
  
  .good-ending {
    color: #4caf50;
    font-size: 1.5rem;
  }
  
  .bad-ending {
    color: #ff6b6b;
    font-size: 1.5rem;
  }
  
  .loading, .error {
    text-align: center;
    padding: 2rem;
    min-height: 50vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  
  /* Styles pour les énigmes */
  .riddle-choice {
    border: 1px solid #5a1414;
    padding: 1rem;
    border-radius: 8px;
    background-color: rgba(90, 20, 20, 0.2);
  }
  
  .riddle-input {
    display: flex;
    margin-top: 1rem;
  }
  
  .riddle-input input {
    flex-grow: 1;
    padding: 0.5rem;
    border: 1px solid #333;
    background-color: #1a1a1a;
    color: white;
    border-radius: 4px 0 0 4px;
  }
  
  .riddle-input button {
    background-color: #5a1414;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
  }
  
  .riddle-error {
    color: #ff6b6b;
    margin-top: 0.5rem;
    font-style: italic;
  }
  
  .riddle-attempts {
    margin-top: 0.5rem;
  }
  
  .attempts-warning {
    color: #ff6b6b;
    font-weight: bold;
  }
  
  /* Style pour les notifications d'objets obtenus */
  .item-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: rgba(90, 20, 20, 0.9);
    padding: 15px;
    border-radius: 8px;
    z-index: 100;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    animation: slide-in 0.3s ease-out;
  }
  
  .notification-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  
  .notification-content p {
    margin: 0 20px 0 0;
    color: white;
  }
  
  .notification-content button {
    background-color: white;
    color: #5a1414;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
  }
  
  @keyframes slide-in {
    from {
      transform: translateX(100%);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  
  /* Scrollbar personnalisée pour le contenu principal */
  .scene-main::-webkit-scrollbar {
    width: 8px;
  }
  
  .scene-main::-webkit-scrollbar-track {
    background: #1a1a1a;
    border-radius: 4px;
  }
  
  .scene-main::-webkit-scrollbar-thumb {
    background: #444;
    border-radius: 4px;
  }
  
  .scene-main::-webkit-scrollbar-thumb:hover {
    background: #555;
  }
  
  /* Media queries pour la responsivité */
  @media (max-width: 1024px) {
    .main-content {
      flex-direction: column;
    }
    
    .inventory-container {
      width: 100%;
      margin-top: 20px;
    }
    
    .scene-main {
      padding-right: 0;
    }
  }
  
  @media (max-height: 700px) {
    .scene-image {
      height: 30vh;
    }
  }
  
  @media (max-width: 600px) {
    .scene-image {
      height: 30vh;
    }
    
    .scene-title {
      font-size: 1.5rem;
    }
    
    .scene-description {
      font-size: 1rem;
    }
  }
  </style>