<script lang="ts" setup>
import {reactive, h, ref, toRaw, computed} from "vue";
import {CloseCircleOutlined, ArrowLeftOutlined} from '@ant-design/icons-vue';
import router from "@/router";
import {UseEloquentRouter} from "@/utils/UseEloquentRouter";
import ApiData from "@/components/ApiData.vue";
import Api from "@/utils/Api";
import DataListEdit from "@/components/DataListEdit.vue";

const prefix = 'permissions'
const routeList = '/master-data/' + prefix
const {
  fetchDetailApi,
  createApi,
  // updateApi
} = UseEloquentRouter(prefix)

const loading = ref(false);

const formRef = ref();


const emit = defineEmits(["close"]);
const formState = reactive({
  name: '',
  status: 'active',
  description: '',
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
  router.replace({path: routeList})
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
        <a-card >
          <a-form-item  label="Name"
                       name="name"
                       :rules="[{ required: true }]"
          >
            <a-input v-model:value="formState.name" placeholder="Name.."/>
          </a-form-item>
          <a-form-item  label="Description"
                       name="description"
                       :min="0"
                       :rules="[{ required: true }]"
          >
            <a-textarea rows="5" v-model:value="formState.description"/>
          </a-form-item>

          <a-form-item  label="Status"
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
