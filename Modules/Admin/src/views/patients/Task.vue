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
  mdiAccountCheckOutline,
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
const formRef = ref();
const loading = ref(false);
const auth = useAuthStore();
const needToDoLib = useNeedToDoList();
var needToDoList = [];
const fetch = async function () {
  loading.value = true;
  needToDoList = [];
  var id = router.currentRoute.value.params.id;
  const patient = await fetchDetailApi(id);
  const tasks = await fetchTaskByPatientApi(id);
  tasks.data.data.forEach((item) => {
    if (item.patient_id === formState.id && item.deleted === 0) {
      // item.assignees = item.assignees;
      if (
        auth.hasPermission("Admin") ||
        item.assignees.includes(auth.user.id)
      ) {
        formState.tasks.push(item);
      }
    }
  });
  Object.assign(formState, patient.data, true);
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
      createTaskApi({ ...formTaskState }).then((rs) => {
        fetch();
      });
      confirmLoading.value = true;
      setTimeout(() => {
        openModal.value = false;
        confirmLoading.value = false;
      }, 2000);
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
  } else {
    return [];
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
              #{{ formState.unify_number }}
            </div>
          </h1>
          <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem" plain>List
            tasks of {{ formState.full_name }}
          </a-Divider>
          <div class="w-full px-4">
            <div class="flex items-center font-medium">
              <div class="flex flex-wrap items-center flex-grow text-gray-900">
                <!-- Check Need to do - Start -->
                <div v-if="needToDoList.length > 0" class="w-full p-8 bg-yellow-100 border-red-400 rounded-xl">
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
                <div class="w-full py-4">
                  <a-button v-if="$auth.hasPermission('task.assign')" class="mt-4" type="primary" @click="addTask">
                    <PlusOutlined></PlusOutlined>
                    Add Task
                  </a-button>
                  <table v-if="formState.tasks.length > 0" class="w-full mt-4 table-auto table-task">
                    <thead class="text-xs font-semibold text-gray-400 uppercase bg-gray-50">
                      <th>Task</th>
                      <th class="text-center">Assignees</th>
                      <th class="text-center">Due date</th>
                      <th class="text-center">Task Progress</th>
                      <th class="text-center">Action</th>
                    </thead>
                    <tbody>
                      <tr v-for="task in formState.tasks" :key="task.id">
                        <template v-if="task.deleted !== 1 && task.task_process != 3">
                          <td>
                            <h4 :style="checkOutDate(task) ? 'color: red;' : ''">
                              <a @click="detailTask(task)">{{ task.name }}<span v-if="checkOutDate(task)" color="red"
                                  class="text-xs text-red-900"> (Out date)</span>
                              </a>
                            </h4>
                            <div class="text-xs text-gray-400" v-html="formatDescription(task.description)"></div>
                          </td>
                          <td style="width: 0; min-width: 127px;">
                            <div class="flex">
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
                          </td>
                          <td width="100px" class="text-xs" v-html="task.deadline_at
                            ? dayjs(task.deadline_at).format(
                              'HH:mm MM-DD-YYYY'
                            ).replace(' ', '<br>')
                            : ''">
                          </td>
                          <td style="width: 0; min-width: 127px;">
                            <a-tag v-if="getStatusTask(task.task_process)"
                              :color="getStatusTask(task.task_process).color">
                              <template #icon>
                                <CheckCircleOutlined v-if="task.task_process === 3" />
                                <SyncOutlined v-if="task.task_process === 1" :spin="true" />
                                <ClockCircleOutlined v-if="[null, 0].includes(task.task_process)" />
                              </template>
                              {{ getStatusTask(task.task_process).label }}
                            </a-tag>
                          </td>
                          <td width="150px">
                            <div class="flex flex-wrap justify-center space-x-1">
                              <a-tooltip title="Complete">
                                <a-button v-if="(auth.hasPermission('task.assign') ||
                                  assigneesArray.includes(auth.user.id) ||
                                  auth.hasPermission('task.review')) &&
                                  task.is_completed === 0
                                  " type="link" class="!px-0" @click="completeTask(task.id, fetch)">
                                  <BaseIcon :path="mdiCheckCircleOutline" class="text-current hover:text-green-500">
                                  </BaseIcon>
                                </a-button>
                              </a-tooltip>
                              <a-tooltip :title="task.task_process === 0 ? 'Working' : 'Pending'">
                                <a-button v-if="(auth.hasPermission('task.working') ||
                                  assigneesArray.includes(auth.user.id) ||
                                  auth.hasPermission('task.review')) &&
                                  task.is_completed === 0
                                  " type="link" class="!px-0"
                                  @click="workingTask(task.id, fetch, task.task_process === 1)">
                                  <BaseIcon :path="task.task_process === 0 ? mdiPauseCircleOutline : mdiPlayCircleOutline"
                                    class="text-current hover:text-blue-500"></BaseIcon>
                                </a-button>
                              </a-tooltip>
                              <a-tooltip title="Review">
                                <a-popconfirm placement="leftBottom" title="Review this task" ok-text="Accept Done" cancel-text="Reject"
                                  @confirm="reviewTask(task.id, true, fetch)" @cancel="reviewTask(task.id, false, fetch)">
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
                          </td>
                        </template>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- History -->
          <div class="w-full px-4 pb-3 mt-5 bg-gray-100">
            <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="0" plain>Recent
              history
            </a-Divider>
            <div
              class="relative flex flex-col items-start p-4 mt-3 bg-white rounded-lg cursor-pointer bg-opacity-90 group hover:bg-opacity-100"
              draggable="true">
              <button
                class="absolute top-0 right-0 flex items-center justify-center hidden w-5 h-5 mt-3 mr-2 text-gray-500 rounded hover:bg-gray-200 hover:text-gray-700 group-hover:flex">
                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                  fill="currentColor">
                  <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                  </path>
                </svg>
              </button>
              <span
                class="flex items-center h-6 px-3 text-xs font-semibold text-green-500 bg-green-100 rounded-full">Dev</span>
              <h4 class="mt-3 text-sm font-medium">
                This is the title of the card for the thing that needs to be
                done.
              </h4>
              <div class="flex items-center w-full mt-3 text-xs font-medium text-gray-400">
                <div class="flex items-center">
                  <svg class="w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                      clip-rule="evenodd"></path>
                  </svg>
                  <span class="ml-1 leading-none">Dec 12</span>
                </div>
                <div class="relative flex items-center ml-4">
                  <svg class="relative w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
                      clip-rule="evenodd"></path>
                  </svg>
                  <span class="ml-1 leading-none">4</span>
                </div>
                <div class="flex items-center ml-4">
                  <svg class="w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                      clip-rule="evenodd"></path>
                  </svg>
                  <span class="ml-1 leading-none">1</span>
                </div>
                <img class="w-6 h-6 ml-auto rounded-full" src="https://randomuser.me/api/portraits/men/64.jpg" />
              </div>
            </div>
            <div
              class="relative flex flex-col items-start p-4 mt-3 bg-white rounded-lg cursor-pointer bg-opacity-90 group hover:bg-opacity-100"
              draggable="true">
              <button
                class="absolute top-0 right-0 flex items-center justify-center hidden w-5 h-5 mt-3 mr-2 text-gray-500 rounded hover:bg-gray-200 hover:text-gray-700 group-hover:flex">
                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                  fill="currentColor">
                  <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                  </path>
                </svg>
              </button>
              <span
                class="flex items-center h-6 px-3 text-xs font-semibold text-green-500 bg-green-100 rounded-full">Dev</span>
              <h4 class="mt-3 text-sm font-medium">
                This is the title of the card for the thing that needs to be
                done.
              </h4>
              <div class="flex items-center w-full mt-3 text-xs font-medium text-gray-400">
                <div class="flex items-center">
                  <svg class="w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                      clip-rule="evenodd"></path>
                  </svg>
                  <span class="ml-1 leading-none">Dec 12</span>
                </div>
                <div class="relative flex items-center ml-4">
                  <svg class="relative w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
                      clip-rule="evenodd"></path>
                  </svg>
                  <span class="ml-1 leading-none">4</span>
                </div>
                <div class="flex items-center ml-4">
                  <svg class="w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                      clip-rule="evenodd"></path>
                  </svg>
                  <span class="ml-1 leading-none">1</span>
                </div>
                <img class="w-6 h-6 ml-auto rounded-full" src="https://randomuser.me/api/portraits/men/64.jpg" />
              </div>
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
                  <h5 class="mb-2">Jeremy Tomlinson
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
                        <h5 class="mb-2">Thelma Fridley
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
                  <h5 class="mb-2">Kevin Martinez
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
