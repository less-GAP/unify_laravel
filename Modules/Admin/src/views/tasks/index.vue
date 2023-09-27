<script setup>
import router from "@/router";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import { watch } from "vue";
import { useAuthStore } from "@/stores/auth";
import { DataTable } from "@/components";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import { UseDataTable } from "@/utils/UseDataTable";
import TaskItem from "@/components/TaskItem.vue";
import { Draggable } from "vuedraggable";

const auth = useAuthStore();
const prefix = "task";
const { fetchListApi } = UseEloquentRouter(prefix);
const itemActions = [
  {
    label: "Edit",
    key: "edit",
    show: () => {
      return true;
    },
    action: (item) => {
      router.replace(prefix + "/" + item.id + "/edit");
    },
  },
];
const listActions = [
  {
    label: "Add",
    permission: auth.hasPermission("task.assign"),
    action: () => {
      router.replace(prefix + "/new");
    },
  },
];
const columns = [];

const dragOptions = {
  animation: 200,
  group: "description",
  disabled: false,
  ghostClass: "ghost",
  chosenClass: "chosen",
  dragClass: "drag",
  handle: ".drag-handle",
  forceFallback: true,
  fallbackClass: "fallback",
  fallbackOnBody: true,
  fallbackTolerance: 0,
  direction: "vertical",
  sort: true,
  scroll: true,
  scrollSensitivity: 30,
  scrollSpeed: 10,
  setData: function (dataTransfer, dragEl) {
    dataTransfer.setData("Text", dragEl.textContent);
  },
  dropBubble: false,
  dragoverBubble: false,
};

const tableConfig = UseDataTable(fetchListApi, {
  columns,
  showSelection: true,
  showSort: false,
  listActions,
  itemActions,
});
let reloadTable = () => { };

watch(router.currentRoute, (currentRoute) => {
  if (currentRoute.path === "/" + prefix) {
    reloadTable();
  }
});

function registerTable({ reload }) {
  reloadTable = reload;
}
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <DataTable v-bind="tableConfig" @register="registerTable">
        <template #header>
          <h2>Task List</h2>
        </template>
        <template #table="{
          tableConfig,
          tableData,
          data,
          columns,
          selectionActions,
          reload,
        }">
          <div class="flex overflow-x-auto custom-scroll gap-6 pb-4 h-[calc(100vh-235px)]">
            <div class="flex flex-col flex-shrink-0 p-4 border border-gray-200 rounded-md w-80 dark:border-gray-700">
              <h5 class="mb-4 uppercase">ToDo (3)</h5>
              <div class="flex flex-col h-full gap-4 px-1 overflow-x-hidden overflow-y-auto kanban-board custom-scroll">
                <!-- {{ data }} -->
                <Draggable v-bind="dragOptions" :list="data.filter(item => item.task_process === 0)" class="ant-table-tbody" handle=".drag-handle" tag="div">
                  <div v-for="(item, index) in data" :key="index">
                    <TaskItem :v-model="item"></TaskItem>
                  </div>
                </Draggable>
              </div>
            </div>
            <div class="flex flex-col flex-shrink-0 p-4 border border-gray-200 rounded-md w-80 dark:border-gray-700">
              <h5 class="mb-4 uppercase">IN PROGRESS (2)</h5>
              <div class="flex flex-col h-full gap-4 px-1 overflow-x-hidden overflow-y-auto kanban-board custom-scroll">
                <Draggable v-bind="dragOptions" :list="data.filter(item => item.task_process === 1)" class="ant-table-tbody" handle=".drag-handle" tag="div">
                  <div v-for="(item, index) in data" :key="index">
                    <TaskItem :v-model="item"></TaskItem>
                  </div>
                </Draggable>
              </div>
            </div>
            <div class="flex flex-col flex-shrink-0 p-4 border border-gray-200 rounded-md w-80 dark:border-gray-700">
              <h5 class="mb-4 uppercase">Review (4)</h5>
              <div class="flex flex-col h-full gap-4 px-1 overflow-x-hidden overflow-y-auto kanban-board custom-scroll">
                <Draggable v-bind="dragOptions" :list="data.filter(item => item.task_process === 2)" class="ant-table-tbody" handle=".drag-handle" tag="div">
                  <div v-for="(item, index) in data" :key="index">
                    <TaskItem :v-model="item"></TaskItem>
                  </div>
                </Draggable>
              </div>
            </div>
            <div class="flex flex-col flex-shrink-0 p-4 border border-gray-200 rounded-md w-80 dark:border-gray-700">
              <h5 class="mb-4 uppercase">Done (1)</h5>
              <div class="flex flex-col h-full gap-4 px-1 overflow-x-hidden overflow-y-auto kanban-board custom-scroll">
                <Draggable v-bind="dragOptions" :list="data.filter(item => item.task_process === 3)" class="ant-table-tbody" handle=".drag-handle" tag="div">
                  <div v-for="(item, index) in data" :key="index">
                    <TaskItem :v-model="item"></TaskItem>
                  </div>
                </Draggable>
              </div>
            </div>
          </div>
        </template>
      </DataTable>
      <router-view></router-view>
    </SectionMain>
  </LayoutAuthenticated>
</template>
