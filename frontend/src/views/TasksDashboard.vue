<template>
    <div class="bg-gray-100 h-full">
      <div class="shadow border-b border-gray-300 z-30 bg-white px-6 py-3 text-2xl font-semibold">
        <div class="text-gray-900 text-2xl font-semibold">Dashboard</div>
      </div>
      <div class="flex justify-between items-center px-6 py-2 text-xl">
        <div class="font-semibold text-gray-900">
          Your Tasks:
        </div>
        <div class="items-center items-center font-semibold text-gray-700 text-lg">
          <span class="mr-2">Filter</span>
          <span @click="inFilters = ! inFilters" class="px-2 py-1 text-2xl text-gray-700 bg-gray-300 hover:bg-gray-100 hover:text-indigo-600 transition ease-in-out duration-150 rounded-lg">
            <i class="fas fa-sliders-h"></i>
          </span>
        </div>
      </div>
      <div class="px-6 bg-gray-100 text-gray-800 mb-2" v-if="inFilters">
        <div class="flex flex-wrap">
          <div class="w-1/3">
            <div class="mr-12 border-b-2 border-gray-600 pb-2 text-lg font-semibold">
              Task
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByTaskId()">
                Date Created
              </a>
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByTaskLatest()">
                Latest Created
              </a>
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByStatus('unsigned')" class="mr-12 text-gray-700">
                No Status
              </a>
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByStatus('not_started')" class="mr-12 text-gray-700">
                Not Started
              </a>
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByStatus('in_progress')" class="mr-12 text-gray-700">
                In progress
              </a>
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByStatus('complete')" class="mr-12 text-gray-700">
                Completed
              </a>
            </div>
          </div>
          <div class="w-1/3">
            <div class="mr-12 border-b-2 border-gray-600 pb-2 text-lg font-semibold">
              Goal
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByGoalTitle()" class="mr-12 text-gray-700">
                Title A-Z
              </a>
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByGoalId()" class="mr-12 text-gray-700">
                Date Created
              </a>
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByGoalLatest()">
                Latest Created
              </a>
            </div>
          </div>
          <div class="w-1/3">
            <div class="mr-12 border-b-2 border-gray-600 pb-2 text-lg font-semibold">
              Project
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByProjectTitle()" class="mr-12 text-gray-700">
                Title A-Z
              </a>
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByProjectId()" class="mr-12 text-gray-700">
                Date Created
              </a>
            </div>
            <div class="mr-12 text-gray-700">
              <a href="#" @click.prevent="sortByProjectLatest()">
                Latest Created
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="px-6" v-if="tasks.length">
        <div class="space-y-2" v-if="sortedTasks.length">
          <div v-for="task in sortedTasks" :key="task.id">
            <task :task="task"></task>
          </div>
        </div>
        <div v-else>
          None of the tasks match this filter.
        </div>
      </div>
      <div class="mx-8 space-y-2" v-else>
        There aren't any tasks assigned to you.
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
      tasks: [],
      sortedTasks: [],
      inFilters: false
    }
  },
  methods: {
    sortByTaskId () {
      this.sortedTasks = this.tasks
      this.sortedTasks.sort((a, b) => a.id - b.id)
    },
    sortByTaskLatest () {
      this.sortedTasks = this.tasks
      this.sortedTasks.sort((a, b) => b.id - a.id)
    },
    sortByStatus (status) {
      this.sortedTasks = this.tasks.filter(function (task) {
        return task.status === status
      })
    },
    sortByGoalTitle () {
      this.sortedTasks = this.tasks
      this.sortedTasks.sort((a, b) => a.goal.title.localeCompare(b.goal.title))
    },
    sortByGoalId () {
      this.sortedTasks = this.tasks
      this.sortedTasks.sort((a, b) => a.goal.id - b.goal.id)
    },
    sortByGoalLatest () {
      this.sortedTasks = this.tasks
      this.sortedTasks.sort((a, b) => b.goal.id - a.goal.id)
    },
    sortByProjectId () {
      this.sortedTasks = this.tasks
      this.sortedTasks.sort((a, b) => a.goal.id - b.goal.id)
    },
    sortByProjectLatest () {
      this.sortedTasks = this.tasks
      this.sortedTasks.sort((a, b) => b.goal.id - a.goal.id)
    },
    sortByProjectTitle () {
      this.sortedTasks = this.tasks
      this.sortedTasks.sort((a, b) => a.goal.project.title.localeCompare(b.goal.project.title))
    }
  },
  created () {
    axios.get(`/user/tasks?token=${this.$store.state.authToken}`)
      .then(res => {
        this.tasks = res.data
        this.sortedTasks = res.data.filter(function (task) {
          return task.status !== 'complete'
        })
      })
  }
}
</script>
