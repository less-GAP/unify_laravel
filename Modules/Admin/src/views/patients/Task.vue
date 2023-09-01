<script setup>
import { reactive, h, ref, toRaw, computed } from "vue";
import { CloseCircleOutlined } from '@ant-design/icons-vue';
import router from "@/router";
import {useAuthStore} from "@/stores/auth";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";

const prefix = 'patient'
const {
  fetchDetailApi,
  deleteApi,
  updateApi,
} = UseEloquentRouter(prefix);

const props = defineProps({
  value: {
    type: Object,
    default: {}
  }, visible: {
    type: Boolean,
    default: true
  },
});
const formState = reactive({});
const loading = ref(false);

const fetch = async function () {
  loading.value = true;
  var id = router.currentRoute.value.params.id;
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
const submit = (status) => {
  formRef.value
    .validate()
    .then(() => {
        updateApi({ ...formState, status: status }).then(rs => {
        Object.assign(formState, rs.data.result)
      });
    })
};
const closeDetail = function () {
  router.replace({ path: '/' + prefix })
}
</script>
<template>
    <div>
        
    </div>
</template>