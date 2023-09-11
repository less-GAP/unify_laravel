<script setup>
import router from "@/router";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import dayjs from "dayjs";
import { h, watch } from "vue";
import { useAuthStore } from "@/stores/auth";
import { DataTablePatient } from "@/components";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import { UseDataTable } from "@/utils/UseDataTable";
import { DeleteOutlined } from "@ant-design/icons-vue";
import {
  mdiGenderMale,
  mdiGenderFemale,
  mdiCalendarCheckOutline,
  mdiHistory,
  mdiCheckOutline,
  mdiPencil,
  mdiAlertCircle,
} from "@mdi/js";
import { BaseIcon } from "@/components";
import { getStatusPatient } from "@/utils/Patient";

const prefix = "patient";
const { fetchListApi } = UseEloquentRouter(prefix);
const auth = useAuthStore();
var item = {};
const itemActions = [
  {
    label: "Edit",
    key: "edit",
    show: (item) => {
      return true;
    },
    action: (item) => {
      router.replace(prefix + "/" + item.id + "/edit");
    },
  },
  {
    label: "Approve Patient",
    key: "editProcess",
    show: (item) => {
      return true;
    },
    action: (item) => {
      router.replace(prefix + "/" + item.id + "/process");
    },
  },
  {
    label: "Add Task",
    key: "addTask",
    show: (item) => {
      return true;
    },
    action: (item) => {
      router.replace(prefix + "/" + item.id + "/task");
    },
  },
  {
    label: "History",
    key: "history",
    show: (item) => {
      return true;
    },
    action: (item) => {
      router.replace(prefix + "/" + item.id + "/history");
    },
  },
];
const listActions = [
  {
    label: "Add",
    action: (_) => {
      //showEditUser({}, reload)
      router.replace(prefix + "/new");
    },
  },
];
const columns = [
  {
    title: "",
    key: "status",
    width: 30,
  },
  {
    title: "Number",
    key: "unify_number",
    width: 70,
  },
  {
    title: "Patient",
    key: "full_name",
    width: "",
  },
  {
    title: "STATUS",
    key: "unify_status",
    width: 100,
  },
  {
    title: "Task Status",
    key: "unify_task_status",
    width: 125,
  },
  {
    title: "DOB",
    key: "dob",
    width: 100,
  },
  {
    title: "INFO",
    key: "info",
    width: 400,
  },
  {
    title: "CREATED AT",
    key: "created_at",
    width: 130,
  },
  {
    title: "Assigned",
    key: "assigned",
    width: 40,
  },
];

const customFormat = "MM-DD-YYYY";
const dbFormat = "YYYY-MM-DD"; // format of datepicker
const dob_value = (item) => {
  return item.dob ? dayjs(item.dob, dbFormat).format(customFormat) : "-";
};

const age = (item) => {
  return item.dob
    ? "(" + dayjs().diff(dayjs(item.dob, dbFormat), "year") + " years old)"
    : "-";
};

