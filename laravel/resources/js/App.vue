<script>

</script>
<template>
  <div class="app-container">
    <h1 class="app-title">Mon Histoire Guidée</h1>
    
    <div v-if="loading" class="loading-container">
      <LoadingSpinner />
    </div>
    
    <ErrorMessage v-if="error" :message="error.statusText || 'Une erreur est survenue'" />
    
    <template v-if="!loading && !error">
      <!-- Si aucune histoire n'est sélectionnée, afficher la sélection -->
      <StorySelection 
        v-if="!currentScene" 
        :stories="stories?.stories || []" 
        @story-selected="startStory" 
      />
      
      <!-- Si une histoire est en cours, afficher la scène -->
      <SceneViewer 
        v-else 
        :scene="currentScene" 
        :inventory="inventory" 
        @make-choice="makeChoice"
        @solve-riddle="solveRiddle"
        @restart="restartStory"
      />
    </template>
  </div>
</template>
