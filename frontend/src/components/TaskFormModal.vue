<template>
  <div class="text-gray-800 shadow-lg rounded-md text-sm bg-gray-100 mx-2 my-3 py-1 px-2">
    <div class="text-center text-xl hover:text-blue-600 transition ease-in-out duration-150">
      <button type="button" @click="creating_task = true" class="w-full">
        <i class="far fa-plus-square"></i>
      </button>
    </div>
    <div class="absolute inset-0 flex items-center justify-center" v-if="creating_task">
      <div class="fixed inset-0 h-full w-full opacity-75 bg-gray-900" @click="creating_task = false"></div>
      <div class="fixed items-center bg-gray-100 rounded-lg px-3 pt-2 pb-4 w-4/5 md:w-3/5 lg:w-1/2">
        <div class="flex items-center justify-between">
          <div class="px-2 text-gray-100 text-xl"></div> <!--Div block in order to take space on left-->
          <div class="text-2xl text-gray-800 text-center font-semibold">
            Create a Task
          </div>
          <button @click="creating_task = false" class="px-2 text-gray-800 text-xl font-semibold hover:bg-gray-400 rounded-lg transition ease-in-out duration-150">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="text-xl text-gray-800">
          Enter Task Text:
        </div>
        <textarea type="text" class="px-2 py-1 bg-gray-300 rounded-md w-3/4" v-model="body"></textarea>
        <div class="mt-1 text-xl text-gray-800">
          Assign to:
        </div>
        <select class="mt-2 bg-gray-300 rounded-md px-2 py-1" v-model="assigned_user">
          <option :value="null">Unassigned</option>
          <option v-for="member in project.members" :key="member.id" :value="member.id">{{ member.email }}</option>
        </select>
        <div class="text-center text-lg">
          <button type="button" @click="addTask()" class="mx-1 px-2 py-1 font-semibold rounded bg-blue-600 hover:bg-blue-500 text-white transition ease-in-out duration-150">Create</button>
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
      creating_task: false,
      body: '',
      assigned_user: null
    }
  },
  computed: {
    authToken () {
      return this.$store.state.authToken
    }
  },
  methods: {
    addTask () {
      axios.post(`/projects/${this.$route.params.id}/tasks?token=${this.authToken}`, {
        status: this.status,
        body: this.body,
        user_id: this.assigned_user
      })
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.$emit('addTask', res.data)
            this.creating_task = false
          }
        })
        .catch(res => {
          alert('Server-side error occurred.')
        })
    }
  }
}
</script>
