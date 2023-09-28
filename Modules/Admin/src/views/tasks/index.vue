<script setup>
import router from "@/router";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import { watch, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { DataTable } from "@/components";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import { UseDataTable } from "@/utils/UseDataTable";
import TaskItem from "@/components/TaskItem.vue";
import draggable from "vuedraggable";

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
const taskColumns = ref([
  {
    name: "ToDo",
    key: "todo",
    task_process: 0,
  },
  {
    name: "Working",
    key: "working",
    task_process: 1,
  },
  {
    name: "Review",
    key: "review",
    task_process: 2,
  },
  {
    name: "Done",
    key: "done",
    task_process: 3,
  }
]);

const dragOptions = {
  animation: 200,
  group: "description",
  disabled: false,
  ghostClass: "ghost"
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
      <div class="taskList">
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
          <div class="overflow-hidden">
            <div v-if="data?.length" class="flex pb-4 h-[calc(100vh-235px)] -mx-2">
              <div v-for="column in taskColumns" :key="column.key" class="w-full px-2 md:w-1/2 lg:w-1/4">
                <div class="flex flex-col p-4 border border-gray-200 rounded-lg inner">
                  <h2 class="mb-2 text-lg font-semibold uppercase">{{ column.key }} ({{ data.filter(item => item.task_process == 0).length }})</h2>
                  <div class="flex flex-col h-full gap-4 px-1 overflow-x-hidden overflow-y-auto kanban-board">
                    <draggable v-bind="dragOptions" :list="data.filter(item => column.task_process == 0)"
                      :group="{ name: column.key }">
                      <template #item="{ element }">
                        <TaskItem :value="element" class="p-4 mb-2 bg-white rounded-md shadow-md" />
                      </template>
                    </draggable>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </template>
        </DataTable>
      </div>
      <router-view></router-view>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<style>
/* .taskList  */
.taskList .shadow.bg-white {
  background-color: transparent !important;
  box-shadow: none !important;
}

.taskList .shadow .ant-timeline {
  margin-left: 0 !important;
  margin-right: 0 !important;
  margin-bottom: 0 !important;
}</style>