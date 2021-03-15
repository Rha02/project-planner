<template>
  <div class="justify-center text-gray-900 bg-gray-200 overflow-y-auto h-screen relative" v-on:scroll="positionLines()">
    <div class="bg-gray-100 shadow z-30 relative">
      <div class="mx-8 py-2 font-semibold">
        <div class="text-2xl  w-full">
          <router-link :to="`/projects/${project.id}`" class="font-semibold text-blue-600 hover:underline">
            {{ project.title }}
          </router-link>
        </div>
      </div>
    </div>
      <div class="mx-8 pt-2 flex justify-between z-20 relative bg-gray-200">
        <div class="font-semibold text-xl text-gray-700 hover:text-gray-700">
          <span class="text-gray-800">{{ goal.title }}</span>
          <span class="ml-1 text-lg">
            <button type="button" @click="inSettings = true" class="px-1 bg-gray-400 hover:bg-gray-300 hover:text-blue-600 rounded-lg transition ease-in-out duration-150">
              <i class="fas fa-pen"></i>
            </button>
          </span>
        </div>
        <div class="w-4/5 md:w-1/4 lg:w-1/3 xl:w-1/5 flex relative z-50">
          <div class="text-gray-800 font-semibold text-xl flex-none relative z-50">
            Status:
            <span class="text-green-600" v-if="goal.status == 'complete'">Completed</span>
            <span class="text-orange-600" v-if="goal.status == 'in_progress'">In Progress</span>
            <span class="text-red-600" v-if="goal.status == 'not_started'">Not Started</span>
            <span class="text-gray-600" v-if="goal.status == 'unsigned'">Unsigned</span>
          </div>
        </div>
      </div>
      <div class="py-2 text-xl text-center font-semibold flex justify-center z-20 relative bg-gray-200">
        Tasks: {{ tasks.length }}
        <div class="mx-2 text-center text-xl text-blue-600 hover:text-blue-500 transition ease-in-out duration-150">
          <button type="button" @click="creating_task = true" class="w-full">
            <i class="far fa-plus-square"></i>
          </button>
        </div>
      </div>
      <div class="mx-4 py-2 bg-white shadow rounded-lg overflow-y-auto shadow-lg"
            style="height:75vh;"
            v-on:scroll="positionLines()">
          <div class="" v-for="leveledTasks in taskHierarchy" :key="leveledTasks[0].depth">
            <div class="mx-4 my-4 flex flex-wrap justify-around">
              <div class="w-1/4 mb-16 px-2 py-2 flex justify-center items-center"
                    v-for="task in leveledTasks" :key="task.id">
                <task :id="`id${task.id}`"
                      :task="task"
                      :project="project"
                      :chain="chain"
                      @taskDeleted="removeTask"
                      @taskUpdated="updateTask"
                      @chainTasks="chainingTasks"
                      @breakSequence="breakSequence"
                      @cancelChaining="resetChain()"></task>
              </div>
            </div>
          </div>
      </div>
    <div class="" v-if="inSettings">
      <goal-settings :goal="goal" @stopShowing="inSettings = false" @deleteGoal="deleteGoal()" @updateGoal="updateGoal"></goal-settings>
    </div>
    <div class="" v-if="creating_task">
      <task-form @stopShowing="creating_task = false" :project="project" @addTask="createTask"></task-form>
    </div>
  </div>
</template>

