<script lang="ts" setup>
import {reactive, ref, toRaw} from "vue";
import SectionMain from "@/components/SectionMain.vue";
import {DataTable} from "@/components";

import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import {useMainStore} from "@/stores/main";
import {
  mdiAccount,
  mdiMail,
  mdiAsterisk,
  mdiFormTextboxPassword,
  mdiGithub,
} from "@mdi/js";


import {PlusOutlined, LoadingOutlined, DeleteOutlined} from '@ant-design/icons-vue';


import router from "@/router";

import Api from "@/utils/Api";

import 'jodit/es5/jodit.css';

import {JoditEditor, Jodit} from 'jodit-vue';

import {notification} from 'ant-design-vue';

import type {UploadProps} from 'ant-design-vue';

import ProductList from "./ProductList.vue";
import InputUploadGetPath from "../../components/InputUploadGetPath.vue";
import {InputTags} from "@/components";
import {createApi, defaultNewValue, formConfig, fetchDetailApi, getPostUrl, getPostDetail} from "./meta";

const mainStore = useMainStore();

const loading = ref(false);
const showPicker = ref(false);

const activeKey = ref('1');

const formRef = ref();
const onSelectImage = function () {
};

const formState = reactive({...defaultNewValue});
console.log({...defaultNewValue});
const isShowModal = ref(false)

const fetch = async function () {
  loading.value = true;
  var id = router.currentRoute.value.params.id;
  if (id > 0) {
    loading.value = true
    const value = await fetchDetailApi(id)
    Object.assign(formState, value.data)
    loading.value = false
  } else {
    loading.value = false
  }
}
fetch();

const submit = (status) => {
  formRef.value
    .validate()
    .then(() => {
      createApi({...formState, status: status}).then(rs => {
        Object.assign(formState, rs.data.result)
      });
    })

};
const postUrls = ref([])
const showDetectPost = ref(false)
const detectPost = async (url) => {
  showDetectPost.value = true
  const rs = await getPostUrl({url, data: formState})
  postUrls.value = rs.data
};
const postDetail = ref([])
const showDetectPostDetail = ref(false)
const detectPostDetail = async (url) => {
  showDetectPostDetail.value = true
  const rs = await getPostDetail({url, data: formState})
  postDetail.value = rs.data
};
const back = () => {
  router.push('/products');
};
const appendFile = function(name, files){
  // console.log(44,name, files)
  formState.filename = name;
  formState.file = files[0];
}

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <a-form v-if="formState" layout="vertical"
              v-bind="formConfig"
              ref="formRef"
              :model="formState"
              @finish="onFinish"
      >
        <a-row :gutter="50">
          <a-col :lg="16" :md="24">
            <a-card>
              <a-row :gutter="20">
                <a-col :span="24">
                  <a-form-item label="Name"
                               name="name"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.name" placeholder="Name.."/>
                  </a-form-item>

                  <a-form-item label="Image"
                               name="image"
                               :rules="[{ required: true }]"
                  >
                    <InputUploadGetPath width="200px" autocomplete="off" v-model:value="formState.image">
                    </InputUploadGetPath>
                  </a-form-item>
                  <a-form-item label="File zip"
                               name="file"
                               :rules="[{ required: true }]"
                  >
                   <input @change="appendFile($event.target.name, $event.target.files)" type="file" accept=".zip" />
                  </a-form-item>


                </a-col>

              </a-row>

            </a-card>

          </a-col>
          <a-col :lg="8" :md="24">
            <a-card>
              <a-space class="flex items-end float-right" align="right">
                <a-button @click="submit('active')" :loading="loading" type="primary">Save And Active</a-button>
              </a-space>
            </a-card>

          </a-col>
        </a-row>
      </a-form>

    </SectionMain>
  </LayoutAuthenticated>

</template>

<style>
.ant-input {
  border-color: #d9d9d9 !important;
  border-radius: 5px !important;
}

.ant-modal-wrap {
  z-index: 100001 !important;
}
</style>
