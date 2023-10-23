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

  import {notification, SelectProps} from 'ant-design-vue';

  import {InputUpload, InputUploadGetPath, FilePicker, InputTags, RemoteSelect, BaseIcon, ApiData} from "@/components";

  import ProductList from "./ProductList.vue";

  import dayjs from "dayjs";

  import {PlusOutlined, LoadingOutlined, DeleteOutlined, FormOutlined} from '@ant-design/icons-vue';

  const auth = useAuthStore();

  const prefix = 'inventory'

  const {
    fetchDetailApi,
    createApi,
  } = UseEloquentRouter(prefix)

  const loading = ref(false);

  const activeKey = ref('1');

  const isShowModal = ref(false);

  const showPicker = ref(false);

  const showDetail = ref(false);

  const formRef = ref();

  const formState = ref({
    type: 'in',
    products: []
  });

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
      title: 'Expiration Date',
      dataIndex: 'expiration_date',
      key: 'expiration_date',
      width: 150
    },
    {
      title: 'Action',
      dataIndex: 'action',
      key: 'action',
      width: 100
    },
  ]);

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
        Api.post(prefix, toRaw(formState.value)).then(rs => {
          // notification[rs.data.code == 0 ? 'error' : 'success']({
          //   message: 'Notification',
          //   description: rs.data.message,
          // });
          if (rs.data.code == 1) {
            back();
          }
        });
      });
  };
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
    showDetail.value = false;
  }

  const addProduct = () => {
    selectProduct.value = {};
    showDetail.value = true;
  };

  const edit = (item) => {
    selectProduct.value = item;
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
    <SectionMain>
      <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit" class="w-full">
        <div class="mx-4">
          <a-tabs v-model:activeKey="activeKey" @change="tabActive">
            <a-tab-pane key="1" tab="General">
              <a-row :gutter="20">
                <a-col :span="24">
                  <a-form-item label="Note for this change" name="log_detail" :rules="[{ required: true, message: 'Please enter note!' }]" v-if="formState.id > 0">
                    <a-textarea class="!rounded-none w-full" v-model:value="formState.log_detail"
                                placeholder="Make a note of any changes you make to the doctor record"
                                :auto-size="{ minRows: 2, maxRows: 10 }"/>
                  </a-form-item>
                </a-col>
              </a-row>
              <a-row :gutter="20">
                <a-col :span="24">
                  <a-form-item label="Type" name="type" :rules="[{ required: true, message: 'Select a type!' }]">
                    <a-radio-group v-model:value="formState.type" button-style="solid" size="middle">
                      <a-radio-button value="in">In</a-radio-button>
                      <a-radio-button value="out">Out</a-radio-button>
                    </a-radio-group>
                  </a-form-item>
                </a-col>
              </a-row>
              <a-row :gutter="20">
                <a-col :span="12">
                  <a-form-item label="Date" name="date" :rules="[{ required: true, message: 'Please input !' }]">
                    <a-date-picker
                      v-model:value="formState.date"
                      input-read-only
                      value-format="YYYY-MM-DD"
                      format="MM-DD-YYYY"
                      class="w-[150px]"
                    ></a-date-picker>
                  </a-form-item>
                </a-col>
              </a-row>
              <a-row :gutter="20">
                <a-col :span="12">
                  <a-form-item label="Code" name="code" :rules="[{ required: true, message: 'Please input !' }]">
                    <a-input v-model:value="formState.code" placeholder="Code..."/>
                  </a-form-item>
                  <a-form-item label="Supplier" name="supplier_id" :rules="[{ required: true, message: 'Please input !' }]">
                    <ApiData url="supplier/all" method="GET" :params="{}">
                      <template #default="{ data }">
                        <a-select class="w-[200px]" v-model:value="formState.supplier_id"
                                  placeholder="Select product..." option-label-prop="children">
                          <template v-for="(value, key) in data" :key="key">
                            <a-select-option :value="value.id" :label="value.name">
                              <img :src="value.image_url" class="w-5 h-5 inline-block"/>&nbsp;&nbsp;&nbsp;&nbsp;{{value.name}}
                            </a-select-option>
                          </template>
                        </a-select>
                      </template>
                    </ApiData>
                  </a-form-item>
                </a-col>
              </a-row>
              <a-row :gutter="20">
                <a-col :span="24">
                  <a-form-item style="width:100%" label="File">
                    <InputUploadGetPath autocomplete="off" v-model:value="formState.file" :accept="'application/pdf'"></InputUploadGetPath>
                  </a-form-item>
                  <a-form-item label="Description">
                    <a-textarea v-model:value="formState.description" placeholder="Description..." :rows="4"/>
                  </a-form-item>
                </a-col>
              </a-row>
            </a-tab-pane>
            <a-tab-pane key="2" tab="Products">
              <div class="flex flex-row justify-end">
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
                      <a-button type="text" :icon="h(FormOutlined)" label="" :outline="true" @click="edit(record)"></a-button>
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

  <a-modal append-to-body v-model:open="showDetail" :zIndex="10" width="40%" title="Select Product" :closable="true" :footer="null" :maskClosable="false">
    <ProductList :value="selectProduct" @close="showDetail = false" @select="onSelectProduct" :key="selectProduct.id ? selectProduct : 0"></ProductList>
  </a-modal>
</template>

<style>

</style>
