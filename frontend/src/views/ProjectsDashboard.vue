<template>
  <div class="mx-4 md:container md:mx-auto mt-4 text-gray-900">
    <div class="" v-if="ownedProjects.length">
      <div class="w-full py-2 text-center text-2xl text-gray-800 border-b-2 border-gray-600 font-semibold">
        Your Projects
      </div>
      <div class="mt-2 space-y-4">
        <div class="px-3 py-3 w-full rounded-lg overflow-hidden shadow-lg transition-shadow duration-150 hover:shadow-xl border-gray-300 bg-gray-100"
          v-for="project in ownedProjects" :key="project.id">
          <project :project="project"></project>
        </div>
      </div>
    </div>
    <div class="" v-if="sharedProjects.length">
      <div class="w-full mt-4 py-2 text-center text-2xl text-gray-800 border-b-2 border-gray-600 font-semibold">
        Shared Projects
      </div>
      <div class="mt-2 space-y-4">
        <div class="px-3 py-3 w-full rounded-lg overflow-hidden shadow-lg transition-shadow duration-150 hover:shadow-xl border-gray-300 bg-gray-100"
          v-for="project in sharedProjects" :key="project.id">
          <project :project="project"></project>
        </div>
      </div>
    </div>
    <div class="" v-if="!sharedProjects.length && !ownedProjects.length">
      <div class="w-full py-2 text-center text-xl text-gray-800 font-semibold">
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
