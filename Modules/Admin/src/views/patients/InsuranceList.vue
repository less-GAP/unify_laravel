<template>
  <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit" class="w-full">
    <div class="my-4">
      <a-row :gutter="20">
        <a-col :span="24">
          <a-form-item label="Insurance Coverage" name="coverage" :rules="[{ required: true, message: 'Please input !' }]">
            <ApiData url="master-data/insurance" method="GET" :params="{}">
              <template #default="{ data }">
                <a-select class="w-[200px]" v-model:value="formState.coverage"
                          placeholder="Select coverage..." option-label-prop="children" @change="changeInsurance">
                  <template v-for="(value, key) in JSON.parse(data.data)" :key="key">
                    <a-select-option :value="value.value" :label="value.label" v-bind="value">
                      {{value.label}}
                    </a-select-option>
                  </template>
                </a-select>
              </template>
            </ApiData>
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="Insurance ID" name="insurance_id" :rules="[{ required: true, message: 'Please input !' }]">
            <a-input v-model:value="formState.insurance_id" style="width: 100%"/>
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="Active date" name="active_date" :rules="[{ required: true, message: 'Please input !' }]">
            <a-date-picker
              v-model:value="formState.active_date"
              input-read-only
              value-format="YYYY-MM-DD"
              format="MM-DD-YYYY"
              class="w-full"
            ></a-date-picker>
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="Expired date" name="expired_date" :rules="[{ required: true, message: 'Please input !' }]">
            <a-date-picker
              v-model:value="formState.expired_date"
              input-read-only
              value-format="YYYY-MM-DD"
              format="MM-DD-YYYY"
              class="w-full"
            ></a-date-picker>
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
      const coverages = ref([]);

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

      function changeInsurance(value, option) {
        formState.value.coverage = option;
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
        changeInsurance
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
