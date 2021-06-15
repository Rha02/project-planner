<template>
  <div class="" v-on:scroll="positionLines()">
    <div class="shadow border-b border-gray-300 z-30 bg-white px-6 py-3 text-2xl font-semibold z-30 relative">
      <router-link :to="`/projects/${$route.params.id}`" class="font-semibold text-blue-600 hover:underline">
        {{ project.title }}
      </router-link>
    </div>
    <div class="bg-gray-100 text-xl flex justify-between px-6 py-2 z-30 relative">
      <div class="font-semibold text-gray-800">
        <span class="text-gray-900">{{ goal.title }}</span>
        <span class="ml-1 text-lg">
          <button type="button" @click="inSettings = true" class="px-1 bg-gray-300 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition ease-in-out duration-150">
            <i class="fas fa-pen"></i>
          </button>
        </span>
      </div>
      <div class="text-gray-900 font-semibold text-xl">
        Status:
        <span class="text-green-600" v-if="goal.status == 'complete'">Completed</span>
        <span class="text-orange-600" v-if="goal.status == 'in_progress'">In Progress</span>
        <span class="text-red-600" v-if="goal.status == 'not_started'">Not Started</span>
        <span class="text-gray-600" v-if="goal.status == 'unsigned'">Unsigned</span>
      </div>
    </div>
    <div class="py-2 text-lg text-center font-semibold flex justify-center bg-gray-100 z-30 relative">
      Tasks: {{ tasks.length }}
      <div class="mx-2 text-center text-lg text-blue-600 hover:text-blue-500 transition ease-in-out duration-150">
        <button type="button" @click="creating_task = true" class="w-full">
          <i class="far fa-plus-square"></i>
        </button>
      </div>
    </div>
    <div class="mx-6 px-4 py-4 rounded shadow bg-white h-3/4 overflow-y-auto"
          v-on:scroll="positionLines()" v-if="tasks.length">
        <div class="flex flex-wrap justify-around" v-for="leveledTasks in taskHierarchy" :key="leveledTasks[0].depth">
          <div class="w-1/4 px-2 py-2 flex justify-center items-center mb-12"
                v-for="task in leveledTasks" :key="task.id">
            <task :id="`id${task.id}`"
                  :task="task"
                  :project="project"
                  :members="members"
                  :chain="chain"
                  @taskDeleted="removeTask"
                  @taskUpdated="updateTask"
                  @chainTasks="chainingTasks"
                  @breakSequence="breakSequence"
                  @cancelChaining="resetChain()"></task>
          </div>
        </div>
    </div>
    <div class="mx-8" v-else>
      There are no tasks for this goal.
    </div>
    <div class="bg-gray-100 h-1/4 z-30 relative"></div>
    <div class="" v-if="inSettings">
      <goal-settings :goal="goal" @stopShowing="inSettings = false" @deleteGoal="deleteGoal()" @updateGoal="updateGoal"></goal-settings>
    </div>
    <div class="" v-if="creating_task">
      <task-form @stopShowing="creating_task = false" :project="project" :members="members" @addTask="createTask"></task-form>
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
  props: {
    project_param: Object,
    goal_param: Object,
    members_param: Array
  },
  data () {
    return {
      arrows: [],
      tasks: [],
      project: {
        title: 'PROJECT'
      },
      goal: {
        title: 'GOAL',
        status: 'STATUS'
      },
      members: [],
      inSettings: false,
      creating_task: false,
      chain: {
        isChaining: false,
        firstChainedTaskId: null
      },
      projectPromise: null,
      goalPromise: null,
      tasksPromise: null,
      membersPromise: null
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
  async created () {
    if (!this.project_param) {
      this.projectPromise = axios.get(`/projects/${this.$route.params.id}?token=${this.authToken}`)
        .then(res => {
          this.project = res.data
        })
        .catch(res => {
          alert('An error has occurred?')
        })
      this.goalPromise = axios.get(`/projects/${this.$route.params.id}/goals/${this.$route.params.goal_id}?token=${this.authToken}`)
        .then(res => {
          this.goal = res.data
        })
        .catch(res => {
          alert('An error has occurred?')
        })
      this.membersPromise = axios.get(`/projects/${this.$route.params.id}/members?token=${this.authToken}`)
        .then(res => {
          this.members = res.data
        })
        .catch(res => {
          alert('An error has occurred?')
        })
    } else {
      this.project = this.project_param
      this.goal = this.goal_param
      this.members = this.members_param
    }
    this.tasksPromise = axios.get(`/projects/${this.$route.params.id}/goals/${this.$route.params.goal_id}/tasks?token=${this.authToken}`)
      .then(res => {
        this.tasks = res.data
        for (var i = 0; i < this.tasks.length; i++) {
          this.calculateDepth(this.tasks[i])
        }
      })
  },
  async mounted () {
    await this.projectPromise
    await this.goalPromise
    await this.membersPromise
    await this.tasksPromise
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
