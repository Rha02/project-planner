<template>
  <div class="container mx-auto flex justify-center items-center">
    <div class="shadow-lg w-3/4 md:w-3/5 xl:w-1/2 mt-4 bg-gray-100 rounded-2xl py-2">
      <div class="my-2">
        <div class="text-2xl text-gray-800 text-center">
          Registration
        </div>
        <div class="text-red-600 text-center font-semibold" v-if="has_error && !success">
          <p v-if="error == 'registration_validation_error'">Validation Errors</p>
          <p v-else>Error, cannot register at the momend. If the problem persists, please contact the Administrator</p>
        </div>
        <form class="text-center text-gray-800 space-y-4" @submit.prevent="register" method="post" v-if="!success">
          <div class="space-y-1">
            <label for="name" class="text-lg">Name</label>
            <br>
            <input type="text" class="bg-gray-200 hover:bg-gray-300 py-1 px-2 shadow w-3/5 rounded-lg" id="name" placeholder="Your Name" v-model="name">
            <br>
            <span class="text-red-600 font-semibold" v-if="has_error && errors.name">{{ errors.name }}</span>
          </div>
          <div class="space-y-1">
            <label for="email" class="text-lg">Email</label>
            <br>
            <input type="email" class="bg-gray-200 hover:bg-gray-300 py-1 px-2 shadow w-3/5 rounded-lg" id="email" placeholder="Your Email" v-model="email">
            <br>
            <span class="text-red-600 font-semibold" v-if="has_error && errors.email">{{ errors.email }}</span>
          </div>
          <div class="space-y-1">
            <label for="password" class="text-lg">Password</label>
            <br>
            <input type="password" placeholder="Password" class="bg-gray-200 hover:bg-gray-300 py-1 px-2 shadow w-3/5 rounded-lg" id="password" v-model="password">
            <br>
            <span class="text-red-600 font-semibold" v-if="has_error && errors.password">{{ errors.password }}</span>
          </div>
          <div class="space-y-1">
            <label for="password_confirmation" class="text-lg">Confirm Password</label>
            <br>
            <input type="password" placeholder="Retype Password" class="bg-gray-200 hover:bg-gray-300 py-1 px-2 shadow w-3/5 rounded-lg" id="password_confirmation" v-model="password_confirmation">
            <br>
            <span class="text-red-600 font-semibold" v-if="has_error && errors.password_confirmation">{{ errors.password_confirmation }}</span>
          </div>
          <button type="submit" class="px-2 py-1 bg-blue-500 hover:bg-blue-600 hover:text-white text-gray-100 rounded">Register</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      has_error: false,
      error: '',
      errors: {},
      success: false
    }
  },
  methods: {
    register () {
      const formData = {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation
      }
      this.$store.dispatch('register', formData)
    }
  }
}
</script>

<style lang="css" scoped>
</style>
