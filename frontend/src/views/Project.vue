<template>
  <div class="container mx-auto justify-center mt-4 text-gray-900">
    <div class="text-2xl">
      {{ project.title }}
    </div>
    <div class="text-gray-800">
      {{ project.description }}
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
  </div>
</template>

<script>
import TaskForm from '../components/TaskForm.vue'
import Task from '../components/Task.vue'
import axios from 'axios'
export default {
  components: {
    taskform: TaskForm,
    task: Task
  },
  data () {
    return {
      project: {},
      tasks: []
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
        axios.get(`/api/projects/${this.$route.params.id}/tasks?token=${this.$store.state.authToken}`)
          .then(res2 => {
            this.tasks = res2.data
          })
      })
  }
}
</script>
