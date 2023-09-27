<template>
  <div>
      <div class="p-6">
        <div class="flex items-center justify-between">
          <small>18 Jul 2023</small>
        </div>
        <h5 class="my-2">
          <span
            class="text-base font-medium text-gray-700 dark:text-slate-400"
            >{{ value.name }}</span
          >
        </h5>
        <p class="space-x-3">
          <span class="mb-2 text-nowrap"
            ><i
              class="text-gray-500 ri-briefcase-2-line dark:text-gray-400"
            ></i>
            iOS</span
          >&nbsp;<span class="mb-2 text-nowrap"
            ><i class="text-gray-500 ri-discuss-line dark:text-gray-400"></i
            ><b class="text-gray-500 dark:text-gray-400">&nbsp;74</b>
            Comments</span
          >
        </p>
        <div class="mt-5">
          <div class="flex items-center">
            <div class="-me-3">
              <div
                class="bg-success text-white font-medium flex items-center justify-center rounded-full h-8 w-8 hover:-translate-y-0.5 transition-all duration-200"
              >
                K
              </div>
            </div>
            <div class="-me-3">
              <div
                class="bg-primary text-white font-medium flex items-center justify-center rounded-full h-8 w-8 hover:-translate-y-0.5 transition-all duration-200"
              >
                9+
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "TaskItem",
  props: {
    value: {
      type: Array,
      default: () => [],
    },
  },
  // emits: ['change', 'delete', 'update:value'],
  setup(props, { emit, attrs }) {
    return {
    };
  },
});
</script>