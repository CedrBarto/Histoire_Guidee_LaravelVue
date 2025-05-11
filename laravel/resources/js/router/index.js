// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../pages/HomePage.vue';
import StoryList from '../pages/StoriesPage.vue';
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
    component: StoryList
  },
  {
    path: '/story/:storyId/scene/:sceneId',
    name: 'scene',
    component: ScenePage,
    props: true,
    // Ajouter un garde de navigation ici
    beforeEnter: (to, from, next) => {
      // Si l'utilisateur vient d'une autre scène ou de la liste d'histoires, c'est ok
      if (from.name === 'scene' || from.name === 'stories') {
        next(); // Autoriser la navigation
      } else {
        // Sinon, rediriger vers la page d'histoires
        next({ name: 'stories' });
      }
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