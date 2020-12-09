<template>
    <div class="">
            <nav class="bg-gray-800 text-gray-300 text-lg">
              <div class="container mx-auto flex justify-between py-2">
                <ul>
                  <li class="hover:bg-gray-700 hover:text-white py-1 rounded-lg">
                      <router-link to="/dashboard" class="px-2 py-1">Home</router-link>
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
                        <button @click.prevent="createProject" class="px-2">Create a Project</button>
                    </li>
                    <li class="px-2 py-1 rounded-lg">
                        {{ user.name }}
                    </li>
                </ul>
              </div>
            </nav>
        <div class="">
            <router-view></router-view>
        </div>
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
