<template>
  <div class="mx-4 md:container md:mx-auto text-gray-800 mt-4">
    <h1 class="py-2 text-2xl text-center font-semibold border-b-2 border-gray-600">Current Tasks</h1>
    <div class="flex flex-wrap mt-2" v-if="tasksExist">
      <div class="w-full lg:w-1/3">
        <div class="mx-4">
          <div class="text-center text-xl font-semibold text-gray-700">
            Unsigned
          </div>
          <div class="my-2 space-y-2">
            <div class="" v-for="task in filteredTasks('unsigned')" :key="task.id">
              <task :task="task"></task>
            </div>
          </div>
        </div>
      </div>
      <div class="w-full mt-2 lg:w-1/3 lg:mt-0">
        <div class="mx-4">
          <div class="text-center text-xl font-semibold text-gray-700">
            Not Started
          </div>
          <div class="my-2 space-y-2">
            <div class="" v-for="task in filteredTasks('not_started')" :key="task.id">
              <task :task="task"></task>
            </div>
          </div>
        </div>
      </div>
      <div class="w-full mt-2 lg:w-1/3 lg:mt-0">
        <div class="mx-4">
          <div class="text-center text-xl font-semibold text-gray-700">
            In Progress
          </div>
          <div class="my-2 space-y-2">
            <div class="" v-for="task in filteredTasks('in_progress')" :key="task.id">
              <task :task="task"></task>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="text-center text-xl font-semibold mt-2 text-gray-700" v-else>
      You don't have any incomplete tasks assigned.
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import UserTask from '../components/UserTask.vue'
export default {
  components: {
    task: UserTask
  },
  data () {
    return {
      tasks: []
    }
  },
  computed: {
    tasksExist () {
      if (this.tasks.length > this.filteredTasks('complete')) {
        return true
      }
      return false
    }
  },
  methods: {
    filteredTasks (status) {
      return this.tasks.filter(function (task) {
        return task.status === status
      })
    }
  },
  created () {
    axios.get(`/user/tasks?token=${this.$store.state.authToken}`)
      .then(res => {
        this.tasks = res.data
      })
  }
}
</script>
