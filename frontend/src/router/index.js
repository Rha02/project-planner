import Vue from 'vue'
import VueRouter from 'vue-router'

import TasksDashboard from '../views/TasksDashboard.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import ProjectsDashboard from '../views/ProjectsDashboard.vue'
import Project from '../views/Project.vue'

import store from '../store'

Vue.use(VueRouter)

const routes = [
  {
    path: '/dashboard',
    name: 'TasksDashboard',
    component: TasksDashboard,
    beforeEnter (to, from, next) {
      if (store.state.authToken) {
        next()
      } else {
        next('/login')
      }
    }
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
    path: '/projects',
    name: 'Dashboard',
    component: ProjectsDashboard,
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
