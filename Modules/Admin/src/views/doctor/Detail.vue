<script lang="ts" setup>
  import {reactive, h, ref, toRaw, computed, watch} from "vue";
  import {mdiBackspace, mdiContentSave, mdiAccountArrowUp} from '@mdi/js';
  import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
  import {BaseIcon} from "@/components";
  import router from "@/router";
  import {useAuthStore} from "@/stores/auth";
  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import listStates from "@/utils/States";
  import {notification} from "ant-design-vue";
  import Api from "@/utils/Api";

  const prefix = 'doctor'
  const {
    fetchDetailApi,
    createApi,
  } = UseEloquentRouter(prefix)

  const loading = ref(false);
  const auth = useAuthStore();

  const genderList = [
    {
      value: 0,
      label: 'Male'
    },
    {
      value: 1,
      label: 'Female'
    },
    {
      value: 2,
      label: 'Other'
    }
  ];

  const formRef = ref();


  const formState = ref({
    status: '1'
  });

  const fetch = async function () {
    loading.value = true;
    var id = parseInt(router.currentRoute.value.params.id);
    if (id > 0) {
      var value = await fetchDetailApi(id);
      formState.value = value.data;
      formState.value.status = value.data.status.toString();
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
        Api.post(prefix, toRaw(formState.value)).then(rs => {
          // notification[rs.data.code == 0 ? 'error' : 'success']({
          //   message: 'Notification',
          //   description: rs.data.message,
          // });
          if (rs.data.code == 1) {
            back();
          }
        });
      })
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
                <a-form-item label="Note for this change" name="log_detail" :rules="[{ required: true, message: 'Please enter note!' }]" v-if="formState.id > 0">
                  <a-textarea class="!rounded-none w-full" v-model:value="formState.log_detail"
                              placeholder="Make a note of any changes you make to the doctor record"
                              :auto-size="{ minRows: 2, maxRows: 10 }"/>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="8">
                <a-form-item label="Status" name="status" :rules="[{ required: true, message: 'Select a status!' }]">
                  <a-radio-group v-model:value="formState.status" button-style="solid" size="middle">
                    <a-radio-button value="1">Inactive</a-radio-button>
                    <a-radio-button value="0">Active</a-radio-button>
                    <a-radio-button value="2">Trash</a-radio-button>
                  </a-radio-group>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Publish date" name="unify_active"
                             :rules="[{ required: true, message: 'Select a publish date!' }]" v-if="formState.status == 0">
                  <a-date-picker class="w-full" :showTime="{ format: 'HH:mm' }"
                                 v-model:value="formState.unify_active" valueFormat="YYYY-MM-DD HH:mm:ss" format="HH:mm MM-DD-YYYY"
                  ></a-date-picker>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="8">
                <a-form-item label="First Name" name="first_name"
                             :rules="[{ required: true, message: 'Please enter first name!' }]">
                  <a-input v-model:value="formState.first_name"/>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Last Name" name="last_name"
                             :rules="[{ required: true, message: 'Please enter last name!' }]">
                  <a-input v-model:value="formState.last_name"/>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Date of Birth" name="dob" required>
                  <a-date-picker v-model:value="formState.dob" valueFormat="YYYY-MM-DD" format="MM-DD-YYYY" inputReadOnly
                                 class="w-full"></a-date-picker>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="8">
                <a-form-item label="Gender" name="gender" :rules="[{ required: true, message: 'Please enter gender!' }]">
                  <a-select v-model:value="formState.gender" allowClear="" class="w-full" :options="genderList">
                  </a-select>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Email" name="email" :rules="[{type: 'email', message: 'The input is not valid email!'}]">
                  <a-input v-model:value="formState.email"></a-input>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Phone" name="phone" :rules="[{ required: true, message: 'Please enter phone!' }]">
                  <a-input v-model:value="formState.phone" class="w-full"></a-input>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="8">
                <a-form-item label="Certificate" name="certificate">
                  <a-input v-model:value="formState.certificate" class="w-full"></a-input>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="8">
                <a-form-item label="Address" name="address" :rules="[{ required: true, message: 'Please enter address!' }]">
                  <a-input v-model:value="formState.address" class="w-full"></a-input>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="City" name="city" :rules="[{ required: true, message: 'Please enter city!' }]">
                  <a-input v-model:value="formState.city" class="w-full"></a-input>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="State" name="state" :rules="[{ required: true, message: 'Please enter state!' }]">
                  <a-select v-model:value="formState.state" allowClear="" class="w-full" showSearch
                            placeholder="Select a state">
                    <a-select-option v-for="state in listStates" :key="state.value" :value="state.label">{{ state.label
                      }} ({{ state.value }})
                    </a-select-option>
                  </a-select>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="8">
                <a-form-item label="Zip" name="zip" :rules="[{ required: true, message: 'Please enter zip!' }]">
                  <a-input-number v-model:value="formState.zip" class="!w-full"></a-input-number>
                </a-form-item>
              </a-col>
              <a-col :span="8">

              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="24">
                <a-form-item label="Note" name="note">
                  <a-textarea class="!rounded-none w-full" v-model:value="formState.note" rows="2"/>
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

  .ant-modal-wrap {
    z-index: 100001 !important;
  }

  .ant-form-item {
    margin-bottom: 0;
  }

  .ant-drawer-body {
    padding: 0 !important
  }</style>
