import Vue from 'vue'
import App from './App.vue'
import axios from 'axios'
import VueRouter from 'vue-router'
import router from './router'
import store from './store'
import './assets/styles/index.css'

Vue.use(VueRouter)

axios.defaults.baseURL = 'http://todoapp.loc/api'

Vue.config.productionTip = false

new Vue({
  router,
  store,
  created () {
    store.dispatch('tryAutoLogin')
  },
  render: h => h(App)
}).$mount('#app')
