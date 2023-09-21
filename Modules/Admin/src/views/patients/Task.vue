<script setup>
import { reactive, ref } from "vue";
import { notification } from "ant-design-vue";
import {
  mdiBackspace,
  mdiCheckCircleOutline,
  mdiDeleteOffOutline,
  mdiPauseCircleOutline,
  mdiPlayCircleOutline,
  mdiPencil,
  mdiCalendarClockOutline,
  mdiUploadOutline,
  mdiLink,
  mdiTrashCan,
  mdiAccountCheckOutline,
  mdiCheckboxMarkedCircle,
  mdiReply
} from "@mdi/js";
import { BaseIcon } from "@/components";
import router from "@/router";
import { useAuthStore } from "@/stores/auth";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import { useNeedToDoList } from "@/utils/Patient";
import { PlusOutlined, CheckCircleOutlined, SyncOutlined, ClockCircleOutlined } from "@ant-design/icons-vue";
import dayjs from "dayjs";
import relativeTime from 'dayjs/plugin/relativeTime'
dayjs.extend(relativeTime)
import Api from "@/utils/Api";
import {
  deleteTask,
  completeTask,
  workingTask,
  reviewTask,
  getStatusTask
} from "@/utils/Task";

const prefix = "patient";
const { fetchDetailApi, updateApi } = UseEloquentRouter(prefix);

const fetchTaskByPatientApi = function (patient_id) {
  return Api.get("task/list", {
    params: {
      patient_id: patient_id,
      deleted: 0,
    },
  });
};

defineProps({
  value: {
    type: Object,
    default: {},
  },
  visible: {
    type: Boolean,
    default: true,
  },
});
const formState = reactive({
  tasks: [],
});
const taskHistory = ref([]);
const formRef = ref();
const loading = ref(false);
const auth = useAuthStore();
const needToDoLib = useNeedToDoList();
var needToDoList = [];
const fetch = async function () {
  loading.value = true;
  needToDoList = [];
  var id = router.currentRoute.value.params.id;
  const response = await fetchDetailApi(id);
  const responseTask = await fetchTaskByPatientApi(id);
  taskHistory.value = responseTask.data;
  if (taskHistory.value.length > 0) {
    taskHistory.value.forEach((item, index) => {
      if (item.task_process != 3 || item.deleted != 1) {
        taskHistory.value.splice(index, 1);
      }
      item.assignees = JSON.parse(item.assignees);
    });
  }
  console.log(taskHistory.value);
  if (response.data.status === 200) {
    const data = response.data.data;
    Object.assign(formState, data, true);
    needToDoLib.forEach((item) => {
      if (formState[item.key] != null && formState[item.key] !== 0) {
        return;
      }
      if (item.key == "doctor_id" && formState[item.key] !== null) {
        return;
      }
      needToDoList.push({
        value: item.key,
        label: item.noti,
      });
    });
  } else {
    notification.error({
      message: "Error",
      description: response.data.message,
    });
    router.replace({ path: "/" + prefix });
  }
  loading.value = false;
};
fetch();
const submit = () => {
  formRef.value.validate().then(() => {
    updateApi(formState.id, { ...formState }).then((rs) => {
      Object.assign(formState, rs.data.result);
    });
  });
};
const closeDetail = function () {
  router.replace({ path: "/" + prefix });
};

// get list user
const fetchListUserApi = function () {
  return Api.get("user/all");
};
var listUserAssignees = [];
const fetchListUser = async function () {
  const value = await fetchListUserApi();
  listUserAssignees = value.data.map((item) => {
    item.label = item.full_name;
    item.value = item.id;
    return item;
  });
};
fetchListUser();

const nameAssignee = (id_user, isFull) => {
  const user = listUserAssignees.find((item) => {
    return item.id == id_user;
  });

  if (user && user.full_name) {
    if (isFull) {
      return user.full_name;
    } else {
      const fullNameParts = user.full_name.split(" ");
      // if (fullNameParts.length >= 2) {
      const lastWord = fullNameParts[fullNameParts.length - 1];
      if (lastWord.length > 0) {
        return lastWord.charAt(0);
      }
      // }
    }
  }
  return "N/A";
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
  var listChecked = document.querySelectorAll(".listNeedToDo input:checked");
  var txt = "";
  listChecked.forEach((item) => {
    txt += "- " + item.value + "\n";
  });
  formTaskState.description = txt;
  formTaskState.assignees = [{
    value: formState.sale_user,
  }];
  formTaskState.name = "Check & Update for " + formState.full_name;
  openModal.value = true;
};

