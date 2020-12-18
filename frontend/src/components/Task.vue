<template>
  <div class="">
    <div class="text-gray-700 lg:text-white hover:text-gray-700 shadow-lg rounded-md text-lg bg-gray-100 mx-2 my-3 py-1 px-2 items-center">
      <div class="w-full text-gray-800 overflow-auto">
        {{ task.body }}
      </div>
      <div class="flex justify-between">
        <div class="text-center hover:text-red-700 transition ease-in-out duration-150">
          <button type="button" class="px-1" @click="deleteTask">
            <i class="fas fa-trash"></i>
          </button>
        </div>
        <div class="text-center hover:text-blue-700 transition ease-in-out duration-150">
          <button type="button" class="px-1" @click="editing = true">
            <i class="fas fa-pen"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="" v-if="editing">
      <task-edit-modal :task="task" @stopShowing="editing = false" @updateTask="updateTask"></task-edit-modal>
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
  computed: {
    taskUri () {
      return `/projects/${this.$route.params.id}/tasks/${this.task.id}?token=${this.$store.state.authToken}`
    }
  },
  methods: {
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
        body: payload.body
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
    }
  }
}
</script>
