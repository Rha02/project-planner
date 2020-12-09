<template>
  <div class="absolute inset-0 flex items-center justify-center">
    <div class="fixed inset-0 h-full w-full opacity-75 bg-gray-900" @click="stopShowing()"></div>
    <div class="fixed items-center bg-gray-100 rounded-lg px-3 pt-2 pb-4 w-1/2">
      <div class="flex items-center justify-between">
        <div class="px-2 text-gray-100 text-xl"></div> <!--Div block in order to take space on left-->
        <div class="text-2xl text-gray-800 text-center font-semibold">
          Task
        </div>
        <button @click="stopShowing()" class="px-2 text-gray-800 text-xl font-semibold hover:bg-gray-400 rounded-lg transition ease-in-out duration-150">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="text-xl text-gray-800">
        Enter Task Text:
      </div>
      <textarea type="text" class="px-2 py-1 bg-gray-300 rounded-md" v-model="formData.body"></textarea>
      <div class="text-xl text-gray-800">
        Assign Status:
      </div>
      <select class="mt-2 bg-gray-300 rounded-md px-2 py-1" v-model="formData.status">
        <option value="unsigned">No Status</option>
        <option value="not_started">Not Started</option>
        <option value="in_progress">In Progress</option>
        <option value="complete">Completed</option>
      </select>
      <div class="text-center">
          <button type="button" @click="updateTask()" class="mx-1 px-2 py-1 font-semibold rounded bg-blue-600 hover:bg-blue-500 text-white transition ease-in-out duration-150">Update</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['task'],
  data () {
    return {
      formData: {
        body: '',
        status: ''
      }
    }
  },
  methods: {
    stopShowing () {
      this.$emit('stopShowing')
    },
    updateTask () {
      this.$emit('updateTask', this.formData)
    }
  },
  created () {
    this.formData.body = this.task.body
    this.formData.status = this.task.status
  }
}
</script>
