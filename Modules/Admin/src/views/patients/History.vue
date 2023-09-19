<script setup>
import {reactive, h, ref, toRaw, computed} from "vue";
import {mdiBackspace, mdiContentSave} from '@mdi/js';
import {BaseIcon, ApiData, DataTable} from "@/components";
import router from "@/router";
import {useAuthStore} from "@/stores/auth";
import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
import dayjs from 'dayjs';
import {UseDataTable} from "@/utils/UseDataTable";

const prefix = 'activity'
const patientId = router.currentRoute.value.params.id
const {
  fetchDetailApi,
  fetchListApi,
} = UseEloquentRouter(prefix, {
  'include': 'user',
  'order': '-id'
});
const tableConfig = UseDataTable(fetchListApi, {
  showSelection: false,
  pagination: {
    perPage: 10
  },
  filter: {
    subject_id: patientId,
    subject_type: 'App\\Models\\Patient'
  }
});
defineProps({
  value: {
    type: Object,
    default: {}
  }, visible: {
    type: Boolean,
    default: true
  },
});
const formState = reactive({});
const formRef = ref();
const loading = ref(false);
const auth = useAuthStore();

const customFormat = 'MM-DD-YYYY';
const dbFormat = "YYYY-MM-DD";
const age = (formState) => {
  return formState.dob ? '(' + dayjs().diff(dayjs(formState.dob, dbFormat), 'year') + ' years old)' : '';
};

const fetch = async function () {
  loading.value = true;
  var id = router.currentRoute.value.params.id;
  if (id !== 'new') {
    loading.value = true
    const value = await fetchDetailApi(id)
    Object.assign(formState, value.data)
    loading.value = false
  } else {
    loading.value = false
  }
}
let reloadTable = () => {
};

function registerTable({reload}) {
  reloadTable = reload;
}

fetch();
const submit = () => {
  formRef.value
    .validate()
    .then(() => {
      updateApi(formState.id, {...formState}).then(rs => {
        Object.assign(formState, rs.data.result)
      });
    })
};
const closeDetail = function () {
  router.replace({path: '/patient' })
};
</script>
<template>
  <a-drawer :closable="false" style="position:relative;display:flex;flex-direction:column;height:100vh;"
            @close="closeDetail" :open="visible" width="40vw">
    <a-form layout="vertical" v-bind="$config.formConfig" ref="formRef" :model="formState" @finish="submit">
      <div class="bg-gray-200 p-3">
        <a-button class="!hidden md:!inline-block" type="link" @click="closeDetail">
          <template #icon>
            <div class="flex">
              <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500"/>
              <span class="text-stone-500 ml-1">Back</span>
            </div>
          </template>
        </a-button>
        <a-button class="!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0" type="link"
                  @click="closeDetail">
          <template #icon>
            <div class="flex">
              <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500"/>
              <span class="text-stone-500 ml-1">Back</span>
            </div>
          </template>
        </a-button>
        <a-space class="float-right">
        </a-space>
      </div>
      <div class="px-4 mt-5 overflow-y-auto" style="height:calc(100% - 60px)">

        <div class="flex flex-wrap -mx-4">
          <ApiData :url="'patient/'+patientId">
            <template #default="{data}">
              <h1 class="w-full px-4 flex flex-col">
                <div class="flex items-center leading-none whitespace-nowrap">
                  <span>{{ data.full_name }}</span>
                </div>
                <div class="text-gray-400 text-sm">#{{ data.unify_number }}</div>
              </h1>
            </template>
          </ApiData>


          <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem" plain>
            History
          </a-Divider>
          <div class="w-full px-4 pl-6">
            <DataTable v-bind="tableConfig" @register="registerTable">
              <template #table="{
          tableConfig,
          tableData,
          data,
          columns,
          selectionActions,
          reload,
        }">
                <a-timeline class="!m-5">
                  <a-timeline-item v-for="item in data" :key="item.id" color="green">
                    <div class="flex items-center">
                      <img class="w-6 h-6 rounded-full" :src="item.user?.profile_photo_url">
                      <div class="by ml-2">{{ item.user?.full_name }}</div>
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                      <a-tag>{{ item.event }}</a-tag>
                      at {{ $format.formatDateTime(item.created_at) }}
                    </div>
                    <div class="text-lg">{{ item.description }}</div>
                  </a-timeline-item>
                </a-timeline>
              </template>
            </DataTable>


          </div>
        </div>
      </div>
    </a-form>
  </a-drawer>
</template>

<style>
.ant-drawer-body {
  padding: 0 !important
}
</style>
