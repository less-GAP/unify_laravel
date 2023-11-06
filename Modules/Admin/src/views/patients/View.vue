<script lang="ts" setup>
import { reactive, h, ref, toRaw, computed } from "vue";
import { useMainStore } from "@/stores/main";
import router from "@/router";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import Api from "@/utils/Api";
import { mdiGenderMale, mdiGenderFemale, mdiFolderMultipleImage, mdiPill, mdiMedicalBag, mdiNoteText, mdiBagPersonal, mdiCheckAll, mdiCancel, mdiAlertCircle } from '@mdi/js';
import { BaseIcon } from "@/components";
import 'jodit/es5/jodit.css';
import dayjs from 'dayjs';
import { mdiBackspace } from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import {
    fetchListInsurancesApi,
    fetchListDoctorStatusApi
} from "@/utils/Patient";

const prefix = "patient";
const { fetchDetailApi } = UseEloquentRouter(prefix);

const customFormat = 'MM-DD-YYYY';
const dbFormat = "YYYY-MM-DD"; // format of datepicker

const dob_value = (item) => {
    return item.dob ? dayjs(item.dob, dbFormat).format(customFormat) : '-';
};

const age = (item) => {
    return item.dob ? '(' + dayjs().diff(dayjs(item.dob, dbFormat), 'year') + ' years old)' : '-';
};

const formatDateTime = (datetime) => {
    return datetime ? dayjs(datetime, 'YYYY-MM-DD HH:mm:ss').format('HH:mm MM-DD-YYYY') : '-';
};

const listInsurances = ref([]);
const doctorObj = ref([]);
const listUploadFiles = ref([]);
const formState = reactive({})
const loading = ref(false);
const doctorStatusObj = ref([]);

const fetch = async function () {
    loading.value = true;
    var id = router.currentRoute.value.params.id;
    console.log(id);
    const rsInsurances = await fetchListInsurancesApi();
    listInsurances.value = rsInsurances;
    const rs = await fetchDetailApi(id);
    const data = rs.data;
    if (data.status == 200) {
        if (data.data.insurance_coverages && data.data.insurance_coverages.length > 0) {
            data.data.insurance_coverages = JSON.parse(data.data.insurance_coverages);
            data.data.insurance_coverages.map((item) => {
                return listInsurances.value.find((insurance) => {
                    if (insurance.value === item.coverage) {
                        item.coverage = insurance.label;
                    }
                });
            });
        }
        Object.assign(formState, data.data)
        if (formState.doctor_status != undefined) {
            doctorStatusObj.value = fetchListDoctorStatusApi().find(item => item.value === formState.doctor_status);
        }
        if (formState.sale_user) {
            Api.get(`user/${formState.sale_user}`).then((res) => {
                if (res.status === 200) {
                    formState.sale_user_object = res.data.full_name;
                }
            });
        }
        if (formState.doctor_id) {
            Api.get(`doctor/${formState.doctor_id}`).then((res) => {
                if (res.status === 200) {
                    doctorObj.value = res.data;
                }
            });
        }
        if (formState.signature) {
            listUploadFiles.value.push({
                alt: 'Signature',
                site_path: formState.signature
            });
        }

        if (formState.images) {
            listUploadFiles.value.push(...formState.images);
        }

        loading.value = false
    } else {
        loading.value = false
        router.replace(prefix)
    }
}
fetch();

const productColumns = ref([
  {
    title: '#',
    dataIndex: 'stt',
    key: 'stt',
    width: 50,
    render: (id, record, index) => {
      ++index;
      return index;
    },
  },
  {
    title: 'Product',
    dataIndex: 'id',
    key: 'id',
  },
  {
    title: "Amount",
    dataIndex: 'amount',
    key: "amount",
    width: 150,
    align: 'center'
  },
  {
    title: "Delivery Type",
    dataIndex: 'delivery_type',
    key: "delivery_type",
    width: 250,
  },
  {
    title: "Delivery date",
    dataIndex: 'delivery_date',
    key: "delivery_date",
    width: 150,
  },
]);

