<template>
  <div class="justify-center text-gray-900 bg-gray-200 overflow-y-auto h-screen" v-on:scroll="positionLines()">
    <div class="bg-gray-100 shadow">
      <div class="mx-8 py-2 font-semibold">
        <div class="text-2xl  w-full">
          <router-link :to="`/projects/${project.id}`" class="font-semibold text-blue-600 hover:underline">
            {{ project.title }}
          </router-link>
        </div>
      </div>
    </div>
    <div class="">
      <div class="mx-8 pt-2 flex justify-between">
        <div class="font-semibold text-xl text-gray-700 hover:text-gray-700 w-full">
          <span class="text-gray-800">{{ goal.title }}</span>
          <span class="ml-1 text-lg">
            <button type="button" @click="inSettings = true" class="px-1 bg-gray-400 hover:bg-gray-300 hover:text-blue-600 rounded-lg transition ease-in-out duration-150">
              <i class="fas fa-pen"></i>
            </button>
          </span>
        </div>
        <div class="w-4/5 md:w-1/4 lg:w-1/3 xl:w-1/5 flex">
          <div class="text-gray-800 font-semibold text-xl flex-none">
            Status:
            <span class="text-green-600" v-if="goal.status == 'complete'">Completed</span>
            <span class="text-orange-600" v-if="goal.status == 'in_progress'">In Progress</span>
            <span class="text-red-600" v-if="goal.status == 'not_started'">Not Started</span>
            <span class="text-gray-600" v-if="goal.status == 'unsigned'">Unsigned</span>
          </div>
        </div>
      </div>
      <div class="mt-2 text-xl text-center font-semibold flex justify-center">
        Tasks: {{ tasks.length }}
        <task-form :project="project" @addTask="createTask"></task-form>
      </div>
      <div class="mx-4 my-2 bg-white shadow rounded-lg overflow-y-auto shadow-lg"
            style="height:75vh;"
            v-on:scroll="positionLines()">
          <div class="" v-for="leveledTasks in taskHierarchy" :key="leveledTasks[0].depth">
            <div class="mx-4 my-4 flex flex-wrap justify-around">
              <div class="w-1/4 mb-16 px-2 py-2 flex justify-center items-center"
                    v-for="task in leveledTasks" :key="task.id">
                <span :id="`id${task.id}`" class="rounded border-l-4 bg-white shadow px-2 text-center border-gray-600"
                      v-bind:class="{ 'border-green-600': task.status == 'complete', 'border-orange-600': task.status == 'in_progress', 'border-red-600': task.status == 'not_started' }">
                  {{ task.body }}
                </span>
              </div>
            </div>
          </div>
      </div>
    </div>
    <div class="" v-if="inSettings">
      <goal-settings :goal="goal" @stopShowing="inSettings = false" @deleteGoal="deleteGoal()" @updateGoal="updateGoal"></goal-settings>
    </div>
  </div>
</template>

<script>
import LeaderLine from 'leader-line'
import axios from 'axios'
import GoalEditModal from '../components/GoalEditModal.vue'
import TaskFormModal from '../components/TaskFormModal.vue'
export default {
  components: {
    goalSettings: GoalEditModal,
    taskForm: TaskFormModal
  },
  data () {
    return {
      arrows: [],
      project: {},
      goal: {},
      tasks: [],
      inSettings: false
    }
  },
  computed: {
    taskHierarchy () {
      let i = 1
      const orderedTasks = []
      while (this.tasks.some(task => task.depth === i)) {
        orderedTasks.push(this.tasks.filter(task => task.depth === i))
        i++
      }
      return orderedTasks
    },
    authToken () {
      return this.$store.state.authToken
    }
  },
  methods: {
    updateGoal (payload) {
      axios.patch(`/projects/${this.project.id}/goals/${this.goal.id}?token=${this.authToken}`, {
        title: payload.title,
        status: payload.status
      })
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.inSettings = false
            this.goal.title = res.data.title
            this.goal.status = res.data.status
          }
        })
        .catch(res => {
          alert('Server-side error occurred.')
        })
    },
    deleteGoal () {
      axios.delete(`/projects/${this.project.id}/goals/${this.goal.id}?token=${this.authToken}`)
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.$router.push(`/projects/${this.project.id}`)
          }
        })
        .catch(res => {
          alert('Server-side error occurred.')
        })
    },
    positionLines () {
      for (var i = 0; i < this.arrows.length; i++) {
        this.arrows[i].position()
      }
    },
    calculateDepth (task) {
      const taskIdx = this.tasks.findIndex(t => t.id === task.id)
      if (!task.prev_tasks.length) {
        this.tasks[
          taskIdx
        ].depth = 1
        return 1
      }
      let depth = 0
      for (var i = 0; i < task.prev_tasks.length; i++) {
        const prevTaskIdx = this.tasks.findIndex(t => t.id === task.prev_tasks[i])
        const prevTask = this.tasks[prevTaskIdx]
        if (!prevTask.depth) {
          prevTask.depth = this.calculateDepth(prevTask)
          this.tasks[prevTaskIdx].depth = prevTask.depth
        }
        if (prevTask.depth > depth) {
          depth = prevTask.depth
        }
      }
      this.tasks[taskIdx].depth = depth + 1
      return depth + 1
    },
    createTask (payload) {
      console.log(payload)
      this.tasks.push(payload)
      console.log(payload)
      this.calculateDepth(payload)
      console.log(payload)
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
                  for (var k = 0; k < this.tasks.length; k++) {
                    this.calculateDepth(this.tasks[k])
                  }
                })
            })
        }
      })
      .catch(res => {
        alert('Server-side error occurred. Try again later.')
      })
  },
  mounted () {
    for (var i = 0; i < this.tasks.length; i++) {
      for (var j = 0; j < this.tasks[i].next_tasks.length; j++) {
        this.arrows.push(
          new LeaderLine(
            document.getElementById(`id${this.tasks[i].id}`),
            document.getElementById(`id${this.tasks[i].next_tasks[j]}`),
            {
              color: '#7db7ff',
              startSocket: 'bottom',
              endSocket: 'top'
            }
          )
        )
      }
    }
  },
  destroyed () {
    for (var i = 0; i < this.arrows.length; i++) {
      this.arrows[i].remove()
    }
  }
}
</script>
