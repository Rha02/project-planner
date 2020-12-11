<template>
  <div class="text-gray-800 shadow-lg rounded-md text-sm bg-gray-100 mx-2 my-3 py-1 px-2">
    <div class="text-center text-xl hover:text-blue-600 transition ease-in-out duration-150" v-if="!creating_task">
      <button type="button" @click="creating_task = true" class="w-full">
        <i class="far fa-plus-square"></i>
      </button>
    </div>
    <div class="text-center text-lg" v-else>
      <textarea type="text" rows="4" cols="22" class="mt-2 bg-gray-300 rounded-md px-2 py-1" v-model="body"></textarea>
      <div class="flex justify-between px-1 py-1">
        <button type="button" class="px-2 py-1" @click="creating_task = false">Cancel</button>
        <button type="button" @click="addTask" class="bg-blue-600 text-white hover:bg-blue-500 px-2 py-1 rounded-md transition ease-in-out duration-150">Add</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  props: ['status'],
  data () {
    return {
      creating_task: false,
      body: ''
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
        body: this.body
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
