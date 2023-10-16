<script lang="ts" setup>
  import {reactive, h, ref, toRaw, computed, watch} from "vue";
  import {mdiBackspace, mdiContentSave, mdiAccountArrowUp} from '@mdi/js';
  import {BaseIcon} from "@/components";
  import router from "@/router";
  import {useAuthStore} from "@/stores/auth";
  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import listStates from "@/utils/States";

  const prefix = 'product'

  const {
    fetchDetailApi,
    createApi,
  } = UseEloquentRouter(prefix)

  const loading = ref(false);
  const auth = useAuthStore();

  const activeKey = ref('1');

  const genderList = [
    {
      value: 0,
      label: 'Male'
    },
    {
      value: 1,
      label: 'Female'
    }
  ];

  const formRef = ref();

  const props = defineProps({
    value: {
      type: Object,
      default: {}
    },
  })
  const emit = defineEmits(["close"]);

  const formState = reactive({});


  const submit = () => {
    formRef.value
      .validate()
      .then(() => {
        createApi({...formState}).then(rs => {
          Object.assign(formState, rs.data.result)
        });
      })
  };

  const closeDetail = function () {
    emit('close');
  }

</script>

<template>

  <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit" class="w-full">
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
        <a-button @click="submit()" type="primary">
          <div class="flex">
            <BaseIcon :path="mdiContentSave" class="w-4 text-white"/>
            <span class="ml-1 text-white">Save</span>
          </div>
        </a-button>
      </a-space>
    </div>
    <div class="mx-2">
      <a-tabs v-model:activeKey="activeKey">
        <a-tab-pane key="1" tab="General">

          <a-col :span="24">
            <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem"
                       plain>Sumary
            </a-Divider>
            <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
              <a-form-item label="First Name" name="first_name"
                           :rules="[{ required: true, message: 'Please enter first name!' }]">
                <a-input v-model:value="formState.first_name"/>
              </a-form-item>
            </div>
            <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
              <a-form-item label="Last Name" name="last_name"
                           :rules="[{ required: true, message: 'Please enter last name!' }]">
                <a-input v-model:value="formState.last_name"/>
              </a-form-item>
            </div>
            <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
              <a-form-item label="Date of Birth" name="dob" required>
                <a-date-picker v-model:value="formState.dob" valueFormat="YYYY-MM-DD" format="MM-DD-YYYY" inputReadOnly
                               class="w-full"></a-date-picker>
              </a-form-item>
            </div>
            <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
              <a-form-item label="Gender" name="gender" :rules="[{ required: true, message: 'Please enter gender!' }]">
                <a-select v-model:value="formState.gender" allowClear="" class="w-full" :options="genderList">
                </a-select>
              </a-form-item>
            </div>
            <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
              <a-form-item label="Email" name="email" :rules="[
              {
                type: 'email',
                message: 'The input is not valid email!',
              },
            ]">
                <a-input v-model:value="formState.email"></a-input>
              </a-form-item>
            </div>
            <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
              <a-form-item label="Phone" name="phone" :rules="[{ required: true, message: 'Please enter phone!' }]">
                <a-input v-model:value="formState.phone" class="w-full"></a-input>
              </a-form-item>
            </div>
            <div class="w-full px-4 mb-4">
              <a-form-item label="Certificate" name="certificate">
                <a-input v-model:value="formState.certificate" class="w-full"></a-input>
              </a-form-item>
            </div>
            <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem"
                       plain>Address
            </a-Divider>
            <div class="w-full px-4 mb-4">
              <a-form-item label="Address" name="address" :rules="[{ required: true, message: 'Please enter address!' }]">
                <a-input v-model:value="formState.address" class="w-full"></a-input>
              </a-form-item>
            </div>
            <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
              <a-form-item label="City" name="city" :rules="[{ required: true, message: 'Please enter city!' }]">
                <a-input v-model:value="formState.city" class="w-full"></a-input>
              </a-form-item>
            </div>
            <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
              <a-form-item label="State" name="state" :rules="[{ required: true, message: 'Please enter state!' }]">
                <a-select v-model:value="formState.state" allowClear="" class="w-full" showSearch
                          placeholder="Select a state">
                  <a-select-option v-for="state in listStates" :key="state.value" :value="state.label">{{ state.label
                    }} ({{ state.value }})
                  </a-select-option>
                </a-select>
              </a-form-item>
            </div>
            <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
              <a-form-item label="Zip" name="zip" :rules="[{ required: true, message: 'Please enter zip!' }]">
                <a-input-number v-model:value="formState.zip" class="!w-full"></a-input-number>
              </a-form-item>
            </div>
            <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem"
                       plain>Note
            </a-Divider>
            <div class="w-full px-4 mb-4">
              <a-form-item label="Note" name="note">
                <a-textarea class="!rounded-none w-full" v-model:value="formState.note"
                            :auto-size="{ minRows: 2, maxRows: 10 }"/>
              </a-form-item>
            </div>
          </a-col>
        </a-tab-pane>
        <a-tab-pane key="2" tab="Inventories">
        </a-tab-pane>
      </a-tabs>
    </div>
  </a-form>
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
