<template>
  <div class="justify-center text-gray-900 bg-gray-200 h-full">
    <div class="bg-gray-100 shadow">
      <div class="mx-8 py-3 font-semibold">
        <div class="text-2xl  w-full">
          <router-link :to="`/projects/${project.id}`" class="font-semibold text-blue-600 hover:underline">
            {{ project.title }}
          </router-link>
        </div>
      </div>
    </div>
    <div class="mx-8 py-3 font-semibold text-xl text-gray-200 hover:text-gray-700 w-full">
      <div class="" v-if="editing_title">
        <input class="text-gray-900 bg-gray-200 w-3/4 px-1" ref="title" v-model="formData.title">
        <span class="ml-1 space-x-1 text-lg">
          <button type="button" @click="editing_title = false" class="px-2 hover:bg-red-600 rounded-lg hover:text-white transition ease-in-out duration-150">
            <i class="fas fa-times"></i>
          </button>
          <button type="button" @click="updateProjectTitle()" class="px-1 hover:bg-green-600 rounded-lg hover:text-white transition ease-in-out duration-150">
            <i class="fas fa-check"></i>
          </button>
        </span>
      </div>
      <div v-else>
        <span class="text-gray-800">{{ goal.title }}</span>
        <span class="ml-1 text-lg">
          <button type="button" @click="editTitle()" class="px-1 hover:bg-gray-400 hover:text-purple-700 rounded-lg transition ease-in-out duration-150">
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
  </div>
</template>

<script>
import TaskFormModal from '../components/TaskFormModal.vue'
import Task from '../components/Task.vue'
import axios from 'axios'
export default {
  components: {
    taskform: TaskFormModal,
    task: Task
  },
  data () {
    return {
      project: {},
      goal: {},
      tasks: [],
      editing_title: false,
      formData: {
        title: ''
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
      this.formData.title = this.goal.title
    }
  },
  methods: {
    filteredTasks (status) {
      return this.tasks.filter(function (task) {
        return task.status === status // statuses: complete, in_progress, not_started, unsigned
      })
    },
    editTitle () {
      this.editing_title = true
      this.$nextTick(() => this.$refs.title.focus())
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
          axios.get(`/projects/${this.project.id}/goals/${this.$route.params.goal_id}?token=${this.authToken}`)
            .then(res2 => {
              this.goal = res2.data
              axios.get(`/projects/${this.project.id}/goals/${this.goal.id}/tasks?token=${this.authToken}`)
                .then(res3 => {
                  this.tasks = res3.data
                })
            })
        }
      })
      .catch(res => {
        alert('Server-side error occurred. Try again later.')
      })
  }
}
</script>
