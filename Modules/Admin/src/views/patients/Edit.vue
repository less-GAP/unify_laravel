<script lang="ts" setup>
import { reactive, h, ref, toRaw, computed } from "vue";
import { CloseCircleOutlined } from '@ant-design/icons-vue';
import router from "@/router";
import {useAuthStore} from "@/stores/auth";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import { getProcess } from "@/utils/Process";

const prefix = 'patient'
const {
  fetchDetailApi,
  createApi,
  // updateApi
} = UseEloquentRouter(prefix)

import {
  fetchListStatesApi,
  fetchListInsurancesApi,
  fetchListDoctorsApi,
  fetchListDoctorStatusApi,
} from "./meta";

const listStates = fetchListStatesApi();
const listInsurances = fetchListInsurancesApi();
const listDoctors = fetchListDoctorsApi();
const listDoctorStatus = fetchListDoctorStatusApi();
const loading = ref(false);
const authStore = useAuthStore();
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
  if (nameRoute == 'patient-edit') {
    loading.value = true
    const value = await fetchDetailApi(id)
    Object.assign(formState, value.data)
    loading.value = false
  } else {
    loading.value = false
  }
}
fetch();

const saler_id = computed(() => {
  return formState.sale_user?formState.sale_user:authStore.user.id;
});
const patient_process = computed(() => {
  return formState.unify_process?formState.unify_process:0;
});