<script>
import LeaderLine from 'leader-line'
import axios from 'axios'
import GoalEditModal from '../components/GoalEditModal.vue'
import TaskFormModal from '../components/TaskFormModal.vue'
import Task from '../components/Task.vue'
export default {
  components: {
    goalSettings: GoalEditModal,
    taskForm: TaskFormModal,
    task: Task
  },
  data () {
    return {
      arrows: [],
      project: {},
      goal: {},
      tasks: [],
      inSettings: false,
      creating_task: false,
      chain: {
        isChaining: false,
        firstChainedTaskId: null
      },
      dataPromise: null
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
    breakSequence (taskId) {
      const taskIdx = this.tasks.findIndex(t => t.id === taskId)
      for (var j = 0; j < this.arrows.length; j++) {
        if (this.arrows[j].start === taskId || this.arrows[j].end === taskId) {
          if (this.arrows[j].start === taskId) {
            this.tasks[this.tasks.findIndex(t => t.id === this.arrows[j].end)].prev_tasks = []
          } else {
            this.tasks[this.tasks.findIndex(t => t.id === this.arrows[j].start)].next_tasks = []
          }
          this.arrows[j].line.remove()
          this.arrows.splice(j, 1)
          j--
        }
      }
      this.tasks[taskIdx].prev_tasks = []
      this.tasks[taskIdx].next_tasks = []
    },
    resetChain () {
      this.chain.isChaining = false
      this.chain.firstChainedTaskId = null
    },
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
        this.arrows[i].line.position()
      }
    },
    calculateDepth (task) {
      let depth = 0
      for (var i = 0; i < task.prev_tasks.length; i++) {
        const prevTaskIdx = this.tasks.findIndex(t => t.id === task.prev_tasks[i].from_task_id)
        const prevTask = this.tasks[prevTaskIdx]
        if (!prevTask.depth) {
          prevTask.depth = this.calculateDepth(prevTask)
          this.tasks[prevTaskIdx].depth = prevTask.depth
        } else if (prevTask.depth > depth) {
          depth = prevTask.depth
        }
      }
      this.tasks[this.tasks.findIndex(t => t.id === task.id)].depth = depth + 1
      return depth + 1
    },
    createTask (payload) {
      this.tasks.push(payload)
      this.calculateDepth(payload)
      this.creating_task = false
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
          this.calculateDepth(this.tasks[i])
        }
      }
    },
    chainingTasks (payload) {
      if (this.chain.isChaining) {
        axios.post(`/projects/${this.project.id}/sequence?token=${this.authToken}`, {
          to_task_id: payload,
          from_task_id: this.chain.firstChainedTaskId
        })
          .then(res => {
            this.calculateDepth(this.tasks.find(t => t.id === res.data.to_task_id))
            this.createArrowLines(res.data)
          })
      } else {
        this.chain.isChaining = true
        this.chain.firstChainedTaskId = payload
      }
    },
    createArrowLines (sequence) {
      this.arrows.push(
        {
          line: new LeaderLine(
            document.getElementById(`id${sequence.from_task_id}`),
            document.getElementById(`id${sequence.to_task_id}`),
            {
              color: '#7db7ff',
              startSocket: 'bottom',
              endSocket: 'top'
            }
          ),
          start: sequence.from_task_id,
          end: sequence.to_task_id
        }
      )
    }
  },
  created () {
    axios.get(`/projects/${this.$route.params.id}?token=${this.authToken}`)
      .then(res => {
        if (res.data.is_error) {
          alert(res.data.message)
        } else {
          this.project = res.data
        }
      })
      .catch(res => {
        alert('Server-side error occurred. Try again later.')
      })
    axios.get(`/projects/${this.$route.params.id}/goals/${this.$route.params.goal_id}?token=${this.authToken}`)
      .then(res => {
        this.goal = res.data
      })
    this.dataPromise = axios.get(`/projects/${this.$route.params.id}/goals/${this.$route.params.goal_id}/tasks?token=${this.authToken}`)
      .then(res => {
        this.tasks = res.data
        for (var i = 0; i < this.tasks.length; i++) {
          this.calculateDepth(this.tasks[i])
        }
      })
  },
  async mounted () {
    await this.dataPromise
    for (var i = 0; i < this.tasks.length; i++) {
      for (var j = 0; j < this.tasks[i].next_tasks.length; j++) {
        this.createArrowLines(this.tasks[i].next_tasks[j])
      }
    }
  },
  destroyed () {
    for (var i = 0; i < this.arrows.length; i++) {
      this.arrows[i].line.remove()
    }
  }
}
</script>
