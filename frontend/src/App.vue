<template>
    <div class="">
            <nav class="bg-gray-800 text-gray-300 text-lg">
              <div class="container mx-auto flex justify-between py-2 px-1">
                <ul class="flex">
                  <li class="hover:bg-gray-700 hover:text-white py-1 rounded-lg hidden lg:block">
                      <router-link to="/dashboard" class="px-2 py-1">Home</router-link>
                  </li>
                  <li class="hover:bg-gray-700 hover:text-white py-1 rounded-lg hidden lg:block">
                      <router-link to="/projects" class="px-2 py-1">Your Projects</router-link>
                  </li>
                  <li class="hover:bg-gray-700 hover:text-white py-1 rounded-lg block lg:hidden">
                      <button class="px-2" @click="isMenuOpen = !isMenuOpen">
                        <i class="fas fa-bars"></i>
                      </button>
                  </li>
                </ul>
                <ul class="flex" v-if="! isAuth">
                    <li class="hover:bg-gray-700 hover:text-white py-1 rounded-lg">
                        <router-link to="/login" class="px-2 py-1">Login</router-link>
                    </li>
                    <li class="hover:bg-gray-700 hover:text-white py-1 rounded-lg">
                        <router-link to="/register" class="px-2 py-1">Register</router-link>
                    </li>
                </ul>
                <ul class="flex" v-if="isAuth">
                    <li class="py-1 rounded-lg hover:bg-gray-700 hover:text-white">
                        <button @click.prevent="createProject()" class="px-2">Create a Project</button>
                    </li>
                    <li class="px-2 py-1 rounded-lg">
                        {{ user.name }}
                    </li>
                    <li class="py-1 rounded-lg hover:bg-gray-700 hover:text-white hidden lg:block">
                        <button @click.prevent="logout()" class="px-2">Sign out</button>
                    </li>
                </ul>
              </div>
            </nav>
            <div class="block lg:hidden" v-if="isMenuOpen">
              <a href="#" @click.prevent="goToDashboard()" class="bg-gray-800 hover:bg-gray-700 block text-gray-200 text-lg px-2 py-1 border-t-2 border-gray-900">
                Home
              </a>
              <a href="#" @click.prevent="goToProjects()" class="bg-gray-800 hover:bg-gray-700 block text-gray-200 text-lg px-2 py-1 border-t-2 border-gray-900" v-if="isAuth">
                Your Projects
              </a>
              <a href="#" @click.prevent="createProject()" class="bg-gray-800 hover:bg-gray-700 block text-gray-200 text-lg px-2 py-1 border-t-2 border-gray-900" v-if="isAuth">
                Create a Project
              </a>
              <a href="#" @click.prevent="logout()" class="bg-gray-800 hover:bg-gray-700 block text-gray-200 text-lg px-2 py-1 border-t-2 border-gray-900" v-if="isAuth">
                Sign out
              </a>
            </div>
        <div class="">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import router from './router/'
import axios from 'axios'
export default {
  data () {
    return {
      isMenuOpen: false
    }
  },
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
            this.isMenuOpen = false
          }
        })
        .catch(res => {
          alert('An internal server error occurred! Please try again later.')
        })
    },
    goToProjects () {
      router.replace('/projects')
      this.isMenuOpen = false
    },
    goToDashboard () {
      router.replace('/dashboard')
      this.isMenuOpen = false
    },
    logout () {
      this.$store.dispatch('logout')
      this.isMenuOpen = false
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
