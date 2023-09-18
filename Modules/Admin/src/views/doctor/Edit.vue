<script lang="ts" setup>
import { reactive, h, ref, toRaw, computed, watch } from "vue";
import { mdiBackspace, mdiContentSave, mdiAccountArrowUp } from '@mdi/js';
import { BaseIcon } from "@/components";
import router from "@/router";
import { useAuthStore } from "@/stores/auth";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import listStates from "@/utils/States";

const prefix = 'doctor'
const {
  fetchDetailApi,
  createApi,
} = UseEloquentRouter(prefix)

const loading = ref(false);
const auth = useAuthStore();
const currentRoute = router.currentRoute.value;

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
  }, visible: {
    type: Boolean,
    default: true
  },
})
const emit = defineEmits(["close"]);
const formState = reactive({});

const fetch = async function () {
  
  loading.value = true;
  var nameRoute = currentRoute.name;
  var id = currentRoute.params.id;
  if (nameRoute == 'doctor-edit') {
    loading.value = true
    var value = await fetchDetailApi(id)
    //prepare data
    Object.assign(formState, value.data)
    loading.value = false
  } else {
    loading.value = false
  }
}
fetch();

const checkAccess = () => {
  if (auth.hasPermission('doctor.create') || auth.hasPermission('doctor.edit')) {
    return true;
  }
  return false;
}

if (!checkAccess) {
  router.replace({ path: '/' + prefix })
}

const submit = () => {
  formRef.value
    .validate()
    .then(() => {
      formState.full_name = formState.first_name + ' ' + formState.last_name;
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
  <a-drawer :closable="false" style="position:relative;display:flex;flex-direction:column;height:100vh;"
    @close="closeDetail" :open="visible" width="50vw">
    <a-form layout="vertical" v-bind="$config.formConfig" ref="formRef" :model="formState" @finish="submit">
      <div class="p-3 bg-gray-200">
        <a-button class="!hidden md:!inline-block" type="link" @click="closeDetail">
          <template #icon>
            <div class="flex">
              <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
              <span class="ml-1 text-stone-500">Back</span>
            </div>
          </template>
        </a-button>
        <a-button class="!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0" type="link"
          @click="closeDetail">
          <template #icon>
            <div class="flex">
              <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
              <span class="ml-1 text-stone-500">Back</span>
            </div>
          </template>
        </a-button>
        <a-space class="float-right">
          <a-tag v-if="formState.status == 1" color="gray">Inactive</a-tag>
          <a-tag v-if="formState.status == 2" color="gray">Trashed</a-tag>
          <a-tag v-else color="green">Active</a-tag>

          <a-button v-if="currentRoute.name == 'doctor-add'" @click="submit()" type="primary" class="uppercase">
            <div class="flex">
              <BaseIcon :path="mdiContentSave" class="w-4 text-white" />
              <span class="ml-1 text-white">Save And Active</span>
            </div>
          </a-button>
          <a-button v-if="currentRoute.name == 'doctor-edit'" @click="submit()" type="primary"
            class="uppercase">
            <div class="flex">
              <BaseIcon :path="mdiContentSave" class="w-4 text-white" />
              <span class="ml-1 text-white">Update</span>
            </div>
          </a-button>
        </a-space>
      </div>
      <div class="px-4 mt-5 overflow-y-auto" style="height:calc(100% - 60px);">
        <div class="flex flex-wrap -mx-4">

          <h1 v-if="currentRoute.name == 'doctor-edit'" class="flex flex-col w-full px-4">
            <div class="flex items-center leading-none whitespace-nowrap">
              <span>{{ formState.full_name }}</span>
            </div>
          </h1>
          <a-Divider v-if="currentRoute.name == 'doctor-edit'" class="!font-bold !text-blue-700" dashed
            orientation="left" orientation-margin="1rem" plain>Log</a-Divider>
          <div class="w-full px-4">
            <a-form-item v-if="currentRoute.name == 'doctor-edit'" label="Note for this change" name="log_detail"
              :rules="[{ required: true }]">
              <a-textarea class="!rounded-none w-full" v-model:value="formState.log_detail"
                placeholder="Make a note of any changes you make to the doctor record"
                :auto-size="{ minRows: 2, maxRows: 10 }" />
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3 empty:hidden">
            <a-form-item v-if="currentRoute.name == 'doctor-edit' && formState.unify_process == 2" label="Status"
              :rules="[{ required: true }]">
              <a-select v-model:value="formState.status" allowClear="" class="w-full" :options="[
                {
                  value: 0,
                  label: 'Active'
                },
                {
                  value: 1,
                  label: 'Inactive'
                },
                {
                  value: 2,
                  label: 'Trash'
                },
              ]">
              </a-select>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3 empty:hidden">
            <a-form-item v-if="currentRoute.name == 'doctor-edit' && formState.unify_process == 2" label="Publish date"
              :rules="[{ required: true }]">
              <a-date-picker class="w-full" :showTime="{ format: 'HH:mm' }" inputReadOnly
                v-model:value="formState.unify_active" valueFormat="YYYY-MM-DD HH:mm:ss" format="HH:mm MM-DD-YYYY"
                :disabled="formState.unify_process != 2"></a-date-picker>
            </a-form-item>
          </div>
          <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem"
            plain>Sumary</a-Divider>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
            <a-form-item label="First Name" name="first_name"
              :rules="[{ required: true, message: 'Please enter first name!' }]">
              <a-input v-model:value="formState.first_name" />
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
            <a-form-item label="Last Name" name="last_name"
              :rules="[{ required: true, message: 'Please enter last name!' }]">
              <a-input v-model:value="formState.last_name" />
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
          <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem"
            plain>Address</a-Divider>
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
                <a-select-option v-for="state in listStates" :key="state.value" :value="state.value">{{ state.label
                }} ({{ state.value }})</a-select-option>
              </a-select>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/3">
            <a-form-item label="Zip" name="zip" :rules="[{ required: true, message: 'Please enter zip!' }]">
              <a-input-number v-model:value="formState.zip" class="!w-full"></a-input-number>
            </a-form-item>
          </div>
          <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem"
            plain>Note</a-Divider>
          <div class="w-full px-4 mb-4">
            <a-form-item label="Note" name="note">
              <a-textarea class="!rounded-none w-full" v-model:value="formState.note"
                :auto-size="{ minRows: 2, maxRows: 10 }" />
            </a-form-item>
          </div>
        </div>
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
}</style>
