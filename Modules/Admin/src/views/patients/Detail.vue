<script lang="ts" setup>
  import {reactive, h, ref, toRaw, computed, watch} from "vue";
  import {mdiBackspace, mdiContentSave, mdiAccountArrowUp, mdiTrashCanOutline} from '@mdi/js';
  import {BaseIcon, InputUpload} from "@/components";
  import {ApiData} from "@/components";
  import {notification} from "ant-design-vue";
  import router from "@/router";
  import {useAuthStore} from "@/stores/auth";
  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import {getProcess} from "@/utils/Patient";
  import InsuranceListEdit from "@/components/InsuranceListEdit.vue";
  import dayjs from 'dayjs';
  import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
  import Api from "@/utils/Api";
  import {DeleteOutlined} from "@ant-design/icons-vue";

  import ProductList from "./ProductList.vue";

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
    updateApi,
  } = UseEloquentRouter(prefix)

  const listInsurances = fetchListInsurancesApi();
  const listDoctors = ref([]);
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

  const formState = ref({
    products: []
  });

  const current = ref<number>(0);

  const fetch = async function () {
    loading.value = true;
    var nameRoute = currentRoute.name;
    var id = currentRoute.params.id;
    listDoctors.value = await fetchListDoctorsApi();
    if (id > 0) {
      loading.value = true
      var response = await fetchDetailApi(id)
      if (response.data.status === 200) {
        //prepare data
        const data = response.data.data
        data.insurance_coverages = JSON.parse(data.insurance_coverages);
        formState.value = data;
        //Object.assign(formState, data)
        loading.value = false
        current.value = formState.value.unify_process;
      } else {
        notification.error({
          message: "Error",
          description: response.data.message,
        });
        setTimeout(() => {
          back();
        }, 3000);
      }
    } else {
      formState.value.sale_user = auth.user.id;
      formState.value.unify_process = 0;
      current.value = 0;
      loading.value = false
    }
  }
  fetch();


  const submit = (status) => {
    formRef.value
      .validate()
      .then(() => {
        if (formState.value.unify_status > 1) { // die or inactive => to archive
          formState.value.unify_inactive_at = dayjs().format('YYYY-MM-DD HH:mm:ss');
        }
        const new_insurance_coverages = formState.value.insurance_coverages;
        var insurance_coverages = ref([]);
        if (formState.value.insurance_coverages) {
          formState.value.insurance_coverages.forEach((element, index) => {
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

        Api.post(prefix, toRaw(formState.value)).then(rs => {
          if (rs.data.code == 1) {
            back();
          }
        });
      })
  };
  // Submit when seller approve profile from Waiting to Eligibility Check
  const approval = (step) => {
    formRef.value
      .validate()
      .then(() => {
        const new_insurance_coverages = formState.value.insurance_coverages;
        var insurance_coverages = ref([]);
        if (formState.value.insurance_coverages) {
          formState.value.insurance_coverages.forEach((element, index) => {
            insurance_coverages.value.push({
              coverage: element.coverage,
              insurance_id: element.insurance_id,
              active_date: element.active_date,
              expired_date: element.expired_date,
            });
          });
        }
        if (insurance_coverages.value.length) {
          formState.value.insurance_coverages = JSON.stringify(toRaw(insurance_coverages.value))
        } else {
          formState.value.insurance_coverages = null
        }

        if (step == 'eligibility_check') {
          formState.value.unify_process = 1;
        }

        Api.post(prefix, toRaw(formState.value)).then(rs => {
          if (rs.data.code == 1) {
            back();
          }
        });
      })
  };

  const showDetail = ref(false);

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
      title: "Slug",
      key: "slug",
    },
    {
      title: "Status",
      key: "status",
      width: 100,
    },
    {
      title: 'Action',
      dataIndex: 'action',
      key: 'action',
      width: 100
    },
  ]);

  const selectProduct = ref({});
  const onSelectProduct = (item) => {
    var add = true;
    formState.value.products.forEach((value, index) => {
      if (value.id == item.id) {
        formState.value.products[index] = item;
        add = false;
      }
    });
    if (add) {
      formState.value.products.push(item);
    }
    showDetail.value = false;
  }

  const addProduct = () => {
    selectProduct.value = {};
    showDetail.value = true;
  };

  const delProduct = (item) => {
    formState.value.products.forEach((value, index) => {
      if (value.id == item.id) {
        formState.value.products.splice(index, 1);
      }
    });
  };

  const back = () => {
    router.push('/' + prefix);
  };

</script>