const insuranceColumns = ref([
  {
    title: '#',
    dataIndex: 'stt',
    key: 'stt',
    width: 50,
    render: (id, record, index) => {
      ++index;
      return index;
    },
  },
  {
    title: 'Insurance Coverage',
    dataIndex: 'coverage',
    key: 'coverage',
  },
  {
    title: "Insurance ID",
    dataIndex: 'insurance_id',
    key: "insurance_id",
    width: 200,
  },
  {
    title: "Active date",
    dataIndex: 'active_date',
    key: "active_date",
    width: 200,
  },
  {
    title: "Expired date",
    dataIndex: 'expired_date',
    key: "expired_date",
    width: 200,
  },
]);

</script>

<template>
    <LayoutAuthenticated>
        <SectionMain >
          <a-skeleton v-if="loading" active class="p-10"/>
          <div v-else>
            <div class="p-5">
              <a-button class="!hidden md:!inline-block" type="link" @click="router.back()">
                <template #icon>
                  <div class="flex">
                    <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
                    <span class="ml-1 text-stone-500">Back</span>
                  </div>
                </template>
              </a-button>
              <h1 class="mb-3">
                <div class="flex items-center mb-3 text-xl">
                  <BaseIcon :path="mdiAlertCircle" class="flex-none mr-1 text-red-600 w-7"
                            v-if="formState.need_improve === 0 && formState.unify_status !== 1" />
                  <span>{{ formState.full_name }}</span>
                </div>
                <div class="text-gray-400 text-base">#{{ formState.unify_number }}</div>
              </h1>
              <hr class="my-2">
            </div>
            <div class="p-5 mx-auto">
              <div class="md:flex no-wrap md:-mx-2 ">
                <!-- Left Side -->
                <div class="w-full md:w-3/12 md:mx-2">
                  <!-- Profile Card -->
                  <ul class="p-5 text-gray-600 bg-white divide-y rounded-lg shadow hover:text-gray-700 hover:shadow">
                    <li class="flex items-center py-3">
                      <span>Status</span>
                      <span class="ml-auto">
                                    <a-tag v-if="formState.unify_process === 0" color="yellow">Waiting</a-tag>
                                    <a-tag v-else-if="formState.unify_process === 1" color="orange">Eligibility
                                        Check</a-tag>
                                    <a-tag v-else-if="formState.unify_status === 1" color="green">
                                        <div class="pt-1 leading-none">Active</div>
                                        <div class="pb-1 leading-none">
                                            <small>({{
                                                dayjs(formState.unify_active, "YYYY-MM-DD HH:mm:ss").format(
                                                  "HH:mm MM-DD-YYYY"
                                                )
                                              }})</small>
                                        </div>
                                    </a-tag>
                                    <a-tag v-else-if="formState.unify_status === 2" color="gray">Inactive</a-tag>
                                    <a-tag v-else-if="formState.unify_status === 3" color="gray">Decease</a-tag>
                                </span>
                    </li>
                    <li class="flex items-center py-3" v-if="formState.unify_status === 1">
                      <span>Publish date</span>
                      <span class="ml-auto text-xs">{{ formatDateTime(formState.unify_active) }}</span>
                    </li>
                    <li class="flex items-center py-3">
                      <span>Created date</span>
                      <span class="ml-auto text-xs">{{ formatDateTime(formState.created_at) }}</span>
                    </li>
                    <li class="flex items-center py-3">
                      <span>Saler</span>
                      <span class="ml-auto text-xs">{{ formState.sale_user_object }}</span>
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
                      <div class="grid -mx-4 text-sm md:grid-cols-2">
                        <div class="grid grid-cols-2 px-4">
                          <div class="py-2 font-semibold">First Name</div>
                          <div class="py-2">{{ formState.first_name }}</div>
                        </div>
                        <div class="grid grid-cols-2 px-4">
                          <div class="py-2 font-semibold">Last Name</div>
                          <div class="py-2">{{ formState.last_name }}</div>
                        </div>
                        <div class="grid grid-cols-2 px-4">
                          <div class="py-2 font-semibold">Gender</div>
                          <div class="flex items-center py-2">
                            <BaseIcon :path="mdiGenderMale" class="w-6 h-6 mr-1 !text-blue-600"
                                      v-if="formState.gender === 0" />
                            <BaseIcon :path="mdiGenderFemale" class="w-6 h-6 mr-1 text-pink-600"
                                      v-if="formState.gender === 1" />
                            <div>{{ (formState.gender === 1) ? 'Female' : 'Male' }}</div>
                          </div>
                        </div>
                        <div class="grid grid-cols-2 px-4">
                          <div class="py-2 font-semibold">Phone</div>
                          <div class="py-2">
                            <a class="text-blue-800" :href="'tel:' + formState.phone">{{ formState.phone
                              }}</a>
                          </div>
                        </div>
                        <div class="grid grid-cols-2 px-4">
                          <div class="py-2 font-semibold">Current Address</div>
                          <div class="py-2">{{ formState.street }}, {{ formState.city }}, {{ formState.state
                            }}, {{ formState.zip }}</div>
                        </div>
                        <div class="grid grid-cols-2 px-4">
                          <div class="py-2 font-semibold">Date of Birth</div>
                          <div class="py-2">{{ dob_value(formState) }} <span class="text-xs text-gray-600">{{
                              age(formState) }}</span></div>
                        </div>
                        <div class="grid grid-cols-2 px-4">
                          <div class="py-2 font-semibold">Email.</div>
                          <div class="py-2">
                            <a class="text-blue-800" :href="'mailto:' + formState.email">{{ formState.email
                              }}</a>
                          </div>
                        </div>
                        <div class="grid grid-cols-2 px-4">
                          <div class="py-2 font-semibold">Weight</div>
                          <div class="py-2">{{ formState.weight ? formState.weight : 'N/A' }}</div>
                        </div>
                        <div class="grid grid-cols-2 px-4">
                          <div class="py-2 font-semibold">Height</div>
                          <div class="py-2">{{ formState.height ? formState.height : 'N/A' }}</div>
                        </div>
                        <div class="grid grid-cols-2 px-4">
                          <div class="py-2 font-semibold">Note</div>
                          <div class="py-2">{{ formState.note ? formState.note : 'N/A' }}</div>
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
                        <ul class="grid space-y-2 list-inside">
                          <li v-for="item in formState.insurances" :key="item">
                            <div class="">
                              <div class="flex items-start">
                                <BaseIcon :path="mdiCheckAll" class="w-6 mr-2 text-teal-600" />
                                <div class="flex flex-col">
                                  <h5>Coverage: <span class="text-teal-600">{{ item.coverage.label }}</span>
                                  </h5>
                                  <div class="text-sm">
                                    <div class="text-sm">ID: <span class="text-teal-600 text-sm">{{ item.insurance_id ??
                                    'N/A' }}</span></div>
                                    <div class="text-sm">Active date: <span class="text-teal-600">{{
                                        item.active_date ?
                                          dayjs(item.active_date,
                                            "YYYY-MM-DD").format("MM-DD-YYYY") :
                                          'N/A' }}</span></div>
                                    <div class="text-sm">Expired date: <span class="text-teal-600">{{
                                        item.expired_date ?
                                          dayjs(item.expired_date,
                                            "YYYY-MM-DD").format("MM-DD-YYYY") :
                                          'N/A' }}</span></div>
                                  </div>
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
                            <div class="text-teal-600">{{ doctorObj.full_name }}</div>
                            <div class="text-xs text-gray-500">{{ doctorStatusObj.label }}</div>
                          </li>
                        </ul>
                        <div class="mt-3">
                          <a-Divider class="!font-bold !text-gray-700" dashed orientation="left"
                                     orientation-margin="0" plain>Doctor note</a-Divider>
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

                    <div class="grid">
                      <div>
                        <div class="flex items-center mb-3 space-x-2 font-semibold leading-8 text-gray-900">
                                        <span clas="text-green-500">
                                            <BaseIcon :path="mdiBagPersonal" class="w-6" />
                                        </span>
                          <span class="tracking-wide">Products</span>
                        </div>
                        <a-table :data-source="formState.products" :columns="productColumns" bordered :pagination="false" triped>
                          <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'id'">
                              <div class="flex flex-row items-center">
                                <img class="w-20 h-auto float-left" :src="record.product.image_url" :alt="record.product.name" v-if="record.product.image_url"/>
                                <img class="w-20 h-auto float-left" src="/src/assets/no_image_available.png" v-else/>
                                <label class="ml-4 font-semibold">{{record.product.name}}</label>
                              </div>
                            </template>
                            <template v-if="column.key === 'delivery_type'">
                              <label v-if="record.delivery_type == 'one_times'">One Times</label>
                              <label v-if="record.delivery_type == 'weekly'">Weekly</label>
                              <label v-if="record.delivery_type == 'monthly'">Monthly</label>
                              <label v-if="record.delivery_type == 'yearly'">Yearly</label>
                            </template>
                            <template v-if="column.key === 'delivery_date'">
                              <label v-if="record.delivery_type == 'one_times' || record.delivery_type == 'yearly'">
                                {{dayjs(record.delivery_date, "YYYY-MM-DD").format("MM-DD-YYYY" )}}
                              </label>
                              <label v-if="record.delivery_type == 'monthly'">
                                {{record.delivery_value}}
                              </label>
                              <label v-if="record.delivery_type == 'weekly'">
                                {{record.delivery_option.label}}
                              </label>
                            </template>
                            <template v-if="column.key === 'action'">
                              <a-button type="text" :icon="h(FormOutlined)" label="" :outline="true" @click="editProduct(record)"></a-button>
                              <a-popconfirm title="Do you want delete this?" ok-text="Yes" cancel-text="No" @confirm="delProduct(record)">
                                <a-button type="text" danger :icon="h(DeleteOutlined)" label="" :outline="true">
                                </a-button>
                              </a-popconfirm>
                            </template>
                          </template>
                        </a-table>
                      </div>
                    </div>
                  </div>

                  <div class="my-4"></div>

                  <!-- Experience and education -->
                  <div class="p-5 text-gray-600 bg-white rounded-lg shadow hover:text-gray-700 hover:shadow">
                    <div class="flex items-center mb-3 space-x-2 font-semibold leading-8 text-gray-900">
                                <span clas="text-green-500">
                                    <BaseIcon :path="mdiFolderMultipleImage" class="w-6" />
                                </span>
                      <span class="tracking-wide">FILES</span>
                    </div>
                    <div v-if="listUploadFiles.length == 0" class="flex flex-wrap -mx-4">
                      <div
                        class="flex flex-col items-center justify-center w-24 h-24 mx-auto my-2 text-gray-400 bg-gray-100 rounded-lg">
                        <BaseIcon :path="mdiFolderMultipleImage" class="w-12" />
                        <div class="mt-2 text-sm">No files</div>
                      </div>
                    </div>
                    <div v-else class="flex flex-wrap -mx-4">
                      <div class="px-4">
                        <a-image-preview-group style="width:100%;overflow: auto;gap:8px">
                          <template v-for="(file, index) in listUploadFiles" :key="index">
                            <a-card shadow="none"
                                    style="display:inline-block;margin-right:5px;text-align: center;width:200px;height:200px;position:relative">
                              <template #cover>
                                <a-image style="width:200px;height:200px;object-fit:contain" :src="$url(file.site_path)"
                                         :alt="alt" />
                              </template>
                            </a-card>
                          </template>
                        </a-image-preview-group>
                      </div>
                    </div>
                  </div>

                  <div class="pb-4"></div>

                </div>
              </div>
            </div>
          </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
