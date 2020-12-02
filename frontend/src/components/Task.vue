<template>
  <div class="">
    <div class="text-white hover:text-gray-800 shadow-lg rounded-md text-lg bg-gray-100 mx-2 my-3 py-1 px-2 items-center">
      <div class="w-full text-gray-800 overflow-auto">
        {{ task.body }}
      </div>
      <div class="flex justify-between">
        <div class="w-12 text-center hover:text-red-700">
          <button type="button" @click="deleteTask" class="w-12">
            <i class="fas fa-trash"></i>
          </button>
        </div>
        <div class="w-12 text-center hover:text-blue-700">
          <button type="button" class="w-12">
            <i class="fas fa-pen" @click="editing = true"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="">
      <task-edit-modal :showing="editing" :task="task" @stopShowing="editing = false" @updateTask="updateTask"></task-edit-modal>
    </div>
  </div>
</template>

<script>
import TaskEditModal from '../components/TaskEditModal.vue'
import axios from 'axios'
export default {
  props: ['task'],
  components: {
    taskEditModal: TaskEditModal
  },
  data () {
    return {
      editing: false
    }
  },
  methods: {
    deleteTask () {
      axios.delete(`/api/projects/${this.$route.params.id}/tasks/${this.task.id}?token=${this.$store.state.authToken}`)
        .then(res => {
          this.$emit('taskDeleted', this.task.id)
        })
    },
    updateTask (payload) {
      axios.patch(`/api/projects/${this.$route.params.id}/tasks/${this.task.id}?token=${this.$store.state.authToken}`, {
        status: payload.status,
        body: payload.body
      })
        .then(res => {
          this.$emit('taskUpdated', res.data)
          this.editing = false
        })
        .catch(res => {
          alert('An error has occured! Please try again later.')
          this.editing = false
        })
    }
  }
}
</script>