const detailTask = function (task) {
  Object.assign(taskDetail, task);
  // taskDetail.assignees = JSON.parse(taskDetail.assignees);
  // console.log(taskDetail.assignees);
  openModalDetail.value = true;
}

// on click OK
const handleAddTask = () => {
  formTaskRef.value.validate().then(() => {
    formTaskState.created_by = auth.user.id;
    formTaskState.patient_id = formState.id;
    formTaskState.assignees = JSON.stringify(formTaskState.assignees);
    try {
      confirmLoading.value = true;
      createTaskApi({ ...formTaskState }).then((rs) => {
        confirmLoading.value = false;
        openModal.value = false;
        fetch();
      });
    } catch (e) {
      console.log(e);
    }
  });
};

const editTask = async function (id) {
  const task = await Api.get("task/" + id);
  task.data.assignees = JSON.parse(task.data.assignees);
  Object.assign(formTaskState, task.data);
  openModal.value = true;
};

const checkOutDate = function (task) {
  if (!task) return false;
  if (task.deadline_at) {
    return dayjs(task.deadline_at).isBefore(dayjs());
  }
}

const formatDescription = function (description) {
  if (description !== null && typeof description === "string") {
    return description.replace(/\n/g, "<br>");
  } else {
    return "";
  }
};

const assigneesArray = function (assignees) {
  if (assignees !== null && typeof assignees === "string") {
    return JSON.parse(assignees);
  }
  return [];
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

const dbFormat = "YYYY-MM-DD"; // format of datepicker
const age = (dob) => {
  return dob
    ? "(" + dayjs().diff(dayjs(dob, dbFormat), "year") + " years old)"
    : "-";
};
</script>
<template>
  <a-drawer :closable="false" style="
      position: relative;
      display: flex;
      flex-direction: column;
      height: 100vh;
    " :open="visible" width="70vw" @close="closeDetail">
    <a-form v-bind="$config.formConfig" ref="formRef" layout="vertical" :model="formState" @finish="submit">
      <div class="p-3 bg-gray-200">
        <a-button class="!hidden md:!inline-block" type="link" @click="closeDetail">
          <template #icon>
            <div class="flex">
              <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
              <span class="ml-1 text-stone-500">Back</span>
            </div>
          </template>
        </a-button>
        <a-button class="!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0" type="link"
          @click="closeDetail">
          <template #icon>
            <div class="flex">
              <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
              <span class="ml-1 text-stone-500">Back</span>
            </div>
          </template>
        </a-button>
        <a-space class="float-right">
          <!-- <a-button @click="submit()" :loading="loading" type="primary">Update</a-button> -->
        </a-space>
      </div>
      <div class="px-4 mt-5 overflow-y-auto" style="height: calc(100% - 60px)">
        <div class="flex flex-wrap -mx-4">
          <h1 class="flex flex-col w-full px-4">
            <div class="flex items-center leading-none whitespace-nowrap">
              <span>{{ formState.full_name }}</span>
            </div>
            <div class="text-sm text-gray-400">
              #{{ formState.unify_number }} - <span class="mb-2 text-xs text-stone-400">Seller: {{
                nameAssignee(formState.sale_user, true) }}</span>
            </div>
          </h1>
          <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem" plain>List
            tasks of {{ formState.full_name }}
          </a-Divider>
          <div class="w-full px-4 bg-gray-100">
            <div class="flex items-center font-medium">
              <div class="flex flex-wrap items-center flex-grow text-gray-900">
                <!-- Check Need to do - Start -->
                <div v-if="needToDoList.length > 0" class="w-full p-8 mt-5 bg-yellow-100 border-red-400 rounded-xl">
                  <div class="flex items-center mb-6">
                    <svg class="w-8 h-8 text-indigo-500 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h4 class="ml-3 text-lg font-semibold">Need to do</h4>
                  </div>
                  <div class="listNeedToDo">
                    <div v-for="ntd in needToDoList" :key="ntd.value">
                      <input :id="ntd.value" class="hidden" type="checkbox" :value="ntd.label" />
                      <label class="flex items-center h-10 px-2 rounded-lg cursor-pointer hover:bg-white"
                        :for="ntd.value">
                        <span
                          class="flex items-center justify-center w-5 h-5 text-transparent border-2 border-gray-900 rounded-full">
                          <svg class="hidden w-4 h-4 fill-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                              clip-rule="evenodd" />
                          </svg>
                        </span>
                        <span class="ml-4 text-sm">{{ ntd.label }}</span>
                      </label>
                    </div>
                  </div>
                </div>
                <!-- Check Need to do - End  -->
                <!-- Task list -->
                <div class="w-full pb-4">
                  <a-button v-if="$auth.hasPermission('task.assign')" class="mt-4" type="primary" @click="addTask">
                    <PlusOutlined></PlusOutlined>
                    Add Task
                  </a-button>
                  <div v-if="formState.tasks.length > 0" class="">
                    <div v-for="task in formState.tasks" :key="task.id" class="">
                      <template v-if="task.deleted != 1 && task.task_process != 3">
                        <div
                          class="relative flex flex-col items-start p-4 mt-3 bg-white rounded-lg cursor-pointer bg-opacity-90 group hover:bg-opacity-100"
                          draggable="true">
                          <div class="absolute top-0 right-0 flex items-center mt-3 mr-2 space-x-1 action">
                            <a-tooltip title="Complete">
                              <a-button v-if="(auth.hasPermission('task.assign') ||
                                task.assignees && assigneesArray(task.assignees).includes(auth.user.id) ||
                                auth.hasPermission('task.review')) &&
                                task.is_completed === 0
                                " type="link" class="!px-0" @click="completeTask(task.id, fetch)">
                                <BaseIcon :path="mdiCheckCircleOutline" class="text-current hover:text-green-500">
                                </BaseIcon>
                              </a-button>
                            </a-tooltip>
                            <a-tooltip :title="task.task_process === 0 ? 'Working' : 'Pending'">
                              <a-button v-if="(auth.hasPermission('task.working') ||
                                task.assignees && assigneesArray(task.assignees).includes(auth.user.id) ||
                                auth.hasPermission('task.review')) &&
                                task.is_completed === 0
                                " type="link" class="!px-0"
                                @click="workingTask(task.id, fetch, task.task_process === 1)">
                                <BaseIcon :path="task.task_process === 0 ? mdiPauseCircleOutline : mdiPlayCircleOutline"
                                  class="text-current hover:text-blue-500"></BaseIcon>
                              </a-button>
                            </a-tooltip>
                            <a-tooltip title="Review">
                              <a-popconfirm placement="leftBottom" title="Review this task" ok-text="Accept Done"
                                cancel-text="Reject" @confirm="reviewTask(task.id, true, fetch)"
                                @cancel="reviewTask(task.id, false, fetch)">
                                <a-button v-if="auth.hasPermission('task.review') &&
                                  task.is_completed == 1
                                  " type="link" class="!px-0">
                                  <BaseIcon :path="mdiAccountCheckOutline" class="text-current hover:text-green-500">
                                  </BaseIcon>
                                </a-button>
                              </a-popconfirm>
                            </a-tooltip>
                            <a-tooltip title="Edit">
                              <a-button v-if="auth.hasPermission('task.edit') &&
                                task.is_completed !== 1
                                " type="link" class="!px-0" @click="editTask(task.id)">
                                <BaseIcon :path="mdiPencil" class="text-current hover:text-blue-500"></BaseIcon>
                              </a-button>
                            </a-tooltip>
                            <a-tooltip title="Delete">
                              <a-popconfirm title="Are you sure delete this task?" ok-text="Yes" cancel-text="No">
                                <a-button v-if="auth.hasPermission('task.delete') &&
                                  task.is_completed !== 1
                                  " type="link" danger class="!px-0" @click="deleteTask(task.id, fetch)">
                                  <BaseIcon :path="mdiDeleteOffOutline" class="text-current hover:text-orange-500">
                                  </BaseIcon>
                                </a-button>
                              </a-popconfirm>
                            </a-tooltip>
                          </div>
                          <h4 :style="checkOutDate(task) ? 'color: red;' : ''" class="text-sm font-medium">
                            <a-tag v-if="getStatusTask(task.task_process)"
                              :color="getStatusTask(task.task_process).color" class="!mr-2">
                              <template #icon>
                                <CheckCircleOutlined v-if="task.task_process === 3" />
                                <SyncOutlined v-if="task.task_process === 1" :spin="true" />
                                <ClockCircleOutlined v-if="[null, 0].includes(task.task_process)" />
                              </template>
                              {{ getStatusTask(task.task_process).label }}
                            </a-tag>
                            <a @click="detailTask(task)">{{ task.name }}<span v-if="checkOutDate(task)" color="red"
                                class="text-xs text-red-900"> (Out date)</span>
                            </a>
                          </h4>
                          <div class="text-xs text-gray-400" v-html="formatDescription(task.description)"></div>
                          <div class="flex items-center w-full mt-3 text-xs font-medium text-gray-400">
                            <div class="flex items-center">
                              <svg class="w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                  d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                  clip-rule="evenodd"></path>
                              </svg>
                              <span class="ml-1 leading-none" v-html="task.deadline_at
                                ? dayjs(task.deadline_at).format(
                                  'HH:mm MM-DD-YYYY'
                                )
                                : ''"></span>
                            </div>
                            <div v-if="task.assignees !== undefined" class="flex ml-auto">
                              <div v-for="id_assignee in assigneesArray(
                                task.assignees
                              )" :key="id_assignee" class="item-assignee">
                                <a-avatar-group>
                                  <a-tooltip :title="nameAssignee(id_assignee, true)" placement="top">
                                    <a-avatar style="background-color: #87d068">{{ nameAssignee(id_assignee) }}</a-avatar>
                                  </a-tooltip>
                                </a-avatar-group>
                              </div>
                            </div>
                          </div>
                        </div>
                      </template>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- History -->
          <div v-if="formState.tasks.length > 0" class="w-full px-4 pb-3 bg-gray-100">
            <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="0" plain>Recent
              history
            </a-Divider>
            <div v-for="task in formState.tasks" :key="task.id" class="">
              <template v-if="task.deleted != 1 && task.task_process == 3">
                <div
                  class="relative flex flex-col items-start p-4 mt-3 bg-white rounded-lg cursor-pointer bg-opacity-90 group hover:bg-opacity-100"
                  draggable="true">
                  <div class="absolute top-0 right-0 flex items-center mt-3 mr-2 space-x-1 action">
                    <a-tag v-if="task.task_process === 3 && task.deleted != 1" color="green" class="!flex items-center">
                      <BaseIcon :path="mdiCheckboxMarkedCircle" class="!text-green-500 !mr-1"></BaseIcon> <span>Done</span>
                    </a-tag>
                    <a-tag v-if="task.deleted != 0" color="red" class="!flex items-center">
                      <BaseIcon :path="mdiTrashCan" class="!text-red-500 !mr-1"></BaseIcon> <span>Trashed</span>
                    </a-tag>
                  </div>
                  <h4 :style="checkOutDate(task) ? 'color: red;' : ''" class="mt-3 text-sm font-medium">
                    <a @click="detailTask(task)">{{ task.name }}</a>
                  </h4>
                  <div class="text-xs text-gray-400" v-html="formatDescription(task.description)"></div>
                  <div class="flex items-center w-full mt-3 text-xs font-medium text-gray-400">
                    <div class="flex items-center">
                      <svg class="w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                          d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                          clip-rule="evenodd"></path>
                      </svg>
                      <span class="ml-1 leading-none" v-html="task.deadline_at
                        ? dayjs(task.deadline_at).format(
                          'HH:mm MM-DD-YYYY'
                        )
                        : ''"></span>
                    </div>
                    <div v-if="task.assignees !== undefined" class="flex ml-auto">
                      <div v-for="id_assignee in assigneesArray(
                        task.assignees
                      )" :key="id_assignee" class="item-assignee">
                        <a-avatar-group>
                          <a-tooltip :title="nameAssignee(id_assignee, true)" placement="top">
                            <a-avatar style="background-color: #87d068">{{ nameAssignee(id_assignee) }}</a-avatar>
                          </a-tooltip>
                        </a-avatar-group>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
    </a-form>

    <!-- Modal Add Task -->
    <a-modal v-model:open="openModal" append-to-body title="Add Task" :confirm-loading="confirmLoading"
      @ok="handleAddTask">
      <a-form v-bind="$config.formConfig" ref="formTaskRef" layout="vertical" :model="formTaskState">
        <a-form-item label="Task name" required>
          <a-input v-model:value="formTaskState.name"></a-input>
        </a-form-item>
        <div class="flex flex-wrap -mx-2">
          <div class="w-full px-2">
            <a-form-item label="Due date">
              <a-date-picker v-model:value="formTaskState.deadline_at" class="w-full" :show-time="{ format: 'HH:mm' }"
                input-read-only value-format="YYYY-MM-DD HH:mm:ss" format="HH:mm MM-DD-YYYY"></a-date-picker>
            </a-form-item>
          </div>
        </div>
        <a-form-item label="Assignees" required>
          <a-select v-model:value="formTaskState.assignees" :options="listUserAssignees" mode="multiple">
          </a-select>
        </a-form-item>
        <a-form-item label="Description">
          <a-textarea v-model:value="formTaskState.description" :rows="4"></a-textarea>
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

                <div v-for="id_assignee in assigneesArray(
                  taskDetail.assignees
                )" :key="id_assignee" class="flex items-center item-assignee">
                  <a-avatar-group>
                    <a-avatar style="background-color: #87d068">{{ nameAssignee(id_assignee) }}</a-avatar>
                  </a-avatar-group>
                  <div class="ml-1">
                    <h5 class="font-medium">{{ nameAssignee(id_assignee, true) }}</h5>
                  </div>
                </div>
              </div>
            </div> <!-- col-end -->

            <div class="col-span-1">
              <p class="text-gray-400">Patient</p>
              <div class="flex items-center gap-3 mt-1">
                <div class="">
                  <h5 class="font-medium">
                    <a @click="router.replace('/patient/' + formState.id + '/edit')">[#{{ formState.unify_number }}] {{
                      formState.full_name }}</a>
                  </h5>
                  <p class="text-xs text-stone-400">{{ age(formState.dob) }}</p>
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
        <a-form>
          <a-divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="0" plain>Comments &
            Logs</a-divider>
          <div class="mt-4"><!--Log created-->
            <div class="flex items-center gap-2">
              <a-avatar-group>
                <a-avatar style="background-color: orange">{{ nameAssignee(taskDetail.created_by) }}</a-avatar>
              </a-avatar-group>
              <div class="w-full">
                <h5 class="font-semibold">{{ nameAssignee(taskDetail.created_by, true) }}
                  <small class="font-normal">created this task.</small>
                  <small class="float-right font-normal">{{ dayjs(taskDetail.created_at).fromNow() }}</small>
                </h5>
              </div>
            </div>
            <div class="mt-4" v-if="taskDetail.is_completed != 0"><!--Log completed-->
              <div class="flex items-center gap-2">
                <a-avatar-group>
                  <a-avatar style="background-color: orange">{{ nameAssignee(taskDetail.created_by) }}</a-avatar>
                </a-avatar-group>
                <div class="w-full">
                  <h5 class="font-semibold">{{ nameAssignee(taskDetail.created_by, true) }}
                    <small class="font-normal">have completed this task.</small>
                    <small class="float-right font-normal">{{ dayjs(taskDetail.completed_at).fromNow() }}</small>
                  </h5>
                </div>
              </div>
            </div>
            <div class="mt-4" v-if="taskDetail.deleted != 0"><!--Log deleted-->
              <div class="flex items-center gap-2">
                <a-avatar-group>
                  <a-avatar style="background-color: orange">{{ nameAssignee(taskDetail.deleted_by) }}</a-avatar>
                </a-avatar-group>
                <div class="w-full">
                  <h5 class="font-semibold">{{ nameAssignee(taskDetail.deleted_by, true) }}
                    <small class="font-normal">have deleted this task.</small>
                    <small class="float-right font-normal">{{ dayjs(taskDetail.deleted_at).fromNow() }}</small>
                  </h5>
                </div>
              </div>
            </div>
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
                        <p>i'm in the middle of a timelapse animation myself! (Very different though.) Awesome stuff.</p>
                        <a href="javascript: void(0);" class="flex items-center mt-2 text-xs font-light !text-stone-500">
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

  </a-drawer>
</template>

<style>
.ant-drawer-body {
  padding: 0 !important;
}

.listNeedToDo input:checked~label svg {
  display: block !important;
}

.table-task {}
</style>
