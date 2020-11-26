import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Dashboard from '../views/Dashboard.vue'
import Project from '../views/Project.vue'

import store from '../store'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    beforeEnter (to, from, next) {
      if (store.state.authToken) {
        next()
      } else {
        next('/login')
      }
    }
  },
  {
    path: '/projects/:id',
    component: Project,
    beforeEnter (to, from, next) {
      if (store.state.authToken) {
        next()
      } else {
        next('/login')
      }
    }
  }
]

Vue.router = new VueRouter({
  routes
})

export default Vue.router
