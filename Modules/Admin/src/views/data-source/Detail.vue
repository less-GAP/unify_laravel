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
                    <a-input v-model:value="formState.name" placeholder="Title.."/>
                  </a-form-item>
                  <a-form-item label="Url"
                               name="url"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.url" placeholder="Title.."/>
                  </a-form-item>
                  <a-form-item label="Detect Category Url"
                               name="rule_detect_category_link"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.rule_detect_category_link"/>
                  </a-form-item>
                  <a-form-item label="Detect Post Url"
                               name="rule_detect_post_link"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.rule_detect_post_link"/>
                  </a-form-item>
                  <a-form-item label="Detect Post Title"
                               name="rule_detect_post_title"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.rule_detect_post_title"/>
                  </a-form-item>
                  <a-form-item label="Detect Post Excerpt"
                               name="rule_detect_post_excerpt"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.rule_detect_post_excerpt"/>
                  </a-form-item>
                  <a-form-item label="Detect Post Content"
                               name="rule_detect_post_content"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.rule_detect_post_content"/>
                  </a-form-item>
                  <a-form-item label="Detect Post Keywords"
                               name="rule_detect_post_keywords"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.rule_detect_post_keywords"/>
                  </a-form-item>
                  <a-form-item label="Detect Post Image"
                               name="rule_detect_post_image"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.rule_detect_post_image"/>
                  </a-form-item>
                  <a-form-item label="Detect Post Tags"
                               name="rule_detect_post_tags"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.rule_detect_post_tags"/>
                  </a-form-item>
                  <a-form-item label="Country"
                               name="country"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.country"/>
                  </a-form-item>
                  <a-form-item label="Country"
                               name="country"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.country"/>
                  </a-form-item>
                  <a-form-item label="Craw Frequently Hours"
                               name="craw_frequently_hours"
                               :rules="[{ required: true }]"
                  >
                    <a-input-number step="1" :min="1" v-model:value="formState.craw_frequently_hours"/>
                  </a-form-item>

                </a-col>

              </a-row>

            </a-card>

          </a-col>
          <a-col :lg="8" :md="24">
            <a-card>
              <a-tag v-if="formState.status=='active'" color="success">Active</a-tag>
              <a-tag v-else color="orange">Draft</a-tag>
              <a-space class="flex items-end float-right" align="right">
                <a-button @click="submit('inactive')" :loading="loadingDraft" type="dashed">Save Draft</a-button>
                <a-button @click="submit('active')" :loading="loading" type="primary">Save And Active</a-button>
              </a-space>
            </a-card>
            <a-card class="mt-5">

              <a-space v-if="formState.id" class="flex items-end float-right" align="right">
                <!--                <a-button v-if="formState.rule_detect_category_link" @click="detectCategory" :loading="loadingDraft" >Test Category</a-button>-->
                <a-button v-if="formState.rule_detect_post_link && formState.url" @click="detectPost(formState.url)">
                  Test Post
                </a-button>
              </a-space>
            </a-card>
          </a-col>
        </a-row>
      </a-form>

    </SectionMain>
  </LayoutAuthenticated>
  <a-drawer v-model:visible="showDetectPost" width="90vw">

    <a-list :pagination="{pageSize:10}" size="small" bordered :data-source="postUrls">
      <template #renderItem="{ item }">
        <a-list-item>
          <a-button type="link">
            {{ item }}
          </a-button>
          <a-button @click="detectPostDetail(item)" type="dashed" style="float: right">Test</a-button>
        </a-list-item>
      </template>

    </a-list>
  </a-drawer>
  <a-drawer v-model:visible="showDetectPostDetail" width="90vw">
    <h2 class="mb-5">{{ postDetail.title }}</h2>
    <a-descriptions :column="2" title="" bordered>
      <a-descriptions-item label="Image">
        <a-image height="150px" :src="postDetail.image"></a-image>
      </a-descriptions-item>
      <a-descriptions-item label="Excerpt">
        {{postDetail.excerpt}}
      </a-descriptions-item>
      <a-descriptions-item label="Keywords">
        {{postDetail.keywords}}
      </a-descriptions-item>
      <a-descriptions-item label="Tags">
        {{postDetail.tags}}
      </a-descriptions-item>
    </a-descriptions>
    <iframe style="width:100%;height:50vh" :srcdoc="postDetail.content" frameborder="0"></iframe>
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
</style>
