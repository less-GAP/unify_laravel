<template>
  <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit" class="w-full">
    <div class="my-4">
      <a-row :gutter="20">
        <a-col :span="24">
          <a-form-item label="Shipping" name="shipping_id" :rules="[{ required: true, message: 'Please input !' }]">
            <ApiData url="user/all" method="GET" :params="{type:'shipper'}">
              <template #default="{ data }">
                <a-select class="w-[200px]" v-model:value="formState.shipping_id"
                          placeholder="Select shipping..." option-label-prop="children" @change="changeShipping">
                  <template v-for="(value, key) in data" :key="key">
                    <a-select-option :value="value.id" :label="value.name" v-bind="value">
                      {{value.full_name}} - {{value.phone}}
                    </a-select-option>
                  </template>
                </a-select>
              </template>
            </ApiData>
          </a-form-item>
          <a-form-item label="Phone" name="shipper_phone" :rules="[{ required: true, message: 'Please enter phone!' }]">
            <a-input v-model:value="formState.shipper_phone" class="w-full" placeholder="Phone..."></a-input>
          </a-form-item>
          <a-form-item label="Email" name="shipper_email" :rules="[{type: 'email', message: 'The input is not valid email!',}]">
            <a-input v-model:value="formState.shipper_email" placeholder="Email..."></a-input>
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
      const formState = ref({
        patient: {}
      });

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

      function changeShipping(value, option) {
        if (option) {
          formState.value.shipper = option;
          formState.value.shipper_phone = option.phone;
          formState.value.shipper_email = option.email;
          formState.value.shipper_name = option.full_name;
        }
      };

      onMounted(() => {
        console.log(props.value)
        formState.value = props.value;
        formState.value.shipper_phone = props.value.shipper_phone;
        formState.value.shipper_email = props.value.shipper_email;
      });

      return {
        loading,
        formRef,
        back,
        formState,
        submit,
        changeShipping,
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
