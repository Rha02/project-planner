<template>
  <div class="absolute z-50 inset-0 text-gray-800 rounded-md text-sm flex items-center justify-center">
    <div class="fixed inset-0 h-full w-full opacity-75 bg-gray-900" @click="stopShowing()"></div>
    <div class="fixed items-center bg-gray-100 rounded-lg px-3 pt-2 pb-4 w-4/5 md:w-3/5 lg:w-1/2">
      <div class="flex items-center justify-between">
        <div class="px-2 text-gray-100 text-xl"></div> <!--Div block in order to take space on left-->
        <div class="text-2xl text-gray-800 text-center font-semibold">
          New Task
        </div>
        <button @click="stopShowing()" class="px-2 text-gray-800 text-xl font-semibold hover:bg-gray-400 rounded-lg transition ease-in-out duration-150">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="text-xl text-gray-800">
        Enter Task Text:
      </div>
      <textarea type="text" class="px-2 py-1 bg-gray-300 rounded-md w-3/4" v-model="body"></textarea>
      <div class="text-xl text-gray-800">
        Assign Status:
      </div>
      <select class="mt-2 bg-gray-300 rounded-md px-2 py-1" v-model="status">
        <option value="unsigned">No Status</option>
        <option value="not_started">Not Started</option>
        <option value="in_progress">In Progress</option>
        <option value="complete">Completed</option>
      </select>
      <div class="mt-1 text-xl text-gray-800">
        Assign to:
      </div>
      <select class="mt-2 bg-gray-300 rounded-md px-2 py-1" v-model="assigned_user">
        <option :value="null">Unassigned</option>
        <option v-for="member in members" :key="member.id" :value="member.id">{{ member.email }}</option>
      </select>
      <div class="text-center text-lg mt-2">
        <button type="button" @click="addTask()" class="mx-1 px-2 py-1 font-semibold rounded bg-blue-600 hover:bg-blue-500 text-white transition ease-in-out duration-150">Create</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  props: ['project', 'members'],
  data () {
    return {
      body: '',
      status: 'unsigned',
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
      axios.post(`/projects/${this.project.id}/goals/${this.$route.params.goal_id}/tasks?token=${this.authToken}`, {
        status: this.status,
        body: this.body,
        user_id: this.assigned_user
      })
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.$emit('addTask', res.data)
          }
        })
        .catch(res => {
          alert('Server-side error occurred.')
        })
    },
    stopShowing () {
      this.$emit('stopShowing')
    }
  }
}
</script>
