<script setup>
import { reactive, ref, h, watch } from "vue";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import { DataTable } from "@/components";
import router from "@/router";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import { UseDataTable } from "@/utils/UseDataTable";
import { CloseCircleOutlined, PlusOutlined, LoadingOutlined, EditOutlined, DeleteOutlined } from '@ant-design/icons-vue';
import { fetchListStatusPatientApi } from './meta';
import dayjs from 'dayjs';

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
    label: 'Delete',
    key: 'delete',
    class: 'font-medium text-red-600 dark:text-red-500 hover:underline',
    action(item, reload) {
      deleteApi(item.id).then(rs => {
      }).finally(() => {
        reload();
      });
    }
  }
]
const listActions = [
  {
    label: 'Add',
    action: (reload) => {
      //showEditUser({}, reload)
      router.push(prefix + '/new')
    }
  }
]
const columns = [
  {
    title: 'PATIENT ID',
    key: 'id',
    width: 60
  },
  {
    title: 'ACTIVE',
    key: 'unify_status',
    width: 60
  },
  {
    title: 'FULL NAME',
    key: 'full_name',
    width: 60
  },
  {
    title: 'DOB (M-D-Y)',
    key: 'dob',
    width: 60
  },
  {
    title: 'PHONE',
    key: 'phone',
    width: 60
  },
  {
    title: 'ADDRESS',
    key: 'street',
    width: 60
  },
  {
    title: 'CITY',
    key: 'city',
    width: 60
  },
  {
    title: 'ZIPCODE',
    key: 'zip',
    width: 60
  },
  {
    title: 'DATE ACTIVE',
    key: 'date_active',
    width: 60
  },
  {
    title: 'CREATED AT',
    key: 'created_at',
    width: 120
  },
]
const customFormat = 'MM-DD-YYYY';
const dbFormat = "YYYY-MM-DD H:i:s"; // format of datepicker

const dob_value = (item) => {
  return item.dob?dayjs(item.dob, dbFormat).format(customFormat):'-';
};

const tableConfig = UseDataTable(fetchListApi, {
  columns,
  listActions,
  itemActions
})
let reloadTable = () => {
}

watch(router.currentRoute.path, (path) => {
  console.log(999, path)
  if (path === prefix) {
    reloadTable()
  }
});

function registerTable({ reload }) {
  reloadTable = reload
}

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <DataTable @register="registerTable" v-bind="tableConfig">
        <template #header>
          <h2>Patient List</h2>
        </template>
        <!-- <template #filter="{tableConfig,filter,reload}">
                    <a-input @keypress="reload" v-model:value="filter.phone" placeholder="Phone"></a-input>
        </template> -->

        <template #cellAction[edit]="{ item, actionMethod }">
          <a-button type="text" :icon="h(EditOutlined)" label="" :outline="true"></a-button>
        </template>
        <template #cellAction[delete]="{ item, actionMethod }">
          <a-popconfirm title="Bạn muốn xóa sản phẩm này?" ok-text="Yes" cancel-text="No" @confirm="actionMethod">
            <a-button type="text" danger :icon="h(DeleteOutlined)" label="" :outline="true"></a-button>
          </a-popconfirm>
        </template>
        <template #cell[id]="{ item, column }">
          <div class="text-center">{{ item.id }}</div>
        </template>
        <template #cell[image]="{ item, column }">
          <a-image height="50px" class="w-20 h-auto" :src="item.image_url" :alt="item.name" />
        </template>
        <template #cell[phone]="{ item, column }">
          <a-input @change="updateApi(item.id, { phone: item.phone })" v-model:value="item.phone" />
        </template>
        <template #cell[dob]="{ item, column }">
          {{ dob_value(item) }}
        </template>
        <template #cell[street]="{ item, column }">
          <a-input v-model:value="item.street" @change="updateApi(item.id, { street: item.street })" />
        </template>
        <template #cell[city]="{ item, column }">
          <a-input v-model:value="item.city" @change="updateApi(item.id, { city: item.city })" />
        </template>
        <template #cell[zip]="{ item, column }">
          <a-input v-model:value="item.zip" @change="updateApi(item.id, { zip: item.zip })" />
        </template>
        <template #cell[unify_status]="{ item, column }">
          <div v-if="item.unify_status === 0" class="">Waiting</div>
          <div v-else-if="item.unify_status === 1" class="">Active</div>
          <div v-else-if="item.unify_status === 2" class="">Inactive</div>
          <div v-else-if="item.unify_status === 3" class="">Decease</div>
          <div v-else class="">Waiting</div>
        </template>
      </DataTable>
      <router-view></router-view>

    </SectionMain>
  </LayoutAuthenticated>
</template>
