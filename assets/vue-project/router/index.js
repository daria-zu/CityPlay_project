import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/map',
    component: () => import('../components/Map.vue'),
  },
  {
    path: '/',
    component: () => import('../components/VueEnter.vue') 
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
