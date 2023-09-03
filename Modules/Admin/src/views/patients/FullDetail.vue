<script lang="ts" setup>
import { reactive, h, ref, toRaw } from "vue";
import { useMainStore } from "@/stores/main";
import router from "@/router";
import Api from "@/utils/Api";
import { mdiGenderMale, mdiGenderFemale, mdiCalendarCheckOutline, mdiHistory, mdiCheckOutline, mdiPencil } from '@mdi/js';
import { BaseIcon } from "@/components";
import 'jodit/es5/jodit.css';
import dayjs from 'dayjs';

import { JoditEditor, Jodit } from 'jodit-vue';
import { notification } from 'ant-design-vue';
import type { UploadProps } from 'ant-design-vue';

import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";

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
                <h1 class="mb-3 flex items-center justify-between">
                    <div class="mb-3 flex items-center">
                        <BaseIcon :path="mdiGenderMale" class="w-6 h-6 mr-2 !text-blue-600" v-if="formState.gender === 0" />
                        <BaseIcon :path="mdiGenderFemale" class="w-6 h-6 mr-2 text-pink-600"
                            v-if="formState.gender === 1" />
                        <span>{{ formState.full_name }}</span>
                    </div>
                    <div class="text-gray-400">#{{ formState.id }}</div>
                </h1>
                <hr class="my-2">
            </div>


            <div class="mx-auto p-5">
                <div class="md:flex no-wrap md:-mx-2 ">
                    <!-- Left Side -->
                    <div class="w-full md:w-3/12 md:mx-2">
                        <!-- Profile Card -->
                        <ul
                            class="bg-white shadow text-gray-600 hover:text-gray-700 hover:shadow p-5 divide-y rounded-lg">
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
                                <span>Active date</span>
                                <span class="text-xs ml-auto">{{ dayjs(formState.unify_active, 'YYYY-MM-DD HH:mm:ss').format('HH:mm MM-DD-YYYY') }}</span>
                            </li>
                            <li class="flex items-center py-3" v-if="formState.unify_status === 1">
                                <span>Create date</span>
                                <span class="text-xs ml-auto">{{ dayjs(formState.created_at, 'YYYY-MM-DD HH:mm:ss').format('HH:mm MM-DD-YYYY') }}</span>
                            </li>
                        </ul>
                    </div>
                    <!-- Right Side -->
                    <div class="w-full md:w-9/12 mx-2 h-64">
                        <!-- Profile tab -->
                        <!-- About Section -->
                        <div class="bg-white shadow text-gray-600 hover:text-gray-700 hover:shadow p-5 rounded-lg">
                            <div class="grid">
                                <div class="py-2 flex font-semibold">
                                    <div clas="text-green-500">
                                        <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-2">About</div>
                                </div>
                                <div class="mb-5">
                                    <hr class="my-2">
                                </div>
                            </div>
                            <div class="text-gray-700">
                                <div class="grid md:grid-cols-2 text-sm">
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
                                        <div class="py-2">{{ (formState.gender === 1) ? 'Female' : 'Male' }}</div>
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
                                   
                                </div>
                            </div>
                           
                        </div>
                        <!-- End of about section -->

                        <div class="my-4"></div>

                        <!-- Experience and education -->
                        <div class="bg-white shadow text-gray-600 hover:text-gray-700 hover:shadow p-5 rounded-lg">

                            <div class="grid grid-cols-2">
                                <div>
                                    <div class="flex items-center space-x-2 font-semibold text-gray-900 leading-8 mb-3">
                                        <span clas="text-green-500">
                                            <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </span>
                                        <span class="tracking-wide">INSURANCE</span>
                                    </div>
                                    <ul class="list-inside space-y-2">
                                        <li>
                                            <div class="text-teal-600">Owner at Her Company Inc.</div>
                                            <div class="text-gray-500 text-xs">March 2020 - Now</div>
                                        </li>
                                        <li>
                                            <div class="text-teal-600">Owner at Her Company Inc.</div>
                                            <div class="text-gray-500 text-xs">March 2020 - Now</div>
                                        </li>
                                        <li>
                                            <div class="text-teal-600">Owner at Her Company Inc.</div>
                                            <div class="text-gray-500 text-xs">March 2020 - Now</div>
                                        </li>
                                        <li>
                                            <div class="text-teal-600">Owner at Her Company Inc.</div>
                                            <div class="text-gray-500 text-xs">March 2020 - Now</div>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2 font-semibold text-gray-900 leading-8 mb-3">
                                        <span clas="text-green-500">
                                            <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path fill="#fff" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                <path fill="#fff"
                                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                                                </path>
                                            </svg>
                                        </span>
                                        <span class="tracking-wide">Doctor</span>
                                    </div>
                                    <ul class="list-inside space-y-2">
                                        <li>
                                            <div class="text-teal-600">Masters Degree in Oxford</div>
                                            <div class="text-gray-500 text-xs">March 2020 - Now</div>
                                        </li>
                                        <li>
                                            <div class="text-teal-600">Bachelors Degreen in LPU</div>
                                            <div class="text-gray-500 text-xs">March 2020 - Now</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End of Experience and education grid -->
                        </div>
                        <!-- End of profile tab -->
                    </div>
                </div>
            </div>

    </SectionMain>
</LayoutAuthenticated></template>
