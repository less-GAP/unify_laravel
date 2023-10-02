<script lang="ts" setup>
import { reactive, ref } from "vue";
import { mdiBackspace, mdiContentSave } from '@mdi/js';
import { BaseIcon } from "@/components";
import { ApiData } from "@/components";
import router from "@/router";
import { useAuthStore } from "@/stores/auth";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";

const prefix = 'task'
const {
  fetchDetailApi,
  createApi,
} = UseEloquentRouter(prefix)

const loading = ref(false);
const auth = useAuthStore();
const currentRoute = router.currentRoute.value;

const formRef = ref();

const props = defineProps({
  value: {
    type: Object,
    default: {}
  }, visible: {
    type: Boolean,
    default: true
  },
})
const emit = defineEmits(["close"]);
const formState = reactive({});

const fetch = async function () {
  if (currentRoute.name == 'task-edit') {
    loading.value = true
    const response = await fetchDetailApi(currentRoute.params.id);
    Object.assign(formState, response.data);
  }
  loading.value = false
}
fetch();

const closeDetail = function () {
  router.replace({ path: '/' + prefix })
}

const submit = () => {
  loading.value = true
  formRef.value.validate().then(() => {
    formState.created_by = auth.user.id;
    formState.assignees = JSON.stringify(formState.assignees);
    try {
      createApi({ ...formState }).then((rs) => {
        closeDetail()
      });
    } catch (e) {
      console.log(e);
    }
  });
  loading.value = false;
};

</script>

<template>
  <a-drawer :closable="false" style="position:relative;display:flex;flex-direction:column;height:100vh;"
    @close="closeDetail" :open="visible">
    <a-form id="formAddTask" v-bind="$config.formConfig" ref="formRef" layout="vertical" :model="formState"
      @finish="submit">
      <div class="p-3 bg-gray-200">
        <a-button type="link" @click="closeDetail">
          <template #icon>
            <div class="flex">
              <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
              <span class="ml-1 text-stone-500">Back</span>
            </div>
          </template>
        </a-button>
        <a-space class="float-right">
          <a-button @click="submit()" type="primary" class="uppercase">
            <div class="flex">
              <BaseIcon :path="mdiContentSave" class="w-4 text-white" />
              <span class="ml-1 text-white">{{ currentRoute.name == 'task-edit' ? 'Update' : 'Add new' }}</span>
            </div>
          </a-button>
        </a-space>
      </div>
      <div class="px-4 mt-5 overflow-y-auto" style="height:calc(100vh - 100px);">
        <a-form-item label="Task name" name="name" :rules="[{ required: true, message: 'Please enter task name!' }]">
          <a-input v-model:value="formState.name"></a-input>
        </a-form-item>
        <div class="flex flex-wrap -mx-2">
          <div class="w-full px-2">
            <a-form-item label="Due date" name="deadline_at">
              <a-date-picker v-model:value="formState.deadline_at" class="w-full" :show-time="{ format: 'HH:mm' }"
                input-read-only value-format="YYYY-MM-DD HH:mm:ss" format="HH:mm MM-DD-YYYY"></a-date-picker>
            </a-form-item>
          </div>
        </div>
        <a-form-item label="Patient" name="patient_id" :rules="[{ required: true, message: 'Please select patient!' }]">
          <ApiData url="patient/all" method="GET">
            <template #default="{ data }">
              <a-select v-model:value="formState.patient_id" :options="data"
                :fieldNames="{ label: 'full_name', value: 'id' }" showSearch optionFilterProp="full_name">
              </a-select>
            </template>
          </ApiData>
        </a-form-item>
        <a-form-item label="Assignees" name="assignees"
          :rules="[{ required: true, message: 'Please select assignees!' }]">
          <ApiData url="user/all" method="GET">
            <template #default="{ data }">
              <a-select v-model:value="formState.assignees" :options="data"
                :fieldNames="{ label: 'full_name', value: 'id' }" mode="multiple">
              </a-select>
            </template>
          </ApiData>
        </a-form-item>
        <a-form-item label="Description" name="description">
          <a-textarea v-model:value="formState.description" :rows="4"></a-textarea>
        </a-form-item>
      </div>
    </a-form>
  </a-drawer>
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