const submit = (status) => {
  formRef.value
    .validate()
    .then(() => {
      createApi({ ...formState, status: status }).then(rs => {
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
    @close="closeDetail" :open="visible" width="90vw">
    <a-form layout="vertical" v-bind="$config.formConfig" ref="formRef" :model="formState" @finish="submit">
      <a-card class="shadow bg-gray-50">
        <a-button class="!hidden md:!inline-block" danger type="link" @click="closeDetail">Back
          <template #icon>
            <CloseCircleOutlined />
          </template>
        </a-button>
        <a-button class="!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0" type="primary"
          @click="closeDetail">
          <template #icon>
            <CloseCircleOutlined />
          </template>
        </a-button>
        <a-space class="float-right">
          <a-tag v-if="formState.unify_process == 0" color="gray">{{ getProcess(formState.unify_process)?getProcess(formState.unify_process).label:'' }}</a-tag>
          <a-tag v-if="formState.unify_process == 1" color="orange">{{ getProcess(formState.unify_process)?getProcess(formState.unify_process).label:'' }}</a-tag>
          <a-tag v-if="formState.unify_process == 2" color="blue">{{ getProcess(formState.unify_process)?getProcess(formState.unify_process).label:'' }}</a-tag>
          <a-tag v-else-if="formState.unify_process" color="gray">{{ getProcess(formState.unify_process)?getProcess(formState.unify_process).label:'' }}</a-tag>
          <!-- <a-button @click="submit('draft')" :loading="loadingDraft" type="dashed">Save Draft</a-button> -->
          <a-button @click="submit('publish')" :loading="loading" type="primary">Save And Active</a-button>
        </a-space>
      </a-card>
      <div class="px-4 mt-5 overflow-y-auto" style="height:calc(100% - 60px);">
        <div class="flex flex-wrap -mx-4">
          <div class="w-full px-4">
            <a-form-item v-if="currentRoute.name=='patient-edit'" label="Note for this change" name="log_detail" :rules="[{required: true}]">
              <a-textarea class="!rounded-none w-full" v-model:value="formState.log_detail" placeholder="Make a note of any changes you make to the patient record"
                :auto-size="{ minRows: 2, maxRows: 10 }" />
            </a-form-item>
          </div>
          <a-Divider plain>Sumary</a-Divider>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Full Name" name="full_name"
              :rules="[{ required: true, message: 'Please enter full name!' }]">
              <a-input v-model:value="formState.full_name" />
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="First Name" name="first_name"
              :rules="[{ required: true, message: 'Please enter first name!' }]">
              <a-input v-model:value="formState.first_name" />
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Last Name" name="last_name"
              :rules="[{ required: true, message: 'Please enter last name!' }]">
              <a-input v-model:value="formState.last_name" />
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Date of Birth" name="dob" required>
              <a-date-picker  v-model:value="formState.dob" valueFormat="YYYY-MM-DD" format="MM-DD-YYYY" inputReadOnly class="w-full"></a-date-picker>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Gender" name="gender" :rules="[{ required: true, message: 'Please enter gender!' }]">
              <a-select v-model:value="formState.gender" allowClear="" class="w-full"
              :options="genderList"
              >
              </a-select>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Email" name="email" :rules="[
              {
                type: 'email',
                message: 'The input is not valid email!',
              },
            ]">
              <a-input v-model:value="formState.email"></a-input>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Phone" name="phone" :rules="[{ required: true, message: 'Please enter phone!' }]">
              <a-input v-model:value="formState.phone" class="w-full"></a-input>
            </a-form-item>
          </div>
          <a-Divider plain>Address</a-Divider>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Street" name="street" :rules="[{ required: true, message: 'Please enter street!' }]">

              <a-input v-model:value="formState.street" class="w-full"></a-input>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Apt" name="apt" :rules="[{ message: 'Please enter apt!' }]">

              <a-input v-model:value="formState.apt" class="w-full"></a-input>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="City" name="city" :rules="[{ required: true, message: 'Please enter city!' }]">

              <a-input v-model:value="formState.city" class="w-full"></a-input>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="State" name="state" :rules="[{ required: true, message: 'Please enter state!' }]">
              <a-select v-model:value="formState.state" allowClear="" class="w-full" showSearch
                placeholder="Choose state">
                <a-select-option v-for="(state, index) in listStates" :key="state.code" :value="state.code">{{ state.name
                }} ({{ state.code }})</a-select-option>
              </a-select>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Zip" name="zip" :rules="[{ required: true, message: 'Please enter zip!' }]">
              <a-input-number v-model:value="formState.zip" class="!w-full"></a-input-number>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Route" name="route" :rules="[{ message: 'Please enter route!' }]">
              <a-input v-model:value="formState.route" class="w-full"></a-input>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Sub-r" name="sub_r" :rules="[{ message: 'Please enter sub-r!' }]">
              <a-input v-model:value="formState.sub_r" class="w-full"></a-input>
            </a-form-item>
          </div>
          <a-Divider plain>Insurance</a-Divider>
          <div class="w-full px-4 mb-4">
            <a-form-item label="Insurance coverages" name="insurance_coverages">
              <a-checkbox-group name="insurance_coverages" v-model:value="formState.insurance_coverages"
                :options="listInsurances"></a-checkbox-group>
            </a-form-item>
          </div>
          <a-Divider plain>Doctor</a-Divider>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Doctor" name="doctor">
              <a-select v-model:value="formState.doctor_id" allowClear="" class="w-full" showSearch
                placeholder="Choose doctor">
                <a-select-option v-for="(doctor, index) in listDoctors" :key="doctor.value" :value="doctor.value">{{
                  doctor.label
                }}</a-select-option>
              </a-select>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Doctor status" name="doctor_status">
              <a-select v-model:value="formState.doctor_status" allowClear="" class="w-full"
                placeholder="Choose status">
                <a-select-option v-for="(status, index) in listDoctorStatus" :key="status.value" :value="status.value">{{
                  status.label
                }}</a-select-option>
              </a-select>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2">
            <a-form-item label="Doctor note" name="doctor_comment">
              <a-input v-model:value="formState.doctor_comment" class="w-full"></a-input>
            </a-form-item>
          </div>
          <a-Divider plain>Note</a-Divider>
          <div class="w-full px-4 mb-4 md:w-1/2">
            <a-form-item label="Note" name="note">
              <a-textarea class="!rounded-none w-full" v-model:value="formState.note"
                :auto-size="{ minRows: 2, maxRows: 10 }" />
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2">
            <a-form-item label="Data" name="unify_data">
              <a-textarea class="!rounded-none w-full" v-model:value="formState.unify_data"
                :auto-size="{ minRows: 2, maxRows: 10 }" />
            </a-form-item>
          </div>
        </div>
      </div>
      <a-input v-model:value="patient_process" name="unify_process" type="hidden"></a-input>
      <a-input v-model:value="saler_id" name="sale_user" type="hidden"></a-input>
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
</style>
