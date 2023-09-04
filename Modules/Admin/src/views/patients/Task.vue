<script setup>
import { reactive, h, ref, toRaw, computed } from "vue";
import { mdiBackspace, mdiContentSave } from '@mdi/js';
import {BaseIcon} from "@/components";
import router from "@/router";
import { useAuthStore } from "@/stores/auth";
import { UseEloquentRouter } from "@/utils/UseEloquentRouter";
import dayjs from 'dayjs';
import { listProcess, getProcess } from "@/utils/Process";

const listProcessOptions = listProcess();
const prefix = 'patient'
const {
    fetchDetailApi,
    deleteApi,
    updateApi,
} = UseEloquentRouter(prefix);

defineProps({
    value: {
        type: Object,
        default: {}
    }, visible: {
        type: Boolean,
        default: true
    },
});
const formState = reactive({});
const formRef = ref();
const loading = ref(false);
const auth = useAuthStore();

const customFormat = 'MM-DD-YYYY';
const dbFormat = "YYYY-MM-DD";
const age = (formState) => {
  return formState.dob ? '(' + dayjs().diff(dayjs(formState.dob, dbFormat), 'year') + ' years old)' : '';
};

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
const submit = () => {
    formRef.value
        .validate()
        .then(() => {
            updateApi( formState.id, {  ...formState }).then(rs => {
                Object.assign(formState, rs.data.result)
            });
        })
};
const closeDetail = function () {
    router.replace({ path: '/' + prefix })
};
</script>
<template>
    <a-drawer :closable="false" style="position:relative;display:flex;flex-direction:column;height:100vh;"
        @close="closeDetail" :open="visible">
        <a-form layout="vertical" v-bind="$config.formConfig" ref="formRef" :model="formState" @finish="submit">
            <div class="bg-gray-200 p-3">
                <a-button class="!hidden md:!inline-block" type="link" @click="closeDetail">
                    <template #icon>
                        <div class="flex">
                            <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
                            <span class="text-stone-500 ml-1">Back</span>
                        </div>
                    </template>
                </a-button>
                <a-button class="!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0" type="link"
                    @click="closeDetail">
                    <template #icon>
                        <div class="flex">
                            <BaseIcon :path="mdiBackspace" class="w-4 text-stone-500" />
                            <span class="text-stone-500 ml-1">Back</span>
                        </div>
                    </template>
                </a-button>
                <a-space class="float-right">
                    <!-- <a-button @click="submit()" :loading="loading" type="primary">Update</a-button> -->
                </a-space>
            </div>
            <div class="px-4 mt-5 overflow-y-auto" style="height:calc(100% - 60px)">
                <div class="flex flex-wrap -mx-4">
                    <h3 class="block w-full px-4 mb-4 leading-6"><strong>List tasks of {{ formState.full_name }}</strong></h3>
                    <div class="w-full px-4">
                      
                    </div>
                </div>
            </div>
        </a-form>
    </a-drawer>
</template>

<style>
.ant-drawer-body {
  padding: 0 !important
}
</style>
