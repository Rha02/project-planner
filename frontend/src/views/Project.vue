<template>
  <div class="justify-center text-gray-900 bg-gray-200 h-full">
    <div class="bg-gray-100">
      <div class="mx-8 py-3 font-semibold flex justify-between">
        <div class="text-2xl text-gray-700" v-if="editing_title">
          <input class="text-gray-900 bg-gray-100 w-3/4 px-1" ref="title" v-model="formData.title">
          <span class="ml-1 space-x-1 text-lg">
            <button type="button" @click="editing_title = false" class="px-2 hover:bg-red-600 rounded-lg hover:text-white transition ease-in-out duration-150">
              <i class="fas fa-times"></i>
            </button>
            <button type="button" @click="updateProjectTitle()" class="px-1 hover:bg-green-600 rounded-lg hover:text-white transition ease-in-out duration-150">
              <i class="fas fa-check"></i>
            </button>
          </span>
        </div>
        <div class="text-2xl text-gray-700 lg:text-gray-100 hover:text-gray-700 w-full" v-else>
          <span class="text-gray-900">{{ project.title }}</span>
          <span class="ml-1 text-lg">
            <button type="button" @click="editTitle()" class="px-1 hover:bg-gray-400 hover:text-purple-700 rounded-lg transition ease-in-out duration-150">
              <i class="fas fa-pen"></i>
            </button>
          </span>
        </div>
        <div class="flex-none text-gray-700 text-2xl">
          <button type="button" @click="inSettings = true" class="px-2 hover:bg-gray-400 hover:text-purple-700 rounded-lg transition ease-in-out duration-150">
            <i class="fas fa-cog"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="mx-8">
      <div class="text-gray-700 mt-1" v-if="editing_description">
        <textarea class="text-gray-900 bg-gray-200 w-2/3 px-1" ref="description" v-model="formData.description"></textarea>
        <span class="ml-1">
          <button type="button" @click="editing_description = false" class="px-2 hover:bg-red-600 rounded-lg hover:text-white transition ease-in-out duration-150">
            <i class="fas fa-times"></i>
          </button>
          <button type="button" @click="updateProjectDescription()" class="px-1 hover:bg-green-600 rounded-lg hover:text-white transition ease-in-out duration-150">
            <i class="fas fa-check"></i>
          </button>
        </span>
      </div>
      <div class="text-gray-700 lg:text-gray-200 hover:text-gray-700 mt-1" v-else>
        <span class="text-gray-800">{{ project.description }}</span>
        <span class="ml-1">
          <button type="button" @click="editDescription()" class="px-1 hover:bg-gray-400 rounded-lg hover:text-purple-700 transition ease-in-out duration-150">
            <i class="fas fa-pen"></i>
          </button>
        </span>
      </div>
    </div>
    <div class="mt-4 text-xl text-center font-semibold">
      Goals: {{ goals.length }}
    </div>
    <div class="flex flex-wrap -mb-4 mt-2">
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            No Status
          </div>
          <div v-for="goal in filteredgoals('unsigned')" :key="goal.id">
            <router-link :to="`/projects/${project.id}/goals/${goal.id}`">
              <goal :goal="goal"></goal>
            </router-link>
          </div>
          <goalform @addGoal="createGoal" status="unsigned" :project="project"></goalform>
        </div>
      </div>
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            Not Started
          </div>
          <div v-for="goal in filteredgoals('not_started')" :key="goal.id">
            <router-link :to="`/projects/${project.id}/goals/${goal.id}`">
              <goal :goal="goal"></goal>
            </router-link>
          </div>
          <goalform @addGoal="createGoal" status="not_started" :project="project"></goalform>
        </div>
      </div>
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            In Progress
          </div>
          <div v-for="goal in filteredgoals('in_progress')" :key="goal.id">
            <router-link :to="`/projects/${project.id}/goals/${goal.id}`">
              <goal :goal="goal"></goal>
            </router-link>
          </div>
          <goalform @addGoal="createGoal" status="in_progress" :project="project"></goalform>
        </div>
      </div>
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            Completed
          </div>
          <div v-for="goal in filteredgoals('complete')" :key="goal.id">
            <router-link :to="`/projects/${project.id}/goals/${goal.id}`">
              <goal :goal="goal"></goal>
            </router-link>
          </div>
          <goalform @addGoal="createGoal" status="complete" :project="project"></goalform>
        </div>
      </div>
    </div>
    <div class="" v-if="inSettings">
      <settings-modal :project="project" @addMember="addMember" @removeMember="removeMember" @deleteProject="deleteProject" @stopShowing="inSettings = false"></settings-modal>
    </div>
  </div>
</template>

<script>
import GoalFormModal from '../components/GoalFormModal.vue'
import ProjectSettingsModal from '../components/ProjectSettingsModal.vue'
import GoalCard from '../components/GoalCard.vue'
import axios from 'axios'
export default {
  components: {
    goalform: GoalFormModal,
    settingsModal: ProjectSettingsModal,
    goal: GoalCard
  },
  data () {
    return {
      project: {},
      editing_title: false,
      editing_description: false,
      inSettings: false,
      goals: [],
      formData: {
        title: '',
        description: ''
      }
    }
  },
  computed: {
    authToken () {
      return this.$store.state.authToken
    }
  },
  watch: {
    editing_title () {
      this.formData.title = this.project.title
    },
    editing_description () {
      this.formData.description = this.project.description
    }
  },
  methods: {
    filteredgoals (status) {
      return this.goals.filter(function (goal) {
        return goal.status === status // statuses: complete, in_progress, not_started, unsigned
      })
    },
    addMember (payload) {
      this.project.members.push(payload)
    },
    removeMember (email) {
      this.project.members = this.project.members.filter(function (member) {
        return member.email !== email
      })
    },
    updateProjectTitle () {
      axios.patch(`/projects/${this.project.id}?token=${this.authToken}`, {
        title: this.formData.title,
        description: this.project.description
      })
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.editing_title = false
            this.project.title = res.data.title
          }
        })
        .catch(res => {
          alert('Server-side error occurred.')
        })
    },
    updateProjectDescription () {
      axios.patch(`/projects/${this.project.id}?token=${this.authToken}`, {
        title: this.project.title,
        description: this.formData.description
      })
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.editing_description = false
            this.project.description = res.data.description
          }
        })
        .catch(res => {
          alert('Server-side error occurred.')
        })
    },
    deleteProject () {
      axios.delete(`/projects/${this.project.id}?token=${this.authToken}`)
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.$router.push('/projects')
          }
        })
        .catch(res => {
          alert('Server-side error occurred.')
        })
    },
    editTitle () {
      this.editing_title = true
      this.$nextTick(() => this.$refs.title.focus())
    },
    editDescription () {
      this.editing_description = true
      this.$nextTick(() => this.$refs.description.focus())
    },
    createGoal (payload) {
      this.goals.push(payload)
    }
  },
  created () {
    axios.get(`/projects/${this.$route.params.id}?token=${this.authToken}`)
      .then(res => {
        if (res.data.is_error) {
          alert(res.data.message)
        } else {
          this.project = res.data
          axios.get(`/projects/${this.project.id}/goals?token=${this.authToken}`)
            .then(res2 => {
              this.goals = res2.data
            })
        }
      })
      .catch(res => {
        alert('Server-side error occurred. Try again later.')
      })
  }
}
</script>
