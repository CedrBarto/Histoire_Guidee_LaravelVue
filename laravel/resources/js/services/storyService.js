import { fetchJson } from '../utils/fetchJson';

// Service pour l'API des histoires guidées
export default {
  // Récupérer toutes les histoires
  getStories() {
    return fetchJson('/stories').request;
  },

  // Démarrer une histoire
  startStory(storyId) {
    return fetchJson({
      url: `/progress/story/${storyId}/start`,
      method: 'POST'
    }).request;
  },

  // Obtenir la progression
  getProgress(storyId) {
    return fetchJson(`/progress/${storyId}`).request;
  },

  // Faire un choix
  makeChoice(progressId, choiceId, answer = null) {
    const data = { choice_id: choiceId };
    if (answer) data.answer = answer;
    
    return fetchJson({
      url: `/progress/${progressId}/choice`,
      method: 'POST',
      data
    }).request;
  },

  // Recommencer après une mort
  restart(progressId) {
    return fetchJson({
      url: `/progress/${progressId}/restart`,
      method: 'POST'
    }).request;
  },

  // Réinitialiser la progression
  resetProgress(storyId) {
    return fetchJson({
      url: `/progress/story/${storyId}/reset`,
      method: 'POST'
    }).request;
  },

  // Récupérer l'inventaire
  getInventory(storyId) {
    return fetchJson(`/progress/inventory/${storyId}`).request;
  },

  // Obtenir une scène spécifique
  getScene(sceneId) {
    return fetchJson(`/scenes/${sceneId}`).request;
  }
};
