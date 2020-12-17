<template>
  <div class="container mx-auto flex justify-center items-center">
    <div class="shadow-lg w-3/4 md:w-3/5 xl:w-1/2 mt-4 bg-gray-100 rounded-2xl py-2">
      <div class="my-2">
        <div class="text-2xl text-gray-800 text-center">
          Login
        </div>
        <form class="text-center text-gray-800 space-y-4" @submit.prevent="onSubmit" method="post">
          <div class="space-y-1">
            <label for="email" class="text-lg">Email</label>
            <br>
            <input type="email" class="bg-gray-200 hover:bg-gray-300 py-1 px-2 shadow w-3/5 rounded-lg" id="email" placeholder="Your Email" v-model="email">
          </div>
          <div class="space-y-1">
            <label for="password" class="text-lg">Password</label>
            <br>
            <input type="password" placeholder="Password" class="bg-gray-200 hover:bg-gray-300 py-1 px-2 shadow w-3/5 rounded-lg" id="password" v-model="password">
          </div>
          <span class="text-red-600 font-semibold" v-if="has_error">{{ error }}</span>
          <br>
          <button type="submit" class="px-2 py-1 bg-blue-500 hover:bg-blue-600 hover:text-white text-gray-100 rounded">Login</button>
        </form>
        <br>
      </div>
    </div>
  </div>
</template>

<script>
function sleep (ms) {
  return new Promise(resolve => setTimeout(resolve, ms))
}
export default {
  data () {
    return {
      email: '',
      password: '',
      has_error: false,
      error: ''
    }
  },
  methods: {
    onSubmit () {
      this.logging_in = true
      const formData = {
        email: this.email,
        password: this.password
      }
      this.$store.dispatch('login', formData)
      if (this.$store.state.error) {
        this.has_error = true
        sleep(500).then(() => {
          this.error = this.$store.state.error.errors
          console.log('error')
        })
        this.password = ''
      }
    }
  }
}
</script>
