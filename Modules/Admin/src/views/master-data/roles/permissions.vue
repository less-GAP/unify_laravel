<script setup>
  import {reactive, ref, h, watch} from "vue";
  import {Modal, DataTable, RemoteSelect, ApiData} from "@/components";
  import router from "@/router";
  import {CloseCircleOutlined, ArrowLeftOutlined} from '@ant-design/icons-vue';

  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import {UseDataTable} from "@/utils/UseDataTable";
  import Api from "@/utils/Api";
  import {useAuthStore} from "@/stores/auth";

  const prefix = 'roles'
  const routePath = '/master-data/' + prefix
  const roleID = router.currentRoute.value.params.id
  const formRef = ref();
  const formAdd = ref({})
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


  const auth = useAuthStore();


  const tableConfig = UseDataTable(function (params) {
    return fetchRelationShipApi(roleID, 'permissions', params)
  }, {
    columns: [
      {
        title: 'Name',
        key: 'name',

      }, {
        title: 'Description',
        key: 'description',
      },
    ],
    listActions: [],
    itemActions: [
      {
        ifShow: auth.hasPermission('Role.delete'),
        label: 'Delete',
        key: 'delete',
        class: '!pr-[10px] !pl-[10px] font-medium !text-red-600 !dark:text-red-500 hover:underline',
        confirm: true,
        action(item, reload) {
          detachRelationShipApi(roleID, 'permissions', item.id).then(rs => {
          }).finally(() => {
            reload();
          });
        }
      }
    ],
    addAction: {
      action: (reload) => {
        //router.push(routePath + '/new')
        formAdd.id = null
        openPermission.value = true;
      },
      ifShow: auth.hasPermission('Role.create')
    },
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

  async function addPermission() {
    formRef.value
      .validate()
      .then(() => {
        attachRelationShipApi(roleID, 'permissions', formAdd.value.id)
        formAdd.value.id = null;
        openPermission.value = false;
        reloadTable()
      });
  }

  const openPermission = ref(false)

  const closeDetail = function () {
    router.replace({path: routePath})
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
    <a-modal v-model:open="openPermission" title="Add permission" @ok="addPermission">
      <a-form :model="formAdd" ref="formRef" validate-trigger="submit" autocomplete="off" layout="vertical">
        <div class="py-4">
          <a-row :gutter="20">
            <a-col :span="24">
              <a-form-item name="id" :rules="[{required:true, message: 'Please select...!'}]" label="Permission">
                <ApiData url="permissions/all" method="GET" :params="{
            'filter[status]': 'active'
          }">
                  <template #default="{ data }">
                    <a-select v-model:value="formAdd.id" placeholder="Select permission..." option-label-prop="children" show-search allowClear>
                      <template v-for="(value, key) in data" :key="key">
                        <a-select-option :value="value.id" :label="value.name">
                          {{value.name}}
                        </a-select-option>
                      </template>
                    </a-select>
                  </template>
                </ApiData>
              </a-form-item>
            </a-col>
          </a-row>
        </div>
      </a-form>
    </a-modal>
  </a-drawer>
</template>

<style>
  .ant-input {
    border-color: #d9d9d9 !important;
    border-radius: 5px !important;
  }

  .ant-form-item {
    margin-bottom: 0;
  }
</style>
