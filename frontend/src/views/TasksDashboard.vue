<template>
    <div class="container bg-gray-200">
      <div class="bg-gray-100 shadow">
        <div class="mx-8 py-3">
          <div class="text-gray-900 text-2xl font-semibold">Dashboard</div>
        </div>
      </div>
      <div class="flex justify-between items-center my-2">
        <div class="mx-8 text-xl font-semibold text-gray-900">
          Your Tasks:
        </div>
        <div class="items-center mx-8 items-center font-semibold text-gray-700 text-lg">
          <span class="mr-2">Filtered by: Latest</span>
          <span class="px-2 py-1 text-2xl text-gray-700 hover:bg-gray-400 transition ease-in-out duration-150 rounded-lg"><i class="fas fa-sliders-h"></i></span>
        </div>
      </div>
      <div class="mx-8 space-y-2">
        <div class="" v-for="task in tasks" :key="task.id">
          <task :task="task"></task>
        </div>
      </div>
    </div>
</template>

<script>
import axios from 'axios'
import Task from '../components/UserTask.vue'
export default {
  components: {
    task: Task
  },
  data () {
    return {
      tasks: []
    }
  },
  created () {
    axios.get(`/user/tasks?token=${this.$store.state.authToken}`)
      .then(res => {
        this.tasks = res.data.filter(function (task) {
          return task.status !== 'complete'
        })
        console.log('hello')
      })
  }
}
</script>
