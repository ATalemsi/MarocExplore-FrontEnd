import './assets/main.css'
import axios from 'axios'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

axios.defaults.baseURL = 'http://localhost:8000'
axios.interceptors.request.use(
  function (config) {
    const token = localStorage.getItem('token')
    const userId = localStorage.getItem('userId')

    if (token && isToken()) {
      config.headers.Authorization = `Bearer ${token}`
    }
    if (userId) {
      config.headers['User-Id'] = userId
    }
    return config
  },
  function (error) {
    return Promise.reject(error)
  }
)

function isToken() {
  const token = localStorage.getItem('token')
  return !!token
}

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !isToken()) {
    next('/')
  } else {
    next()
  }
})
const app = createApp(App)

app.mixin({
  methods: {
    isAuthenticated() {
      const token = localStorage.getItem('token')
      return !!token
    }
  }
})
app.use(createPinia())
app.use(router)

app.mount('#app')
