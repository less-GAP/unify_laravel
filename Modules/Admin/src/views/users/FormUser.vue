<script lang="ts" setup>
import { reactive, h, ref, toRaw, computed } from "vue";
import { CloseCircleOutlined, ArrowLeftOutlined } from '@ant-design/icons-vue';
import router from "@/router";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import ApiData from "@/components/ApiData.vue";
import { useAuthStore } from "@/stores/auth";

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
  role: "",
  password: "",
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
      console.log(formState.roles);

      createApi({ ...formState }).then(rs => {
        Object.assign(formState, rs.data.result)
      });
    })
};
const closeDetail = function () {
  router.replace({ path: '/' + prefix })
}
</script>

<template>
  <a-drawer :closable="false" style="position:relative;display:flex;flex-direction:column;height:100vh;" :open="true"
    width="80vw" @close="closeDetail">
    <a-form autocomplete="off" v-bind="$config.formConfig" :model="formState" ref="formRef" @finish="submit">
      <div style="height:50px;" class=" ">
        <a-button class="float-left" type="link" @click="closeDetail">
          <template #icon>
            <ArrowLeftOutlined />
          </template>
        </a-button>
        <a-space class="float-right" align="right">
          <a-button :loading="loading" type="primary" @click="submit()">Save</a-button>
        </a-space>
      </div>
      <a-form-item name="username" label="UserName" :rules="[{ require: true }]" v-if="!formState.isNew && auth.user.roles.find(x => x.name === 'Admin') !== false">
        <a-input v-model:value="formState.username" autocomplete="off" />
      </a-form-item>
      <a-form-item name="username" label="UserName" :rules="[{ require: true }]" v-else>
        <a-input v-model:value="formState.username" autocomplete="off" readonly/>
      </a-form-item>
      <a-form-item name="full_name" label="Full Name" :rules="[{ required: true }]">
        <a-input v-model:value="formState.full_name" />
      </a-form-item>
      <a-form-item name="email" label="Email" :rules="[{ type: 'email', required: true }]">
        <a-input v-model:value="formState.email" />
      </a-form-item>
      <a-form-item label="Role" name="role" required v-if="!formState.isNew && auth.user.roles.find(x => x.name === 'Admin') !== false">
        <a-select v-model:value="formState.roles" :options="listRoles">
        </a-select>
      </a-form-item>
      <a-form-item label="Role" name="role" required v-else>
        <a-input v-model:value="formState.roles" readonly>
        </a-input>
      </a-form-item>
      <a-form-item label="Password" name="password"
        :rules="formState.isNew ? [{ required: true, message: 'Please input your password!' }] : []">
        <a-input-password v-model:value="formState.password" autocomplete="off">
          <template #prefix>
            <LockOutlined class="site-form-item-icon" />
          </template>
        </a-input-password>
      </a-form-item>


    </a-form>
  </a-drawer>
</template>
