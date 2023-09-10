<script setup>
import {reactive, ref, h, watch} from "vue";
import {Modal, DataTable, RemoteSelect} from "@/components";
import router from "@/router";
import {CloseCircleOutlined, ArrowLeftOutlined} from '@ant-design/icons-vue';

import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
import {UseDataTable} from "@/utils/UseDataTable";
import Api from "@/utils/Api";

const prefix = 'roles'
const routePath = '/master-data/' + prefix
const roleID = router.currentRoute.value.params.id
const formAdd = reactive({})
const {
  fetchRelationShipApi,
  attachRelationShipApi,
  detachRelationShipApi,
  fetchDetailApi,
  fetchListApi,
  createApi,
  deleteApi,
  updateApi
} = UseEloquentRouter(prefix)
const isShowModal = ref(false)

const itemActions = [
  {
    label: 'Delete',
    class: '!pr-[10px] !pl-[10px] font-medium !text-red-600 !dark:text-red-500 hover:underline',
    confirm: true,
    action(item, reload) {
      detachRelationShipApi(roleID, 'permissions', item.id).then(rs => {
      }).finally(() => {
        reload();
      });
    }
  }
]
const listActions = [
  // {
  //   label: 'Add',
  //   action: (reload) => {
  //     //showEditUser({}, reload)
  //     router.push(routePath + '/new')
  //   }
  // }
]
const columns = [

  {
    title: 'Name',
    key: 'name',

  }, {
    title: 'Description',
    key: 'description',
  },

]


const tableConfig = UseDataTable(function (params) {
  return fetchRelationShipApi(roleID, 'permissions', params)
}, {
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

async function addPermission(reload) {
  await attachRelationShipApi(roleID, 'permissions', formAdd.id)
  formAdd.id = null
  reload()
}

const closeDetail = function () {
  router.replace({path: routePath})
}

function getDataID(data) {
  if (!data) {
    return []
  }
  return data.map(item => item.id)
}
</script>
<template>
  <a-drawer body-style="padding:0" :closable="false"
            style="position:relative;display:flex;flex-direction:column;height:100vh;"
            @close="closeDetail" :open="true" width="70vw">
    <a-card body-style="padding:10px;height:55px;"
            class="bg-gray-50  ">
      <a-button class="float-left" type="link" @click="closeDetail">
        <template #icon>
          <ArrowLeftOutlined/>
        </template>
      </a-button>

    </a-card>
    <div class="p-5" style="height:calc(100% - 70px);margin-top:15px;overflow: auto;" :gutter="20">

      <DataTable @register="registerTable" v-bind="tableConfig">
        <template #action="{data,reload}">
          <a-form :model="formAdd" validate-trigger="submit" @finish="addPermission(reload)" layout="inline">
            <a-form-item class="w-[200px]" name="id" :rules="[{required:true}]">
              <RemoteSelect labelKey="name" valueKey="id" v-model:value="formAdd.id"
                            url="permissions/all?filter[status]=active">
                <template #option="{option,valueKey,labelKey}">

                  <a-select-option  :disabled="getDataID(data).includes(option[valueKey])"
                                   :value="option[valueKey]">
                    {{ option[labelKey] }}
                  </a-select-option>
                </template>
              </RemoteSelect>
            </a-form-item>
            <a-form-item>
              <a-button html-type="submit" type="primary">Add Permission</a-button>
            </a-form-item>
          </a-form>
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
        <template #cell[image]="{item,column}">
          <a-image height="50px" class="w-20 h-auto" :src="item.image"
                   :alt="item.name"/>
        </template>


      </DataTable>
    </div>
  </a-drawer>
</template>

<style>
.ant-input {
  border-color: #d9d9d9 !important;
  border-radius: 5px !important;
}

.ant-modal-wrap {
  z-index: 100001 !important;
}

.ant-form-item {
  margin-bottom: 0;
}
</style>
