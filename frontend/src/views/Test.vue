<template>
  <div class="flex">
    <div class="bg-indigo-800 lg:w-1/5 xl:w-1/6 h-screen">
      <div class="mx-2">
        <div class="text-2xl px-2 py-1 font-semibold text-indigo-100">
          TodoApp
        </div>
        <div class="text-lg text-indigo-300 font-semibold">
          <div class="py-2 px-2 hover:bg-indigo-900 hover:text-indigo-100 rounded-lg">
            <i class="fas fa-home"></i> Dashboard
          </div>
          <div class="py-2 px-2 hover:bg-indigo-900 hover:text-indigo-100 rounded-lg">
            <i class="far fa-folder"></i> Projects
          </div>
          <div class="py-2 px-2 hover:bg-indigo-900 hover:text-indigo-100 rounded-lg">
            <i class="fas fa-folder-plus"></i> Create a Project
          </div>
        </div>
      </div>
    </div>
    <div class="container bg-gray-200">
      <div class="bg-gray-100 shadow">
        <div class="mx-8 py-3">
          <div class="text-gray-900 text-2xl font-semibold">Dashboard</div>
        </div>
      </div>
      <div class="flex justify-between items-center my-2">
        <div class="mx-8 text-xl font-semibold text-gray-900">
          Your Tasks:
        </div>
        <div class="items-center mx-8 items-center font-semibold text-gray-700 text-lg">
          <span class="mr-2">Filtered by: Latest</span>
          <span class="px-2 py-1 text-2xl text-gray-700 hover:bg-gray-400 transition ease-in-out duration-150 rounded-lg"><i class="fas fa-sliders-h"></i></span>
        </div>
      </div>
      <div class="mx-8 space-y-2">
        <div class="rounded-lg bg-gray-100 shadow p-2">
          <div class="flex text-gray-800 text-lg">
            <div class="font-semibold">
              <span class="text-blue-700 hover:text-blue-500">TodoApp</span>
            </div>
            <div class="ml-4 font-semibold text-orange-600">
              In Progress
            </div>
          </div>
          <div class="text-gray-800">
            Create a nice looking web layout for the TodoApp
          </div>
        </div>
        <div class="rounded-lg bg-gray-100 shadow p-2">
          <div class="flex text-gray-800 text-lg">
            <div class="font-semibold">
              <span class="text-blue-700 hover:text-blue-500">TodoApp</span>
            </div>
            <div class="ml-4 font-semibold text-red-600">
              Not Started
            </div>
          </div>
          <div class="text-gray-800">
            Plan on the next steps to improve the design
          </div>
        </div>
        <div class="rounded-lg bg-gray-100 shadow p-2">
          <div class="flex text-gray-800 text-lg">
            <div class="font-semibold">
              <span class="text-blue-700 hover:text-blue-500">Test Project</span>
            </div>
            <div class="ml-4 font-semibold text-gray-600">
              No Status
            </div>
          </div>
          <div class="text-gray-800">
            Come up with a good title for this project
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
}
</script>

<style lang="css" scoped>
</style>
