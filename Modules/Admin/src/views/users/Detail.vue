<script lang="ts" setup>
  import {reactive, h, ref, toRaw, computed} from "vue";
  import {CloseCircleOutlined, ArrowLeftOutlined} from '@ant-design/icons-vue';
  import {mdiBackspace, mdiContentSave, mdiAccountArrowUp} from '@mdi/js';
  import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
  import router from "@/router";
  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import ApiData from "@/components/ApiData.vue";
  import {useAuthStore} from "@/stores/auth";
  import {notification} from "ant-design-vue";
  import {RemoteSelect} from "@/components";
  import Api from "@/utils/Api";

  const prefix = 'user'
  const {
    fetchDetailApi,
    createApi,
    // updateApi
  } = UseEloquentRouter(prefix)
  const auth = useAuthStore();
  const currentRoute = router.currentRoute.value;
  const loading = ref(false);
  const activeKey = ref('1');
  const formRef = ref();

  const formState = ref({
    isNew: true,
    status: 'active',
    isDefaultSeller: false,
  });

  const fetch = async function () {
    loading.value = true;
    var id = currentRoute.params.id;
    if (id > 0) {
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

        if (formState.isDefaultSeller) {
          Api.post("config", {
            default_seller: formState.id
          }).then((result) => {
            console.log(result)
          })
        }
      })
  };

  const back = () => {
    router.push('/' + prefix);
  };

</script>

<template>

  <LayoutAuthenticated>
    <a-form autocomplete="off" v-bind="$config.formConfig" :model="formState" ref="formRef" @finish="submit">
      <div class="mx-4">
        <a-tabs v-model:activeKey="activeKey" @change="tabActive">
          <a-tab-pane key="1" tab="General">
            <a-row :gutter="20">
              <a-col :span="12">
                <a-form-item label="Status" name="status" :rules="[{ required: true, message: 'Select a status!' }]">
                  <a-radio-group v-model:value="formState.status" button-style="solid" size="middle">
                    <a-radio-button value="active">Active</a-radio-button>
                    <a-radio-button value="inactive">InActive</a-radio-button>
                  </a-radio-group>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="12">
                <a-form-item label="Username" name="username" :rules="formState.isNew ? [{ required: true, message: 'Please input your password!' }] : []">
                  <a-input v-model:value="formState.username" autocomplete="off"/>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="12">
                <a-form-item label="Full Name" name="full_name" :rules="[{ required: true, message: 'Please input !' }]">
                  <a-input v-model:value="formState.full_name"/>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="12">
                <a-form-item label="Email" name="email" :rules="[{ required: true, message: 'Please input !' }]">
                  <a-input v-model:value="formState.email"/>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="12">
                <a-form-item label="Role" name="role_id" :rules="[{ required: true, message: 'Please input !' }]">
                  <RemoteSelect url="roles/all" labelKey="name" valueKey="id"
                                v-model:value="formState.role_id">
                    <template #option="{option,valueKey,labelKey}">
                      <a-select-option :value="option[valueKey]">
                        {{ option[labelKey] }}
                      </a-select-option>
                    </template>
                  </RemoteSelect>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="12">
                <a-form-item>
                  <label>
                    <a-checkbox v-model:checked="formState.isDefaultSeller"></a-checkbox>
                    <span class="ml-4">Is default Seller</span>
                  </label>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="12">
                <a-form-item label="Password" name="password"
                             :rules="formState.isNew ? [{ required: true, message: 'Please input your password!' }] : []">
                  <a-input-password v-model:value="formState.password" autocomplete="off">
                    <template #prefix>
                      <LockOutlined class="site-form-item-icon"/>
                    </template>
                  </a-input-password>
                </a-form-item>
              </a-col>
            </a-row>
          </a-tab-pane>
        </a-tabs>
        <a-space align="center" class="pt-5">
          <a-button type="primary" html-type="submit">Submit</a-button>
          <a-button type="primary" ghost @click="back()">Back</a-button>
        </a-space>
      </div>
    </a-form>
  </LayoutAuthenticated>
</template>

<style>
  .ant-input {
    border-color: #d9d9d9 !important;
    border-radius: 5px !important;
  }


  .ant-form-item {
    margin-bottom: 0;
  }

  .ant-drawer-body {
    padding: 0 !important
  }
</style>
