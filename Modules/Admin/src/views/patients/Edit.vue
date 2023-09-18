<script lang="ts" setup>
import { reactive, h, ref, toRaw, computed, watch } from "vue";
import { mdiBackspace, mdiContentSave, mdiAccountArrowUp } from '@mdi/js';
import { BaseIcon } from "@/components";
import { ApiData } from "@/components";
import router from "@/router";
import { useAuthStore } from "@/stores/auth";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import { getProcess } from "@/utils/Patient";
import InsuranceListEdit from "@/components/InsuranceListEdit.vue";
import dayjs from 'dayjs';
import {
  fetchListInsurancesApi,
  fetchListDoctorsApi,
  fetchListDoctorStatusApi,
} from "@/utils/Patient";
import listStates from "@/utils/States";

const prefix = 'patient'
const {
  fetchDetailApi,
  createApi,
  updateApi
} = UseEloquentRouter(prefix)

const listInsurances = fetchListInsurancesApi();
const listDoctors = fetchListDoctorsApi();
const listDoctorStatus = fetchListDoctorStatusApi();
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
  if (nameRoute == 'patient-edit') {
    loading.value = true
    var value = await fetchDetailApi(id)
    //prepare data
    value.data.insurance_coverages = JSON.parse(value.data.insurance_coverages);
    Object.assign(formState, value.data)
    loading.value = false
  } else {
    loading.value = false
  }
}
fetch();

const checkAccess = () => {
  if (auth.user.roles.find(x => x.name === 'Admin') !== undefined) {
    return true;
  } else if (auth.user.roles.find(x => x.name === 'Seller') !== undefined && formState.unify_process == 0 && formState.sale_user == auth.user.id) {
    return true;
  } else {
    return false;
  }
}

if (!checkAccess) {
  router.replace({ path: '/' + prefix })
}

const saler_id = computed(() => {
  return formState.sale_user ? formState.sale_user : auth.user.id;
});
const patient_process = computed(() => {
  return formState.unify_process ? formState.unify_process : 0;
});