const tableConfig = UseDataTable(fetchListApi, {
  columns,
  showSelection: true,
  showSort: [
    {
      label: "Latest",
      value: "-id",
    },
    {
      label: "Oldest",
      value: "id",
    },
    {
      label: "Name Alphabet",
      value: "name",
    },
    {
      label: "Last Updated",
      value: "-updated_at",
    },
  ],
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
      <DataTablePatient v-bind="tableConfig" @register="registerTable">
        <template #header>
          <h2>Patient List</h2>
        </template>
        <template #cellAction[edit]="{ item, actionMethod }">
          <a-tooltip title="Edit" class="mr-1">
            <a-button class="justify-center !flex !p-1 !h-auto" :disabled="auth.user.roles.find((x) => x.name === 'Admin') !== undefined ||
                (auth.user.roles.find((x) => x.name === 'Seller') !==
                  undefined &&
                  item.unify_process == 0)
                ? false
                : true
              " :class="auth.user.roles.find((x) => x.name === 'Admin') !== undefined ||
      (auth.user.roles.find((x) => x.name === 'Seller') !==
        undefined &&
        item.unify_process == 0)
      ? ''
      : '!bg-gray-300 opacity-50'
    " @click="actionMethod">
              <BaseIcon :path="mdiPencil" class="w-4 !fill-blue-200" />
            </a-button>
          </a-tooltip>
        </template>
        <template #cellAction[editProcess]="{ item, actionMethod }">
          <a-tooltip title="Approve Patient" class="mr-1">
            <a-button class="justify-center !flex !p-1 !h-auto" :disabled="auth.user.roles.find((x) => x.name === 'Admin') !== undefined &&
                item.unify_process < 2
                ? false
                : true
              " :class="auth.user.roles.find((x) => x.name === 'Admin') !== undefined &&
      item.unify_process < 2
      ? ''
      : '!bg-gray-300 opacity-50'
    " @click="actionMethod">
              <BaseIcon :path="mdiCheckOutline" class="w-4" />
            </a-button>
          </a-tooltip>
        </template>
        <template #cellAction[addTask]="{ item, actionMethod }">
          <a-tooltip title="Add Task" class="mr-1">
            <a-button class="justify-center !flex !p-1 !h-auto" :disabled="auth.user.roles.find((x) => x.name === 'Admin') !== undefined
                ? false
                : true
              " :class="auth.user.roles.find((x) => x.name === 'Admin') !== undefined
      ? ''
      : '!bg-gray-300 opacity-50'
    " @click="actionMethod">
              <BaseIcon :path="mdiCalendarCheckOutline" class="w-4" />
            </a-button>
          </a-tooltip>
        </template>
        <template #cellAction[history]="{ item, actionMethod }">
          <a-tooltip title="View History">
            <a-button class="justify-center !flex !p-1 !h-auto" :disabled="auth.user.roles.find((x) => x.name === 'Admin') !== undefined
                ? false
                : true
              " :class="auth.user.roles.find((x) => x.name === 'Admin') !== undefined
      ? ''
      : '!bg-gray-300 opacity-50'
    " @click="actionMethod">
              <BaseIcon :path="mdiHistory" class="w-4" />
            </a-button>
          </a-tooltip>
        </template>
        <template #cellAction[delete]="{ item, actionMethod }">
          <a-popconfirm title="Do you want to delete?" ok-text="Yes" cancel-text="No" @confirm="actionMethod">
            <a-button type="text" danger :icon="h(DeleteOutlined)" label="" :outline="true"></a-button>
          </a-popconfirm>
        </template>
        <template #cell[id]="{ item, column }">
          <div>{{ item.id }}</div>
        </template>
        <template #cell[status]="{ item, column }">
          <div class="flex justify-center">
            <BaseIcon v-if="item.need_improve === 0 && item.unify_status !== 1" :path="mdiAlertCircle"
              class="flex-none text-red-600 !mr-0" />
          </div>
        </template>
        <template #cell[full_name]="{ item, column }">
          <div class="flex flex-row items-center">
            <BaseIcon v-if="item.gender === 0" :path="mdiGenderMale" class="flex-none !text-blue-600" />
            <BaseIcon v-if="item.gender === 1" :path="mdiGenderFemale" class="flex-none text-pink-600" />

            <span class="pl-1">
              <a :href="'#/patient/' + item.id + '/detail'" class="text-blue-700 underline">{{ item.full_name }}</a>
            </span>
          </div>
        </template>
        <template #cell[image]="{ item, column }">
          <a-image height="50px" class="w-20 h-auto" :src="item.image_url" :alt="item.name" />
        </template>
        <template #cell[info]="{ item, column }">
          <div class="flex">
            <div><strong>Tel:</strong> {{ item.phone }}</div>
          </div>
          <div class="block max-w-[380px] truncate">
            {{ item.street }}, {{ item.city }}, {{ item.state }}, {{ item.zip }}
          </div>
        </template>
        <!-- Update 3/9 23:34 : Merge showing process into status -->
        <!-- <template #cell[unify_process]="{ item, column }">
          <a-tag v-if="item.unify_process === 0 && item.unify_status < 2 || item.unify_process=== null && item.unify_status < 2" color="gray">Waiting</a-tag>
          <a-tag v-else-if="item.unify_process === 1 && item.unify_status < 2" color="orange">Eligibility Check</a-tag>
          <a-tag v-else-if="item.unify_process === 2 && item.unify_status < 2" color="blue">Running</a-tag>
          <a-tag v-else-if="item.unify_status > 1" color="gray">Old patient</a-tag>
        </template> -->
        <template #cell[dob]="{ item, column }">
          <small>{{ dob_value(item) }}</small>
          <br /><span class="text-[11px] text-gray-400">{{ age(item) }}</span>
        </template>
        <template #cell[assigned]="{ item, column }">
          <!-- <a-avatar-group>
            <a-avatar src="https://xsgames.co/randomusers/avatar.php?g=pixel&key=1"/>
            <a href="#">
              <a-avatar style="background-color: #f56a00">K</a-avatar>
            </a>
            <a-tooltip title="Ant User" placement="top">
              <a-avatar style="background-color: #87d068">
                <template #icon>
                  <UserOutlined/>
                </template>
              </a-avatar>
            </a-tooltip>
            <a-avatar style="background-color: #1890ff">
              <template #icon>
                <AntDesignOutlined/>
              </template>
            </a-avatar>
          </a-avatar-group> -->
        </template>
        <template #cell[unify_status]="{ item, column }">
          <a-tag v-if="item.unify_process === 0" color="yellow">Waiting</a-tag>
          <a-tag v-else-if="item.unify_process === 1" color="orange">Eligibility Check</a-tag>
          <a-tag v-else-if="item.unify_status === 1" color="green">
            <div class="pt-1 leading-none">Active</div>
            <div class="pb-1 leading-none">
              <small>({{
                dayjs(item.unify_active, "YYYY-MM-DD HH:mm:ss").format(
                  "HH:mm MM-DD-YYYY"
                )
              }})</small>
            </div>
          </a-tag>
          <a-tag v-else-if="item.unify_status === 2" color="gray">Inactive</a-tag>
          <a-tag v-else-if="item.unify_status === 3" color="gray">Decease</a-tag>
        </template>
      </DataTablePatient>
      <router-view></router-view>
    </SectionMain>
  </LayoutAuthenticated>
</template>
