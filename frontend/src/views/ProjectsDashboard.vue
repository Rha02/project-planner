<template>
  <div class="bg-gray-100 h-full">
    <div class="" v-if="ownedProjects.length">
      <div class="bg-white shadow px-6 py-3 text-gray-900 text-2xl font-semibold">
        Projects
      </div>
      <div class="px-6 my-2 text-xl font-semibold text-gray-900">
        Your Projects:
      </div>
      <div class="mx-6 space-y-2">
        <div class="px-3 py-2 w-full rounded-lg overflow-hidden shadow transition-shadow duration-150 hover:shadow-lg bg-white"
          v-for="project in ownedProjects" :key="project.id">
          <project :project="project"></project>
        </div>
      </div>
    </div>
    <div class="" v-if="sharedProjects.length">
      <div class="px-6 my-2 text-xl font-semibold text-gray-900">
        Shared Projects
      </div>
      <div class="mx-6 space-y-2">
        <div class="px-3 py-2 w-full rounded-lg overflow-hidden shadow transition-shadow duration-150 hover:shadow-lg bg-white"
          v-for="project in sharedProjects" :key="project.id">
          <project :project="project"></project>
        </div>
      </div>
    </div>
    <div class="" v-if="!sharedProjects.length && !ownedProjects.length">
      <div class="w-full py-2 px-6 text-center text-xl text-gray-900 font-semibold">
        You don't have any projects. Get started by creating one.
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import ProjectCard from '../components/ProjectCard.vue'
export default {
  components: {
    project: ProjectCard
  },
  data () {
    return {
      ownedProjects: [],
      sharedProjects: []
    }
  },
  created () {
    axios.get(`/projects?token=${this.$store.state.authToken}`)
      .then(res => {
        if (res.data.is_error) {
          alert(res.data.message)
        } else {
          this.ownedProjects = res.data.ownedProjects
          this.sharedProjects = res.data.sharedProjects
        }
      })
      .catch(res => {
        alert('Server-side error occurred!')
      })
  }
}
</script>
