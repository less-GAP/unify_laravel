<script setup>
import router from "@/router";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import dayjs from "dayjs";
import { h, watch } from "vue";
import { useAuthStore } from "@/stores/auth";
import { DataTable } from "@/components";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import { UseDataTable } from "@/utils/UseDataTable";
import { DeleteOutlined } from "@ant-design/icons-vue";
import { mdiGenderMale, mdiGenderFemale, mdiPencil } from "@mdi/js";
import { BaseIcon } from "@/components";

const prefix = "doctor";
const { fetchListApi } = UseEloquentRouter(prefix);
const auth = useAuthStore();
var item = {};
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
    action: () => {
      router.replace(prefix + "/new");
    },
  },
];
const columns = [
  {
    title: "Doctor",
    key: "full_name",
    width: "",
  },
  {
    title: "STATUS",
    key: "status",
    width: 100,
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
  showSort: false,
  listActions,
  itemActions,
});
let reloadTable = () => {};

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
          <h2>Doctor List</h2>
        </template>
        <template #cellAction[edit]="{ item, actionMethod }">
          <a-tooltip title="Edit" class="mr-1">
            <a-button
              class="justify-center !flex !p-1 !h-auto"
              :disabled="
                auth.user.roles.find((x) => x.name === 'Admin') !== undefined ||
                (auth.user.roles.find((x) => x.name === 'Seller') !==
                  undefined &&
                  item.unify_process == 0)
                  ? false
                  : true
              "
              :class="
                auth.user.roles.find((x) => x.name === 'Admin') !== undefined ||
                (auth.user.roles.find((x) => x.name === 'Seller') !==
                  undefined &&
                  item.unify_process == 0)
                  ? ''
                  : '!bg-gray-300 opacity-50'
              "
              @click="actionMethod"
            >
              <BaseIcon :path="mdiPencil" class="w-4 !fill-blue-200" />
            </a-button>
          </a-tooltip>
        </template>
        <template #cellAction[delete]="{ item, actionMethod }">
          <a-popconfirm
            title="Do you want to delete?"
            ok-text="Yes"
            cancel-text="No"
            @confirm="actionMethod"
          >
            <a-button
              type="text"
              danger
              :icon="h(DeleteOutlined)"
              label=""
              :outline="true"
            ></a-button>
          </a-popconfirm>
        </template>
        <template #cell[id]="{ item, column }">
          <div>{{ item.id }}</div>
        </template>
        <template #cell[full_name]="{ item, column }">
          <div class="flex flex-row items-center">
            <BaseIcon
              v-if="item.gender === 0"
              :path="mdiGenderMale"
              class="flex-none !text-blue-600"
            />
            <BaseIcon
              v-if="item.gender === 1"
              :path="mdiGenderFemale"
              class="flex-none text-pink-600"
            />

            <span class="pl-1">
              {{ item.full_name }}
            </span>
          </div>
        </template>
        <template #cell[info]="{ item, column }">
          <div class="flex">
            <div><strong>Tel:</strong> {{ item.phone }}</div>
          </div>
          <div class="block max-w-[380px] truncate">
            {{ item.address }}, {{ item.city }}, {{ item.state }},
            {{ item.zip }}
          </div>
        </template>
        <template #cell[dob]="{ item, column }">
          <small>{{ dob_value(item) }}</small>
          <br /><span class="text-[11px] text-gray-400">{{ age(item) }}</span>
        </template>
        <template #cell[status]="{ item, column }">
          <a-tag v-if="item.status === null || item.status == 0" color="green"
            >Active</a-tag
          >
          <a-tag v-if="item.status == 1" color="green">Inactive</a-tag>
          <a-tag v-if="item.status == 2" color="green">Trashed</a-tag>
        </template>
      </DataTable>
      <router-view></router-view>
    </SectionMain>
  </LayoutAuthenticated>
</template>
