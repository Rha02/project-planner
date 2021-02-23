<template>
  <div class="justify-center text-gray-900 bg-gray-200 overflow-y-auto h-screen" v-on:scroll="positionLines()">
    <div class="bg-gray-100 shadow">
      <div class="mx-8 py-2 font-semibold">
        <div class="text-2xl  w-full">
          PROJECT
        </div>
      </div>
    </div>
    <div class="">
      <div class="mx-8 pt-2 flex justify-between">
        <div class="font-semibold text-xl text-gray-200 hover:text-gray-700 w-full">
          <span class="text-gray-800">GOAL</span>
          <!--<span class="ml-1 text-lg">
            <button type="button" @click="inSettings = true" class="px-1 hover:bg-gray-400 hover:text-purple-700 rounded-lg transition ease-in-out duration-150">
              <i class="fas fa-pen"></i>
            </button>
          </span>-->
        </div>
        <div class="w-4/5 md:w-1/4 lg:w-1/3 xl:w-1/5 flex">
          <div class="text-gray-800 font-semibold text-xl flex-none">
            Status:
            <!--<span class="text-green-600" v-if="goal.status == 'complete'">Completed</span>
            <span class="text-orange-600" v-if="goal.status == 'in_progress'">In Progress</span>
            <span class="text-red-600" v-if="goal.status == 'not_started'">Not Started</span>
            <span class="text-gray-600" v-if="goal.status == 'unsigned'">Unsigned</span>-->
            <span class="text-indigo-600">PROTOTYPE</span>
          </div>
        </div>
      </div>
      <div class="mt-2 text-xl text-center font-semibold">
        Tasks: TASKS LENGTH
      </div>
      <div class="mx-4 my-2 bg-white shadow rounded-lg overflow-y-auto shadow-lg"
            style="height:75vh;"
            v-on:scroll="positionLines()">
          <div class="">
            <div class="mx-4 my-2 flex flex-wrap justify-around">
              <div class="w-1/4 px-2 py-2 flex justify-center items-center"
                    v-for="task in tasks" :key="task.id">
                <span :id="`id${task.id}`" class="rounded border-l-4 border-green-600 bg-white shadow px-2 text-center">
                  {{ task.body }}
                </span>
              </div>
            </div>
          </div>
      </div>
    </div>
    <!--<div class="" v-if="inSettings">
      <goal-settings :goal="goal" @stopShowing="inSettings = false" @deleteGoal="deleteGoal()" @updateGoal="updateGoal"></goal-settings>
    </div>-->
  </div>
</template>

<script>
import LeaderLine from 'leader-line'
export default {
  data () {
    return {
      arrows: [],
      tasks: [
        {
          status: 'complete',
          body: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit',
          id: 1,
          prev_tasks: [],
          next_tasks: [2],
          depth: 0
        },
        {
          status: 'in_progress',
          body: 'blah blah blah',
          id: 2,
          prev_tasks: [1],
          next_tasks: [3, 4],
          depth: 0
        },
        {
          status: 'not_started',
          body: 'Return blah blah blah to blah ',
          id: 3,
          prev_tasks: [2],
          next_tasks: [],
          depth: 0
        },
        {
          status: 'unsigned',
          body: 'To do blah blah',
          id: 4,
          prev_tasks: [2],
          next_tasks: [],
          depth: 0
        }
      ]
    }
  },
  methods: {
    positionLines () {
      for (var i = 0; i < this.arrows.length; i++) {
        this.arrows[i].position()
      }
    },
    calculateDepth (task) {
      if (!task.prev_tasks.length) {
        console.log('oh no')
        this.tasks[task.id - 1].depth = 1
        return 1
      }
      let depth = 0
      for (var i = 0; i < task.prev_tasks.length; i++) {
        var prevTask = this.tasks[task.prev_tasks[i] - 1]
        console.log(prevTask)
        if (!prevTask.depth) {
          prevTask.depth = this.calculateDepth(prevTask)
          this.tasks[prevTask.id - 1].depth = prevTask.depth
        }
        if (prevTask.depth > depth) {
          depth = prevTask.depth
        }
      }
      this.tasks[task.id - 1].depth = depth + 1
      return depth + 1
    }
  },
  created () {
    for (var k = 0; k < this.tasks.length; k++) {
      this.calculateDepth(this.tasks[k])
    }
  },
  mounted () {
    for (var i = 0; i < this.tasks.length; i++) {
      for (var j = 0; j < this.tasks[i].next_tasks.length; j++) {
        this.arrows.push(
          new LeaderLine(
            document.getElementById(`id${this.tasks[i].id}`),
            document.getElementById(`id${this.tasks[i].next_tasks[j]}`)
          )
        )
      }
    }
  },
  destroyed () {
    for (var i = 0; i < this.arrows.length; i++) {
      this.arrows[i].remove()
    }
  }
}
</script>
