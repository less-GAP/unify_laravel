<script lang="ts" setup>
import { reactive, h, ref, toRaw } from "vue";

import { useMainStore } from "@/stores/main";
import {
  mdiAccount,
  mdiMail,
  mdiAsterisk,
  mdiFormTextboxPassword,
  mdiGithub,
} from "@mdi/js";

import { PlusOutlined, LoadingOutlined, DeleteOutlined } from '@ant-design/icons-vue';

import router from "@/router";

import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import DataListEdit from "@/components/DataListEdit.vue";

const prefix = 'master-data'
const listKey = 'task-status'
const {
  fetchListApi,
  createApi,
  fetchDetailApi,
  deleteApi,
  updateApi
} = UseEloquentRouter(prefix)

const mainStore = useMainStore();

const loading = ref(false);
const showPicker = ref(false);

const activeKey = ref('1');

const formRef = ref();
const onSelectImage = function () {
};
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
const formState = reactive({
  list_key: listKey,
  data: [{}]
});
const isShowModal = ref(false)

const fetch = async function () {
  loading.value = true;
  loading.value = true
  var value = await fetchDetailApi(listKey)
  if (value.data) {
    try {
      value.data.data = JSON.parse(value.data.data);
    } catch (e) {
      console.log(e);
      
    }
    Object.assign(formState, value.data)
  }
  loading.value = false

}
fetch();

const submit = (status) => {

  createApi({ ...formState, status: status }).then(rs => {
    Object.assign(formState, rs.data.result)
  });

};

</script>

<template>
  <a-button @click="submit" class="float-right" type="primary" success>Save</a-button>
  <DataListEdit :columns="[{
    title: 'Label',
    dataIndex: 'label'
  }, {
    title: 'Value',
    dataIndex: 'value'
  }, {
    title: 'Color',
    dataIndex: 'color'
  }, {
    title: 'Background color',
    dataIndex: 'background_color'
  },
  ]" v-model:value="formState.data"></DataListEdit>
</template>

<style scoped>
.ant-input {
  border-color: #d9d9d9 !important;
  border-radius: 5px !important;
}

.ant-modal-wrap {
  z-index: 100001 !important;
}
</style>
