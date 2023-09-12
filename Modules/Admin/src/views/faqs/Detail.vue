<script lang="ts" setup>
import { reactive, ref, toRaw } from "vue";
import { useMainStore } from "@/stores/main";
import {
  mdiAccount,
  mdiMail,
  mdiAsterisk,
  mdiFormTextboxPassword,
  mdiGithub,
} from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";

import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";

import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";

import { PlusOutlined, LoadingOutlined, DeleteOutlined } from '@ant-design/icons-vue';

import { InputUpload, FilePicker } from "@/components";

import router from "@/router";

import Api from "@/utils/Api";

import 'jodit/es5/jodit.css';

import { JoditEditor, Jodit } from 'jodit-vue';

import { notification } from 'ant-design-vue';

const mainStore = useMainStore();

const loading = ref(false);

const formRef = ref();

const formState = ref({
  status: 'A',
  position: 0,
  type: 'faq',
});

const showPicker = ref(false);

const onFinish = () => {
  formRef.value
    .validate()
    .then(() => {
      Api.post('faqs', toRaw(formState.value)).then(rs => {
        notification[rs.data.code == 0 ? 'error' : 'success']({
          message: 'Notification',
          description: rs.data.message,
        });

        if (rs.data.code == 1) {
          router.push('/faqs');
        }
      });
    })
    .catch(error => {
      notification['error']({
        message: 'Notification',
        description: error,
      });
    });
};

const back = () => {
  router.push('/faqs');
};

