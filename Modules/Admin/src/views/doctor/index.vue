<script setup>
  import router from "@/router";
  import SectionMain from "@/components/SectionMain.vue";
  import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
  import dayjs from "dayjs";
  import {h, watch} from "vue";
  import {useAuthStore} from "@/stores/auth";
  import {DataTable} from "@/components";
  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import {UseDataTable} from "@/utils/UseDataTable";
  import {DeleteOutlined, EditOutlined} from "@ant-design/icons-vue";
  import {mdiGenderMale, mdiGenderFemale, mdiPencil} from "@mdi/js";
  import {BaseIcon} from "@/components";

  const prefix = "doctor";
  const {fetchListApi, deleteApi} = UseEloquentRouter(prefix);
  const auth = useAuthStore();
  var item = {};

  const customFormat = "MM-DD-YYYY";
  const dbFormat = "YYYY-MM-DD"; // format of datepicker
  const date_value = (item) => {
    console.log(item);
    return item ? dayjs(item, dbFormat).format(customFormat) : "-";
  };

  const age = (item) => {
    return item.dob
      ? "(" + dayjs().diff(dayjs(item.dob, dbFormat), "year") + " years old)"
      : "-";
  };

  const tableConfig = UseDataTable(fetchListApi, {
    showSelection: true,
    showSort: false,
    showReload: true,
    columns: [
      {
        title: "Gender",
        key: "gender",
        width: 80,
      },
      {
        title: "Doctor",
        key: "full_name",
        width: "",
      },
      {
        title: "Dob",
        key: "dob",
        width: 100,
      },
      {
        title: "Info",
        key: "info",
        width: 400,
      },
      {
        title: "Created at",
        key: "created_at",
        width: 130,
      },
      {
        title: "Publish date",
        key: "unify_active",
        width: 130,
      },
      {
        title: "Status",
        key: "status",
        width: 100,
      },
    ],
    listActions: [],
    itemActions: [
      {
        ifShow: auth.hasPermission('Doctor.update'),
        label: "Edit",
        key: "edit",
        show: () => {
          return true;
        },
        action: (item) => {
          router.push(prefix + "/" + item.id);
        },
      },
      {
        ifShow: auth.hasPermission('Doctor.delete'),
        label: 'Delete',
        key: 'delete',
        class: 'font-medium !text-red-600 dark:text-red-500 hover:underline',
        confirm: true,
        action(item, reload) {
          deleteApi(item.id).then(rs => {
          }).finally(() => {
            reload();
          });
        }
      }
    ],
    addAction: {
      action: (reload) => {
        router.push(prefix + '/0')
      },
      ifShow: auth.hasPermission('Doctor.create')
    },
  });
  let reloadTable = () => {
  };

  watch(router.currentRoute, (currentRoute) => {
    if (currentRoute.path === "/" + prefix) {
      reloadTable();
    }
  });

  function registerTable({reload}) {
    reloadTable = reload;
  }
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <DataTable v-bind="tableConfig" @register="registerTable">
        <template #cellAction[edit]="{item,actionMethod}">
          <a-button
            type="text"
            primary
            @click="actionMethod"
            :icon="h(EditOutlined)"
            label=""
            :outline="true"
          >
          </a-button>
        </template>
        <template #cellAction[delete]="{item,actionMethod}">
          <a-popconfirm
            title="Are you sure delete this doctor?"
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
            >
            </a-button>

          </a-popconfirm>
        </template>
        <template #cell[id]="{ item, column }">
          <div>{{ item.id }}</div>
        </template>
        <template #cell[gender]="{ item, column }">
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
        </template>
        <template #cell[full_name]="{ item, column }">
          <div class="flex flex-row items-center">
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
        <template #cell[unify_active]="{ item, column }">
          <small>{{ item.unify_active ? dayjs(item.unify_active).format('HH:mm MM-DD-YYYY') : '-' }}</small>
        </template>
        <template #cell[dob]="{ item, column }">
          <small>{{ date_value(item.dob) }}</small>
          <br/><span class="text-[11px] text-gray-400">{{ age(item) }}</span>
        </template>
        <template #cell[status]="{ item, column }">
          <a-tag v-if="item.status == '0'" color="green">Active</a-tag>
          <a-tag v-if="item.status == '1'" color="cyan">Inactive</a-tag>
          <a-tag v-if="item.status == '2'" color="red">Trashed</a-tag>
        </template>
      </DataTable>
      <router-view></router-view>
    </SectionMain>
  </LayoutAuthenticated>
</template>
