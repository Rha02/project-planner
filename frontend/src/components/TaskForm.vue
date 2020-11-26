<template>
  <div class="text-gray-800 shadow-lg rounded-md text-sm bg-gray-100 mx-2 my-3 py-1 px-2">
    <div class="text-center text-xl hover:text-blue-600" v-if="!creating_task">
      <button type="button" @click="creating_task = true" class="w-full">
        <i class="far fa-plus-square"></i>
      </button>
    </div>
    <div class="text-center text-lg" v-else>
      <textarea type="text" rows="4" cols="22" class="mt-2 bg-gray-300 rounded-md px-2 py-1" v-model="body"></textarea>
      <div class="flex justify-between px-1 py-1">
        <button type="button" @click="creating_task = false">Cancel</button>
        <button type="button" @click="addTask" class="bg-blue-600 text-gray-100 hover:bg-blue-500 hover:text-white px-2 py-1 rounded">Add</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['statusId'],
  data () {
    return {
      creating_task: false,
      body: ''
    }
  },
  methods: {
    addTask () {
      this.$emit('addTask', {
        body: this.body,
        statusId: this.statusId
      })
      this.creating_task = false
    }
  }
}
</script>
