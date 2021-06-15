<template>
  <div class="justify-center text-gray-900 bg-gray-100 h-full">
    <div class="bg-white shadow px-6 py-3 text-gray-900 text-2xl font-semibold flex justify-between">
        <div>
          <div v-if="editing_title">
            <input class="w-3/4 px-1" ref="title" v-model="formData.title">
            <span class="ml-1 space-x-1 text-lg">
              <button type="button" @click="editing_title = false" class="px-2 hover:bg-red-500 rounded-lg text-red-500 hover:text-white transition ease-in-out duration-150">
                <i class="fas fa-times"></i>
              </button>
              <button type="button" @click="updateProjectTitle()" class="px-1 hover:bg-green-500 rounded-lg text-green-500 hover:text-white transition ease-in-out duration-150">
                <i class="fas fa-check"></i>
              </button>
            </span>
          </div>
          <div v-else>
            <span>{{ project.title }}</span>
            <span class="ml-1 text-lg">
              <button type="button" @click="editTitle()" class="px-1 text-gray-700 bg-gray-200 hover:bg-white hover:text-indigo-600 rounded-lg transition ease-in-out duration-150">
                <i class="fas fa-pen"></i>
              </button>
            </span>
          </div>
        </div>
        <div class="flex-none text-gray-700">
          <button type="button" @click="inSettings = true" class="px-2 bg-gray-200 hover:bg-white hover:text-indigo-600 rounded-lg transition ease-in-out duration-150">
            <i class="fas fa-cog"></i>
          </button>
        </div>
    </div>
    <div class="px-6 py-1">
      <div v-if="editing_description">
        <textarea class="bg-gray-100 w-2/3 px-1" ref="description" v-model="formData.description"></textarea>
        <span class="ml-1">
          <button type="button" @click="editing_description = false" class="px-2 hover:bg-red-500 rounded-lg text-red-500 hover:text-white transition ease-in-out duration-150">
            <i class="fas fa-times"></i>
          </button>
          <button type="button" @click="updateProjectDescription()" class="px-1 hover:bg-green-500 rounded-lg text-green-500 hover:text-white transition ease-in-out duration-150">
            <i class="fas fa-check"></i>
          </button>
        </span>
      </div>
      <div v-else>
        <span>{{ project.description }}</span>
        <span class="ml-1">
          <button type="button" @click="editDescription()" class="px-1 bg-gray-300 hover:bg-gray-100 rounded-lg hover:text-indigo-600 text-gray-800 transition ease-in-out duration-150">
            <i class="fas fa-pen"></i>
          </button>
        </span>
      </div>
    </div>
    <div class="mt-4 text-xl text-center font-semibold">
      Goals: {{ goals.length }}
    </div>
    <div class="flex flex-wrap">
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="text-lg text-center text-gray-800 font-semibold pt-2">
          No Status
        </div>
        <div v-for="goal in filteredgoals('unsigned')" :key="goal.id">
          <router-link :to="{ name: 'goal', params: { id: project.id, goal_id: goal.id, goal: goal, project: project} }">
            <goal :title="goal.title"></goal>
          </router-link>
        </div>
        <goalform @addGoal="createGoal" status="unsigned" :project="project"></goalform>
      </div>
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="text-lg text-center text-gray-800 font-semibold pt-2">
          Not Started
        </div>
        <div v-for="goal in filteredgoals('not_started')" :key="goal.id">
          <router-link :to="{ name: 'goal', params: { id: project.id, goal_id: goal.id, project: project, goal: goal } }">
            <goal :title="goal.title"></goal>
          </router-link>
        </div>
        <goalform @addGoal="createGoal" status="not_started" :project="project"></goalform>
      </div>
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="text-lg text-center text-gray-800 font-semibold pt-2">
          In Progress
        </div>
        <div v-for="goal in filteredgoals('in_progress')" :key="goal.id">
          <router-link :to="{ name: 'goal', params: { id: project.id, goal_id: goal.id, project: project, goal: goal } }">
            <goal :title="goal.title"></goal>
          </router-link>
        </div>
        <goalform @addGoal="createGoal" status="in_progress" :project="project"></goalform>
      </div>
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            Completed
          </div>
          <div v-for="goal in filteredgoals('complete')" :key="goal.id">
            <router-link :to="{ name: 'goal', params: { id: project.id, goal_id: goal.id, project_param: project, goal_param: goal, members_param: members } }">
              <goal :title="goal.title"></goal>
            </router-link>
          </div>
          <goalform @addGoal="createGoal" status="complete" :project="project"></goalform>
        </div>
      </div>
    </div>
    <div class="" v-if="inSettings">
      <settings-modal :project="project" :members="members" @addMember="addMember" @removeMember="removeMember" @deleteProject="deleteProject" @stopShowing="inSettings = false"></settings-modal>
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
      members: [],
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
      this.members.push(payload)
    },
    removeMember (email) {
      this.members = this.members.filter(function (member) {
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
          axios.get(`/projects/${this.project.id}/members?token=${this.authToken}`)
            .then(res3 => {
              this.members = res3.data
            })
        }
      })
      .catch(res => {
        alert('Server-side error occurred. Try again later.')
      })
  }
}
</script>
