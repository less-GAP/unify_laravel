<script lang="ts" setup>
  import {reactive, h, ref, toRaw, computed, watch} from "vue";

  import {mdiBackspace, mdiContentSave, mdiAccountArrowUp} from '@mdi/js';

  import router from "@/router";

  import Api from "@/utils/Api";

  import {useAuthStore} from "@/stores/auth";

  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";

  import listStates from "@/utils/States";

  import SectionMain from "@/components/SectionMain.vue";

  import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";

  import 'jodit/es5/jodit.css';

  import {JoditEditor, Jodit} from 'jodit-vue';

  import {message, notification, SelectProps} from 'ant-design-vue';

  import {InputUpload, InputUploadGetPath, FilePicker, InputTags, RemoteSelect, BaseIcon, ApiData} from "@/components";

  import ProductList from "./components/ProductList.vue";

  import Patient from "./components/Patient.vue";

  import Shipper from "./components/Shipper.vue";

  import dayjs from "dayjs";

  import {PlusOutlined, LoadingOutlined, DeleteOutlined, FormOutlined} from '@ant-design/icons-vue';

  const auth = useAuthStore();

  const prefix = 'order'

  const {
    fetchDetailApi,
    createApi,
  } = UseEloquentRouter(prefix)

  const loading = ref(false);

  const activeKey = ref('1');

  const isShowModal = ref(false);

  const showPicker = ref(false);

  const formRef = ref();

  const formState = ref({
    status: 'new',
    products: [],
    patient: {}
  });


  const fetch = function () {
    loading.value = true;
    var id = router.currentRoute.value.params.id;
    if (id > 0) {
      loading.value = true
      Api.get(prefix + '/' + id).then(rs => {
        formState.value = rs.data.data;
        loading.value = false;
      });
    } else {
      loading.value = false;
    }
  }
  fetch();

  const submit = () => {
    formRef.value
      .validate()
      .then(() => {
        //check
        if (formState.value.products.length == 0) {
          message.error('Please select product!');
          return false;
        }

        if (!formState.value.patient_id) {
          message.error('Please select patient!');
          return false;
        }

        Api.post(prefix, toRaw(formState.value)).then(rs => {
          // notification[rs.data.code == 0 ? 'error' : 'success']({
          //   message: 'Notification',
          //   description: rs.data.message,
          // });
          if (rs.data.code == 1) {
            formRef.value.resetFields();
            back();
          }
        });
      });
  };

  const productColumns = ref([
    {
      title: '#',
      dataIndex: 'id',
      key: 'id',
      width: 50,
      render: (id, record, index) => {
        ++index;
        return index;
      },
    },
    {
      title: 'Product',
      dataIndex: 'product_id',
      key: 'product_id',
    },
    {
      title: 'Supplier',
      dataIndex: 'supplier_id',
      key: 'supplier_id',
    },
    {
      title: 'Trademark',
      dataIndex: 'trademark_id',
      key: 'trademark_id',
    },
    {
      title: 'Amount',
      dataIndex: 'amount',
      key: 'amount',
      className: 'text-center',
      width: 100
    },
    {
      title: 'Action',
      dataIndex: 'action',
      key: 'action',
      width: 100
    },
  ]);

  const showProduct = ref(false);
  const selectProduct = ref({});
  const onSelectProduct = (item) => {
    //console.log(item);
    if (item.id) {
      formState.value.products.forEach((value, index) => {
        if (value.id == item.id) {
          formState.value.products[index] = item;
        }
      });
    } else {
      item.id = formState.value.products.length + 1;
      formState.value.products.push(item);
    }
    showProduct.value = false;
  }

  const addProduct = () => {
    selectProduct.value = {};
    showProduct.value = true;
  };

  const editProduct = (item) => {
    selectProduct.value = item;
    showProduct.value = true;
  };

  const delProduct = (item) => {
    formState.value.products.forEach((value, index) => {
      if (value.id == item.id) {
        formState.value.products.splice(index, 1);
      }
    });
  };

  const showPatient = ref(false);
  const selectPatient = ref({});
  const onSelectPatient = (item) => {
    //console.log(item);
    formState.value.patient = {
      unify_number: item.unify_number,
      full_name: item.full_name,
      patient_id: item.patient_id,
      apt: item.apt,
      city: item.city,
      phone: item.phone,
      email: item.email,
      route: item.route,
      state: item.state,
      street: item.street,
      sub_r: item.sub_r,
      zip: item.zip,
    };
    formState.value.apt = item.apt;
    formState.value.city = item.city;
    formState.value.phone = item.phone;
    formState.value.email = item.email;
    formState.value.patient_id = item.patient_id;
    formState.value.route = item.route;
    formState.value.state = item.state;
    formState.value.street = item.street;
    formState.value.sub_r = item.sub_r;
    formState.value.zip = item.zip;
    showPatient.value = false;
  }

  const addPatient = () => {
    selectPatient.value = {};
    showPatient.value = true;
  };

  const editPatient = (item) => {
    selectPatient.value = item;
    showPatient.value = true;
  };

  const showShipper = ref(false);
  const selectShipper = ref({});
  const onSelectShipper = (item) => {
    formState.value.shipper = {
      shipping_id: item.shipping_id,
      shipper_phone: item.shipper_phone,
      shipper_email: item.shipper_email,
    };
    formState.value.shipper_name = item.shipper_name;
    formState.value.shipper_phone = item.shipper_phone;
    formState.value.shipper_email = item.shipper_email;
    formState.value.shipping_id = item.shipping_id;
    showShipper.value = false;
  }

  const addShipper = () => {
    selectShipper.value = {};
    showShipper.value = true;
  };

  const editShipper = (item) => {
    selectShipper.value = item;
    showShipper.value = true;
  };


  const back = () => {
    router.push('/' + prefix);
  };


