<template>
  <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit" class="w-full">
    <div class="my-4">
      <a-row :gutter="20">
        <a-col :span="12">
          <a-form-item label="Patient" name="patient_id" :rules="[{ required: true, message: 'Please input !' }]">
            <ApiData url="patient/all" method="GET" :params="{unify_process:2}">
              <template #default="{ data }">
                <a-select class="w-[200px]" v-model:value="formState.patient_id"
                          placeholder="Select patient..." option-label-prop="children" @change="changePatient">
                  <template v-for="(value, key) in data" :key="key">
                    <a-select-option :value="value.id" :label="value.name" v-bind="value">
                      #{{value.unify_number}} - {{value.full_name}}
                    </a-select-option>
                  </template>
                </a-select>
              </template>
            </ApiData>
          </a-form-item>
          <a-form-item label="Phone" name="phone" :rules="[{ required: true, message: 'Please enter phone!' }]">
            <a-input v-model:value="formState.phone" class="w-full" placeholder="Phone..."></a-input>
          </a-form-item>
          <a-form-item label="Street" name="street" :rules="[{ required: true, message: 'Please enter street!' }]">
            <a-input v-model:value="formState.street" class="w-full" placeholder="Street..."></a-input>
          </a-form-item>
          <a-form-item label="City" name="city" :rules="[{ required: true, message: 'Please enter city!' }]">
            <a-input v-model:value="formState.city" class="w-full" placeholder="City..."></a-input>
          </a-form-item>
          <a-form-item label="State" name="state" :rules="[{ required: true, message: 'Please enter state!' }]">
            <a-select v-model:value="formState.state" allowClear="" class="w-full" showSearch
                      placeholder="Select a state">
              <a-select-option v-for="state in listStates" :key="state.value" :value="state.label">{{ state.label
                }} ({{ state.value }})
              </a-select-option>
            </a-select>
          </a-form-item>

        </a-col>
        <a-col :span="12">
          <a-form-item label="Email" name="email" :rules="[{type: 'email', message: 'The input is not valid email!',}]">
            <a-input v-model:value="formState.email" placeholder="Email..."></a-input>
          </a-form-item>
          <a-form-item label="Apt" name="apt" :rules="[{ message: 'Please enter apt!' }]">
            <a-input v-model:value="formState.apt" class="w-full" placeholder="Apt..."></a-input>
          </a-form-item>
          <a-form-item label="Route" name="route" :rules="[{ required: true, message: 'Please enter route!' }]">
            <a-input v-model:value="formState.route" class="w-full" placeholder="Route..."></a-input>
          </a-form-item>
          <a-form-item label="Sub-r" name="sub_r" :rules="[{ message: 'Please enter sub-r!' }]">
            <a-input v-model:value="formState.sub_r" class="w-full" placeholder="Sub-r..."></a-input>
          </a-form-item>
          <a-form-item label="Zip" name="zip" :rules="[{ required: true, message: 'Please enter zip!' }]">
            <a-input-number v-model:value="formState.zip" class="!w-full" placeholder="Zip..."></a-input-number>
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

      function changePatient(value, option) {
        if(option){
          formState.value.patient = option;
          formState.value.unify_number = option.unify_number;
          formState.value.full_name = option.full_name;
          formState.value.phone = option.phone;
          formState.value.email = option.email;
          formState.value.street = option.street;
          formState.value.city = option.city;
          formState.value.state = option.state;
          formState.value.email = option.email;
          formState.value.apt = option.apt;
          formState.value.route = option.route;
          formState.value.sub_r = option.sub_r;
          formState.value.zip = option.zip;
        }
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
        changePatient,
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
