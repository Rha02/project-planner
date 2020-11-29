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
      <div class="text-2xl text-gray-200 hover:text-gray-700" v-else>
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
    <div class="text-gray-200 hover:text-gray-700 mt-1" v-else>
      <span class="text-gray-800">{{ project.description }}</span>
      <span class="ml-1">
        <button type="button" @click="editDescription()" class="px-1 hover:bg-gray-400 rounded-lg hover:text-purple-700 transition ease-in-out duration-150">
          <i class="fas fa-pen"></i>
        </button>
      </span>
    </div>
    <div class="mt-4 text-xl text-center font-semibold">
      Tasks: {{ tasks_count }}
    </div>
    <div class="flex flex-wrap -mb-4 mt-2">
      <div class="w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            No Status
          </div>
          <div class="text-white hover:text-gray-800 shadow-lg rounded-md text-lg bg-gray-100 mx-2 my-3 py-1 px-2"
                v-for="task in unsigned_tasks" :key="task.id">
            <task :task="task" @taskDeleted="removeTask" @taskUpdated="updateTask"></task>
          </div>
          <taskform @addTask="createTask" statusId="0"></taskform>
        </div>
      </div>
      <div class="w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            Not Started
          </div>
          <div class="text-white hover:text-gray-800 shadow-lg rounded-md text-lg bg-gray-100 mx-2 my-3 py-1 px-2"
                v-for="task in not_started_tasks" :key="task.id">
            <task :task="task" @taskDeleted="removeTask" @taskUpdated="updateTask"></task>
          </div>
          <taskform @addTask="createTask" statusId="1"></taskform>
        </div>
      </div>
      <div class="w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            In Progress
          </div>
          <div class="text-white hover:text-gray-800 shadow-lg rounded-md text-lg bg-gray-100 mx-2 my-3 py-1 px-2"
                v-for="task in in_progress_tasks" :key="task.id">
            <task :task="task" @taskDeleted="removeTask" @taskUpdated="updateTask"></task>
          </div>
          <taskform @addTask="createTask" statusId="2"></taskform>
        </div>
      </div>
      <div class="w-1/4 px-2">
        <div class="pb-4">
          <div class="text-lg text-center text-gray-800 font-semibold pt-2">
            Completed
          </div>
          <div class="text-white hover:text-gray-800 shadow-lg rounded-md text-lg bg-gray-100 mx-2 my-3 py-1 px-2"
                v-for="task in completed_tasks" :key="task.id">
            <task :task="task" @taskDeleted="removeTask" @taskUpdated="updateTask"></task>
          </div>
          <taskform @addTask="createTask" statusId="3"></taskform>
        </div>
      </div>
    </div>
    <settings-modal :showing="inSettings" @stopShowing="inSettings = false"></settings-modal>
  </div>
</template>

<script>
import TaskForm from '../components/TaskForm.vue'
import Task from '../components/Task.vue'
import ProjectSettingsModal from '../components/ProjectSettingsModal.vue'
import axios from 'axios'
export default {
  components: {
    taskform: TaskForm,
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
    tasks_count () {
      return this.tasks.length
    },
    completed_tasks () {
      return this.tasks.filter(function (task) {
        return task.status === 'complete'
      })
    },
    in_progress_tasks () {
      return this.tasks.filter(function (task) {
        return task.status === 'in_progress'
      })
    },
    not_started_tasks () {
      return this.tasks.filter(function (task) {
        return task.status === 'not_started'
      })
    },
    unsigned_tasks () {
      return this.tasks.filter(function (task) {
        return task.status === 'unsigned'
      })
    }
  },
  methods: {
    updateProjectTitle () {
      axios.patch(`/api/projects/${this.project.id}?token=${this.$store.state.authToken}`, {
        title: this.formData.title,
        description: this.project.description
      })
        .then(res => {
          this.editing_title = false
          this.project.title = res.data.title
        })
    },
    updateProjectDescription () {
      axios.patch(`/api/projects/${this.project.id}?token=${this.$store.state.authToken}`, {
        title: this.project.title,
        description: this.formData.description
      })
        .then(res => {
          this.editing_description = false
          this.project.description = res.data.description
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
      var status = 'unsigned'
      switch (payload.statusId) {
        case '0':
          break
        case '1':
          status = 'not_started'
          break
        case '2':
          status = 'in_progress'
          break
        case '3':
          status = 'complete'
          break
      }
      axios.post(`/api/projects/${this.$route.params.id}/tasks?token=${this.$store.state.authToken}`, {
        status,
        body: payload.body
      }).then(res => {
        this.tasks.push(res.data)
      })
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
    axios.get(`/api/projects/${this.$route.params.id}?token=${this.$store.state.authToken}`)
      .then(res => {
        this.project = res.data
        this.formData.title = this.project.title
        this.formData.description = this.project.description
        axios.get(`/api/projects/${this.$route.params.id}/tasks?token=${this.$store.state.authToken}`)
          .then(res2 => {
            this.tasks = res2.data
          })
      })
  }
}
</script>