</script>
<template>
  <LayoutAuthenticated>
    <SectionMain>
      <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit" class="w-full">
        <div class="mx-4">
          <a-row :gutter="20">
            <a-col :span="16">
              <a-divider orientation="left">Product Info</a-divider>
              <div class="flex flex-row justify-start">
                <a-button @click="addProduct">Add Product</a-button>
              </div>
              <div class="mt-5">
                <a-table :data-source="formState.products" :columns="productColumns" bordered :pagination="false" triped>
                  <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'product_id'">
                      <div v-if="record.product" class="flex flex-row items-center">
                        <img class="w-20 h-auto float-left" :src="record.product.image_url" :alt="record.product.name" v-if="record.product.image_url"/>
                        <img class="w-20 h-auto float-left" src="/src/assets/no_image_available.png" v-else/>
                        <label class="ml-4 font-semibold">{{record.product.name}}</label>
                      </div>
                    </template>
                    <template v-if="column.key === 'supplier_id'">
                      <div v-if="record.supplier" class="flex flex-row items-center">
                        <img class="w-20 h-auto float-left" :src="record.supplier.image_url" :alt="record.supplier.name" v-if="record.supplier.image_url"/>
                        <img class="w-20 h-auto float-left" src="/src/assets/no_image_available.png" v-else/>
                        <label class="ml-4 font-semibold">{{record.supplier.name}}</label>
                      </div>
                    </template>
                    <template v-if="column.key === 'trademark_id'">
                      <div v-if="record.trademark" class="flex flex-row items-center">
                        <img class="w-20 h-auto float-left" :src="record.trademark.image_url" :alt="record.trademark.name" v-if="record.trademark.image_url"/>
                        <img class="w-20 h-auto float-left" src="/src/assets/no_image_available.png" v-else/>
                        <label class="ml-4 font-semibold">{{record.trademark.name}}</label>
                      </div>
                    </template>
                    <template v-if="column.key === 'expiration_date'">
                      {{dayjs(record.expiration_date, "YYYY-MM-DD").format("MM-DD-YYYY" )}}
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
            </a-col>
            <a-col :span="8">
              <a-divider orientation="left">Order Info</a-divider>
              <a-row :gutter="20">
                <a-col :span="24">
                  <a-form-item label="Status" :rules="[{ required: true, message: 'Please select !' }]">
                    <a-select v-model:value="formState.status" allowClear="" class="w-full" :options="[
                {
                  value: 'new',
                  label: 'New'
                },
                {
                  value: 'confirmed',
                  label: 'Confirmed'
                },
                {
                  value: 'delivering',
                  label: 'Delivering'
                },
                {
                  value: 'delivered',
                  label: 'Delivered'
                },
                {
                  value: 'done',
                  label: 'Done'
                },

              ]">
                    </a-select>
                  </a-form-item>
                  <a-form-item label="Delivery Date" name="delivery_date" :rules="[{ required: true, message: 'Please input !' }]">
                    <a-date-picker
                      v-model:value="formState.delivery_date"
                      input-read-only
                      value-format="YYYY-MM-DD"
                      format="MM-DD-YYYY"
                      class="w-[150px]"
                    ></a-date-picker>
                  </a-form-item>
                </a-col>
              </a-row>
              <a-divider orientation="left">Patient Info</a-divider>
              <div class="flex flex-row justify-start">
                <a-button @click="addPatient" v-if="!formState.patient_id">Add Patient</a-button>
              </div>
              <div v-if="formState.patient_id" class="flex flex-row justify-between">
                <div>
                  <span><b>#{{formState.patient.unify_number}} - {{formState.patient.full_name}}</b></span><br/>
                  <span v-if="formState.phone">{{formState.phone}}</span><br/>
                  <span v-if="formState.email">{{formState.email}}</span><br v-if="formState.email"/>
                  <span v-if="formState.street">{{formState.street}}, {{formState.city}}, {{formState.state}}, {{formState.zip}}</span>
                </div>
                <div>
                  <a-button @click="editPatient(formState.patient)" :size="small">
                    <template #icon>
                      <FormOutlined/>
                    </template>
                  </a-button>
                </div>
              </div>
              <a-divider orientation="left">Shipping Info</a-divider>
              <div class="flex flex-row justify-start">
                <a-button @click="addShipper" v-if="!formState.shipping_id">Add Shipper</a-button>
              </div>
              <div v-if="formState.shipping_id" class="flex flex-row justify-between">
                <div>
                  <span><b>{{formState.shipper_name}}</b></span><br/>
                  <span v-if="formState.shipper_phone">{{formState.shipper_phone}}</span><br/>
                  <span v-if="formState.shipper_email">{{formState.shipper_email}}</span>
                </div>
                <div>
                  <a-button @click="editShipper(formState.shipper)" :size="small">
                    <template #icon>
                      <FormOutlined/>
                    </template>
                  </a-button>
                </div>
              </div>
            </a-col>
          </a-row>
          <a-space align="center" class="pt-5">
            <a-button type="primary" html-type="submit">Submit</a-button>
            <a-button type="primary" ghost @click="back()">Back</a-button>
          </a-space>
        </div>
      </a-form>
    </SectionMain>
  </LayoutAuthenticated>

  <a-modal append-to-body v-model:open="showPicker" style="z-index:99999;top: 2vh;height:98vh" height="96vh" width="90vw"
           title="Select file">
    <FilePicker :multiple="true" @close="showPicker = false" @select="onSelectImage"></FilePicker>
    <template #footer>
    </template>
  </a-modal>

  <a-modal append-to-body v-model:open="showProduct" :zIndex="10" width="40%" title="Select Product" :closable="true" :footer="null" :maskClosable="false">
    <ProductList :value="selectProduct" @close="showProduct = false" @select="onSelectProduct" :key="selectProduct.id ? selectProduct : 0"></ProductList>
  </a-modal>

  <a-modal append-to-body v-model:open="showPatient" :zIndex="10" width="60%" title="Select Patient" :closable="true" :footer="null" :maskClosable="false">
    <Patient :value="selectPatient" @close="showPatient = false" @select="onSelectPatient" :key="selectPatient.id ? selectPatient : 0"></Patient>
  </a-modal>

  <a-modal append-to-body v-model:open="showShipper" :zIndex="10" width="40%" title="Select Shipper" :closable="true" :footer="null" :maskClosable="false">
    <Shipper :value="selectShipper" @close="showShipper = false" @select="onSelectShipper" :key="selectShipper.id ? selectShipper : 0"></Shipper>
  </a-modal>

</template>

<style>

</style>
