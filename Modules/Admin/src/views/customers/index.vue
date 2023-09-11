<script setup>
import {reactive, ref, h} from "vue";
import {mdiBallotOutline, mdiAccount, mdiMail, mdiGithub, mdiDelete, mdiEye} from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";

import {Modal, DataTable} from "@/components";
import {DeleteOutlined, FormOutlined} from '@ant-design/icons-vue';
import FormUser from "./FormUser.vue";

import Api from "@/utils/Api";
import router from "@/router";

import {notification} from "ant-design-vue";


const modalState = ref({
    visible: false,
    value: null,
    success: () => {
    },
    cancel: () => {
    },
  });

  function showEditUser(value, success) {
    modalState.value.visible = true;
    modalState.value.value = value;
    modalState.value.success = success;
  }

  const tableConfig = {
    tableConfig: {
      sticky: true,
      scroll: "{ x: 1500, y: 600 }",
      bordered: true,
      showHeader: true
    },
    api: (params) => Api.get('customer/list', {params}),
    addAction: (reload) => {
      showEditUser(null, reload)
    },
    itemActions: [
      {
        label: '',
        class: 'font-medium text-blue-600 dark:text-blue-500 hover:underline',
        icon: mdiDelete,
        key: 'edit',
        action(item, reload) {
        showEditUser(item, reload)
        }
      },
      {
        label: '',
        class: 'font-medium text-red-600 dark:text-red-500 hover:underline',
        icon: mdiDelete,
        key: 'delete',
        action(item, reload) {
          Api.delete('customer/' + item.id).then(reload)
        }
      }
    ],
    columns: [
      {title: 'Group', key: 'customer_group_name'},
      {title: 'Username', key: 'username'},
      {title: 'Full name', key: 'name'},
      {title: 'Email', key: 'email'},
      {title: 'Phone', key: 'phone'},
      {title: 'Active date', key: 'email_verified_at'},
      {title: 'Referral Code', key: 'referral_code'},
      {title: 'Status', key: 'status'}
    ],
    selectionActions: [
      {
        title: 'Active',
        action(selectedKeys) {
          return Api.post('customer/activeList', {
            'items': selectedKeys,
            'status': 'A'
          }).then(rs => {
            notification[rs.data.code == 0 ? 'error' : 'success']({
              message: 'Notification',
              description: rs.data.message,
            });
          })
        },
      },
      {
        title: 'Deactive',
        action(selectedKeys) {
          return Api.post('customer/activeList', {
            'items': selectedKeys,
            'status': 'D'
          }).then(rs => {
            notification[rs.data.code == 0 ? 'error' : 'success']({
              message: 'Notification',
              description: rs.data.message,
            });
          })
        },
      },
      {
      title: 'Delete',
      action(selectedKeys) {
        return Api.post('customer/deleteList', selectedKeys)
      },
      complete() {
        alert('success')
      }
    }
    ]
  }
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <DataTable v-bind="tableConfig">
        <template #cell[full_name]="{item,column}">
          <img class="w-10 h-10 float-left rounded-full" :src="item.profile_photo_url"
               :alt="item.full_name">
          <div class="pl-3 float-left">
            <div class="text-base font-semibold">{{ item.full_name }}</div>
            <div class="font-normal text-gray-500">{{ item.email }}</div>
          </div>
        </template>
        <template #cell[status]="{item,column}">
          <div class="flex items-center" v-if="item.status == 'D'">
            <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div>
            Deactive
          </div>
          <div class="flex items-center" v-if="item.status == 'A'">
            <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div>
            Active
          </div>
        </template>
        <template #cellAction[edit]="{item,actionMethod}">
          <a-button
            type="text"
            :icon="h(FormOutlined)"
            label=""
            :outline="true"
            @click="actionMethod"
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
      </DataTable>
    </SectionMain>

  </LayoutAuthenticated>
  <a-modal :title="modalState.value?.id?'Edit User':'Add User'" :footer="null" width="800px"
           v-model:open="modalState.visible">
    <FormUser v-if="modalState.visible"
              @success="modalState.visible=false;modalState.success()" @cancel="modalState.visible=false"
              :value="modalState.value"></FormUser>
  </a-modal>
</template>
