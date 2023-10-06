<script setup>
import {reactive, ref, h, watch} from "vue";
import {mdiBallotOutline, mdiDelete} from "@mdi/js";
import {Modal, DataTable} from "@/components";
import router from "@/router";

import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
import {UseDataTable} from "@/utils/UseDataTable";

const prefix = 'event-setting'
const routePath = '/configs/event-setting'
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
    class: '!pr-[10px] !pl-[10px]',
    action: (item, reload) => {
      //showEditUser({}, reload)
      router.push(routePath + '/' + item.id)
    }
  },
  {
    label: 'Delete',
    class: '!pr-[10px] !pl-[10px] font-medium !text-red-600 !dark:text-red-500 hover:underline',
    confirm: true,
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
      router.push(routePath + '/new')
    }
  }
]
const columns = [

  {
    title: 'Name',
    key: 'event_name',

  },
  {
    title: 'Notifications',
    key: 'notifications',

  },
  {
    title: 'Status',
    key: 'status',
    width: 100
  },
  {
    title: 'Created at',
    key: 'created_at',
    width: 150
  },
  {
    title: 'Created by',
    key: 'created_by',
    width: 50
  },
]


const tableConfig = UseDataTable(fetchListApi, {
  columns,
  listActions,
  itemActions
})
let reloadTable = () => {
}

watch(router.currentRoute, (data) => {
  if (data.path === routePath) {
    reloadTable()
  }
});

function registerTable({reload}) {
  reloadTable = reload
}

</script>

<template>
  <DataTable @register="registerTable" v-bind="tableConfig">
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
        </a-button>
      </a-popconfirm>
    </template>
    <template #cellAction[edit]="{item,actionMethod}">
      <a-button
        class="mr-5"
        type="text"
        :icon="h(FormOutlined)"
        label=""
        :outline="true"
        @click="actionMethod"
      >
      </a-button>
    </template>
    <template #cell[notifications]="{item,column}">
      {{item.handlers.join(', ')}}
    </template>

    <template #cell[status]="{item,column}">
      <a-switch @change="updateApi(item.id,{status:item.status})" checkedValue="active" unCheckedValue="inactive"
                v-model:checked="item.status"/>
    </template>

  </DataTable>
  <router-view></router-view>

</template>
