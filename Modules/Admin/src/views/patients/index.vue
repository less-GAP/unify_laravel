<script setup>
import {reactive, ref, h, watch} from "vue";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import {DataTable} from "@/components";
import router from "@/router";
import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
import {UseDataTable} from "@/utils/UseDataTable";

const prefix = 'patient'
const {
  fetchListApi,
  createApi,
  deleteApi,
  updateApi
} = UseEloquentRouter(prefix)
const isShowModal = ref(false)

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
    width: '60px'
  },
  {
    title: 'STATUS',
    key: 'status',
    width: '60px'
  },
  {
    title: 'NAME',
    key: 'full_name',
    width: '60px'
  },
  {
    title: 'DOB (M-D-Y)',
    key: 'dob',
    width: '60px'
  },
  {
    title: 'PHONE',
    key: 'phone',
    width: '60px'
  },
  {
    title: 'ADDRESS',
    key: 'address',
    width: '60px'
  },
  {
    title: 'CITY',
    key: 'city',
    width: '60px'
  },
  {
    title: 'ZIPCODE',
    key: 'zipcode',
    width: '60px'
  },
  {
    title: 'DATE ACTIVE',
    key: 'date_active',
    width: '60px'
  },
  {
    title: 'CREATED AT',
    key: 'created_at',
    width: '60px'
  },
]


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

function registerTable({reload}) {
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
        <template #filter="{tableConfig,filter,reload}">
                    <a-input @keypress="reload" v-model:value="filter.phone" placeholder="Phone"></a-input>
        </template>

        <template #cellAction[delete]="{item,actionMethod}">
          <a-popconfirm
            title="Bạn muốn xóa sản phẩm này?"
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
              Delete
            </a-button>
          </a-popconfirm>
        </template>

        <template #cell[image]="{item,column}">
          <a-image height="50px" class="w-20 h-auto" :src="item.image_url"
                   :alt="item.name"/>
        </template>


        <template #cell[status]="{item,column}">
          <a-switch @change="updateApi(item.id,{status:item.status})" checkedValue="publish" unCheckedValue="draft"
                    v-model:checked="item.status"/>
        </template>
      </DataTable>
      <router-view></router-view>

    </SectionMain>
  </LayoutAuthenticated>

</template>
