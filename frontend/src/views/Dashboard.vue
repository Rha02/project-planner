<template>
  <div class="container mx-auto justify-center mt-4 text-gray-900">
    <div class="w-full py-2 text-center text-2xl text-gray-800 border-b-2 border-gray-600">
      Projects
    </div>
    <div class="mt-2 space-y-4">
      <div class="px-3 py-3 w-full rounded-lg overflow-hidden shadow-lg transition-shadow duration-150 hover:shadow-xl border-gray-300 bg-gray-100"
        v-for="project in projects" :key="project.id">
        <div class="flex justify-between text-lg">
          <router-link :to="`/projects/${project.id}`" class="font-semibold text-blue-600 hover:underline">
            {{ project.title ? project.title : 'Untitled' }}
          </router-link>
          <div class="text-purple-600 space-x-1 flex-none">
            <span class="fas fa-user"></span>
            <span class="fas fa-user"></span>
            <span class="fas fa-user"></span>
            <span class="fas fa-user"></span>
          </div>
        </div>
        <div class="text-gray-600 font-semibold">
          {{ project.description ? project.description : 'No description' }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data () {
    return {
      projects: []
    }
  },
  created () {
    axios.get(`/api/projects?token=${this.$store.state.authToken}`)
      .then(res => {
        this.projects = res.data
      })
  }
}
</script>
