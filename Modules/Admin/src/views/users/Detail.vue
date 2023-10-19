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
    fetchDetailApi
  } = UseEloquentRouter(prefix)

  const auth = useAuthStore();

  const loading = ref(false);
  const activeKey = ref('1');
  const formRef = ref();

  const formState = ref({
    isNew: true,
    status: 'active',
  });

  const fetch = async function () {
    loading.value = true;
    var id = parseInt(router.currentRoute.value.params.id);
    if (id > 0) {
      const value = await fetchDetailApi(id)
      formState.value = value.data;
      // formState.value.role_id = value.data.roles ? value.data.roles[0].name : ''
      formState.value.isNew = false
      loading.value = false
    } else {
      formState.value.isNew = true;
      loading.value = false
    }
  }
  fetch();


  const submit = () => {
    formRef.value
      .validate()
      .then(() => {
        Api.post(prefix, toRaw(formState.value)).then(rs => {
          // notification[rs.data.code == 0 ? 'error' : 'success']({
          //   message: 'Notification',
          //   description: rs.data.message,
          // });
          if (rs.data.code == 1) {
            back();
          }
        });
      });
  };

  const back = () => {
    router.push('/' + prefix);
  };

</script>

<template>

  <LayoutAuthenticated>
    <a-form autocomplete="off" layout="vertical" :model="formState" ref="formRef" @finish="submit">
      <div class="mx-4">
        <a-tabs v-model:activeKey="activeKey" @change="tabActive">
          <a-tab-pane key="1" tab="General">
            <a-row :gutter="20">
              <a-col :span="24">
                <a-form-item label="Note for this change" name="log_detail" :rules="[{ required: true, message: 'Please enter note!' }]" v-if="false">
                  <a-textarea class="!rounded-none w-full" v-model:value="formState.log_detail"
                              placeholder="Make a note of any changes you make to the doctor record"
                              :auto-size="{ minRows: 2, maxRows: 10 }"/>
                </a-form-item>
              </a-col>
            </a-row>
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
              <a-col :span="12" v-if="formState.isNew">
                <a-form-item label="Username" name="username" :rules="[{ required: true, message: 'Please input your password!' }]">
                  <a-input v-model:value="formState.username" autocomplete="off" placeholder="Username..."/>
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
                <a-form-item label="Role" name="roles_id" :rules="[{ required: true, message: 'Please input !' }]">
                  <ApiData url="roles/all" method="GET" :params="{}">
                    <template #default="{ data }">
                      <a-select class="w-[200px]" v-model:value="formState.roles_id" mode="multiple"
                                placeholder="Select product..." option-label-prop="children">
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
            <a-row :gutter="20" v-if="false">
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
              <a-col :span="12" v-if="formState.isNew">
                <a-form-item label="Password" name="password"
                             :rules="[{ required: true, message: 'Please input your password!' }]">
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
