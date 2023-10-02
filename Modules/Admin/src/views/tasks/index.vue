<script setup>
import router from "@/router";
import {DataTable, BaseIcon, ApiData} from "@/components";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import {watch, ref, reactive} from "vue";
import {CheckCircleOutlined, ClockCircleOutlined, SyncOutlined} from "@ant-design/icons-vue";
import {useAuthStore} from "@/stores/auth";
import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
import {UseDataTable} from "@/utils/UseDataTable";
import TaskItem from "@/components/TaskItem.vue";
import draggable from "vuedraggable";
import Api from "@/utils/Api";
import {mdiCalendarClockOutline, mdiReply, mdiUploadOutline, mdiLink} from "@mdi/js";
import {
  getStatusTask
} from "@/utils/Task";

const auth = useAuthStore();
const prefix = "task";
const {fetchListApi} = UseEloquentRouter(prefix);
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
    tasks: []
  },
  {
    name: "Working",
    key: "working",
    task_process: 1,
    tasks: []

  },
  {
    name: "Review",
    key: "review",
    task_process: 2,
    tasks: []

  },
  {
    name: "Done",
    key: "done",
    task_process: 3,
    tasks: []

  }
]);

const dragOptions = {
  animation: 200,
  disabled: false,
  ghostClass: "ghost",
  group: "tasks",
  sort: false,
};

// Handle Modal Add Task
const openModalDetail = ref(false);
const taskDetail = reactive({});
const confirmLoading = ref(false);

// const detailTask = function (task) {
//   Object.assign(taskDetail, task);

//   const { fetchListApi } = UseEloquentRouter('activity', {
//     order: "-id",
//   });
//   tableConfig.value = UseDataTable(fetchListApi, {
//     showSelection: false,
//     globalSearch: false,
//     pagination: {
//       perPage: 10,
//     },
//     filter: {
//       subject_id: taskDetail.id,
//       subject_type: "App\\Models\\Task",
//     },
//   });
//   openModalDetail.value = true;
// }

// const formatDescription = function (description) {
//   if (description !== null && typeof description === "string") {
//     return description.replace(/\n/g, "<br>");
//   } else {
//     return "";
//   }
// };

// const submitComment = function (id) {
// };

const getFilteredData = (data, taskProcess) => {
  return data.filter(item => item.task_process === taskProcess);
};

const updateProcessTask = function (data) {
  const task = data.draggedContext.element;
  Api.put('/task/' + task.id, {task_process: data.to.dataset.status})
  task.task_process = parseInt(data.to.dataset.status)
  return true;
  // console.log(mutations);
}

const tableConfig = UseDataTable(fetchListApi, {
  columns,
  showSelection: true,
  showSort: false,
  listActions,
  itemActions,
});
let reloadTable = () => {
};