const submit = (status) => {
  formRef.value
    .validate()
    .then(() => {
      if (formState.unify_status > 1) { // die or inactive => to archive
        formState.unify_inactive_at = dayjs().format('YYYY-MM-DD HH:mm:ss');
      }
      const new_insurance_coverages = formState.insurance_coverages;
      var insurance_coverages = ref([]);
      if (formState.insurance_coverages) {
        formState.insurance_coverages.forEach((element, index) => {
          insurance_coverages.value.push({
            coverage: element.coverage,
            insurance_id: element.insurance_id,
            active_date: element.active_date,
            expired_date: element.expired_date,
          });
        });
      }
      if (insurance_coverages.value.length) {
        formState.insurance_coverages = JSON.stringify(toRaw(insurance_coverages.value))
      } else {
        formState.insurance_coverages = null
      }

      createApi({ ...formState, status: status }).then(rs => {
        Object.assign(formState, rs.data.result)
        formState.insurance_coverages = new_insurance_coverages;
      });
    })
};
// Submit when seller approve profile from Waiting to Eligibility Check
const submitSellerApprove = () => {
  formRef.value
    .validate()
    .then(() => {
      const new_insurance_coverages = formState.insurance_coverages;
      var insurance_coverages = ref([]);
      if (formState.insurance_coverages) {
        formState.insurance_coverages.forEach((element, index) => {
          insurance_coverages.value.push({
            coverage: element.coverage,
            insurance_id: element.insurance_id,
            active_date: element.active_date,
            expired_date: element.expired_date,
          });
        });
      }
      if (insurance_coverages.value.length) {
        formState.insurance_coverages = JSON.stringify(toRaw(insurance_coverages.value))
      } else {
        formState.insurance_coverages = null
      }

      formState.unify_process = 1;

      updateApi(formState.id, { ...formState }).then(rs => {
        Object.assign(formState, rs.data.result)
        formState.insurance_coverages = new_insurance_coverages;
        router.replace({ path: '/' + prefix })
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
          <a-tag v-if="formState.unify_status == 2" color="gray">Inactive</a-tag>
          <a-tag v-if="formState.unify_status == 3" color="gray">Decease</a-tag>
          <a-tag v-if="formState.unify_process == 0" color="gray">{{
            getProcess(formState.unify_process) ? getProcess(formState.unify_process).label : '' }}</a-tag>
          <a-tag v-if="formState.unify_process == 1" color="orange">{{
            getProcess(formState.unify_process) ? getProcess(formState.unify_process).label : '' }}</a-tag>
          <a-tag v-if="formState.unify_process == 2 && formState.unify_status < 2" color="blue">Running</a-tag>
          <ApiData url="master-data/task-status" method="GET" :params="{}">
            <template #default="{ data }">
              <a-select class="w-[200px]" v-model:value="formState.unify_task_status" :options="JSON.parse(data.data)"
                placeholder="Select status for profile">
              </a-select>
            </template>
          </ApiData>
          <a-button v-if="currentRoute.name == 'patient-add'" @click="submit('publish')" type="primary" class="uppercase">
            <div class="flex">
              <BaseIcon :path="mdiContentSave" class="w-4 text-white" />
              <span class="ml-1 text-white">Save And Active</span>
            </div>
          </a-button>
          <a-button
            v-if="(currentRoute.name == 'patient-edit' && auth.user.roles.find(x => x.name === 'Seller') !== undefined && formState.unify_process == 0)"
            @click="submitSellerApprove" type="primary" class="uppercase !bg-green-500 hover:!bg-green-400">
            <div class="flex">
              <BaseIcon :path="mdiAccountArrowUp" class="w-4 text-white" />
              <span class="ml-1 text-white">Approve</span>
            </div>
          </a-button>
          <a-button v-if="currentRoute.name == 'patient-edit'" @click="submit('publish')" type="primary"
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

          <h1 v-if="currentRoute.name == 'patient-edit'" class="flex flex-col w-full px-4">
            <div class="flex items-center leading-none whitespace-nowrap">
              <span>{{ formState.full_name }}</span>
            </div>
            <div class="text-sm text-gray-400">#{{ formState.unify_number }}</div>
          </h1>
          <a-Divider v-if="currentRoute.name == 'patient-edit'" class="!font-bold !text-blue-700" dashed
            orientation="left" orientation-margin="1rem" plain>Log</a-Divider>
          <div class="w-full px-4">
            <a-form-item v-if="currentRoute.name == 'patient-edit'" label="Note for this change" name="log_detail"
              :rules="[{ required: true }]">
              <a-textarea class="!rounded-none w-full" v-model:value="formState.log_detail"
                placeholder="Make a note of any changes you make to the patient record"
                :auto-size="{ minRows: 2, maxRows: 10 }" />
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4 empty:hidden">
            <a-form-item v-if="currentRoute.name == 'patient-edit' && formState.unify_process == 2" label="Status"
              :rules="[{ required: true }]">
              <a-select v-model:value="formState.unify_status" allowClear="" class="w-full" :options="[
                //   {
                //   value:0,
                //   label:'Waiting'
                // },
                {
                  value: 1,
                  label: 'Active'
                },
                {
                  value: 2,
                  label: 'Inactive'
                },
                {
                  value: 3,
                  label: 'Decease'
                },
              ]">
              </a-select>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4 empty:hidden">
            <a-form-item v-if="currentRoute.name == 'patient-edit' && formState.unify_process == 2" label="Publish date"
              :rules="[{ required: true }]">
              <a-date-picker class="w-full" :showTime="{ format: 'HH:mm' }" inputReadOnly
                v-model:value="formState.unify_active" valueFormat="YYYY-MM-DD HH:mm:ss" format="HH:mm MM-DD-YYYY"
                :disabled="formState.unify_process != 2"></a-date-picker>
            </a-form-item>
          </div>
          <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem"
            plain>Sumary</a-Divider>
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
              <a-date-picker v-model:value="formState.dob" valueFormat="YYYY-MM-DD" format="MM-DD-YYYY" inputReadOnly
                class="w-full"></a-date-picker>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Gender" name="gender" :rules="[{ required: true, message: 'Please enter gender!' }]">
              <a-select v-model:value="formState.gender" allowClear="" class="w-full" :options="genderList">
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
          <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem"
            plain>Address</a-Divider>
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
                placeholder="Select a state">
                <a-select-option v-for="state in listStates" :key="state.value" :value="state.value">{{ state.label
                }} ({{ state.value }})</a-select-option>
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
          <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem"
            plain>Insurance</a-Divider>
          <div class="w-full px-4 mb-4">
            <InsuranceListEdit :columns="[{
              title: 'Insurance Coverage',
              dataIndex: 'coverage',
            }, {
              title: 'Insurance ID',
              dataIndex: 'insurance_id'
            }, {
              title: 'Active date',
              dataIndex: 'active_date',
              type: 'date',
            }, {
              title: 'Expired date',
              dataIndex: 'expired_date',
              type: 'date',
            },
            ]" v-model:value="formState.insurance_coverages">
            </InsuranceListEdit>
          </div>
          <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem"
            plain>Doctor</a-Divider>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Doctor" name="doctor">
              <a-select v-model:value="formState.doctor_id" allowClear="" class="w-full" showSearch
                placeholder="Select a doctor">
                <a-select-option v-for="(doctor, index) in listDoctors" :key="doctor.value" :value="doctor.value">{{
                  doctor.label
                }}</a-select-option>
              </a-select>
            </a-form-item>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/2 lg:w-1/4">
            <a-form-item label="Doctor status" name="doctor_status">
              <a-select v-model:value="formState.doctor_status" allowClear="" class="w-full"
                placeholder="Select a status">
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
          <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem"
            plain>Note</a-Divider>
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

.ant-drawer-body {
  padding: 0 !important
}</style>
