<script lang="ts" setup>
  import {reactive, h, ref, toRaw, computed, watch} from "vue";

  import {mdiBackspace, mdiContentSave, mdiAccountArrowUp} from '@mdi/js';

  import router from "@/router";

  import Api from "@/utils/Api";

  import {useAuthStore} from "@/stores/auth";

  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";

  import listStates from "@/utils/States";

  import 'jodit/es5/jodit.css';

  import {JoditEditor, Jodit} from 'jodit-vue';

  import {notification, SelectProps} from 'ant-design-vue';

  import {InputUpload, InputUploadGetPath, FilePicker, InputTags, RemoteSelect, BaseIcon, ApiData} from "@/components";

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

  const formRef = ref();

  const emit = defineEmits(["close"]);

  const props = defineProps({
    value: {
      type: Object,
      default: {}
    },
  });

  const formState = ref({
    type: 'in'
  });


  if (props.value.id > 0) {
    formState.value = props.value;
  } else {
    formState.value = {
      type: 'in'
    };
  }

  //console.log(props.value);

  //formState.value = props.value;

  // watch(() => props.id, async () => {
  //   alert('okie');
  //   //console.log(props.value);
  //   //formState.value = props.value
  // })

  const submit = () => {
    formRef.value
      .validate()
      .then(() => {
        Api.post(prefix, toRaw(formState.value)).then(rs => {
          notification[rs.data.code == 0 ? 'error' : 'success']({
            message: 'Notification',
            description: rs.data.message,
          });
          if (rs.data.code == 1) {
            emit('close');
          }
        });
      });
  };

  const closeDetail = function () {
    emit('close');
  }

</script>
<template>
  <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit" class="w-full">
    <div class="p-3 bg-gray-200">
      <a-button class="!hidden md:!inline-block" type="link" @click="closeDetail">
        <template #icon>
          <div class="flex">
            <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500"/>
            <span class="ml-1 text-stone-500">Back</span>
          </div>
        </template>
      </a-button>
      <a-button class="!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0" type="link"
                @click="closeDetail">
        <template #icon>
          <div class="flex">
            <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500"/>
            <span class="ml-1 text-stone-500">Back</span>
          </div>
        </template>
      </a-button>
      <a-space class="float-right">
        <a-button @click="submit()" type="primary">
          <div class="flex">
            <BaseIcon :path="mdiContentSave" class="w-4 text-white"/>
            <span class="ml-1 text-white">Save</span>
          </div>
        </a-button>
      </a-space>
    </div>
    <div class="mx-4">
      <a-tabs v-model:activeKey="activeKey">
        <a-tab-pane key="1" tab="General">
          <a-row :gutter="20">
            <a-col :span="24">
              <a-form-item label="Type" name="type" :rules="[{ required: true, message: 'Select a type!' }]">
                <a-radio-group v-model:value="formState.type" button-style="solid" size="middle">
                  <a-radio-button value="in">In</a-radio-button>
                  <a-radio-button value="out">Out</a-radio-button>
                </a-radio-group>
              </a-form-item>
            </a-col>
            <a-col :span="12">
              <a-form-item label="Product" name="product_id" :rules="[{ required: true, message: 'Please input !' }]">
                <ApiData url="product/all" method="GET" :params="{}">
                  <template #default="{ data }">
                    <a-select class="w-[200px]" v-model:value="formState.product_id"
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
              <a-form-item label="Trademark" name="trademark_id" :rules="[{ required: true, message: 'Please input !' }]">
                <ApiData url="trademark/all" method="GET" :params="{}">
                  <template #default="{ data }">
                    <a-select class="w-[200px]" v-model:value="formState.trademark_id"
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
            <a-col :span="12">
              <a-form-item label="Date" name="date" :rules="[{ required: true, message: 'Please input !' }]">
                <a-date-picker
                  v-model:value="formState.date"
                  input-read-only
                  value-format="YYYY-MM-DD"
                  format="MM-DD-YYYY"
                  class="w-full"
                ></a-date-picker>
              </a-form-item>
              <a-form-item label="Expiration Date" name="expiration_date" :rules="[{ required: true, message: 'Please input !' }]">
                <a-date-picker
                  v-model:value="formState.expiration_date"
                  input-read-only
                  value-format="YYYY-MM-DD"
                  format="MM-DD-YYYY"
                  class="w-full"
                ></a-date-picker>
              </a-form-item>
              <a-form-item label="Amount" name="amount" :rules="[{ required: true, message: 'Please input !' }]">
                <a-input-number id="inputNumber" v-model:value="formState.amount" :min="0"  style="width: 100%"/>
              </a-form-item>
            </a-col>
            <a-col :span="24">
              <a-form-item label="Description">
                <a-textarea v-model:value="formState.description" placeholder="Description..." :rows="4"/>
              </a-form-item>
            </a-col>
          </a-row>
        </a-tab-pane>
      </a-tabs>
    </div>
  </a-form>
  <a-modal append-to-body v-model:open="showPicker" style="z-index:99999;top: 2vh;height:98vh" height="96vh" width="90vw"
           title="Select file">
    <FilePicker :multiple="true" @close="showPicker = false" @select="onSelectImage"></FilePicker>
    <template #footer>
    </template>
  </a-modal>
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
  }
</style>
