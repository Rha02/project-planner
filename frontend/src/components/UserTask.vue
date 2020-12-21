<template>
  <div class="text-center rounded bg-gray-100 shadow p-1 text-lg">
    <div class="text-gray-800">
      {{ task.body }}
    </div>
    <router-link :to="`/projects/${task.project_id}`" class="my-1 bg-blue-600 px-2 px-y rounded-lg text-white hover:bg-blue-500 transition ease-in-out duration-150">View</router-link>
  </div>
</template>

<script>
export default {
  props: ['task']
}
</script>
