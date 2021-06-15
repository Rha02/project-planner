<template>
  <div class="absolute z-50 inset-0 flex items-center justify-center text-gray-800">
    <div class="fixed inset-0 h-full w-full opacity-75 bg-gray-900" @click="stopShowing"></div>
    <div class="px-3 py-2 fixed w-4/5 md:w-3/5 lg:w-1/2 bg-white rounded-lg shadow-lg">
      <div class="flex items-center justify-between">
        <div class="px-2"></div> <!--Div block in order to take space on left-->
        <div class="text-2xl font-semibold text-center">
          Goal Settings
        </div>
        <button @click="stopShowing" class="px-2 text-xl font-semibold bg-gray-200 hover:bg-white text-gray-700 hover:text-gray-800 rounded-lg transition ease-in-out duration-150">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="space-y-2">
        <div class="">
          <div for="" class="text-xl font-semibold">Goal Title:</div>
          <input type="text" class="w-2/3 text-lg bg-gray-200 px-2 py-1 rounded" v-model="formData.title">
        </div>
        <div class="">
          <div for="" class="text-xl font-semibold">Goal Status:</div>
          <select class="text-lg bg-gray-200 px-2 py-1 rounded" v-model="formData.status">
            <option value="unsigned">No Status</option>
            <option value="not_started">Not Started</option>
            <option value="in_progress">In Progress</option>
            <option value="complete">Completed</option>
          </select>
        </div>
        <div class="text-center text-lg mt-2 space-x-4">
          <button type="button" @click="updateGoal()" class="px-2 py-1 font-semibold rounded bg-blue-500 hover:bg-blue-400 text-white transition ease-in-out duration-150">Update</button>
          <button type="button" @click="deleteGoal()" class="px-2 py-1 font-semibold rounded border border-red-500 hover:bg-red-500 text-red-500 hover:text-white transition ease-in-out duration-150">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['goal'],
  data () {
    return {
      formData: {
        title: '',
        status: ''
      }
    }
  },
  created () {
    this.formData.title = this.goal.title
    this.formData.status = this.goal.status
  },
  methods: {
    stopShowing () {
      this.$emit('stopShowing')
    },
    deleteGoal () {
      if (confirm('You are about to permannently delete this goal. Do you wish to proceed?')) {
        this.$emit('deleteGoal')
      }
    },
    updateGoal () {
      this.$emit('updateGoal', this.formData)
    }
  }
}
</script>
