import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: () => import('../views/LoginPage.vue')
    },
    {
      path: '/home',
      name: 'home',
      component: HomeView
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterPage.vue')
    },
    {
      path: '/itineraries',
      meta: {
        requiresAuth: true
      },
      component: () => import('../views/ItineraryListPage.vue')
    },
    {
      path: '/itineraries/:id',
      meta: {
        requiresAuth: true // This route requires authentication
      },
      component: () => import('../views/ItineraryDetailsPage.vue')
    },
    {
      path: '/create-itinerary',
      meta: {
        requiresAuth: true // This route requires authentication
      },
      component: () => import('../views/CreateItineraryPage.vue')
    },
    {
      path: '/itineraries/:id/destinations',
      name: 'AddDestination',
      meta: {
        requiresAuth: true // This route requires authentication
      },
      component: () => import('../views/AddDestinationPage.vue')
    },
    {
      path: '/to-visit/:id',
      meta: {
        requiresAuth: true // This route requires authentication
      },
      component: () => import('../views/VisitPage.vue')
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    }
  ]
})

export default router
