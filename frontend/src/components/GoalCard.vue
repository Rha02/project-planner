<template>
  <div class="">
    <div class="text-gray-700 lg:text-white hover:text-gray-700 shadow hover:shadow-lg transition ease-in-out duration-150 rounded-md text-lg bg-gray-100 mx-2 my-3 py-1 px-2 items-center">
      <div class="w-full text-gray-800 overflow-auto">
        {{ goal.title }}
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  props: ['goal'],
  data () {
    return {
      editing: false
    }
  },
  computed: {
    goalUri () {
      return `/projects/${this.$route.params.id}/goals/${this.goal.id}?token=${this.$store.state.authToken}`
    }
  },
  methods: {
    deleteTask () {
      axios.delete(this.goalUri)
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.$emit('goalDeleted', this.goal.id)
          }
        })
        .catch(res => {
          alert('Server-side error occurred!')
        })
    },
    updateTask (payload) {
      axios.patch(this.goalUri, {
        status: payload.status,
        title: payload.title
      })
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          } else {
            this.$emit('goalUpdated', res.data)
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
