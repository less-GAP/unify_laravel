<script lang="ts" setup>
import {reactive, ref, toRaw} from "vue";
import SectionMain from "@/components/SectionMain.vue";
import {FilePicker} from "@/components";

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
import {createApi, defaultNewValue, formConfig, fetchDetailApi} from "./meta";

const mainStore = useMainStore();

const loading = ref(false);
const showPicker = ref(false);


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

const back = () => {
  router.push('/products');
};
const activeKey = ref(0);

const newTabIndex = ref(0);

const add = () => {
  formState.pages.push({title: 'New Page',blocks: []});
};

const remove = (targetKey: string) => {
  let lastIndex = 0;

};

const onEditPage = (targetKey: string | MouseEvent, action: string) => {
  if (action === 'add') {
    add();
  } else {
    remove(targetKey as string);
  }
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
          <a-col :lg="18" :md="24">
            <a-card>
              <a-row :gutter="20">
                <a-col :span="24">
                  <a-form-item label="Domain"
                               name="domain"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.domain" placeholder="Name.."/>
                  </a-form-item>
                  <a-form-item label="Theme"
                               name="theme"
                               :rules="[{ required: true }]"
                  >
                    <a-input :disabled="true" v-model:value="formState.theme" placeholder="Theme.."/>
                  </a-form-item>
                  <a-form-item label="Site Title"
                               name="site_title"
                               :rules="[{ required: true }]"
                  >
                    <a-input v-model:value="formState.site_title" placeholder="Site Title.."/>
                  </a-form-item>

                  <a-form-item label="Mô tả ">
                    <a-textarea v-model:value="formState.description" placeholder="Description..." :rows="4"/>
                  </a-form-item>
                </a-col>
              </a-row>

            </a-card>
            <a-card class="mt-5" title="Pages">
              <a-tabs v-model:activeKey="activeKey" type="editable-card" @edit="onEditPage">
                <a-tab-pane v-for="(page,index) in formState.pages" :key="index" :tab="page.title"
                            :closable="page.closable">
                  <a-form-item label="Title"
                  >
                    <a-input v-model:value="page.title" placeholder="Name.."/>
                  </a-form-item>
                  <a-form-item label="Menu Name"
                  >
                    <a-input v-model:value="page.menu_name" placeholder="Menu Name.."/>
                  </a-form-item>
                  <a-form-item label="Slug"
                  >
                    <a-input v-model:value="page.slug" placeholder="Menu Name.."/>
                  </a-form-item>
                  <a-form-item label="Description"
                  >
                    <a-textarea v-model:value="page.description" placeholder="Description.."/>
                  </a-form-item>
                  <a-card class="mt-5" title="Layout">
                    <template #extra>
                      <a-button type="primary" color="success" @click="page.blocks.push({post_tags:[]})">Add</a-button>
                    </template>
                    <a-list
                      item-layout="horizontal"
                      :data-source="page.blocks"
                    >

                      <template #renderItem="{ item ,index }">
                        <a-card class="mt-5">
                          <template #extra>
                            <a-button @click="page.blocks.splice(index,1)" type="link" danger>remove</a-button>
                          </template>
                          <a-form class="flex align-content-between" layout="inline">
                            <a-form-item class="w-1/2" label="Title">
                              <a-input v-model:value="item.title"></a-input>
                            </a-form-item>
                            <a-form-item class="w-1/2" label="Type">
                              <a-select v-model:value="item.block">
                                <a-select-option value="news">News</a-select-option>
                                <a-select-option value="hot_news">Home News</a-select-option>
                              </a-select>
                            </a-form-item>
                            <a-form-item class="w-full" label="Tags">
                              <InputTags v-model:value="item.post_tags"></InputTags>
                            </a-form-item>
                          </a-form>
                        </a-card>
                      </template>
                    </a-list>
                  </a-card>
                </a-tab-pane>
              </a-tabs>
            </a-card>
          </a-col>
          <a-col :lg="6" :md="24">
            <a-card>
              <a-space class="flex items-end float-right" align="right">
                <a-button @click="submit('publish')" :loading="loading" type="primary">Save</a-button>
              </a-space>
            </a-card>
            <a-card class="mt-5">

              <a-form-item style="width:100%" label="Logo">
                <InputUploadGetPath width="200px" autocomplete="off" v-model:value="formState.logo">
                </InputUploadGetPath>
              </a-form-item>
              <a-form-item style="width:100%" label="Icon">
                <InputUploadGetPath width="100px" autocomplete="off" v-model:value="formState.icon">
                </InputUploadGetPath>
              </a-form-item>
              <!--              <a-form-item style="width:100%" label="Hình ảnh">-->
              <!--                <InputUpload :multiple="true" alt="" autocomplete="off"-->
              <!--                             v-model:value="formState.images"></InputUpload>-->
              <!--              </a-form-item>-->
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
