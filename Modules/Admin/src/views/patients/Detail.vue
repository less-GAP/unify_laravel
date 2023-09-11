<script lang="ts" setup>
import { reactive, h, ref, toRaw } from "vue";
import { useMainStore } from "@/stores/main";
import router from "@/router";
import Api from "@/utils/Api";
import { mdiGenderMale, mdiGenderFemale, mdiFolderMultipleImage, mdiPill, mdiMedicalBag, mdiNoteText, mdiBagPersonal, mdiCheckAll,mdiCancel, mdiAlertCircle } from '@mdi/js';
import { BaseIcon } from "@/components";
import 'jodit/es5/jodit.css';
import dayjs from 'dayjs';

import { JoditEditor, Jodit } from 'jodit-vue';
import { notification } from 'ant-design-vue';
import type { UploadProps } from 'ant-design-vue';

import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import {
  fetchListInsurancesApi,
  fetchListDoctorsApi,
  fetchListDoctorStatusApi,
} from "@/utils/Patient";

const customFormat = 'MM-DD-YYYY';
const dbFormat = "YYYY-MM-DD"; // format of datepicker

const dob_value = (item) => {
  return item.dob ? dayjs(item.dob, dbFormat).format(customFormat) : '-';
};

const age = (item) => {
  return item.dob ? '(' + dayjs().diff(dayjs(item.dob, dbFormat), 'year') + ' years old)' : '-';
};

const textNewPatient = (item) => {
  // check new patient or old patient
  // return item.unify_process === 2 && ((now() - item.date_active) > 10) ? 'New Patient' : 'Patient';
  return 'Running';
};

const listInsurances = fetchListInsurancesApi();
const listDoctors = fetchListDoctorsApi();
const listDoctorStatus = fetchListDoctorStatusApi();
const mainStore = useMainStore();
const formState = reactive({})
const loading = ref(false);

const fetch = function () {
    loading.value = true;
    var id = router.currentRoute.value.params.id;
    if (id > 0) {
        loading.value = true
        Api.get('patient/' + id).then(rs => {
            var value = rs.data;
            Object.assign(formState, value)
            loading.value = false
        });
    } else {
        loading.value = false
    }
}
fetch();

