<script setup>
  import {reactive, ref, h, watch} from "vue";
  import {mdiBallotOutline, mdiDelete} from "@mdi/js";
  import {Modal, DataTable} from "@/components";
  import {DeleteOutlined, FormOutlined, ProjectOutlined} from '@ant-design/icons-vue';
  import router from "@/router";

  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import {UseDataTable} from "@/utils/UseDataTable";
  import {useAuthStore} from "@/stores/auth";

  const prefix = 'permissions'
  const routePath = '/master-data/' + prefix
  const {
    fetchListApi,
    createApi,
    deleteApi,
    updateApi
  } = UseEloquentRouter(prefix)
  const isShowModal = ref(false)
  const auth = useAuthStore();

  const tableConfig = UseDataTable(fetchListApi, {
    columns: [
      {
        title: 'Name',
        key: 'name',

      }, {
        title: 'Description',
        key: 'description',

      },
      {
        title: 'Status',
        key: 'status',
        width: 100
      },
      {
        title: 'Created at',
        key: 'created_at',
        width: 200
      },
    ],
    listActions: [],
    itemActions: [
      {
        ifShow: auth.hasPermission('Permission.update'),
        label: 'Edit',
        key: 'edit',
        action: (item, reload) => {
          //showEditUser({}, reload)
          router.push(routePath + '/' + item.id)
        }
      },
      {
        ifShow: auth.hasPermission('Permission.delete'),
        label: 'Delete',
        key: 'delete',
        class: 'font-medium !text-red-600 !dark:text-red-500 hover:underline',
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
        router.push(routePath + '/new')
      },
      ifShow: auth.hasPermission('Permission.create')
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
    <template #cell[image]="{item,column}">
      <a-image height="50px" class="w-20 h-auto" :src="item.image"
               :alt="item.name"/>
    </template>

    <template #cell[status]="{item,column}">
      <a-switch @change="updateApi(item.id,{status:item.status})" checkedValue="active" unCheckedValue="inactive"
                v-model:checked="item.status"/>
    </template>
    <template #cell[is_default]="{item,column}">
      <a-switch @change="updateApi(item.id,{is_default:item.is_default})" :checkedValue="1" :unCheckedValue="0"
                v-model:checked="item.is_default"/>
    </template>
  </DataTable>
  <router-view></router-view>

</template>
