<script setup>
import {reactive, computed, ref, h, watch} from "vue";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue"
import {DataTablePatient} from "@/components";
import router from "@/router";
import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
import {UseDataTable} from "@/utils/UseDataTable";
import {UnorderedListOutlined, ProfileOutlined, SlackOutlined, EditOutlined, DeleteOutlined} from '@ant-design/icons-vue';
import {fetchListStatusPatientApi} from './meta';
import dayjs from 'dayjs';
import {mdiGenderMale, mdiGenderFemale} from '@mdi/js';
import {BaseIcon} from "@/components";

const prefix = 'patient'
const {
  fetchListApi,
  createApi,
  deleteApi,
  updateApi
} = UseEloquentRouter(prefix)
const isShowModal = ref(false)

const listPatientStatus = fetchListStatusPatientApi();

const itemActions = [
  {
    label: 'Edit',
    key: 'edit',
    action: (item, reload) => {
      //showEditUser({}, reload)
      router.push(prefix + '/' + item.id)
    }
  },
  {
    label: 'Edit Process',
    key: 'editProcess',
    action: (item, reload) => {
      router.push(prefix + '/' + item.id + '/process')
    }
  },
  {
    label: 'Add Task',
    key: 'addTask',
    action: (item, reload) => {
      router.push(prefix + '/' + item.id + '/task')
    }
  }
  // {
  //   label: 'Delete',
  //   key: 'delete',
  //   class: 'font-medium text-red-600 dark:text-red-500 hover:underline',
  //   action(item, reload) {
  //     deleteApi(item.id).then(rs => {
  //     }).finally(() => {
  //       reload();
  //     });
  //   }
  // }
]
const listActions = [
  {
    label: 'Add',
    action: (reload) => {
      //showEditUser({}, reload)
      router.push(prefix + '/new')
    }
  },
]
const columns = [
  {
    title: 'ID',
    key: 'id',
    width: 30
  },
  {
    title: 'Patient',
    key: 'full_name',
    width: 120
  },
  {
    title: 'ACTIVE',
    key: 'unify_status',
    width: 60
  },
  {
    title: 'DOB',
    key: 'dob',
    width: 60
  },
  {
    title: 'INFO',
    key: 'info',
    width: 200
  },
  {
    title: 'DATE ACTIVE',
    key: 'date_active',
    width: 60
  },
  {
    title: 'CREATED AT',
    key: 'created_at',
    width: 60
  },
  {
    title: 'Assigned',
    key: 'assigned',
    width: 40
  },
]
const customFormat = 'MM-DD-YYYY';
const dbFormat = "YYYY-MM-DD"; // format of datepicker

const dob_value = (item) => {
  return item.dob ? dayjs(item.dob, dbFormat).format(customFormat) : '-';
};

const age = (item) => {
  return item.dob ? '(' + dayjs().diff(dayjs(item.dob, dbFormat), 'year') + ' years old)' : '-';
};

const tableConfig = UseDataTable(fetchListApi, {
  columns,
  showSelection: true,
  showSort: [
    {
      label: 'Latest',
      value: "-id"
    },
    {
      label: 'Oldest',
      value: "id"
    },
    {
      label: 'Name Alphabet',
      value: "name"
    },
    {
      label: 'Last Updated',
      value: "-updated_at"
    }
  ],
  listActions,
  itemActions
})
let reloadTable = () => {
}

watch(router.currentRoute, (currentRoute) => {
  console.log(999, currentRoute)
  if (currentRoute.path === '/'+prefix) {
    reloadTable()
  }
});

function registerTable({reload}) {
  reloadTable = reload
}

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <DataTablePatient @register="registerTable" v-bind="tableConfig">
        <template #header>
          <h2>Patient List</h2>
        </template>
        <!-- <template #filter="{tableConfig,filter,reload}">
                    <a-input @keypress="reload" v-model:value="filter.phone" placeholder="Phone"></a-input>
        </template> -->
        <template #cellAction[edit]="{ item, actionMethod }">
          <a-tooltip title="Edit">
            <a-button @click="actionMethod" type="text" :icon="h(EditOutlined)" label="" :outline="true"></a-button>
          </a-tooltip>
        </template>
        <template #cellAction[editProcess]="{ item, actionMethod }">
          <a-tooltip title="Approve Patient">
            <a-button @click="actionMethod" type="text" :icon="h(SlackOutlined)" label="" :outline="true"></a-button>
          </a-tooltip>
        </template>
        <template #cellAction[addTask]="{ item, actionMethod }">
          <a-tooltip title="Add Task">
            <a-button @click="actionMethod" type="text" :icon="h(UnorderedListOutlined)" label="" :outline="true"></a-button>
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
        <template #cell[full_name]="{ item, column }">
          <div class="flex flex-row items-center">
            <BaseIcon
                :path="mdiGenderMale"
                class="flex-none !text-blue-600"
                v-if="item.gender === 0"
                />
                <BaseIcon
                :path="mdiGenderFemale"
                class="flex-none text-pink-600"
                v-if="item.gender === 1"
            />
            <span class="pl-1 font-bold">{{ item.full_name }}</span>
          </div>
        </template>
        <template #cell[image]="{ item, column }">
          <a-image height="50px" class="w-20 h-auto" :src="item.image_url" :alt="item.name"/>
        </template>
        <template #cell[info]="{ item, column }">
          <div class="flex">
            <div><strong>Tel:</strong> {{ item.phone }}</div>
          </div>
          <div class="flex">
            <div>{{ item.street }}, {{ item.city }}, {{ item.state }}, {{ item.zip }}</div>
          </div>
        </template>
        <template #cell[dob]="{ item, column }">
          {{ dob_value(item) }}<br/><span class="text-[11px] text-gray-400">{{ age(item) }}</span>
        </template>
        <template #cell[assigned]="{ item, column }">

          <a-avatar-group>
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
          </a-avatar-group>

        </template>
        <template #cell[unify_status]="{ item, column }">
          <a-tag v-if="item.unify_status === 0" color="orange">Waiting</a-tag>
          <a-tag v-else-if="item.unify_status === 1" color="green">Active</a-tag>
          <a-tag v-else-if="item.unify_status === 2" color="red">Inactive</a-tag>
          <a-tag v-else-if="item.unify_status === 3" color="black">Decease</a-tag>
          <a-tag v-else color="orange">Waiting</a-tag>
        </template>


      </DataTablePatient>
      <router-view></router-view>

    </SectionMain>
  </LayoutAuthenticated>
</template>
