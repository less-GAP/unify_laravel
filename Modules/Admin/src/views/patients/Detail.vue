<script lang="ts" setup>
  import {reactive, h, ref, toRaw, computed, watch} from "vue";
  import {mdiBackspace, mdiContentSave, mdiAccountArrowUp, mdiTrashCanOutline} from '@mdi/js';
  import {BaseIcon, InputUpload, InputUploadGetPath} from "@/components";
  import {ApiData} from "@/components";
  import {notification, message} from "ant-design-vue";
  import router from "@/router";
  import {useAuthStore} from "@/stores/auth";
  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
  import {getProcess} from "@/utils/Patient";
  import InsuranceListEdit from "@/components/InsuranceListEdit.vue";
  import dayjs from 'dayjs';
  import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
  import Api from "@/utils/Api";
  import {DeleteOutlined, FormOutlined} from "@ant-design/icons-vue";

  import ProductList from "./components/ProductList.vue";
  import InsuranceList from "./components/InsuranceList.vue";


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

  const formRef = ref();
  const formState = ref({
    unify_process: 0,
    products: [],
    insurances: []
  });

  const formRefLog = ref();
  const formStateLog = ref({
    log_detail: ''
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
        if (!formState.value.products) {
          formState.value.products = [];
        }
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
        if(formState.value.id > 0){
          showLog.value = true;
        }

        // Api.post(prefix, toRaw(formState.value)).then(rs => {
        //   if (rs.data.code == 1) {
        //     back();
        //   }
        // });
      }).catch(error => {
      //console.log(error);

    });
  };

  const onFinishFailed = (errorInfo: any) => {
    //console.log('Failed:', errorInfo);
    message.error('Please input required!');
  };


  // Submit when seller approve profile from Waiting to Eligibility Check
  const approval = (step) => {
    formRef.value
      .validate()
      .then(() => {
        //console.log(formState.value);
        if (step == 'eligibility_check') {
          formState.value.unify_process = 1;
        }
        showLog.value = true;
      })
  };

  const showLog = ref(false);

  const handleCancelLog = () => {
    showLog.value = false;
  };

  const handleOkLog = () => {
    formRefLog.value
      .validate()
      .then(() => {
        formRef.value.log_detail = formStateLog.log_detail;
        Api.post(prefix, toRaw(formState.value)).then(rs => {
          if (rs.data.code == 1) {
            //back();
          }
        });
      });
  };

  const showProductDetail = ref(false);

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
    },
    {
      title: "Delivery date",
      dataIndex: 'delivery_date',
      key: "delivery_date",
      width: 150,
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
    showProductDetail.value = false;
  }

  const addProduct = () => {
    selectProduct.value = {};
    showProductDetail.value = true;
  };

  const editProduct = (item) => {
    selectProduct.value = item;
    showProductDetail.value = true;
  };

  const delProduct = (item) => {
    formState.value.products.forEach((value, index) => {
      if (value.id == item.id) {
        formState.value.products.splice(index, 1);
      }
    });
  };

  const showInsuranceDetail = ref(false);

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
    {
      title: 'Action',
      dataIndex: 'action',
      key: 'action',
      width: 100
    },
  ]);

  const selectInsurance = ref({});
  const onSelectInsurance = (item) => {
    var add = true;
    formState.value.insurances.forEach((value, index) => {
      if (value.id == item.id) {
        formState.value.insurances[index] = item;
        add = false;
      }
    });
    if (add) {
      formState.value.insurances.push(item);
    }
    //console.log(formState.value.insurances);
    showInsuranceDetail.value = false;
  }

  const addInsurance = () => {
    selectInsurance.value = {};
    showInsuranceDetail.value = true;
  };

  const editInsurance = (item) => {
    selectInsurance.value = item;
    showInsuranceDetail.value = true;
  };

  const delInsurance = (item) => {
    formState.value.insurances.forEach((value, index) => {
      if (value.id == item.id) {
        formState.value.insurances.splice(index, 1);
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
    <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit" @finishFailed="onFinishFailed">
      <div class="mx-4">
        <a-tabs v-model:activeKey="activeKey" @change="tabActive">
          <a-tab-pane key="1" tab="General">
            <a-divider orientation="left">Infomation</a-divider>
            <a-row :gutter="20" v-if="false">
              <a-col :span="8">
                <a-form-item label="Status"
                             :rules="[{ required: true }]">
                  <a-select v-model:value="formState.unify_status" allowClear="" class="w-full" :options="[
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
              <a-col :span="8">
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
                             :rules="[{ required: true, message: 'Please enter ID number!' }]">
                  <a-input v-model:value="formState.unify_number" placeholder="ID Number..."/>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="First Name" name="first_name"
                             :rules="[{ required: true, message: 'Please enter first name!' }]">
                  <a-input v-model:value="formState.first_name" placeholder="First Name..."/>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Last Name" name="last_name"
                             :rules="[{ required: true, message: 'Please enter last name!' }]">
                  <a-input v-model:value="formState.last_name" placeholder="Last Name..."/>
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
                                 class="w-full" placeholder="Date of Birth..."></a-date-picker>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Gender" name="gender" :rules="[{ required: true, message: 'Please enter gender!' }]">
                  <a-select v-model:value="formState.gender" allowClear="true" class="w-full" :options="[
                    {
                      value: 0,
                      label: 'Male'
                    },
                    {
                      value: 1,
                      label: 'Female'
                    }
                  ]" placeholder="Gender...">
                  </a-select>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Phone" name="phone" :rules="[{ required: true, message: 'Please enter phone!' }]">
                  <a-input v-model:value="formState.phone" class="w-full" placeholder="Phone..."></a-input>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Email" name="email" :rules="[{type: 'email', message: 'The input is not valid email!',}]">
                  <a-input v-model:value="formState.email" placeholder="Email..."></a-input>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Note" name="note">
                  <a-textarea class="!rounded-none w-full" v-model:value="formState.note"
                              :auto-size="{ minRows: 2, maxRows: 10 }" placeholder="Note..."/>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Data" name="unify_data">
                  <a-textarea class="!rounded-none w-full" v-model:value="formState.unify_data"
                              :auto-size="{ minRows: 2, maxRows: 10 }" placeholder="Data..."/>
                </a-form-item>
              </a-col>
              <a-col :span="24">
                <a-form-item label="Gallery" name="unify_data">
                  <InputUpload v-model:value="formState.images"></InputUpload>
                </a-form-item>
              </a-col>
            </a-row>
            <a-divider orientation="left">Address</a-divider>
            <a-row :gutter="20">
              <a-col :span="8">
                <a-form-item label="Street" name="street" :rules="[{ required: true, message: 'Please enter street!' }]">
                  <a-input v-model:value="formState.street" class="w-full" placeholder="Street..."></a-input>
                </a-form-item>
              </a-col>

              <a-col :span="8">
                <a-form-item label="City" name="city" :rules="[{ required: true, message: 'Please enter city!' }]">
                  <a-input v-model:value="formState.city" class="w-full" placeholder="City..."></a-input>
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
                  <a-input-number v-model:value="formState.zip" class="!w-full" placeholder="Zip..."></a-input-number>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Apt" name="apt" :rules="[{ message: 'Please enter apt!' }]">
                  <a-input v-model:value="formState.apt" class="w-full" placeholder="Apt..."></a-input>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Route" name="route" :rules="[{ message: 'Please enter route!' }]">
                  <a-input v-model:value="formState.route" class="w-full" placeholder="Route..."></a-input>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Sub-r" name="sub_r" :rules="[{ message: 'Please enter sub-r!' }]">
                  <a-input v-model:value="formState.sub_r" class="w-full" placeholder="Sub-r..."></a-input>
                </a-form-item>
              </a-col>
            </a-row>
          </a-tab-pane>
          <a-tab-pane key="2" tab="Insurance">
            <div class="flex flex-row justify-end">
              <a-button @click="addInsurance">Add Insurance</a-button>
            </div>
            <div class="mt-5">
              <a-table :data-source="formState.insurances" :columns="insuranceColumns" bordered :pagination="false" triped>
                <template #bodyCell="{ column, record }">
                  <template v-if="column.key === 'coverage'">
                    <div class="flex flex-row items-center">
                      <!--                        <img class="w-20 h-auto float-left" :src="record.product.image_url" :alt="record.product.name" v-if="record.product.image_url"/>-->
                      <!--                        <img class="w-20 h-auto float-left" src="/src/assets/no_image_available.png" v-else/>-->
                      <label class="ml-4 font-semibold">{{record.coverage.label}}</label>
                    </div>
                  </template>
                  <template v-if="column.key === 'active_date'">
                    {{dayjs(record.active_date, "YYYY-MM-DD").format("MM-DD-YYYY" )}}
                  </template>
                  <template v-if="column.key === 'expired_date'">
                    {{dayjs(record.expired_date, "YYYY-MM-DD").format("MM-DD-YYYY" )}}
                  </template>
                  <template v-if="column.key === 'action'">
                    <a-button type="text" :icon="h(FormOutlined)" label="" :outline="true" @click="editInsurance(record)"></a-button>
                    <a-popconfirm title="Do you want delete this?" ok-text="Yes" cancel-text="No" @confirm="delInsurance(record)">
                      <a-button type="text" danger :icon="h(DeleteOutlined)" label="" :outline="true">
                      </a-button>
                    </a-popconfirm>
                  </template>
                </template>
              </a-table>
            </div>
          </a-tab-pane>
          <a-tab-pane key="3" tab="Doctor">
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
          <a-tab-pane key="4" tab="Products" v-if="formState.id > 0">
            <div class="flex flex-row justify-end">
              <a-button @click="addProduct">Add Product</a-button>
            </div>
            <div class="mt-5">
              <a-table :data-source="formState.products" :columns="productColumns" bordered :pagination="false" triped>
                <template #bodyCell="{ column, record }">
                  <template v-if="column.key === 'id'">
                    <div class="flex flex-row items-center">
                      <img class="w-20 h-auto float-left" :src="record.product.image_url" :alt="record.product.name" v-if="record.product.image_url"/>
                      <img class="w-20 h-auto float-left" src="/src/assets/no_image_available.png" v-else/>
                      <label class="ml-4 font-semibold">{{record.product.name}}</label>
                    </div>
                  </template>
                  <template v-if="column.key === 'delivery_date'">
                    {{dayjs(record.delivery_date, "YYYY-MM-DD").format("MM-DD-YYYY" )}}
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
          </a-tab-pane>
        </a-tabs>
        <a-space align="center" class="pt-5">
          <a-button
            v-if="auth.hasPermission('Patient.EligibilityCheckApprove') && formState.unify_process == 0 && formState.id > 0"
            @click="approval('eligibility_check')" type="primary" class="!bg-green-500 hover:!bg-green-400">
            Approve
          </a-button>
          <a-button type="primary" html-type="submit">Submit</a-button>
          <a-button type="primary" ghost @click="back()">Back</a-button>
        </a-space>
      </div>
    </a-form>
  </LayoutAuthenticated>

  <a-modal append-to-body v-model:open="showProductDetail" :zIndex="10" width="50%" title="Select Product" :closable="true" :footer="null" :maskClosable="false">
    <ProductList :value="selectProduct" @close="showProductDetail = false" @select="onSelectProduct" :key="selectProduct.product ? selectProduct.product.id : 0"></ProductList>
  </a-modal>

  <a-modal append-to-body v-model:open="showInsuranceDetail" :zIndex="10" width="50%" title="Select Insurance" :closable="true" :footer="null" :maskClosable="false">
    <InsuranceList :value="selectInsurance" @close="showInsuranceDetail = false" @select="onSelectInsurance" :key="selectInsurance.product ? selectInsurance.product.id : 0"></InsuranceList>
  </a-modal>


  <a-modal v-model:open="showLog" title="Confirm for change" width="50%" :closable="true" :maskClosable="false">
    <a-form layout="vertical" ref="formRefLog" :model="formStateLog" @finish="handleOkLog">
      <a-row :gutter="20">
        <a-col :span="24">
          <a-form-item label="Note for this change" name="log_detail"
                       :rules="[{ required: true, message: 'Please enter note...' }]">
            <a-textarea class="!rounded-none w-full" v-model:value="formStateLog.log_detail"
                        placeholder="Make a note of any changes you make to the patient record"
                        :auto-size="{ minRows: 4, maxRows: 10 }"/>
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="Task Status" v-if="false">
            <ApiData url="master-data/task-status" method="GET" :params="{}">
              <template #default="{ data }">
                <a-select class="w-[200px]" v-model:value="formStateLog.unify_task_status"
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
    </a-form>
    <template #footer>
      <a-button key="back" @click="handleCancelLog">Back</a-button>
      <a-button key="submit" type="primary" :loading="loading" @click="handleOkLog">Submit</a-button>
    </template>
  </a-modal>

</template>

<style>
  .ant-input {
    border-color: #d9d9d9 !important;
    border-radius: 5px !important;
  }
</style>
