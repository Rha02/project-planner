<template>
  <div class="text-gray-900 rounded text-lg bg-white mx-2 my-3 py-1 px-2 text-gray-800 overflow-auto shadow hover:shadow-lg transition ease-in-out duration-150">
    {{ title }}
  </div>
</template>

<script>
export default {
  props: ['title']
}
</script>
