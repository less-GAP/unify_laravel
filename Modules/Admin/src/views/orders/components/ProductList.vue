<template>
  <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit" class="w-full">
    <div class="my-4">
      <a-row :gutter="20">
        <a-col :span="24">
          <a-form-item label="Product" name="product_id" :rules="[{ required: true, message: 'Please input !' }]">
            <ApiData url="product/all" method="GET" :params="{}">
              <template #default="{ data }">
                <a-select class="w-[200px]" v-model:value="formState.product_id"
                          placeholder="Select product..." option-label-prop="children" @change="changeProduct">
                  <template v-for="(value, key) in data" :key="key">
                    <a-select-option :value="value.id" :label="value.name" v-bind="value">
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
                          placeholder="Select product..." option-label-prop="children" @change="changeSupplier">
                  <template v-for="(value, key) in data" :key="key">
                    <a-select-option :value="value.id" :label="value.name" v-bind="value">
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
                          placeholder="Select product..." option-label-prop="children" @change="changeTrademark">
                  <template v-for="(value, key) in data" :key="key">
                    <a-select-option :value="value.id" :label="value.name" v-bind="value">
                      <img :src="value.image_url" class="w-5 h-5 inline-block"/>&nbsp;&nbsp;&nbsp;&nbsp;{{value.name}}
                    </a-select-option>
                  </template>
                </a-select>
              </template>
            </ApiData>
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="Amount" name="amount" :rules="[{ required: true, message: 'Please input !' }]">
            <a-input-number id="inputNumber" v-model:value="formState.amount" :min="0" style="width: 100%"/>
          </a-form-item>
        </a-col>
      </a-row>
      <div class="py-4 flex justify-end">
        <a-button type="primary" html-type="submit" :loading="loading">Submit</a-button>
        <a-button type="primary" ghost @click="back()" class="ml-4">Back</a-button>
      </div>
    </div>
  </a-form>

</template>

<script lang="ts">
  import {computed, defineComponent, watch, ref, onMounted, unref, toRaw} from 'vue';
  import {isArray, isFunction} from '@/utils/is';
  import {DragOutlined, DeleteOutlined, PlusOutlined} from '@ant-design/icons-vue';

  import {InputUpload, InputUploadGetPath, FilePicker, InputTags, RemoteSelect, BaseIcon, ApiData} from "@/components";

  import Api from '@/utils/Api';
  import {notification} from "ant-design-vue";

  export default defineComponent({
    components: {
      DragOutlined,
      DeleteOutlined,
      PlusOutlined,
      ApiData,
      RemoteSelect
    },
    props: {
      value: Array,
    },
    emits: ['select', 'close'],
    setup(props, {emit}) {
      const isFirstLoaded = ref<Boolean>(false);
      const loading = ref(false);
      const formRef = ref();
      const formState = ref({
        product: {},
        trademark: {}
      });
      const products = ref([]);

      function back() {
        emit('close');
      }

      function submit() {
        formRef.value
          .validate()
          .then(() => {
            //check stock
            Api.post('order/checkStock', toRaw(formState.value)).then(rs => {
              if (rs.data.code == 1) {
                emit('select', toRaw(formState.value));
              }
            });
          });
      };

      function changeProduct(value, option) {
        formState.value.product = option;
      };

      function changeTrademark(value, option) {
        formState.value.trademark = option;
      };

      function changeSupplier(value, option) {
        formState.value.supplier = option;
      };

      onMounted(() => {
        //console.log(props.value)
        formState.value = props.value;
      });


      return {
        loading,
        formRef,
        back,
        formState,
        submit,
        changeProduct,
        changeTrademark,
        changeSupplier
      };
    },
  });
</script>

<style>
  .ant-input {
    border-color: #d9d9d9 !important;
    border-radius: 5px !important;
  }
</style>