const fetch = () => {
  var id = router.currentRoute.value.params.id;
  if (id > 0) {
    loading.value = true
    Api.get('faqs/' + id).then(rs => {
      formState.value = rs.data.data;
      loading.value = false
    });
  }
}
fetch();

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <a-form layout="vertical" ref="formRef" :model="formState" @finish="onFinish">
        <a-tabs class="mt-2" v-model:activeKey="activeKey" @change="tabActive">
          <a-tab-pane key="1" tab="General">
            <a-row :gutter="20">
              <a-col :span="12">
                <a-form-item label="Status" name="status" :rules="[{ required: true, message: 'Select a status!' }]">
                  <a-radio-group v-model:value="formState.status" button-style="solid" size="middle">
                    <a-radio-button value="A">Active</a-radio-button>
                    <a-radio-button value="D">Deactive</a-radio-button>
                  </a-radio-group>

                </a-form-item>
              </a-col>
              <a-col :span="24">
                <a-form-item label="FAQ name" name="name"
                  :rules="[{ required: true, message: 'Please input FAQ name!' }]">
                  <a-input v-model:value="formState.name" placeholder="Enter..." />
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="FAQ type" name="type"
                  :rules="[{ required: true, message: 'Please input FAQ type!' }]">
                  <a-select v-model:value="formState.type" placeholder="">
                    <a-select-option value="faq">FAQ</a-select-option>
                    <a-select-option value="guide">Guide</a-select-option>
                    <a-select-option value="hiw">How it works</a-select-option>
                  </a-select>
                </a-form-item>
              </a-col>
              <a-col :span="8">
                <a-form-item label="Position" name="position"
                  :rules="[{ required: true, message: 'Please input Position!' }]">
                  <a-input-number id="inputNumber" v-model:value="formState.position" :min="0" :max="100000" />
                </a-form-item>
              </a-col>
            </a-row>
            <a-row :gutter="20">
              <a-col :span="24">
                <a-form-item label="Description">
                  <jodit-editor v-if="!loading" style="height: 50vh" v-model="formState.description" :config="{
                    iframe: true,
                    height: '50vh',
                    iframeStyle: 'html{margin:0;padding:0;min-height: 100%;}body{box-sizing:border-box;font-family:roboto;font-size:16px;line-height:1.6;padding:10px;margin:0;background:transparent;color:#000;position:relative;z-index:2;user-select:auto;margin:0px;overflow:auto;outline:none;}table{width:100%;border:none;border-collapse:collapse;empty-cells: show;max-width: 100%;}th,td{padding: 2px 5px;border:1px solid #ccc;-webkit-user-select:text;-moz-user-select:text;-ms-user-select:text;user-select:text}p{margin-top:0;}.jodit_editor .jodit_iframe_wrapper{display: block;clear: both;user-select: none;position: relative;}.jodit_editor .jodit_iframe_wrapper:after {position:absolute;content:\'\';z-index:1;top:0;left:0;right: 0;bottom: 0;cursor: pointer;display: block;background: rgba(0, 0, 0, 0);} .jodit_disabled{user-select: none;-o-user-select: none;-moz-user-select: none;-khtml-user-select: none;-webkit-user-select: none;-ms-user-select: none}',
                    toolbarButtonSize: 'large',
                    buttons: [
                      ...Jodit.defaultOptions.buttons,
                      {
                        name: `<svg xmlns='http://www.w3.org/2000/svg' height='1em' viewBox='0 0 512 512'><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d='M500.3 7.3C507.7 13.3 512 22.4 512 32V176c0 26.5-28.7 48-64 48s-64-21.5-64-48s28.7-48 64-48V71L352 90.2V208c0 26.5-28.7 48-64 48s-64-21.5-64-48s28.7-48 64-48V64c0-15.3 10.8-28.4 25.7-31.4l160-32c9.4-1.9 19.1 .6 26.6 6.6zM74.7 304l11.8-17.8c5.9-8.9 15.9-14.2 26.6-14.2h61.7c10.7 0 20.7 5.3 26.6 14.2L213.3 304H240c26.5 0 48 21.5 48 48V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V352c0-26.5 21.5-48 48-48H74.7zM192 408a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM478.7 278.3L440.3 368H496c6.7 0 12.6 4.1 15 10.4s.6 13.3-4.4 17.7l-128 112c-5.6 4.9-13.9 5.3-19.9 .9s-8.2-12.4-5.3-19.2L391.7 400H336c-6.7 0-12.6-4.1-15-10.4s-.6-13.3 4.4-17.7l128-112c5.6-4.9 13.9-5.3 19.9-.9s8.2 12.4 5.3 19.2zm-339-59.2c-6.5 6.5-17 6.5-23 0L19.9 119.2c-28-29-26.5-76.9 5-103.9c27-23.5 68.4-19 93.4 6.5l10 10.5 9.5-10.5c25-25.5 65.9-30 93.9-6.5c31 27 32.5 74.9 4.5 103.9l-96.4 99.9z'/></svg>`,
                        tooltip: `Select Image`,
                        exec: (editor) => {
                          showPicker = true
                          onSelectImage = function (images) {
                            images.forEach(function (image) {
                              showPicker = false
                              const html = `<img width=100% title=` + image.title + ` src=` + image.file_url + ` />`;
                              editor.s.insertHTML(html);
                            })
                          }
                          // editor.s.insertHTML(new Date().toDateString());
                        }
                      }
                    ]
                  }" />
                </a-form-item>
              </a-col>
            </a-row>
          </a-tab-pane>
          <a-tab-pane key="2" tab="SEO">
            <a-row :gutter="20">
              <a-col :span="24">
                <a-form-item label="Link SEO">
                  <a-input v-model:value="formState.slug" placeholder="Enter..." />
                </a-form-item>
                <a-form-item label="Title">
                  <a-input v-model:value="formState.title" placeholder="Enter..." />
                </a-form-item>
                <a-form-item label="Meta description">
                  <a-textarea v-model:value="formState.meta_description" placeholder="Enter...." :rows="4" />
                </a-form-item>
                <a-form-item label="Meta keyword">
                  <a-textarea v-model:value="formState.meta_keyword" placeholder="Enter...." :rows="4" />
                </a-form-item>
              </a-col>
            </a-row>
          </a-tab-pane>
        </a-tabs>
        <a-space align="center">
          <a-button type="primary" html-type="submit">Submit</a-button>
          <a-button type="primary" ghost @click="back()">Back</a-button>
        </a-space>
      </a-form>
    </SectionMain>
  </LayoutAuthenticated>
  <a-modal append-to-body v-model:open="showPicker" style="z-index:99999;top: 2vh;height:98vh" height="96vh" width="90vw"
    title="Select file">
    <FilePicker :multiple="true" @close="showPicker = false" @select="onSelectImage"></FilePicker>
    <template #footer>
    </template>
  </a-modal>
</template>

<style>
.ant-input {
  border-color: #d9d9d9 !important;
  border-radius: 5px !important;
}
</style>
