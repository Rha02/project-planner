<template>
  <div class="">
    <div class="text-gray-700 lg:text-white hover:text-gray-700 shadow hover:shadow-lg transition ease-in-out duration-150 rounded-md text-lg bg-gray-100 mx-2 my-3 py-1 px-2 items-center">
      <div class="w-full text-gray-800 overflow-auto">
        {{ title }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['title']
}
</script>
