<template>
  <div class="rounded border-l-4 bg-white text-white hover:text-gray-700 shadow px-2 text-center border-gray-600"
        v-bind:class="{ 'border-green-600': task.status == 'complete', 'border-orange-600': task.status == 'in_progress', 'border-red-600': task.status == 'not_started' }">
    <div class="flex">
      <span class="text-gray-800">
        {{ task.body }}
      </span>
    </div>
    <div class="text-gray-700 mt-1">
      <span class="font-semibold">Assignee:</span> {{ assignedUser }}
    </div>
    <div class="flex justify-between mb-1">
      <div class="text-center hover:text-blue-600 hover:bg-gray-300 rounded-lg transition ease-in-out duration-150"
            v-bind:class="{ 'text-blue-600': this.chain.isChaining }">
        <button type="button" class="px-1" @click="chainTasks()" v-if="! lockChain && !isAlreadyChained">
          <i class="fas fa-link"></i>
        </button>
        <button type="button" class="px-1 text-red-600" @click="cancelChaining()" v-if="lockChain && !isAlreadyChained">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="text-center hover:text-blue-600 hover:bg-gray-300 rounded-lg transition ease-in-out duration-150">
        <button type="button" class="px-1" @click="editing = true">
          <i class="fas fa-pen"></i>
        </button>
      </div>
    </div>
    <div class="" v-if="editing">
      <task-edit-modal :task="task" :project="project" @stopShowing="editing = false" @updateTask="updateTask" @removeTask="deleteTask" @breakSequence="breakSequence()"></task-edit-modal>
    </div>
  </div>
</template>

<script>
import TaskEditModal from '../components/TaskEditModal.vue'
import axios from 'axios'
export default {
  props: ['task', 'project', 'chain'],
  components: {
    taskEditModal: TaskEditModal
  },
  data () {
    return {
      editing: false,
      lockChain: false
    }
  },
  computed: {
    taskUri () {
      return `/projects/${this.project.id}/goals/${this.$route.params.goal_id}/tasks/${this.task.id}?token=${this.$store.state.authToken}`
    },
    isAlreadyChained () {
      return this.task.next_tasks.some(sequence => sequence.to_task_id === this.chain.firstChainedTaskId) ||
        this.task.prev_tasks.some(sequence => sequence.from_task_id === this.chain.firstChainedTaskId)
    },
    assignedUser () {
      for (var i = 0; i < this.project.members.length; i++) {
        if (this.project.members[i].id === this.task.user_id) {
          return this.project.members[i].email
        }
      }
      return 'Nobody'
    }
  },
  methods: {
    breakSequence () {
      axios.delete(`/projects/${this.project.id}/sequence/${this.task.id}?token=${this.$store.state.authToken}`)
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.$emit('breakSequence', this.task.id)
          }
        })
        .catch(res => alert('Something went wrong!'))
    },
    deleteTask () {
      axios.delete(this.taskUri)
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.$emit('taskDeleted', this.task.id)
          }
        })
        .catch(res => {
          alert('Server-side error occurred!')
        })
    },
    updateTask (payload) {
      axios.patch(this.taskUri, {
        status: payload.status,
        body: payload.body,
        user_id: payload.user_id
      })
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.$emit('taskUpdated', res.data)
          }
        })
        .catch(res => {
          alert('Server-side error occurred!')
        })
      this.editing = false
    },
    chainTasks () {
      if (!this.chain.isChaining) {
        this.lockChain = true
      }
      this.$emit('chainTasks', this.task.id)
    },
    cancelChaining () {
      this.$emit('cancelChaining')
      this.lockChain = false
    }
  }
}
</script>

<style lang="css" scoped>
</style>
