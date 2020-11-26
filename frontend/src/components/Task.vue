<template>
  <div class="">
    <div class="items-center" v-if="! editing">
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
    <div class="text-center text-lg text-gray-900" v-else>
      <textarea type="text" rows="4" cols="22" class="mt-2 bg-gray-300 rounded-md px-2 py-1" v-model="formData.body"></textarea>
      <br>
      Status:
      <select class="mt-2 bg-gray-300 rounded-md px-2 py-1" v-model="formData.status">
        <option disabled value="">Choose Status</option>
        <option value="unsigned">No Status</option>
        <option value="not_started">Not Started</option>
        <option value="in_progress">In Progress</option>
        <option value="complete">Completed</option>
      </select>
      <div class="flex justify-between px-1 py-1">
        <button type="button" @click="editing = false">Cancel</button>
        <button type="button" @click="updateTask" class="bg-blue-600 text-gray-100 hover:bg-blue-500 hover:text-white px-2 py-1 rounded">Update</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  props: ['task'],
  data () {
    return {
      editing: false,
      formData: {
        status: this.task.status,
        body: this.task.body
      }
    }
  },
  methods: {
    deleteTask () {
      axios.delete(`/api/projects/${this.$route.params.id}/tasks/${this.task.id}?token=${this.$store.state.authToken}`)
        .then(res => {
          this.$emit('taskDeleted', this.task.id)
        })
    },
    updateTask () {
      axios.patch(`/api/projects/${this.$route.params.id}/tasks/${this.task.id}?token=${this.$store.state.authToken}`, {
        status: this.formData.status,
        body: this.formData.body
      })
        .then(res => {
          this.$emit('taskUpdated', res.data)
          this.editing = false
        })
    }
  }
}
</script>
