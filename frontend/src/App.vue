<template>
  <div class="flex h-screen">
    <div class="bg-indigo-800 lg:w-1/5 xl:w-1/6 h-full px-2" v-if="isAuth">
      <div class="text-2xl px-2 py-3 font-bold text-indigo-100">
        TodoApp
      </div>
      <div class="text-xl text-indigo-200">
        <router-link to="/dashboard" class="py-2 px-2 block hover:bg-indigo-700 hover:text-indigo-100 rounded-lg">
          <i class="fas fa-home"></i> Dashboard
        </router-link>
        <router-link to="/projects" class="py-2 px-2 block hover:bg-indigo-700 hover:text-indigo-100 rounded-lg">
          <i class="far fa-folder"></i> Projects
        </router-link>
        <a href="" @click.prevent="createProject()" class="py-2 px-2 block hover:bg-indigo-700 hover:text-indigo-100 rounded-lg">
          <i class="fas fa-folder-plus"></i> Create Project
        </a>
        <a href="" @click.prevent="logout()" class="py-2 px-2 block hover:bg-indigo-700 hover:text-indigo-100 rounded-lg">
          Logout
        </a>
      </div>
    </div>
    <router-view class="w-4/5 xl:w-5/6 overflow-y-auto"></router-view>
  </div>
</template>

<script>
import router from './router/'
import axios from 'axios'
export default {
  computed: {
    isAuth () {
      return this.$store.getters.isAuthenticated
    },
    user () {
      return this.$store.getters.user
    }
  },
  methods: {
    createProject () {
      axios.post(`/projects?token=${this.$store.state.authToken}`, [])
        .then(res => {
          if (res.data.is_error) {
            alert('An error has occured. Please try again later.')
          } else {
            router.replace(`/projects/${res.data.id}`)
          }
        })
        .catch(res => {
          alert('An internal server error occurred! Please try again later.')
        })
    },
    logout () {
      this.$store.dispatch('logout')
    }
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
}
</style>
