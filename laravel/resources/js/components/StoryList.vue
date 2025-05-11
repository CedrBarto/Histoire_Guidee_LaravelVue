<template>
    <div class="story-list">
      <div v-if="loading">Chargement des histoires...</div>
      
      <div v-else-if="error">
        <p>Une erreur s'est produite lors du chargement des histoires.</p>
        <button @click="$emit('retry')">Réessayer</button>
      </div>
      
      <div v-else class="stories-container">
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
            <button @click="$emit('select-story', story.id)">
              Commencer l'histoire
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  
  defineProps({
    stories: {
      type: Array,
      default: () => []
    },
    loading: {
      type: Boolean,
      default: false
    },
    error: {
      type: [Object, String, null],
      default: null
    }
  });
  
  defineEmits(['select-story', 'retry']);
  </script>
  
  <style scoped>
  .story-list {
    width: 100%;
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
  
  button {
    background-color: #5a1414;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
  }
  </style>