// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../pages/HomePage.vue';
import StoriesPage from '../pages/StoriesPage.vue';
import ScenePage from '../components/ScenePage.vue';
import ErrorPage from '../pages/ErrorPage.vue';

const routes = [
  {
    path: '/home',
    name: 'home',
    component: HomePage
  },
  {
    path: '/stories',
    name: 'stories',
    component: StoriesPage
  },
  {
    path: '/story/:storyId/scene/:sceneId',
    name: 'scene',
    component: ScenePage,
    props: true,
    // Ajouter un garde de navigation ici
    beforeEnter: (to, from, next) => {
      // Autoriser navigation normale depuis une scène ou la liste
      if (from.name === 'scene' || from.name === 'stories') {
        return next();
      }

      // Autoriser accès direct uniquement si la progression correspond à la scène demandée
      const savedProgress = localStorage.getItem('story_progress');
      if (savedProgress) {
        try {
          const progress = JSON.parse(savedProgress);
          if (
            progress.storyId == to.params.storyId &&
            progress.sceneId == to.params.sceneId
          ) {
            return next();
          }
        } catch (e) {
          // Ignore parsing error
        }
      }

      // Sinon, rediriger vers la page d'histoires
      next({ name: 'stories'});
    }
  },
  // Attraper toutes les routes non définies et afficher la page d'erreur
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: ErrorPage,
    props: {
      errorCode: 404,
      title: 'Page introuvable',
      message: 'La page que vous recherchez n\'existe pas ou a été déplacée.'
    }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;