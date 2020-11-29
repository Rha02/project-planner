<template>
  <div v-if="showing" class="flex items-center justify-center">
    <div class="fixed inset-0 h-full w-full opacity-75 bg-gray-900" @click="stopShowing"></div>
    <div class="px-3 py-1 fixed w-1/2 bg-gray-100 rounded-lg shadow-lg pt-1 pb-2">
      <div class="flex items-center justify-between">
        <div class="px-2 text-gray-100 text-xl"></div> <!--Div block in order to take space on left-->
        <div class="text-2xl font-semibold text-center text-gray-800">
          Project Settings
        </div>
        <button @click="stopShowing" class="px-2 text-gray-800 text-xl font-semibold hover:bg-gray-400 rounded-lg transition ease-in-out duration-150">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="text-gray-800 text-xl font-semibold">
        Members:
      </div>
      <p class="text-lg text-gray-800">To be implemented</p>
      <div class="flex flex-row-reverse">
          <button type="button" @click="stopShowing" class="mx-1 px-2 py-1 font-semibold rounded bg-blue-600 hover:bg-blue-500 text-white transition ease-in-out duration-150">Update</button>
          <button type="button" class="mx-1 px-2 py-1 font-semibold rounded border border-red-600 hover:bg-red-600 text-red-600 hover:text-white transition ease-in-out duration-150">Delete</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['showing'],
  methods: {
    stopShowing () {
      this.$emit('stopShowing')
    }
  }
}
</script>

<style lang="css" scoped>
</style>
