<template>
  <div class="">
    <div class="text-gray-700 lg:text-white hover:text-gray-700 shadow-lg rounded-md text-lg bg-gray-100 mx-2 my-3 py-1 px-2 items-center">
      <div class="w-full text-gray-800 overflow-auto">
        {{ goal.title }}
      </div>
      <div class="flex justify-between">
        <div class="text-center hover:text-red-700 transition ease-in-out duration-150">
          <button type="button" class="px-1" @click="deleteTask">
            <i class="fas fa-trash"></i>
          </button>
        </div>
        <div class="text-center hover:text-blue-700 transition ease-in-out duration-150">
          <button type="button" class="px-1" @click="editing = true">
            <i class="fas fa-pen"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GoalEditModal from '../components/GoalEditModal.vue'
import axios from 'axios'
export default {
  props: ['goal', 'project'],
  components: {
    goalEditModal: GoalEditModal
  },
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
