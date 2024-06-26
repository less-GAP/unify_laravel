<script setup>
  import {reactive, ref, h, watch} from "vue";
  import {DeleteOutlined, EditOutlined} from '@ant-design/icons-vue';
  import SectionMain from "@/components/SectionMain.vue";
  import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
  import {DataTable} from "@/components";
  import router from "@/router";
  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import {UseDataTable} from "@/utils/UseDataTable";
  import {useAuthStore} from "@/stores/auth";

  const prefix = 'user'
  const {
    fetchListApi,
    createApi,
    deleteApi,
    updateApi
  } = UseEloquentRouter(prefix)
  const isShowModal = ref(false)
  const auth = useAuthStore();

  const tableConfig = UseDataTable(fetchListApi, {
    showSelection: true,
    showSort: false,
    showReload: true,
    columns: [
      {
        title: 'Type',
        key: 'type'
      },
      {
        title: 'Username',
        width: 200,
        key: 'username'
      },
      {
        title: 'Name',
        key: 'full_name'
      },
      {
        title: 'Role',
        key: 'roles'
      }
    ],
    listActions: [],
    itemActions: [
      {
        ifShow: auth.hasPermission('User.update'),
        label: 'Edit',
        key: 'edit',
        action: (item, reload) => {
          //showEditUser({}, reload)
          router.push(prefix + '/' + item.id)
        },
      },
      {
        ifShow: auth.hasPermission('User.delete'),
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
      ifShow: auth.hasPermission('User.create')
    },
  })


  let reloadTable = () => {
  }

  watch(router.currentRoute, (data) => {
    if (data.path === prefix) {
      reloadTable()
    }
  });

  function registerTable({reload}) {
    reloadTable = reload
  }

</script>

<template>
  <LayoutAuthenticated>
    <DataTable @register="registerTable" v-bind="tableConfig">
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
          title="Are you sure delete this user?"
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
      <template #cell[full_name]="{item,column}">
        <img class="float-left w-10 h-10 rounded-full" :src="item.profile_photo_url"
             :alt="item.full_name">
        <div class="float-left pl-3">
          <div class="text-base font-semibold">{{ item.full_name }}</div>
          <div class="font-normal text-gray-500">{{ item.email }}</div>
        </div>
      </template>
      <template #cell[deleted]="{item,column}">
        <a-switch @change="updateApi(item.id,{deleted:item.deleted})" checkedValue="active" unCheckedValue="inactive"
                  v-model:checked="item.deleted"/>
      </template>
      <template #cell[roles]="{item,column}">
        <div v-for="role in item.roles" :key="role.id">
          <a-tag>{{ role.name }}</a-tag>
        </div>
      </template>
    </DataTable>
  </LayoutAuthenticated>
</template>
