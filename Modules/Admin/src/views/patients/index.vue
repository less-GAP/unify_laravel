<script setup>
import {reactive, computed, ref, h, watch} from "vue";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import { useAuthStore } from "@/stores/auth";
import {DataTablePatient} from "@/components";
import router from "@/router";
import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
import {UseDataTable} from "@/utils/UseDataTable";
import {DeleteOutlined} from '@ant-design/icons-vue';
import {fetchListStatusPatientApi} from '@/utils/Patient';
import dayjs from 'dayjs';
import {mdiGenderMale, mdiGenderFemale, mdiCalendarCheckOutline, mdiHistory, mdiCheckOutline, mdiPencil, mdiAlertCircle} from '@mdi/js';
import {BaseIcon} from "@/components";
import { now } from "moment";

const prefix = 'patient'
const {
  fetchListApi,
  createApi,
  deleteApi,
  updateApi
} = UseEloquentRouter(prefix)
const isShowModal = ref(false)
const auth = useAuthStore();
const listPatientStatus = fetchListStatusPatientApi();

console.log(auth.user.role);

const itemActions = [
  {
    label: 'Edit',
    key: 'edit',
    show: (item) => {
      return true;
    },
    action: (item, reload) => {
      router.push(prefix + '/' + item.id + '/edit')
    }
  },
  {
    label: 'Approve Patient',
    key: 'editProcess',
    show: (item) => {
      // [1,2,3].includes(item.unify_status)
      // if(auth.user.role !== 'admin' || item.unify_status === 2 || item.unify_status === 3 || item.unify_status === 1){
      //   return false;
      // }
      return true;
    },
    action: (item, reload) => {
      router.push(prefix + '/' + item.id + '/process')
    }
  },
  {
    label: 'Add Task',
    key: 'addTask',
    show: (item) => {
      return true;
    },
    action: (item, reload) => {
      router.push(prefix + '/' + item.id + '/task')
    }
  },
  {
    label: 'History',
    key: 'history',
    show: (item) => {
      return true;
    },
    action: (item, reload) => {
      router.push(prefix + '/' + item.id + '/history')
    }
  },
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
    title: '',
    key: 'status',
    width: 30
  },
  {
    title: 'Number',
    key: 'unify_number',
    width: 70
  },
  {
    title: 'Patient',
    key: 'full_name',
    width: ''
  },
  {
    title: 'STATUS',
    key: 'unify_status',
    width: 100
  },
  // {
  //   title: 'PROCESS',
  //   key: 'unify_process',
  //   width: 125
  // },
  {
    title: 'DOB',
    key: 'dob',
    width: 100
  },
  {
    title: 'INFO',
    key: 'info',
    width: 400
  },
  {
    title: 'CREATED AT',
    key: 'created_at',
    width: 130
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

const textNewPatient = (item) => {
  // check new patient or old patient
  // return item.unify_process === 2 && ((now() - item.date_active) > 10) ? 'New Patient' : 'Patient';
  return 'Running';
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
          <a-tooltip title="Edit" class="mr-1">
            <a-button @click="actionMethod" class="justify-center !flex !p-1 !h-auto">
              <BaseIcon :path="mdiPencil" class="w-4 !fill-blue-200"/>
            </a-button>
          </a-tooltip>
        </template>
        <template #cellAction[editProcess]="{ item, actionMethod }">
          <a-tooltip title="Approve Patient" class="mr-1">
            <a-button @click="actionMethod" class="justify-center !flex !p-1 !h-auto" :disabled="((auth.user.role !== 'admin' || [1,2,3].includes(item.unify_status)) ? true : false)" :class="((auth.user.role !== 'admin' || [1,2,3].includes(item.unify_status)) ? '!bg-gray-300 opacity-50' : '')">
              <BaseIcon :path="mdiCheckOutline" class="w-4"/>
            </a-button>
          </a-tooltip>
        </template>
        <template #cellAction[addTask]="{ item, actionMethod }">
          <a-tooltip title="Add Task" class="mr-1">
            <a-button @click="actionMethod" class="justify-center !flex !p-1 !h-auto">
              <BaseIcon :path="mdiCalendarCheckOutline" class="w-4"/>
            </a-button>
          </a-tooltip>
        </template>
        <template #cellAction[history]="{ item, actionMethod }">
          <a-tooltip title="View History">
            <a-button @click="actionMethod" class="justify-center !flex !p-1 !h-auto">
              <BaseIcon :path="mdiHistory" class="w-4"/>
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
                <BaseIcon
                :path="mdiAlertCircle"
                class="flex-none text-red-600 !mr-0"
                v-if="item.need_improve === 0 && item.unify_status !== 1"
            />
          </div>
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

            <span class="pl-1">
              <a :href="'#/patient/' + item.id + '/detail'" class="text-blue-700 underline">{{ item.full_name }}</a>
            </span>
          </div>
        </template>
        <template #cell[image]="{ item, column }">
          <a-image height="50px" class="w-20 h-auto" :src="item.image_url" :alt="item.name"/>
        </template>
        <template #cell[info]="{ item, column }">
          <div class="flex">
            <div><strong>Tel:</strong> {{ item.phone }}</div>
          </div>
          <div class="block max-w-[380px] truncate">{{ item.street }}, {{ item.city }}, {{ item.state }}, {{ item.zip }}</div>
        </template>
        <!-- Update 3/9 23:34 : Merge showing process into status -->
        <!-- <template #cell[unify_process]="{ item, column }">
          <a-tag v-if="item.unify_process === 0 && item.unify_status < 2 || item.unify_process=== null && item.unify_status < 2" color="gray">Waiting</a-tag>
          <a-tag v-else-if="item.unify_process === 1 && item.unify_status < 2" color="orange">Eligibility Check</a-tag>
          <a-tag v-else-if="item.unify_process === 2 && item.unify_status < 2" color="blue">{{ textNewPatient(item) }}</a-tag>
          <a-tag v-else-if="item.unify_status > 1" color="gray">Old patient</a-tag>
        </template> -->
        <template #cell[dob]="{ item, column }">
          <small>{{ dob_value(item) }}</small>
          <br/><span class="text-[11px] text-gray-400">{{ age(item) }}</span>
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
              <small>({{ dayjs(item.unify_active, 'YYYY-MM-DD HH:mm:ss').format('HH:mm MM-DD-YYYY') }})</small>
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
