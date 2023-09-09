<script lang="ts" setup>
import {reactive, h, ref, toRaw, computed} from "vue";
import {CloseCircleOutlined, ArrowLeftOutlined} from '@ant-design/icons-vue';
import router from "@/router";
import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
import ApiData from "@/components/ApiData.vue";

const prefix = 'user'
const {
  fetchDetailApi,
  createApi,
  // updateApi
} = UseEloquentRouter(prefix)

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
  role: "user",
  password: "",
});

const fetch = async function () {
  loading.value = true;
  var id = router.currentRoute.value.params.id;
  if (id !== 'new') {
    loading.value = true
    const value = await fetchDetailApi(id)
    Object.assign(formState, value.data)
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
      createApi({...formState}).then(rs => {
        Object.assign(formState, rs.data.result)
        console.log(formState);
      });
    })
};
const closeDetail = function () {
  router.replace({path: '/'+prefix})
}

</script>

<template>
  <a-drawer
:closable="false" style="position:relative;display:flex;flex-direction:column;height:100vh;"
            :open="true" width="80vw" @close="closeDetail">
    <a-form
      autocomplete="off"
      v-bind="$config.formConfig"
      @finish="submit"
    >
      <div
style="height:50px;"
           class=" ">
        <a-button class="float-left" type="link" @click="closeDetail">
          <template #icon>
            <ArrowLeftOutlined/>
          </template>
        </a-button>
        <a-space class="float-right" align="right">
          <!--                <a-button v-if="formState.rule_detect_category_link" @click="detectCategory" :loading="loadingDraft" >Test Category</a-button>-->
          <a-button :loading="loading" type="primary" @click="submit()">Save</a-button>
        </a-space>
      </div>
      <a-form-item name="username" label="UserName" :rules="[{ required: true }]">
        <a-input v-model:value="formState.username" autocomplete="off"/>
      </a-form-item>
      <a-form-item name="full_name" label="Full Name" :rules="[{ required: true }]">
        <a-input v-model:value="formState.full_name"/>
      </a-form-item>
      <a-form-item name="email" label="Email" :rules="[{ type: 'email',required: true  }]">
        <a-input v-model:value="formState.email"/>
      </a-form-item>
      <a-form-item label="Role" name="role" required>
        <a-select v-model:value="formState.role"
        :options="listRoles"
        >
        </a-select>
      </a-form-item>
      <a-form-item
        label="Password"
        name="password"
        :rules="formState.isNew?[{ required: true, message: 'Please input your password!' }]:[]"
      >
        <a-input-password v-model:value="formState.password" autocomplete="off">
          <template #prefix>
            <LockOutlined class="site-form-item-icon"/>
          </template>
        </a-input-password>
      </a-form-item>


    </a-form>
  </a-drawer>
</template>
