<template>
  <div class="container mx-auto justify-center mt-4 text-gray-900">
    <div class="flex justify-between mx-2 md:mx-1 lg:mx-0 xl:mx-0">
      <div class="text-2xl text-gray-700" v-if="editing_title">
        <input class="text-gray-900 bg-gray-200 w-3/43 px-1" ref="title" v-model="formData.title">
        <span class="ml-1 space-x-1 text-lg">
          <button type="button" @click="editing_title = false" class="px-2 hover:bg-red-600 rounded-lg hover:text-white transition ease-in-out duration-150">
            <i class="fas fa-times"></i>
          </button>
          <button type="button" @click="updateProjectTitle()" class="px-1 hover:bg-green-600 rounded-lg hover:text-white transition ease-in-out duration-150">
            <i class="fas fa-check"></i>
          </button>
        </span>
      </div>
      <div class="text-2xl text-gray-700 lg:text-gray-200 hover:text-gray-700" v-else>
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
    <div class="mx-2 md:mx-1 lg:mx-0">
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
      Tasks: {{ tasks.length }}
    </div>
    <div class="flex flex-wrap -mb-4 mt-2">
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            No Status
          </div>
          <div class=""
                v-for="task in filteredTasks('unsigned')" :key="task.id">
            <task :task="task" :project="project" @taskDeleted="removeTask" @taskUpdated="updateTask"></task>
          </div>
          <taskform @addTask="createTask" status="unsigned" :project="project"></taskform>
        </div>
      </div>
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            Not Started
          </div>
          <div class=""
                v-for="task in filteredTasks('not_started')" :key="task.id">
            <task :task="task" :project="project" @taskDeleted="removeTask" @taskUpdated="updateTask"></task>
          </div>
          <taskform @addTask="createTask" status="not_started" :project="project"></taskform>
        </div>
      </div>
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            In Progress
          </div>
          <div class=""
                v-for="task in filteredTasks('in_progress')" :key="task.id">
            <task :task="task" :project="project" @taskDeleted="removeTask" @taskUpdated="updateTask"></task>
          </div>
          <taskform @addTask="createTask" status="in_progress" :project="project"></taskform>
        </div>
      </div>
      <div class="w-full md:w-1/2 lg:w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            Completed
          </div>
          <div class=""
                v-for="task in filteredTasks('complete')" :key="task.id">
            <task :task="task" :project="project" @taskDeleted="removeTask" @taskUpdated="updateTask"></task>
          </div>
          <taskform @addTask="createTask" status="complete" :project="project"></taskform>
        </div>
      </div>
    </div>
    <div class="" v-if="inSettings">
      <settings-modal :project="project" @addMember="addMember" @removeMember="removeMember" @deleteProject="deleteProject" @stopShowing="inSettings = false"></settings-modal>
    </div>
  </div>
</template>

<script>
import TaskFormModal from '../components/TaskFormModal.vue'
import Task from '../components/Task.vue'
import ProjectSettingsModal from '../components/ProjectSettingsModal.vue'
import axios from 'axios'
export default {
  components: {
    taskform: TaskFormModal,
    task: Task,
    settingsModal: ProjectSettingsModal
  },
  data () {
    return {
      project: {},
      editing_title: false,
      editing_description: false,
      inSettings: false,
      tasks: [],
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
    filteredTasks (status) {
      return this.tasks.filter(function (task) {
        return task.status === status // statuses: complete, in_progress, not_started, unsigned
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
    createTask (payload) {
      this.tasks.push(payload)
    },
    removeTask (taskId) {
      this.tasks = this.tasks.filter(function (task) {
        return task.id !== taskId
      })
    },
    updateTask (payload) {
      for (var i = 0; i < this.tasks.length; i++) {
        if (this.tasks[i].id === payload.id) {
          this.tasks.splice(i, 1, payload)
        }
      }
    }
  },
  created () {
    axios.get(`/projects/${this.$route.params.id}?token=${this.authToken}`)
      .then(res => {
        if (res.data.is_error) {
          alert(res.data.message)
        } else {
          this.project = res.data
          axios.get(`/projects/${this.project.id}/tasks?token=${this.authToken}`)
            .then(res2 => {
              this.tasks = res2.data
            })
        }
      })
      .catch(res => {
        alert('Server-side error occurred. Try again later.')
      })
  }
}
</script>
