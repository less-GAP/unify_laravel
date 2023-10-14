<script lang="ts" setup>
import {reactive, h, ref, toRaw, computed} from "vue";
import {CloseCircleOutlined, ArrowLeftOutlined} from '@ant-design/icons-vue';
import router from "@/router";
import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
import ApiData from "@/components/ApiData.vue";
import Api from "@/utils/Api";
import DataListEdit from "@/components/DataListEdit.vue";
import {RemoteSelect} from "@/components";

const prefix = 'event-setting'
const routePath = '/configs/event-setting'

const {
  fetchDetailApi,
  createApi,
  // updateApi
} = UseEloquentRouter(prefix)

const loading = ref(false);

const formRef = ref();


const emit = defineEmits(["close"]);
const formState = reactive({
  event_name: '',
  status: 'active',
  handlers: [],
  data: {
    email_template: ''
  },
});

const fetch = async function () {
  loading.value = true;
  var id = router.currentRoute?.value.params.id;
  if (id !== 'new') {
    loading.value = true
    const value = await fetchDetailApi(id)
    Object.assign(formState, value.data)
    loading.value = false
  } else {
    loading.value = false
  }
}
fetch();


const submit = () => {
  formRef.value
    .validate()
    .then(() => {
      createApi({...formState}).then(rs => {
        Object.assign(formState, rs.data.result)
      });
    })
};
const closeDetail = function () {
  router.replace({path: routePath})
}


</script>

<template>
  <a-drawer body-style="padding:0" :closable="false"
            style="position:relative;display:flex;flex-direction:column;height:100vh;"
            @close="closeDetail" :open="true" width="500px">
    <a-form class="w-full h-full bg-gray-100 !p-5 " layout="vertical" v-bind="$config.formConfig" ref="formRef"
            :model="formState"
            @finish="submit">
      <a-card body-style="padding:10px;height:55px;"
              class="bg-gray-50  ">
        <a-button class="float-left" type="link" @click="closeDetail">
          <template #icon>
            <ArrowLeftOutlined/>
          </template>
        </a-button>
        <a-space class="float-right" align="right">
          <!--                <a-button v-if="formState.rule_detect_category_link" @click="detectCategory" :loading="loadingDraft" >Test Category</a-button>-->
          <a-button v-if="!formState.id" @click="submit()" :loading="loading" type="primary">Create</a-button>
          <a-button v-else @click="submit()" :loading="loading" type="primary" class="!bg-yellow-400">Update</a-button>
        </a-space>
      </a-card>
      <div style="height:calc(100% - 70px);margin-top:15px;overflow: auto;padding:0;" :gutter="20">
        <a-card>
          <a-form-item label="Event Name"
                       name="event_name"
                       :rules="[{ required: true }]"
          >
            <a-select v-model:value="formState.event_name">
              <a-select-option value="patients.created">Create Patient</a-select-option>
              <a-select-option value="patients.updated">Update Patient</a-select-option>
              <a-select-option value="patients.deleted">Delete Patient</a-select-option>
            </a-select>
          </a-form-item>
          <a-form-item label="Notifications"
                       name="handlers"
                       :min="0"
                       :rules="[{ required: true }]"
          >
            <a-select mode="multiple" rows="5" v-model:value="formState.handlers">
              <a-select-option value="notification_message">Notification message</a-select-option>
              <a-select-option value="notification_email">Notification Email</a-select-option>
              <a-select-option value="notification_desktop">Notification Desktop</a-select-option>
            </a-select>
          </a-form-item>
          <a-form-item label="Notification to"
                       :name="['data','notification_to']"
                       :min="0"
                       :rules="[{ required: true }]"
          >
            <a-select mode="multiple" v-model:value="formState.data.notification_to">
              <a-select-option value="admin">Admin</a-select-option>
              <a-select-option value="owner">Owner</a-select-option>
            </a-select>
          </a-form-item>
          <a-form-item v-if="formState.handlers.includes('notification_message')" label="Notification Title"
                       :name="['data','notification_tittle']"
                       :min="0"
                       :rules="[{ required: true }]"
          >
            <a-input v-model:value="formState.data.notification_tittle"/>
          </a-form-item>
          <a-form-item v-if="formState.handlers.includes('notification_message')" label="Notification Message"
                       :name="['data','notification_message']"
                       :min="0"
                       :rules="[{ required: true }]"
          >
            <a-textarea rows="5" v-model:value="formState.data.notification_message"/>
          </a-form-item>
          <a-form-item v-if="formState.handlers.includes('notification_email')"
                       label="Notification Email Template"
                       :name="['data','notification_message']"
                       :min="0"
                       :rules="[{ required: true }]"
          >
            <RemoteSelect
              url="email-template/all"
              valueKey="id"
              labelKey="email_title"
              v-model:value="formState.data.email_template"
            ></RemoteSelect>
          </a-form-item>

          <a-form-item label="Status"
                       name="status"
                       :rules="[{ required: true }]"
          >
            <a-switch checkedValue="active" unCheckedValue="inactive" v-model:checked="formState.status"/>
          </a-form-item>

        </a-card>


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
</style>
