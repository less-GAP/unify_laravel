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
        </a-col>
      </a-row>
      <a-row :gutter="20">
        <a-col :span="8">
          <a-form-item label="Amount" name="amount" :rules="[{ required: true, message: 'Please input !' }]">
            <a-input-number id="inputNumber" v-model:value="formState.amount" :min="0" style="width: 100%"/>
          </a-form-item>
        </a-col>
      </a-row>
      <a-row :gutter="20">
        <a-col :span="8">
          <a-form-item label="Delivery Type" name="delivery_type" :rules="[{ required: true, message: 'Please input !' }]">
            <a-select class="w-[200px]" v-model:value="formState.delivery_type"
                      placeholder="Select type..." option-label-prop="children" @change="changeDelivery">
              <a-select-option value="one_times">One Times</a-select-option>
              <a-select-option value="weekly">Weekly</a-select-option>
              <a-select-option value="monthly">Monthly</a-select-option>
              <a-select-option value="yearly">Yearly</a-select-option>
            </a-select>
          </a-form-item>
        </a-col>
        <a-col :span="8" v-if="formState.delivery_type">
          <a-form-item label="Delivery date" name="delivery_date" :rules="[{ required: true, message: 'Please input !' }]"
                       v-if="formState.delivery_type == 'one_times' || formState.delivery_type == 'yearly'"
          >
            <a-date-picker
              v-model:value="formState.delivery_date"
              input-read-only
              value-format="YYYY-MM-DD"
              format="MM-DD-YYYY"
              class="w-full"
            ></a-date-picker>
          </a-form-item>
          <a-form-item label="Delivery value" name="delivery_value" :rules="[{ required: true, message: 'Please input !' }]"
                       v-if="formState.delivery_type == 'weekly'">
            <a-select class="w-[200px]" v-model:value="formState.delivery_value"
                      placeholder="Select type..." :options="weeks" >
            </a-select>
          </a-form-item>
          <a-form-item label="Delivery value" name="delivery_value" :rules="[{ required: true, message: 'Please input !' }]"
                       v-if="formState.delivery_type == 'monthly'">
            <a-select class="w-[200px]" v-model:value="formState.delivery_value"
                      placeholder="Select type..." option-label-prop="children" :options="months">
            </a-select>
          </a-form-item>
        </a-col>
      </a-row>
      <div class="py-4 flex justify-end">
        <a-button type="primary" html-type="submit">Submit</a-button>
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
      const formState = ref({});
      const products = ref([]);
      const weeks = ref([
        {
          label: 'Monday',
          value: '1'
        },
        {
          label: 'Tuesday',
          value: '2'
        },
        {
          label: 'Wednesday',
          value: '3'
        },
        {
          label: 'Thursday',
          value: '4'
        },
        {
          label: 'Friday',
          value: '5'
        },
        {
          label: 'Saturday',
          value: '6'
        },
        {
          label: 'Sunday',
          value: '7'
        }
      ]);
      const months = ref([]);
      for (var day = 1; day < 29; day++) {
        months.value.push({
          value: day,
          label: day
        })
      }


      function back() {
        emit('close');
      }

      function submit() {
        formRef.value
          .validate()
          .then(() => {
            emit('select', toRaw(formState.value));
          });
      };

      function changeProduct(value, option) {
        formState.value.product = option;
      };

      function changeDelivery(value, option) {
        formState.value.delivery_option = option;
        formState.value.delivery_value = null
      };


      // onMounted(() => {
      //   //console.log(props.value)
      //   formState.value = props.value;
      // });

      watch(props.value, () => {
          formState.value = props.value;
          //console.log(props.value);
        },
        {immediate: true}
      )

      return {
        loading,
        formRef,
        back,
        formState,
        submit,
        changeProduct,
        weeks,
        months,
        changeDelivery
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
