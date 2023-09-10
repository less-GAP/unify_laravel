<script lang="ts" setup>
import {reactive, h, ref, toRaw, computed} from "vue";
import {CloseCircleOutlined, ArrowLeftOutlined} from '@ant-design/icons-vue';
import {mdiBackspace, mdiContentSave, mdiAccountArrowUp} from '@mdi/js';
import router from "@/router";
import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
import ApiData from "@/components/ApiData.vue";
import {useAuthStore} from "@/stores/auth";
import {notification} from "ant-design-vue";
import {RemoteSelect} from "@/components";
const prefix = 'user'
const {
  fetchDetailApi,
  createApi,
  // updateApi
} = UseEloquentRouter(prefix)
const auth = useAuthStore();
const currentRoute = router.currentRoute.value;
const loading = ref(false);
const formRef = ref();
const listRoles = ref([
  {
    value: 'Admin',
    label: 'Admin'
  },
  {
    value: 'Seller Manager',
    label: 'Seller Manager'
  },
  {
    value: 'Seller',
    label: 'Seller'
  },
  {
    value: 'Staff Manager',
    label: 'Staff Manager'
  },
  {
    value: 'Staff',
    label: 'Staff'
  },
]);
const formState = reactive({
  isNew: true,
  full_name: "",
  username: "",
  email: "",
  roles: "",
  password: "",
  isDefaultSeller: false,
});

const fetch = async function () {
  loading.value = true;
  var id = currentRoute.params.id;
  if (id !== 'new') {
    loading.value = true
    const value = await fetchDetailApi(id)
    Object.assign(formState, value.data)
    formState.roles = value.data.roles ? value.data.roles[0].name : ''
    formState.isNew = false
    loading.value = false
  } else {
    loading.value = false
  }
}
fetch();


const submit = () => {
  formRef.value
    .validate()
    .then(() => {
      if (formState.roles === null) {
        notification.error({
          message: 'Error',
          description: 'Please select role',
        });
        return false;
      }
      createApi({...formState}).then(rs => {
        Object.assign(formState, rs.data.result)
      });
    })
};
const closeDetail = function () {
  router.replace({path: '/' + prefix})
}
</script>

<template>
  <a-drawer :closable="false" style="position:relative;display:flex;flex-direction:column;height:100vh;" :open="true"
            @close="closeDetail" width="30vw">
    <a-form autocomplete="off" v-bind="$config.formConfig" :model="formState" ref="formRef" @finish="submit">
      <div class="p-3 bg-gray-200">
        <a-button class="!hidden md:!inline-block" type="link" @click="closeDetail">
          <template #icon>
            <div class="flex">
              <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500"/>
              <span class="ml-1 text-stone-500">Back</span>
            </div>
          </template>
        </a-button>
        <a-button class="!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0" type="link"
                  @click="closeDetail">
          <template #icon>
            <div class="flex">
              <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500"/>
              <span class="ml-1 text-stone-500">Back</span>
            </div>
          </template>
        </a-button>
        <a-space class="float-right">
          <a-button @click="submit" type="primary" class="uppercase">
            <div class="flex">
              <BaseIcon :path="mdiContentSave" class="w-4 text-white"/>
              <span class="ml-1 text-white">Save</span>
            </div>
          </a-button>
        </a-space>
      </div>
      <div class="px-4 mt-5 overflow-y-auto" style="height:calc(100% - 60px);">

        <a-form-item label="UserName" :rules="[{ require: true }]">
          <a-input v-model:value="formState.username" autocomplete="off"
                   :readonly="!formState.isNew && !$auth.hasPermission('User.updateUsername')"/>
        </a-form-item>
        <a-form-item label="Full Name" :rules="[{ required: true }]">
          <a-input v-model:value="formState.full_name"/>
        </a-form-item>
        <a-form-item label="Email" :rules="[{ type: 'email', required: true }]">
          <a-input v-model:value="formState.email"/>
        </a-form-item>
        <a-form-item label="Role" v-if="!formState.isNew">
          <RemoteSelect url="roles/all" labelKey="name" valueKey="id" :readonly="!$auth.hasPermission('user.updateRole')"
                    v-model:value="formState.roles" :options="listRoles">
          </RemoteSelect>
        </a-form-item>

        <a-form-item>
          <label >
            <a-checkbox v-model:checked="formState.isDefaultSeller"></a-checkbox>
            <span class="ml-4">Is default Seller</span>
          </label>
        </a-form-item>
        <a-form-item label="Password"
                     :rules="formState.isNew ? [{ required: true, message: 'Please input your password!' }] : []">
          <a-input-password v-model:value="formState.password" autocomplete="off">
            <template #prefix>
              <LockOutlined class="site-form-item-icon"/>
            </template>
          </a-input-password>
        </a-form-item>
      </div>

    </a-form>
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

.ant-drawer-body {
  padding: 0 !important
}
</style>