</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>

            <div class="p-5">
                <a-breadcrumb>
                    <a-breadcrumb-item>Patients</a-breadcrumb-item>
                    <a-breadcrumb-item>Detail</a-breadcrumb-item>
                </a-breadcrumb>
                <h1 class="flex items-center justify-between mb-3">
                    <div class="flex items-center mb-3">
                        <BaseIcon
                            :path="mdiAlertCircle"
                            class="flex-none mr-1 text-red-600 w-7"
                            v-if="formState.need_improve === 0 && formState.unify_status !== 1"
                        />
                        <span>{{ formState.full_name }}</span>
                    </div>
                    <div class="text-gray-400">#{{ formState.unify_number }}</div>
                </h1>
                <hr class="my-2">
            </div>


            <div class="p-5 mx-auto">
                <div class="md:flex no-wrap md:-mx-2 ">
                    <!-- Left Side -->
                    <div class="w-full md:w-3/12 md:mx-2">
                        <!-- Profile Card -->
                        <ul
                            class="p-5 text-gray-600 bg-white divide-y rounded-lg shadow hover:text-gray-700 hover:shadow">
                            <li class="flex items-center py-3">
                                <span>Status</span>
                                <span class="ml-auto">
                                    <a-tag class="!mr-0" v-if="formState.unify_status === 0 || formState.unify_status=== null" color="orange">Waiting</a-tag>
                                    <a-tag class="!mr-0" v-else-if="formState.unify_status === 1" color="green">Active</a-tag>
                                    <a-tag class="!mr-0" v-else-if="formState.unify_status === 2" color="red">Inactive</a-tag>
                                    <a-tag class="!mr-0" v-else-if="formState.unify_status === 3" color="black">Decease</a-tag>
                                </span>
                            </li>
                            <li class="flex items-center py-3">
                                <span>Process</span>
                                <span class="ml-auto">
                                    <a-tag class="!mr-0" v-if="formState.unify_process === 0 || formState.unify_process=== null" color="gray">Waiting</a-tag>
                                    <a-tag class="!mr-0" v-else-if="formState.unify_process === 1" color="orange">Eligibility Check</a-tag>
                                    <a-tag class="!mr-0" v-else-if="formState.unify_process === 2" color="blue">{{ textNewPatient(formState) }}</a-tag>
                                </span>
                            </li>
                            <li class="flex items-center py-3" v-if="formState.unify_status === 1">
                                <span>Publish date</span>
                                <span class="ml-auto text-xs">{{ dayjs(formState.unify_active, 'YYYY-MM-DD HH:mm:ss').format('HH:mm MM-DD-YYYY') }}</span>
                            </li>
                            <li class="flex items-center py-3">
                                <span>Create date</span>
                                <span class="ml-auto text-xs">{{ dayjs(formState.created_at, 'YYYY-MM-DD HH:mm:ss').format('HH:mm MM-DD-YYYY') }}</span>
                            </li>
                            <li class="flex items-center py-3">
                                <span>Saler</span>
                                <span class="ml-auto text-xs">{{ formState.sale_user }}</span>
                            </li>
                        </ul>
                    </div>
                    <!-- Right Side -->
                    <div class="w-full h-64 mx-2 md:w-9/12">
                        <!-- Profile tab -->
                        <!-- About Section -->
                        <div class="p-5 text-gray-600 bg-white rounded-lg shadow hover:text-gray-700 hover:shadow">
                            <div class="grid">
                                <div class="flex items-center py-2 font-semibold">
                                    <div clas="text-green-500">
                                        <BaseIcon :path="mdiNoteText" class="w-6" />
                                    </div>
                                    <div class="ml-2">ABOUT</div>
                                </div>
                                <div class="mb-5">
                                    <hr class="my-2">
                                </div>
                            </div>
                            <div class="text-gray-700">
                                <div class="grid text-sm md:grid-cols-2">
                                    <div class="grid grid-cols-2">
                                        <div class="py-2 font-semibold">First Name</div>
                                        <div class="py-2">{{ formState.first_name }}</div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="py-2 font-semibold">Last Name</div>
                                        <div class="py-2">{{ formState.last_name }}</div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="py-2 font-semibold">Gender</div>
                                        <div class="flex items-center py-2">
                                            <BaseIcon :path="mdiGenderMale" class="w-6 h-6 mr-1 !text-blue-600" v-if="formState.gender === 0" />
                                            <BaseIcon :path="mdiGenderFemale" class="w-6 h-6 mr-1 text-pink-600" v-if="formState.gender === 1" />
                                            <div>{{ (formState.gender === 1) ? 'Female' : 'Male' }}</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="py-2 font-semibold">Phone</div>
                                        <div class="py-2">
                                            <a class="text-blue-800" href="tel:">{{ formState.phone }}</a>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="py-2 font-semibold">Current Address</div>
                                        <div class="py-2">{{ formState.street }}, {{ formState.city }}, {{ formState.state }}, {{ formState.zip }}</div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="py-2 font-semibold">Date of Birth</div>
                                        <div class="py-2">{{ dob_value(formState) }} <span class="text-xs text-gray-600">{{ age(formState) }}</span></div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="py-2 font-semibold">Email.</div>
                                        <div class="py-2">
                                            <a class="text-blue-800" href="mailto:">{{ formState.email }}</a>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="py-2 font-semibold">Weight</div>
                                        <div class="py-2">{{ formState.weight ? formState.weight : 'N/A' }}</div>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="py-2 font-semibold">Height</div>
                                        <div class="py-2">{{ formState.height ? formState.height : 'N/A' }}</div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <!-- End of about section -->

                        <div class="my-4"></div>

                        <!-- Experience and education -->
                        <div class="p-5 text-gray-600 bg-white rounded-lg shadow hover:text-gray-700 hover:shadow">

                            <div class="grid grid-cols-2">
                                <div>
                                    <div class="flex items-center mb-3 space-x-2 font-semibold leading-8 text-gray-900">
                                        <span clas="text-green-500">
                                            <BaseIcon :path="mdiMedicalBag" class="w-6" />

                                        </span>
                                        <span class="tracking-wide">INSURANCE</span>
                                    </div>
                                    <ul class="space-y-2 list-inside">
                                        <li v-for="item in listInsurances" :key="item.value">
                                            <div class="text-teal-600">
                                                <div class="flex">
                                                    <BaseIcon v-if="formState.insurance_coverages && formState.insurance_coverages.includes(item.value)"  :path="mdiCheckAll" class="w-6 mr-2 text-teal-600" />
                                                    <BaseIcon v-else :path="mdiCancel" class="w-6 mr-2 text-red-600" />
                                                    <div class="flex flex-col">
                                                        <div v-if="formState.insurance_coverages && formState.insurance_coverages.includes(item.value)" class="font-bold text-teal-600">{{ item.label }}</div>
                                                        <div v-else class="text-gray-600">{{ item.label }}</div>
                                                        <div class="text-xs text-gray-500">{{ item.value }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <div class="flex items-center mb-3 space-x-2 font-semibold leading-8 text-gray-900">
                                        <span clas="text-green-500">
                                            <BaseIcon :path="mdiPill" class="w-6" />
                                        </span>
                                        <span class="tracking-wide">DOCTOR</span>
                                    </div>
                                    <ul class="space-y-2 list-inside">
                                        <li>
                                            <div class="text-teal-600">Masters Degree in Oxford</div>
                                            <div class="text-xs text-gray-500">March 2020 - Now</div>
                                        </li>
                                    </ul>
                                    <div class="mt-3">
                                        <a-Divider class="!font-bold !text-gray-700" dashed orientation="left" orientation-margin="0" plain>Doctor note</a-Divider>
                                        <div class="text-sm">{{ formState.doctor_comment }}</div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Experience and education grid -->
                        </div>
                        <!-- End of profile tab -->


                        <div class="my-4"></div>

                        <!-- Experience and education -->
                        <div class="p-5 text-gray-600 bg-white rounded-lg shadow hover:text-gray-700 hover:shadow">

                            <div class="grid grid-cols-2">
                                <div>
                                    <div class="flex items-center mb-3 space-x-2 font-semibold leading-8 text-gray-900">
                                        <span clas="text-green-500">
                                            <BaseIcon :path="mdiBagPersonal" class="w-6" />
                                        </span>
                                        <span class="tracking-wide">SUPPLIES</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="my-4"></div>

                        <!-- Experience and education -->
                        <div class="p-5 text-gray-600 bg-white rounded-lg shadow hover:text-gray-700 hover:shadow">

                            <div class="grid grid-cols-2">
                                <div>
                                    <div class="flex items-center mb-3 space-x-2 font-semibold leading-8 text-gray-900">
                                        <span clas="text-green-500">
                                            <BaseIcon :path="mdiFolderMultipleImage" class="w-6" />
                                        </span>
                                        <span class="tracking-wide">FILES</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

    </SectionMain>
</LayoutAuthenticated></template>
