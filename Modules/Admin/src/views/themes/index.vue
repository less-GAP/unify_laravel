<script setup>
import {reactive, ref, h} from "vue";
import {mdiBallotOutline, mdiDelete} from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import {DataTable} from "@/components";

import {PlusOutlined, LoadingOutlined, DeleteOutlined, FormOutlined} from '@ant-design/icons-vue';

import {InputUpload} from "@/components";

import Api from "@/utils/Api";
import router from "@/router";

import {notification} from "ant-design-vue";
import {tableConfig, updateApi} from "./meta";

const isShowModal = ref(false)

const editProduct = ref(null);

function showEditUser(user, reload) {
  isShowModal.value = true;
  editProduct.value = user;
}


</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <DataTable v-bind="tableConfig">
        <template #cellAction[delete]="{item,actionMethod}">
          <a-popconfirm
            title="Bạn muốn xóa sản phẩm này?"
            ok-text="Yes"
            cancel-text="No"
            @confirm="actionMethod"
          >
            <a-button
              type="text"
              danger
              :icon="h(DeleteOutlined)"
              label=""
              :outline="true"
            >
            </a-button>
          </a-popconfirm>
        </template>
        <template #cellAction[edit]="{item,actionMethod}">
          <a-button
            type="text"
            :icon="h(FormOutlined)"
            label=""
            :outline="true"
            @click="actionMethod"
          >
          </a-button>
        </template>
        <template #cell[image]="{item,column}">
          <a-image height="50px" class="w-20 h-auto" :src="item.image_url"
                   :alt="item.name"/>
        </template>


        <template #cell[status]="{item,column}">
          <a-switch @change="updateApi(item.id,{status:item.status})" checkedValue="active" unCheckedValue="inactive"
                    v-model:checked="item.status"/>
        </template>
      </DataTable>
    </SectionMain>
  </LayoutAuthenticated>

</template>
