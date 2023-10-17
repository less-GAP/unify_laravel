<script lang="ts" setup>
  import {reactive, h, ref, toRaw, computed, watch} from "vue";

  import {mdiBackspace, mdiContentSave, mdiAccountArrowUp} from '@mdi/js';

  import router from "@/router";

  import Api from "@/utils/Api";

  import {useAuthStore} from "@/stores/auth";

  import {UseEloquentRouter} from "@/utils/UseEloquentRouter";

  import listStates from "@/utils/States";

  import 'jodit/es5/jodit.css';

  import {JoditEditor, Jodit} from 'jodit-vue';

  import {notification, SelectProps} from 'ant-design-vue';

  import {InputUpload, InputUploadGetPath, FilePicker, InputTags, RemoteSelect, BaseIcon} from "@/components";

  const auth = useAuthStore();

  const prefix = 'product'

  const {
    fetchDetailApi,
    createApi,
  } = UseEloquentRouter(prefix)

  const loading = ref(false);

  const activeKey = ref('1');

  const isShowModal = ref(false);

  const showPicker = ref(false);

  const formRef = ref();

  const emit = defineEmits(["close"]);

  const props = defineProps({
    value: {
      type: Object,
      default: {}
    },
    id: {
      type: String
    }
  });

  const formState = ref({
    status: 'A'
  });


  watch(() => props.value, (data) => {
    console.log(data);
  });

  // watch(() => props.id, async () => {
  //   alert('okie');
  //   //console.log(props.value);
  //   //formState.value = props.value
  // })

  const submit = () => {
    formRef.value
      .validate()
      .then(() => {
        Api.post('product', toRaw(formState.value)).then(rs => {
          notification[rs.data.code == 0 ? 'error' : 'success']({
            message: 'Notification',
            description: rs.data.message,
          });
          if (rs.data.code == 1) {
            emit('close');
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

  const closeDetail = function () {
    emit('close');
  }


</script>

<template>

  <a-form layout="vertical" ref="formRef" :model="formState" @finish="submit" class="w-full">
    <div class="p-3 bg-gray-200">
      <a-button class="!hidden md:!inline-block" type="link" @click="closeDetail">
        <template #icon>
          <div class="flex">
            <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500"/>
            <span class="ml-1 text-stone-500">Back</span>
          </div>
        </template>
      </a-button>
      <a-button class="!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0" type="link"
                @click="closeDetail">
        <template #icon>
          <div class="flex">
            <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500"/>
            <span class="ml-1 text-stone-500">Back</span>
          </div>
        </template>
      </a-button>
      <a-space class="float-right">
        <a-button @click="submit()" type="primary">
          <div class="flex">
            <BaseIcon :path="mdiContentSave" class="w-4 text-white"/>
            <span class="ml-1 text-white">Save</span>
          </div>
        </a-button>
      </a-space>
    </div>
    <div class="mx-4">
      <a-tabs v-model:activeKey="activeKey">
        <a-tab-pane key="1" tab="General">
          <a-row :gutter="20">
            <a-col :span="24">
              <a-form-item label="Status" name="status" :rules="[{ required: true, message: 'Select a status!' }]">
                <a-radio-group v-model:value="formState.status" button-style="solid" size="middle">
                  <a-radio-button value="A">Active</a-radio-button>
                  <a-radio-button value="D">Deactive</a-radio-button>
                </a-radio-group>
              </a-form-item>

              <a-form-item label="Name" name="name" :rules="[{ required: true, message: 'Please input !' }]">
                <a-input v-model:value="formState.name" placeholder="Content..."/>
              </a-form-item>
            </a-col>
            <a-col :span="24">
              <a-form-item label="Short description">
                <a-textarea v-model:value="formState.short_description" placeholder="Content..." :rows="4"/>
              </a-form-item>
              <a-form-item label="Description" name="description">
                <jodit-editor v-if="!loading" style="height: 40vh" v-model="formState.description" :config="{
                            iframe: true,
                            height: '40vh',
                            iframeStyle: 'html{margin:0;padding:0;min-height: 100%;}body{box-sizing:border-box;font-family:roboto;font-size:16px;line-height:1.6;padding:10px;margin:0;background:transparent;color:#000;position:relative;z-index:2;user-select:auto;margin:0px;overflow:auto;outline:none;}table{width:100%;border:none;border-collapse:collapse;empty-cells: show;max-width: 100%;}th,td{padding: 2px 5px;border:1px solid #ccc;-webkit-user-select:text;-moz-user-select:text;-ms-user-select:text;user-select:text}p{margin-top:0;}.jodit_editor .jodit_iframe_wrapper{display: block;clear: both;user-select: none;position: relative;}.jodit_editor .jodit_iframe_wrapper:after {position:absolute;content:\'\';z-index:1;top:0;left:0;right: 0;bottom: 0;cursor: pointer;display: block;background: rgba(0, 0, 0, 0);} .jodit_disabled{user-select: none;-o-user-select: none;-moz-user-select: none;-khtml-user-select: none;-webkit-user-select: none;-ms-user-select: none}',
                            toolbarButtonSize: 'middle',
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
                          }"/>
              </a-form-item>
            </a-col>
          </a-row>
        </a-tab-pane>
        <a-tab-pane key="2" tab="Additional Infomation">
          <a-row :gutter="20">
            <a-col :span="24">
              <a-form-item style="width:100%" label="Image">
                <InputUploadGetPath autocomplete="off" v-model:value="formState.image"></InputUploadGetPath>
              </a-form-item>
              <a-form-item label="Link SEO">
                <a-input v-model:value="formState.slug" placeholder="Content..."/>
              </a-form-item>
              <a-form-item label="Title">
                <a-input v-model:value="formState.title" placeholder="Content..."/>
              </a-form-item>
              <a-form-item label="Meta description">
                <a-textarea v-model:value="formState.meta_description" placeholder="Content..." :rows="4"/>
              </a-form-item>
              <a-form-item label="Meta keyword">
                <a-textarea v-model:value="formState.meta_keyword" placeholder="Content..." :rows="4"/>
              </a-form-item>
            </a-col>
          </a-row>
        </a-tab-pane>
        <a-tab-pane key="3" tab="Inventories">
        </a-tab-pane>
      </a-tabs>
    </div>
  </a-form>
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