function registerTable({reload}) {
  reloadTable = reload;
}
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <div class="taskList">
        <h2>Task List</h2>
        <div class="overflow-hidden">
          <div class="flex pb-4 -mx-2">
            <div v-for="column in taskColumns" :key="column.key" class="w-full px-2 md:w-1/2 lg:w-1/4">
              <ApiData @change="(items)=>{column.tasks = items}"
                       :url="'/task/all?filter[task_process]='+column.task_process"
                       class="flex flex-col p-4 border border-gray-200 rounded-lg inner">
                <template #default="{data}">
                  <h2 class="mb-2 text-lg font-semibold uppercase">{{ column.key }} ({{
                      data.length
                    }})</h2>
                  <div
                    class="flex  flex-col h-[calc(100vh-285px)] gap-4 pl-1 pr-4 overflow-x-hidden overflow-y-auto kanban-board">
                    <draggable v-bind="dragOptions"
                               v-model="column.tasks"
                               :group="{ name: 'task-list' }"
                               class="dragArea"
                               :data-status="column.task_process"
                               :item-key="column.key" :move="evt=>{return updateProcessTask(evt,column)}">
                      <template #item="{ element }">
                        <TaskItem :value="element" class="p-4  mb-2 bg-white rounded-md shadow-md"/>
                      </template>
                    </draggable>
                  </div>
                </template>

              </ApiData>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Detail Task -->
      <a-modal v-model:open="openModalDetail" append-to-body :title="taskDetail.name" width="1000px"
               :confirm-loading="confirmLoading">
        <div class="detail">
          <div class="mb-4">
            <a-tag v-if="getStatusTask(taskDetail.task_process)" :color="getStatusTask(taskDetail.task_process).color">
              <template #icon>
                <CheckCircleOutlined v-if="taskDetail.task_process === 3"/>
                <SyncOutlined v-if="taskDetail.task_process === 1" :spin="true"/>
                <ClockCircleOutlined v-if="[null, 0].includes(taskDetail.task_process)"/>
              </template>
              {{ getStatusTask(taskDetail.task_process).label }}
            </a-tag>
            <!-- <input class="rounded form-checkbox text-primary" type="checkbox" id="customckeck1" checked="">
      <label class="ms-1.5" for="customckeck1">Mark as completed</label> -->
          </div>
          <h5 class="">Description:</h5>
          <div class="text-gray-400">
            {{ taskDetail.description }}
          </div>
          <div class="my-5">
            <div class="grid gap-3 sm:grid-cols-3">
              <div class="col-span-1">
                <p class="text-gray-400">Assigned To</p>
                <div class="space-y-1">

                  <div v-for="user in taskDetail.assignees" :key="user.id" class="flex items-center item-assignee">
                    <a-avatar-group>
                      <img class="w-8 h-8 rounded-full" :src="user?.profile_photo_url" alt="user photo">
                    </a-avatar-group>
                    <div class="ml-1">
                      <h5 class="font-medium">{{ user.full_name }}</h5>
                    </div>
                  </div>
                </div>
              </div> <!-- col-end -->

              <div class="col-span-1">
                <p class="text-gray-400">Patient</p>
                <div class="flex items-center gap-3 mt-1">
                  <div class="">
                    <h5 class="font-medium">
                      <a @click="router.replace('/patient/' + taskDetail.patient.id + '/edit')">[#{{
                          taskDetail.patient.unify_number
                        }}] {{
                          taskDetail.patient.full_name
                        }}</a>
                    </h5>
                    <p class="text-xs text-stone-400">{{ age(taskDetail.patient.dob) }}</p>
                  </div>
                </div>
              </div> <!-- col-end -->

              <div class="col-span-1">
                <p class="text-gray-400">Due Date</p>
                <div class="flex items-center gap-3 mt-1">
                  <BaseIcon :path="mdiCalendarClockOutline"></BaseIcon>
                  <div class="ml-1">
                    <h5 class="font-medium">{{ taskDetail.deadline_at }}</h5>
                  </div>
                </div>
              </div> <!-- col-end -->
            </div> <!-- grid-end -->
          </div>
        </div>
        <!-- Comment & Log -->
        <div class="comment">
          <a-divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="0"
                     plain>Logs
          </a-divider>
          <!-- <DataTable v-if="tableConfig" v-bind="tableConfig">
            <template #table="{
              tableConfig,
              tableData,
              data,
              columns,
              selectionActions,
              reload,
            }">
              <a-timeline v-if="data.length > 0" class="!m-5">
                <a-timeline-item v-for="item in data" color="green" class="!mb-5">
                  <div class="flex items-center">
                    <img class="w-6 h-6 rounded-full" :src="item.causer?.profile_photo_url" />
                    <div class="mx-2 by">{{ item.causer?.full_name }}</div>
                    <a-tag>{{ item.event }}</a-tag>
                    <div class="ml-auto text-xs text-gray-500">
                      at {{ $format.formatDateTime(item.created_at) }}
                    </div>
                  </div>
                  <div class="text-xs">{{ formatDescription(item.description) }}</div>
                </a-timeline-item>
              </a-timeline>
            </template>
          </DataTable> -->
          <a-form>
            <a-divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="0"
                       plain>Comments
            </a-divider>
            <div class="">
              <div class="mt-4">
                <div class="flex gap-2">
                  <a-avatar-group>
                    <a-avatar style="background-color: blue">J</a-avatar>
                  </a-avatar-group>
                  <div class="w-full">
                    <h5 class="mb-2 font-semibold">Jeremy Tomlinson
                      <small class="float-right">5 hours ago</small>
                    </h5>
                    <p>Nice work, makes me think of The Money Pit.</p>
                    <a href="javascript: void(0);" class="flex items-center mt-2 text-xs font-light !text-stone-500">
                      <BaseIcon :path="mdiReply" class="inline-block !text-current"/>
                      Reply
                    </a>
                    <!-- chat-end -->

                    <div class="mt-4">
                      <div class="flex gap-2">
                        <a-avatar-group>
                          <a-avatar style="background-color: yellow; color:#333">T</a-avatar>
                        </a-avatar-group>
                        <div class="w-full">
                          <h5 class="mb-2 font-semibold">Thelma Fridley
                            <small class="float-right">3 hours ago</small>
                          </h5>
                          <p>i'm in the middle of a timelapse animation myself! (Very different though.) Awesome stuff.
                          </p>
                          <a href="javascript: void(0);"
                             class="flex items-center mt-2 text-xs font-light !text-stone-500">
                            <BaseIcon :path="mdiReply" class="inline-block !text-current"/>
                            Reply
                          </a>
                        </div>
                      </div>
                    </div> <!-- chat-end -->
                  </div>

                </div>
              </div>
              <div class="mt-4">
                <div class="flex gap-2">
                  <a-avatar-group>
                    <a-avatar style="background-color: orange">K</a-avatar>
                  </a-avatar-group>
                  <div class="w-full">
                    <h5 class="mb-2 font-semibold">Kevin Martinez
                      <small class="float-right">1 day ago</small>
                    </h5>
                    <p>It would be very nice to have.</p>
                    <a href="javascript: void(0);" class="flex items-center mt-2 text-xs font-light !text-stone-500">
                      <BaseIcon :path="mdiReply" class="inline-block !text-current"/>
                      Reply
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="border mt-7">
              <textarea rows="3" class="w-full border-0 focus:outline-0 focus:border-0 focus:ring-0"
                        placeholder="Your comment..."></textarea>
              <div class="flex items-center justify-between px-3 py-2 bg-gray-50">
                <div>
                  <a href="#" class="inline-block px-1 hover:bg-light hover:text-slate-900">
                    <BaseIcon :path="mdiUploadOutline"/>
                  </a>
                  <a href="#" class="inline-block px-1 hover:bg-light hover:text-slate-900">
                    <BaseIcon :path="mdiLink"/>
                  </a>
                </div>
                <a-button @click="submitComment(taskDetail.id)" type="primary" class="uppercase">Send</a-button>
              </div>
            </div> <!-- textarea-end -->

          </a-form>
        </div>
        <template #footer>
          <a-button key="submit" type="primary" @click="() => {
            openModalDetail = false;
          }">Ok
          </a-button>
        </template>
      </a-modal>
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
}
</style>
<style scoped>
.dragArea {
  min-height: 100px;
  width: 100%;
  outline: 1px dashed;
}
</style>
