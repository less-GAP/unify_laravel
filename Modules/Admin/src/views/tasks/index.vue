<script setup>
import router from "@/router";
import { ApiData, DataTable, BaseIcon } from "@/components";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import { watch, ref, reactive } from "vue";
import { CheckCircleOutlined, ClockCircleOutlined, SyncOutlined } from "@ant-design/icons-vue";
import { useAuthStore } from "@/stores/auth";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import { UseDataTable } from "@/utils/UseDataTable";
import TaskItem from "@/components/TaskItem.vue";
import draggable from "vuedraggable";
import Api from "@/utils/Api";
import { mdiCalendarClockOutline, mdiReply, mdiUploadOutline, mdiLink } from "@mdi/js";
import {
  deleteTask,
  completeTask,
  workingTask,
  reviewTask,
  getStatusTask
} from "@/utils/Task";

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
      addTask();
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
  disabled: false,
  ghostClass: "ghost",
  group: "tasks",
  sort: false,
};

// Handle Modal Add Task
const openModal = ref(false);
const openModalDetail = ref(false);
const taskDetail = reactive({});
const confirmLoading = ref(false);
const formTaskState = reactive({});
const formTaskRef = ref();
const createTaskApi = function (data) {
  return Api.post("task", data);
};
// on click Add task
const addTask = function () {
  openModal.value = true;
};

const detailTask = function (task) {
  Object.assign(taskDetail, task);

  const { fetchListApi } = UseEloquentRouter('activity', {
    order: "-id",
  });
  tableConfig.value = UseDataTable(fetchListApi, {
    showSelection: false,
    globalSearch: false,
    pagination: {
      perPage: 10,
    },
    filter: {
      subject_id: taskDetail.id,
      subject_type: "App\\Models\\Task",
    },
  });
  openModalDetail.value = true;
}

const handleAddTask = () => {
  confirmLoading.value = true;
  formTaskRef.value.validate().then(() => {
    formTaskState.created_by = auth.user.id;
    formTaskState.assignees = JSON.stringify(formTaskState.assignees);
    try {
      createTaskApi({ ...formTaskState }).then((rs) => {
        openModal.value = false;
        fetch();
      });
    } catch (e) {
      console.log(e);
    }
  });
  confirmLoading.value = false;
};

const editTask = async function (id) {
  const task = await Api.get("task/" + id);
  Object.assign(formTaskState, task.data);
  openModal.value = true;
};

const formatDescription = function (description) {
  if (description !== null && typeof description === "string") {
    return description.replace(/\n/g, "<br>");
  } else {
    return "";
  }
};

const submitComment = function (id) {
  // const comment = document.getElementById("comment-" + id).value;
  // if (comment !== "") {
  //   const data = {
  //     task_id: id,
  //     comment: comment,
  //     created_by: auth.user.id,
  //   };
  //   Api.post("task/comment", data).then((rs) => {
  //     fetch();
  //   });
  // }
};

const getFilteredData = (data, taskProcess) => {
  return data.filter(item => item.task_process === taskProcess);
};

const updateProcessTask = function (newIndex, oldIndex, element) {
  console.log(newIndex, oldIndex, element);
  // console.log(mutations);
}

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
              <div v-if="data?.length" class="flex pb-4 -mx-2">
                <div v-for="column in taskColumns" :key="column.key" class="w-full px-2 md:w-1/2 lg:w-1/4">
                  <div class="flex flex-col p-4 border border-gray-200 rounded-lg inner">
                    <h2 class="mb-2 text-lg font-semibold uppercase">{{ column.key }} ({{ data.filter(item =>
                      item.task_process == column.task_process).length }})</h2>
                    <div class="flex flex-col h-[calc(100vh-285px)] gap-4 pl-1 pr-4 overflow-x-hidden overflow-y-auto kanban-board">
                      <draggable v-bind="dragOptions" :list="getFilteredData(data, column.task_process)"
                        :item-key="column.key" :move="updateProcessTask">
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

      <!-- Modal Add Task -->
      <a-modal v-model:open="openModal" append-to-body title="Add Task" :confirm-loading="confirmLoading" :footer="null">
        <a-form id="formAddTask" v-bind="$config.formConfig" ref="formTaskRef" layout="vertical" :model="formTaskState" @finish="handleAddTask">
          <a-form-item label="Task name" name="name" :rules="[{ required: true, message: 'Please enter task name!' }]">
            <a-input v-model:value="formTaskState.name"></a-input>
          </a-form-item>
          <div class="flex flex-wrap -mx-2">
            <div class="w-full px-2">
              <a-form-item label="Due date" name="deadline_at">
                <a-date-picker v-model:value="formTaskState.deadline_at" class="w-full" :show-time="{ format: 'HH:mm' }"
                  input-read-only value-format="YYYY-MM-DD HH:mm:ss" format="HH:mm MM-DD-YYYY"></a-date-picker>
              </a-form-item>
            </div>
          </div>
          <a-form-item label="Patient" name="patient_id" :rules="[{ required: true, message: 'Please select patient!' }]">
            <ApiData url="patient/all" method="GET">
              <template #default="{ data }">
                <a-select v-model:value="formTaskState.patient_id" :options="data"
                  :fieldNames="{ label: 'full_name', value: 'id' }" showSearch optionFilterProp="full_name">
                </a-select>
              </template>
            </ApiData>
          </a-form-item>
          <a-form-item label="Assignees" name="assignees" :rules="[{ required: true, message: 'Please select assignees!' }]">
            <ApiData url="user/all" method="GET">
              <template #default="{ data }">
                <a-select v-model:value="formTaskState.assignees" :options="data"
                  :fieldNames="{ label: 'full_name', value: 'id' }" mode="multiple">
                </a-select>
              </template>
            </ApiData>
          </a-form-item>
          <a-form-item label="Description" name="description">
            <a-textarea v-model:value="formTaskState.description" :rows="4"></a-textarea>
          </a-form-item>
          <a-form-item>
            <a-button class="!ml-auto" type="primary" @click="handleAddTask">Add Task</a-button>
          </a-form-item>
        </a-form>
      </a-modal>

      <!-- Modal Detail Task -->
      <a-modal v-model:open="openModalDetail" append-to-body :title="taskDetail.name" width="1000px"
        :confirm-loading="confirmLoading">
        <div class="detail">
          <div class="mb-4">
            <a-tag v-if="getStatusTask(taskDetail.task_process)" :color="getStatusTask(taskDetail.task_process).color">
              <template #icon>
                <CheckCircleOutlined v-if="taskDetail.task_process === 3" />
                <SyncOutlined v-if="taskDetail.task_process === 1" :spin="true" />
                <ClockCircleOutlined v-if="[null, 0].includes(taskDetail.task_process)" />
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
                        taskDetail.patient.unify_number }}] {{
    taskDetail.patient.full_name }}</a>
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
            plain>Logs</a-divider>
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
              plain>Comments</a-divider>
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
                      <BaseIcon :path="mdiReply" class="inline-block !text-current" /> Reply
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
                            <BaseIcon :path="mdiReply" class="inline-block !text-current" /> Reply
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
                      <BaseIcon :path="mdiReply" class="inline-block !text-current" /> Reply
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
                    <BaseIcon :path="mdiUploadOutline" />
                  </a>
                  <a href="#" class="inline-block px-1 hover:bg-light hover:text-slate-900">
                    <BaseIcon :path="mdiLink" />
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
          }">Ok</a-button>
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