<template>
  <LayoutAuthenticated>
    <div class="mx-4">
      <div class="mb-5 bg-gray-100" v-if="formState.id > 0">
        <a-steps
          v-model:current="current"
          type="navigation"
          size="small"
          :items="[
        {
          title: 'Waiting',
          description: '',
        },
        {
          title: 'Eligibility Check',
          description: '',
          disabled: true
        },
        {
          title: 'Running',
          description: '',
          disabled: true
        },
        {
          title: 'Finshed',
          description: '',
          disabled: true
        },
      ]">
        </a-steps>
      </div>
    </div>
    <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit">
      <div class="mx-4">
        <a-tabs v-model:activeKey="activeKey" @change="tabActive">
          <a-tab-pane key="1" tab="General">
            <a-row :gutter="20">
              <a-col :span="12">
                <a-form-item label="Note for this change" name="log_detail"
                             :rules="[{ required: true }]" v-if="formState.id > 0">
                  <a-textarea class="!rounded-none w-full" v-model:value="formState.log_detail"
                              placeholder="Make a note of any changes you make to the patient record"
                              :auto-size="{ minRows: 2, maxRows: 10 }"/>
                </a-form-item>
              </a-col>
              <a-col :span="6">
                <a-form-item label="Task Status" v-if="formState.id > 0">
                  <ApiData url="master-data/task-status" method="GET" :params="{}">
                    <template #default="{ data }">
                      <a-select class="w-[200px]" v-model:value="formState.unify_task_status"
                                placeholder="Select status for profile">
                        <template v-for="(value, key) in JSON.parse(data.data)" :key="key">
                          <a-select-option :value="value.value" :style="'color:'+value.color+';background-color:'+value.background_color">{{value.label}}</a-select-option>
                        </template>
                      </a-select>
                    </template>
                  </ApiData>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="6">
                <a-form-item label="Status"
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
              </a-col>
              <a-col :span="6">
                <a-form-item label="Publish date" :rules="[{ required: true }]" v-if="formState.unify_status == '1'">
                  <a-date-picker class="w-full" :showTime="{ format: 'HH:mm' }"
                                 v-model:value="formState.unify_active"
                                 valueFormat="YYYY-MM-DD HH:mm:ss" format="HH:mm MM-DD-YYYY"
                  ></a-date-picker>
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="8">
                <a-form-item label="ID Number" name="unify_number"
                             :rules="[{ required: true, message: 'Please enter unify number!' }]">
                  <a-input v-model:value="formState.unify_number"/>
                </a-form-item>
              </a-col>
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
              <a-col :span="8" v-if="false">
                <a-form-item label="Full Name" name="full_name"
                             :rules="[{ required: true, message: 'Please enter full name!' }]">
                  <a-input v-model:value="formState.full_name" disabled/>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Date of Birth" name="dob" required>
                  <a-date-picker v-model:value="formState.dob" valueFormat="YYYY-MM-DD" format="MM-DD-YYYY" inputReadOnly
                                 class="w-full"></a-date-picker>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Gender" name="gender" :rules="[{ required: true, message: 'Please enter gender!' }]">
                  <a-select v-model:value="formState.gender" allowClear="" class="w-full" :options="genderList">
                  </a-select>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Email" name="email" :rules="[{type: 'email', message: 'The input is not valid email!',}]">
                  <a-input v-model:value="formState.email"></a-input>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Phone" name="phone" :rules="[{ required: true, message: 'Please enter phone!' }]">
                  <a-input v-model:value="formState.phone" class="w-full"></a-input>
                </a-form-item>
              </a-col>
              <a-col :span="12">
                <a-form-item label="Note" name="note">
                  <a-textarea class="!rounded-none w-full" v-model:value="formState.note"
                              :auto-size="{ minRows: 2, maxRows: 10 }"/>
                </a-form-item>
              </a-col>
              <a-col :span="12">
                <a-form-item label="Data" name="unify_data">
                  <a-textarea class="!rounded-none w-full" v-model:value="formState.unify_data"
                              :auto-size="{ minRows: 2, maxRows: 10 }"/>
                </a-form-item>
              </a-col>
              <a-col :span="24">
                <a-form-item label="Gallery" name="unify_data">
                  <InputUpload :multiple="true" v-model:value="formState.images"></InputUpload>
                </a-form-item>
              </a-col>
            </a-row>
          </a-tab-pane>
          <a-tab-pane key="2" tab="Address">
            <a-row :gutter="20">
              <a-col :span="8">
                <a-form-item label="Street" name="street" :rules="[{ required: true, message: 'Please enter street!' }]">
                  <a-input v-model:value="formState.street" class="w-full"></a-input>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Apt" name="apt" :rules="[{ message: 'Please enter apt!' }]">
                  <a-input v-model:value="formState.apt" class="w-full"></a-input>
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
              <a-col :span="8">
                <a-form-item label="Zip" name="zip" :rules="[{ required: true, message: 'Please enter zip!' }]">
                  <a-input-number v-model:value="formState.zip" class="!w-full"></a-input-number>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Route" name="route" :rules="[{ message: 'Please enter route!' }]">
                  <a-input v-model:value="formState.route" class="w-full"></a-input>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Sub-r" name="sub_r" :rules="[{ message: 'Please enter sub-r!' }]">
                  <a-input v-model:value="formState.sub_r" class="w-full"></a-input>
                </a-form-item>
              </a-col>
            </a-row>
          </a-tab-pane>
          <a-tab-pane key="3" tab="Insurance">
            <a-row :gutter="20">
              <a-col :span="24">
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
              </a-col>
            </a-row>
          </a-tab-pane>
          <a-tab-pane key="4" tab="Doctor">
            <a-row :gutter="20">
              <a-col :span="12">
                <a-form-item label="Doctor" name="doctor">
                  <a-select v-model:value="formState.doctor_id" allowClear="" class="w-full" showSearch
                            placeholder="Select a doctor">
                    <a-select-option v-for="(doctor, index) in listDoctors" :key="doctor.value" :value="parseInt(doctor.value)">{{
                      doctor.label
                      }}
                    </a-select-option>
                  </a-select>
                </a-form-item>
              </a-col>
              <a-col :span="12">
                <a-form-item label="Doctor status" name="doctor_status">
                  <a-select v-model:value="formState.doctor_status" allowClear="" class="w-full"
                            placeholder="Select a status">
                    <a-select-option v-for="(status, index) in listDoctorStatus" :key="status.value" :value="status.value">{{
                      status.label
                      }}
                    </a-select-option>
                  </a-select>
                </a-form-item>
              </a-col>
              <a-col :span="24">
                <a-form-item label="Doctor note" name="doctor_comment">
                  <a-textarea class="!rounded-none w-full" v-model:value="formState.doctor_comment"
                              placeholder="Doctor note"
                              :auto-size="{ minRows: 2, maxRows: 10 }"/>
                </a-form-item>
              </a-col>
            </a-row>
          </a-tab-pane>
          <a-tab-pane key="5" tab="Products" v-if="formState.id > 0">
            <a-row :gutter="20">
              <a-col :span="6">
                <a-form-item label="Delivery date" name="delivery_date" required>
                  <a-date-picker v-model:value="formState.delivery_date" valueFormat="YYYY-MM-DD" format="MM-DD-YYYY" class="w-full"></a-date-picker>
                </a-form-item>
              </a-col>
            </a-row>
            <div class="flex flex-row justify-end">
              <a-button @click="addProduct">Add Product</a-button>
            </div>
            <div class="mt-5">
              <a-table :data-source="formState.products" :columns="productColumns" bordered :pagination="false" triped>
                <template #bodyCell="{ column, record }">
                  <template v-if="column.key === 'id'">
                    <div class="flex flex-row items-center">
                      <img class="w-20 h-auto float-left" :src="record.image_url" :alt="record.name" v-if="record.image_url"/>
                      <img class="w-20 h-auto float-left" src="/src/assets/no_image_available.png" v-else/>
                      <label class="ml-4 font-semibold">{{record.name}}</label>
                    </div>
                  </template>

                  <template v-if="column.key === 'slug'">
                    /{{ record.slug }}
                  </template>

                  <template v-if="column.key === 'status'">
                    <a-tag v-if="record.status == 'A'" color="green">Active</a-tag>
                    <a-tag v-if="record.status == 'D'" color="green">Inactive</a-tag>
                  </template>
                  <template v-if="column.key === 'action'">
                    <a-popconfirm title="Do you want delete this?" ok-text="Yes" cancel-text="No" @confirm="delProduct(record)">
                      <a-button type="text" danger :icon="h(DeleteOutlined)" label="" :outline="true">
                      </a-button>
                    </a-popconfirm>
                  </template>
                </template>
              </a-table>
            </div>
          </a-tab-pane>
        </a-tabs>
      </div>
      <a-input v-model:value="formState.unify_process" name="unify_process" type="hidden"></a-input>
      <a-input v-model:value="formState.sale_user" name="sale_user" type="hidden"></a-input>
      <a-space align="center" class="pt-5">
        <a-button
          v-if="auth.hasPermission('Patient.EligibilityCheckApprove') && formState.unify_process == 0"
          @click="approval('eligibility_check')" type="primary" class="!bg-green-500 hover:!bg-green-400">
          Approve
        </a-button>
        <a-button type="primary" html-type="submit">Submit</a-button>
        <a-button type="primary" ghost @click="back()">Back</a-button>
      </a-space>
    </a-form>
  </LayoutAuthenticated>

  <a-modal append-to-body v-model:open="showDetail" :zIndex="10" width="70%" title="Select Product" :closable="true" :footer="null" :maskClosable="false">
    <ProductList @close="showDetail = false" @select="onSelectProduct"></ProductList>
  </a-modal>

</template>

<style>
  .ant-input {
    border-color: #d9d9d9 !important;
    border-radius: 5px !important;
  }
</style>
