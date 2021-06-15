<template>
  <div class="absolute z-50 inset-0 flex items-center justify-center text-gray-800">
    <div class="fixed inset-0 h-full w-full opacity-75 bg-gray-900" @click="stopShowing()"></div>
    <div class="fixed items-center bg-white rounded-lg px-3 py-2 w-4/5 md:w-3/5 lg:w-1/2">
      <div class="flex items-center justify-between">
        <div class="px-2"></div> <!--Div block in order to take space on left-->
        <div class="text-2xl text-center font-semibold">
          Task
        </div>
        <button @click="stopShowing()" class="px-2 text-xl font-semibold bg-gray-200 hover:bg-white text-gray-700 hover:text-gray-800 rounded-lg transition ease-in-out duration-150">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="text-xl">
        Enter Task Text:
      </div>
      <textarea type="text" class="mt-1 px-2 py-1 bg-gray-200 rounded w-3/4" v-model="formData.body"></textarea>
      <div class="text-xl">
        Assign Status:
      </div>
      <select class="mt-2 bg-gray-200 rounded px-2 py-1" v-model="formData.status">
        <option value="unsigned">No Status</option>
        <option value="not_started">Not Started</option>
        <option value="in_progress">In Progress</option>
        <option value="complete">Completed</option>
      </select>
      <div class="mt-1 text-xl text-gray-800">
        Assign to:
      </div>
      <select class="mt-2 bg-gray-200 rounded px-2 py-1" v-model="formData.user_id">
        <option :value="null">Unassigned</option>
        <option v-for="member in members" :key="member.id" :value="member.id">{{ member.email }}</option>
      </select>
      <div class="text-center text-lg mt-2">
        <button type="button" @click="breakSequence()" class="px-2 py-1 font-semibold rounded border border-yellow-500 hover:bg-yellow-500 text-yellow-500 hover:text-white transition ease-in-out duration-150">Break Sequence</button>
      </div>
      <div class="text-center text-lg mt-2 space-x-4">
        <button type="button" @click="updateTask()" class="px-2 py-1 font-semibold rounded bg-blue-500 hover:bg-blue-400 text-white transition ease-in-out duration-150">Update</button>
        <button type="button" @click="removeTask()" class="px-2 py-1 font-semibold rounded border border-red-500 hover:bg-red-500 text-red-500 hover:text-white transition ease-in-out duration-150">Delete</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['task', 'members'],
  data () {
    return {
      formData: {
        body: '',
        status: '',
        user_id: null
      }
    }
  },
  methods: {
    stopShowing () {
      this.$emit('stopShowing')
    },
    updateTask () {
      this.$emit('updateTask', this.formData)
    },
    removeTask () {
      this.$emit('removeTask')
    },
    breakSequence () {
      this.$emit('breakSequence')
    }
  },
  created () {
    this.formData.body = this.task.body
    this.formData.status = this.task.status
    this.formData.user_id = this.task.user_id
  }
}
</script>
