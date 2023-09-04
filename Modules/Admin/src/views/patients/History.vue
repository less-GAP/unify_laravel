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
    @close="closeDetail" :open="visible" width="40vw">
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
                </a-space>
            </div>
            <div class="px-4 mt-5 overflow-y-auto" style="height:calc(100% - 60px)">
                <div class="flex flex-wrap -mx-4">
                    <h1 class="w-full px-4 flex flex-col">
                        <div class="flex items-center leading-none whitespace-nowrap">
                            <span>{{ formState.full_name }}</span>
                        </div>
                        <div class="text-gray-400 text-sm">#{{ formState.unify_number }}</div>
                    </h1>
                    <a-Divider class="!font-bold !text-blue-700" dashed orientation="left" orientation-margin="1rem" plain>History</a-Divider>
                    <div class="w-full px-4 pl-6">
                      
                        <a-timeline>
                            <a-timeline-item color="green">
                                <template #dot>
                                    <a-avatar size="small" :style="{ backgroundColor: '#ff7a50', verticalAlign: 'middle' }" :gap="gap">
                                    TM
                                </a-avatar>
                                </template>
                                <div class="flex items-center">
                                    <img class="w-6 h-6 rounded-full" src="https://randomuser.me/api/portraits/men/64.jpg">
                                    <div class="by ml-2">Kevin Tee</div>
                                </div>
                                <div class="mt-1 text-xs text-gray-500">{{ dayjs(formState.created_at, 'YYYY-MM-DD HH:mm:ss').format('HH:mm MM-DD-YYYY') }}</div>
                                <div class="text-lg">Create a services site 2015-09-01</div>
                            </a-timeline-item>
                            <a-timeline-item color="green">
                                <template #dot>
                                    <a-avatar size="small" :style="{ backgroundColor: '#2196f3', verticalAlign: 'middle' }" :gap="gap">
                                    GC
                                </a-avatar>
                                </template>
                                <div class="flex items-center">
                                    <img class="w-6 h-6 rounded-full" src="https://randomuser.me/api/portraits/men/64.jpg">
                                    <div class="by ml-2">Kevin Tee</div>
                                </div>
                                <div class="mt-1 text-xs text-gray-500">{{ dayjs(formState.created_at, 'YYYY-MM-DD HH:mm:ss').format('HH:mm MM-DD-YYYY') }}</div>
                                <div class="text-lg">Create a services site 2015-09-01</div>
                            </a-timeline-item>
                            <a-timeline-item color="red">
                                <template #dot>
                                    <a-avatar size="small" :style="{ backgroundColor: '#00dac6', verticalAlign: 'middle' }" :gap="gap">
                                    SM
                                    </a-avatar>
                                </template>
                                <div class="flex items-center">
                                    <img class="w-6 h-6 rounded-full" src="https://randomuser.me/api/portraits/men/64.jpg">
                                    <div class="by ml-2">Kevin Tee</div>
                                </div>
                                <div class="mt-1 text-xs text-gray-500">{{ dayjs(formState.created_at, 'YYYY-MM-DD HH:mm:ss').format('HH:mm MM-DD-YYYY') }}</div>
                                <div class="text-lg">Create a services site 2015-09-01</div>
                            </a-timeline-item>
                            <a-timeline-item color="red">
                                <template #dot>
                                    <a-avatar size="small" :style="{ backgroundColor: '#87d068', verticalAlign: 'middle' }" :gap="gap">
                                    SM
                                    </a-avatar>
                                </template>
                                <div class="flex items-center">
                                    <img class="w-6 h-6 rounded-full" src="https://randomuser.me/api/portraits/men/64.jpg">
                                    <div class="by ml-2">Kevin Tee</div>
                                </div>
                                <div class="mt-1 text-xs text-gray-500">{{ dayjs(formState.created_at, 'YYYY-MM-DD HH:mm:ss').format('HH:mm MM-DD-YYYY') }}</div>
                                <div class="text-lg">Create a services site 2015-09-01</div>
                            </a-timeline-item>
                            <a-timeline-item color="red">
                                <template #dot>
                                    <a-avatar size="small" :style="{ backgroundColor: '#8b8b8b', verticalAlign: 'middle' }" :gap="gap">
                                    SM
                                    </a-avatar>
                                </template>
                                <div class="flex items-center">
                                    <img class="w-6 h-6 rounded-full" src="https://randomuser.me/api/portraits/men/64.jpg">
                                    <div class="by ml-2">Kevin Tee</div>
                                </div>
                                <div class="mt-1 text-xs text-gray-500">{{ dayjs(formState.created_at, 'YYYY-MM-DD HH:mm:ss').format('HH:mm MM-DD-YYYY') }}</div>
                                <div class="text-lg">Create a services site 2015-09-01</div>
                            </a-timeline-item>
                        </a-timeline>


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
