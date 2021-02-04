<template>
  <div class="absolute inset-0 flex items-center justify-center">
    <div class="fixed inset-0 h-full w-full opacity-75 bg-gray-900" @click="stopShowing"></div>
    <div class="px-3 py-2 fixed w-4/5 md:w-3/5 lg:w-1/2 bg-gray-100 rounded-lg shadow-lg pt-1 pb-2">
      <div class="flex items-center justify-between">
        <div class="px-2 text-gray-100 text-xl"></div> <!--Div block in order to take space on left-->
        <div class="text-2xl font-semibold text-center text-gray-900">
          Goal Settings
        </div>
        <button @click="stopShowing" class="px-2 text-gray-800 text-xl font-semibold hover:bg-gray-400 rounded-lg transition ease-in-out duration-150">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="space-y-2">
        <div class="text-gray-800">
          <div for="" class="text-xl font-semibold">Goal Title:</div>
          <input type="text" class="w-2/3 text-lg bg-gray-300 px-2 py-1 rounded" v-model="formData.title">
        </div>
        <div class="text-gray-800">
          <div for="" class="text-xl font-semibold">Goal Status:</div>
          <select class="text-lg bg-gray-300 px-2 py-1 rounded" v-model="formData.status">
            <option value="unsigned">No Status</option>
            <option value="not_started">Not Started</option>
            <option value="in_progress">In Progress</option>
            <option value="complete">Completed</option>
          </select>
        </div>
        <div class="text-center text-lg mt-2 space-x-2">
          <button type="button" @click="updateGoal()" class="mx-1 px-2 py-1 font-semibold rounded bg-blue-600 hover:bg-blue-500 text-white transition ease-in-out duration-150">Update</button>
          <button type="button" @click="deleteGoal()" class="mx-1 px-2 py-1 font-semibold rounded border border-red-600 hover:bg-red-600 text-red-600 hover:text-white transition ease-in-out duration-150">Delete</button>
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
  computed: {
    authToken () {
      return this.$store.state.authToken
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
