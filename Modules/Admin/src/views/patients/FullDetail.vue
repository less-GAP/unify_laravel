<script lang="ts" setup>
import {reactive, h, ref, toRaw} from "vue";
import {useMainStore} from "@/stores/main";
import router from "@/router";
import Api from "@/utils/Api";

import 'jodit/es5/jodit.css';

import {JoditEditor, Jodit} from 'jodit-vue';

import {notification} from 'ant-design-vue';

import type {UploadProps} from 'ant-design-vue';

import {InputTags, InputUploadGetPath, FilePicker} from "@/components";
import {createApi, newModel, formConfig, fetchDetailApi} from "./meta";
import {back} from "./meta";

import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";

const mainStore = useMainStore();

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <div class="p-5">
        <h1 class="mb-3">Detail</h1>
        <hr class="my-2">
      </div>
    </SectionMain>
  </LayoutAuthenticated>
</template>
