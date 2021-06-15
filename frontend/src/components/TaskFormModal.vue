<template>
  <div class="absolute z-50 inset-0 text-gray-800 flex items-center justify-center">
    <div class="fixed inset-0 h-full w-full opacity-75 bg-gray-900" @click="stopShowing()"></div>
    <div class="fixed items-center bg-white rounded-lg px-3 py-2 w-4/5 md:w-3/5 lg:w-1/2">
      <div class="flex items-center justify-between">
        <div class="px-2"></div> <!--Div block in order to take space on left-->
        <div class="text-2xl text-center font-semibold">
          New Task
        </div>
        <button @click="stopShowing()" class="px-2 text-xl font-semibold bg-gray-200 hover:bg-white text-gray-700 hover:text-gray-8 rounded-lg transition ease-in-out duration-150">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="text-xl">
        Enter Task Text:
      </div>
      <textarea type="text" class="px-2 py-1 mt-1 bg-gray-200 rounded w-3/4" v-model="body"></textarea>
      <div class="text-xl">
        Assign Status:
      </div>
      <select class="mt-1 bg-gray-200 rounded px-2 py-1" v-model="status">
        <option value="unsigned">No Status</option>
        <option value="not_started">Not Started</option>
        <option value="in_progress">In Progress</option>
        <option value="complete">Completed</option>
      </select>
      <div class="mt-1 text-xl">
        Assign to:
      </div>
      <select class="mt-2 bg-gray-200 rounded px-2 py-1" v-model="assigned_user">
        <option :value="null">Unassigned</option>
        <option v-for="member in members" :key="member.id" :value="member.id">{{ member.email }}</option>
      </select>
      <div class="text-center text-lg mt-2">
        <button type="button" @click="addTask()" class="px-2 py-1 font-semibold rounded bg-blue-500 hover:bg-blue-400 text-white transition ease-in-out duration-150">Create</button>
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
