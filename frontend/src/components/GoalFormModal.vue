<template>
  <div class="text-gray-800 shadow hover:shadow-lg transition ease-in-out duration-150 rounded-md text-sm bg-white mx-2 my-3">
    <div class="text-center text-xl hover:text-blue-600 transition ease-in-out duration-150 py-1 px-2">
      <button type="button" @click="creating_goal = true" class="w-full">
        <i class="far fa-plus-square"></i>
      </button>
    </div>
    <div class="absolute inset-0 flex items-center justify-center" v-if="creating_goal">
      <div class="fixed inset-0 h-full w-full opacity-75 bg-gray-900" @click="creating_goal = false"></div>
      <div class="fixed items-center bg-white rounded-lg px-3 py-2 w-4/5 md:w-3/5 lg:w-1/2">
        <div class="flex items-center justify-between">
          <div class="px-2"></div> <!--Div block in order to take space on left-->
          <div class="text-2xl text-center font-semibold">
            Create a Goal
          </div>
          <button @click="creating_goal = false" class="px-2 text-xl font-semibold bg-gray-200 hover:bg-white text-gray-700 hover:text-gray-800 rounded-lg transition ease-in-out duration-150">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="text-xl mb-1">
          Enter Goal Title:
        </div>
        <input type="text" class="px-2 py-1 bg-gray-200 rounded w-3/4" v-model="title">
        <div class="text-center text-lg mt-2">
          <button type="button" @click="addGoal()" class="mx-1 px-2 py-1 font-semibold rounded bg-blue-500 hover:bg-blue-400 text-white transition ease-in-out duration-150">Create</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  props: ['status', 'project'],
  data () {
    return {
      creating_goal: false,
      title: ''
    }
  },
  methods: {
    addGoal () {
      axios.post(`/projects/${this.project.id}/goals?token=${this.$store.state.authToken}`, {
        status: this.status,
        title: this.title
      })
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.$emit('addGoal', res.data)
            this.creating_goal = false
          }
        })
        .catch(res => {
          alert('Server-side error occurred.')
        })
    }
  }
}
</script>
