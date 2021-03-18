<template>
  <div class="">
    <div class="flex justify-between text-lg">
      <router-link :to="`/projects/${project.id}`" class="font-semibold text-blue-600 hover:underline">
        {{ project.title ? project.title : 'Untitled' }}
      </router-link>
      <div class="text-purple-600 space-x-1 flex-none" v-if="project.members.length < 6">
        <span class="fas fa-user" v-for="n in project.members.length" :key="n"></span>
      </div>
      <div class="text-purple-600 font-bold flex-none" v-else>
      {{ project.members.length }} <span class="fas fa-user-friends"></span>
      </div>
    </div>
    <div class="text-gray-800">
      {{ project.description ? project.description : 'No description' }}
    </div>
  </div>
</template>

<script>
export default {
  props: ['project']
}
</script>
