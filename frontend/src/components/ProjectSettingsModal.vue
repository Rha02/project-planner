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
      <ul class="text-gray-800 text-lg">
        <li>
          {{ project.members[0].email }} <span class="ml-1 text-yellow-700"><i class="fas fa-crown"></i></span>
        </li>
        <li v-for="member in this.project.members.slice(1)" :key="member.name">
          {{ member.email }}
          <button @click="removeMember(member.email)" class="px-1 text-gray-800 hover:text-red-700 transition ease-in-out duration-150"><i class="fas fa-times"></i></button>
        </li>
      </ul>
      <div class="mt-2 text-gray-800 text-lg font-semibold">
        Add a Member:
      </div>
      <div class="">
        <input type="email" id="memberInput" class="bg-gray-300 rounded px-1" placeholder="Type user email">
        <button type="button" @click="addMember()" class="px-1 ml-1 rounded bg-blue-600 hover:bg-blue-500 text-white transition ease-in-out duration-150">
          <i class="fas fa-plus"></i>
        </button>
      </div>
      <div class="flex flex-row-reverse">
          <button type="button" @click="deleteProject()" class="mx-1 px-2 py-1 font-semibold rounded border border-red-600 hover:bg-red-600 text-red-600 hover:text-white transition ease-in-out duration-150">Delete</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  props: ['showing', 'project'],
  methods: {
    addMember () {
      var memberEmail = document.getElementById('memberInput').value
      axios.get(`/api/users?email=${memberEmail}&token=${this.$store.state.authToken}`)
        .then(res => {
          if (res.data.is_error) {
            alert(res.data.message)
          }
          var repeatedEmail = false
          for (var i = 0; i < this.project.members.length; i++) {
            if (this.project.members[i].email === res.data.email) {
              repeatedEmail = true
              break
            }
          }
          if (repeatedEmail) {
            alert('This user is already a member of this project')
          } else {
            document.getElementById('memberInput').value = ''
            axios.post(`/api/projects/${this.project.id}/members?token=${this.$store.state.authToken}`, {
              email: res.data.email
            })
              .then(res2 => {
                this.$emit('addMember', res.data)
              })
              .catch(res2 => {
                console.log(res2)
                alert('An error has occurred. Try again later')
              })
          }
        })
    },
    removeMember (email) {
      axios.delete(`/api/projects/${this.project.id}/members?email=${email}&token=${this.$store.state.authToken}`)
        .then(res => {
          this.$emit('removeMember', email)
        })
        .catch(res => {
          alert('An error occurred!')
          console.log(res)
        })
    },
    stopShowing () {
      this.$emit('stopShowing')
    },
    deleteProject () {
      if (confirm('You are about to permannently delete this project. Do you wish to proceed?')) {
        this.$emit('deleteProject')
      }
    }
  }
}
</script>
